<?php
if (!defined('ABSPATH')) exit;

class LW_Shortcode {

    public function __construct() {
        add_shortcode('lw_search_bar', [$this, 'render']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
    }

    public function enqueue_assets() {
        global $post;
        if (!is_a($post, 'WP_Post') || !has_shortcode($post->post_content, 'lw_search_bar')) {
            return;
        }

        self::enqueue_shared_assets();
    }

    /**
     * Enqueue shared JS/CSS and localize apartment data.
     * Used by both the shortcode and Elementor widgets.
     */
    public static function enqueue_shared_assets() {
        // EasierIcons SDK for view toggle icons
        wp_enqueue_script(
            'easiericons-sdk',
            'https://ezicons.com/sdk.js',
            [],
            null,
            false
        );
        // Only add the filter once
        if (!has_filter('script_loader_tag', [__CLASS__, 'add_easiericons_data_key'])) {
            add_filter('script_loader_tag', [__CLASS__, 'add_easiericons_data_key'], 10, 2);
        }

        wp_enqueue_style(
            'lw-search-css',
            LW_SEARCH_URL . 'assets/css/lw-search.css',
            [],
            LW_SEARCH_VERSION
        );

        wp_enqueue_script(
            'lw-search-js',
            LW_SEARCH_URL . 'assets/js/lw-search.js',
            [],
            LW_SEARCH_VERSION,
            true
        );

        // Only localize once per page load
        static $localized = false;
        if ($localized) return;
        $localized = true;

        $apartments = self::get_all_apartments();

        $areas = array_filter(array_column($apartments, 'area'), function ($v) { return $v !== null; });
        $floors = array_column($apartments, 'floor_num');
        $rooms = array_column($apartments, 'rooms');

        // Collect investment names from settings
        $investment_names = [];
        $local_name = get_option('lw_investment_name', '');
        if ($local_name) {
            $investment_names[] = $local_name;
        }
        $sources = get_option('lw_sources', []);
        if (is_array($sources)) {
            foreach ($sources as $src) {
                $name = $src['name'] ?? '';
                if ($name && !in_array($name, $investment_names, true)) {
                    $investment_names[] = $name;
                }
            }
        }
        sort($investment_names);

        wp_localize_script('lw-search-js', 'lwSearchData', [
            'apartments'      => $apartments,
            'investmentNames' => $investment_names,
            'areaMin'         => $areas ? floor(min($areas)) : 0,
            'areaMax'         => $areas ? ceil(max($areas)) : 200,
            'floorMin'        => $floors ? min($floors) : 0,
            'floorMax'        => $floors ? max($floors) : 10,
            'roomsMin'        => $rooms ? min($rooms) : 1,
            'roomsMax'        => $rooms ? max($rooms) : 6,
        ]);
    }

    /**
     * Add data-key attribute to EasierIcons SDK script tag.
     */
    public static function add_easiericons_data_key($tag, $handle) {
        if ($handle === 'easiericons-sdk') {
            $tag = str_replace(' src=', ' data-key="iek_rNzVYc9CxXGFO67HX8jqTnPpItL0PcRG" src=', $tag);
        }
        return $tag;
    }

    /**
     * Fetch all apartments (local + remote). Shared across shortcode and widgets.
     */
    public static function get_all_apartments() {
        // Local apartments (cached) — requires lw-search-bar-helper plugin
        $local = [];
        if (class_exists('LW_Rest_Endpoint')) {
            $local = get_transient('lw_apartments_local');
            if ($local === false) {
                $endpoint = new LW_Rest_Endpoint();
                $response = $endpoint->get_apartments();
                $local = $response->get_data();
                set_transient('lw_apartments_local', $local, HOUR_IN_SECONDS);
            }
        }

        // Remote apartments from all configured sources
        $sources = get_option('lw_sources', []);
        if (!is_array($sources)) $sources = [];

        // Backwards compat: migrate old single-source option
        $legacy_url = get_option('lw_remote_site_url', '');
        if ($legacy_url && empty($sources)) {
            $sources = [['url' => $legacy_url, 'name' => '']];
        }

        $remote = [];
        foreach ($sources as $i => $src) {
            $url = $src['url'] ?? '';
            if (!$url) continue;

            $cache_key = 'lw_apartments_remote_' . md5($url);
            $cached = get_transient($cache_key);
            if ($cached !== false) {
                $remote = array_merge($remote, $cached);
                continue;
            }

            $response = wp_remote_get(
                trailingslashit($url) . 'wp-json/lw/v1/apartments',
                ['timeout' => 10, 'sslverify' => false]
            );

            if (!is_wp_error($response) && wp_remote_retrieve_response_code($response) === 200) {
                $body = wp_remote_retrieve_body($response);
                $data = json_decode($body, true);
                if (is_array($data)) {
                    set_transient($cache_key, $data, HOUR_IN_SECONDS);
                    $remote = array_merge($remote, $data);
                }
            }
        }

        return array_merge($local, $remote);
    }

    public function render($atts) {
        ob_start();
        ?>
        <div class="lw-search-bar" id="lw-search-bar">
            <div class="lw-search-form">
                <!-- Row 1: Inwestycja + Powierzchnia -->
                <div class="lw-search-row">
                    <div class="lw-field lw-field--investment">
                        <svg class="lw-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M6.44444 2.75C5.32453 2.75 4.41667 3.65787 4.41667 4.77778V21.25H6.25V10.3333C6.25 8.99865 7.33198 7.91667 8.66667 7.91667H15.3333C16.668 7.91667 17.75 8.99865 17.75 10.3333V21.25H19.5833V4.77778C19.5833 3.65787 18.6755 2.75 17.5556 2.75H6.44444ZM21.0833 21.25V4.77778C21.0833 2.82944 19.5039 1.25 17.5556 1.25H6.44444C4.49611 1.25 2.91667 2.82944 2.91667 4.77778V21.25H2C1.58579 21.25 1.25 21.5858 1.25 22C1.25 22.4142 1.58579 22.75 2 22.75H22C22.4142 22.75 22.75 22.4142 22.75 22C22.75 21.5858 22.4142 21.25 22 21.25H21.0833ZM16.25 21.25V10.3333C16.25 9.82707 15.8396 9.41667 15.3333 9.41667H12.75V21.25H16.25ZM11.25 21.25V9.41667H8.66667C8.16041 9.41667 7.75 9.82707 7.75 10.3333V21.25H11.25ZM9.58333 5.33333C9.58333 4.91912 9.91912 4.58333 10.3333 4.58333H13.6667C14.0809 4.58333 14.4167 4.91912 14.4167 5.33333C14.4167 5.74755 14.0809 6.08333 13.6667 6.08333H10.3333C9.91912 6.08333 9.58333 5.74755 9.58333 5.33333Z" fill="#B8A080"/></svg>
                        <span class="lw-label-text">Inwestycja</span>
                        <select id="lw-filter-investment" class="lw-select">
                            <option value="">Wszystkie</option>
                        </select>
                    </div>
                    <div class="lw-field lw-field--area">
                        <span class="lw-label-text">Powierzchnia:</span>
                        <span class="lw-range-val" id="lw-area-min-val">0 m2</span>
                        <div class="lw-range-wrapper">
                            <div class="lw-range-track" id="lw-range-track"></div>
                            <input type="range" id="lw-area-min" class="lw-range" min="0" max="200" value="0">
                            <input type="range" id="lw-area-max" class="lw-range" min="0" max="200" value="200">
                        </div>
                        <span class="lw-range-val" id="lw-area-max-val">200 m2</span>
                    </div>
                </div>

                <!-- Row 2: Pokoje + Piętro -->
                <div class="lw-search-row">
                    <div class="lw-field lw-field--rooms">
                        <span class="lw-label-text">Pokoje:</span>
                        <div class="lw-inline-select">
                            <svg class="lw-icon" width="20" height="20" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path fill="#B8A080" fill-rule="evenodd" d="M12.05 1.25h-.12v0c-1.03-.01-1.95-.01-2.79.01 -.06-.02-.11-.02-.16-.02 -.07 0-.14 0-.2.02 -.91.02-1.7.07-2.4.16 -1.45.19-2.59.59-3.48 1.49 -.9.89-1.31 2.03-1.5 3.47 -.1.69-.15 1.49-.17 2.39 -.02.06-.03.12-.03.19 0 .05 0 .1.01.15 -.02.83-.02 1.76-.02 2.78v0V12v0c-.01 2.3-.01 4.11.18 5.52 .19 1.44.59 2.58 1.49 3.47 .89.89 2.03 1.3 3.47 1.49 .69.09 1.49.14 2.39.16 .06.01.12.02.19.02 .05 0 .1-.01.15-.02 .83.01 1.76.01 2.78.01h.05c.41 0 .75-.34.75-.75 0-2.35 1.9-4.25 4.25-4.25 .41 0 .75-.34.75-.75 0-.42-.34-.75-.75-.75 -2.93 0-5.34 2.17-5.71 4.99 -.56-.01-1.07-.01-1.55-.01V9.63h4.25c.41 0 .75-.34.75-.75 0-.42-.34-.75-.75-.75h-5H2.67c.02-.62.06-1.16.13-1.64 .17-1.28.49-2.05 1.06-2.62 .56-.57 1.34-.9 2.61-1.07 .48-.07 1.02-.11 1.63-.14v2.2c0 .41.33.75.75.75 .41 0 .75-.34.75-.75V2.61c.67-.01 1.42-.01 2.25-.01 2.37 0 4.08 0 5.38.17 1.27.17 2.04.49 2.61 1.06 .56.56.89 1.34 1.06 2.61 .06.48.1 1.02.13 1.63h-3.21c-.42 0-.75.33-.75.75 0 .41.33.75.75.75h3.24c0 .67 0 1.42 0 2.25 0 2.37-.01 4.08-.18 5.38 -.18 1.27-.5 2.04-1.07 2.61 -.64.63-1.52.96-3.08 1.12 -.42.04-.72.4-.68.81 .04.41.4.71.81.67 1.69-.17 2.99-.56 3.99-1.56 .89-.9 1.3-2.04 1.49-3.48 .18-1.42.18-3.23.18-5.53v-.12c0-1.03 0-1.95-.02-2.79 .01-.06.01-.11.01-.16 0-.07-.01-.14-.03-.2 -.03-.91-.08-1.7-.17-2.4 -.2-1.45-.6-2.59-1.5-3.48 -.9-.9-2.04-1.31-3.48-1.5C15.91 1 14.1 1 11.8 1v0Zm-3.81 8.5V21.2c-.62-.03-1.16-.07-1.64-.14 -1.28-.18-2.05-.5-2.62-1.07 -.57-.57-.9-1.35-1.07-2.62 -.18-1.3-.18-3.01-.18-5.39 0-.83 0-1.58 0-2.25h5.49Z"/></svg>
                            <select id="lw-filter-rooms-min" class="lw-select">
                                <option value="">od</option>
                            </select>
                        </div>
                        <div class="lw-inline-select">
                            <svg class="lw-icon" width="20" height="20" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path fill="#B8A080" fill-rule="evenodd" d="M12.05 1.25h-.12v0c-1.03-.01-1.95-.01-2.79.01 -.06-.02-.11-.02-.16-.02 -.07 0-.14 0-.2.02 -.91.02-1.7.07-2.4.16 -1.45.19-2.59.59-3.48 1.49 -.9.89-1.31 2.03-1.5 3.47 -.1.69-.15 1.49-.17 2.39 -.02.06-.03.12-.03.19 0 .05 0 .1.01.15 -.02.83-.02 1.76-.02 2.78v0V12v0c-.01 2.3-.01 4.11.18 5.52 .19 1.44.59 2.58 1.49 3.47 .89.89 2.03 1.3 3.47 1.49 .69.09 1.49.14 2.39.16 .06.01.12.02.19.02 .05 0 .1-.01.15-.02 .83.01 1.76.01 2.78.01h.05c.41 0 .75-.34.75-.75 0-2.35 1.9-4.25 4.25-4.25 .41 0 .75-.34.75-.75 0-.42-.34-.75-.75-.75 -2.93 0-5.34 2.17-5.71 4.99 -.56-.01-1.07-.01-1.55-.01V9.63h4.25c.41 0 .75-.34.75-.75 0-.42-.34-.75-.75-.75h-5H2.67c.02-.62.06-1.16.13-1.64 .17-1.28.49-2.05 1.06-2.62 .56-.57 1.34-.9 2.61-1.07 .48-.07 1.02-.11 1.63-.14v2.2c0 .41.33.75.75.75 .41 0 .75-.34.75-.75V2.61c.67-.01 1.42-.01 2.25-.01 2.37 0 4.08 0 5.38.17 1.27.17 2.04.49 2.61 1.06 .56.56.89 1.34 1.06 2.61 .06.48.1 1.02.13 1.63h-3.21c-.42 0-.75.33-.75.75 0 .41.33.75.75.75h3.24c0 .67 0 1.42 0 2.25 0 2.37-.01 4.08-.18 5.38 -.18 1.27-.5 2.04-1.07 2.61 -.64.63-1.52.96-3.08 1.12 -.42.04-.72.4-.68.81 .04.41.4.71.81.67 1.69-.17 2.99-.56 3.99-1.56 .89-.9 1.3-2.04 1.49-3.48 .18-1.42.18-3.23.18-5.53v-.12c0-1.03 0-1.95-.02-2.79 .01-.06.01-.11.01-.16 0-.07-.01-.14-.03-.2 -.03-.91-.08-1.7-.17-2.4 -.2-1.45-.6-2.59-1.5-3.48 -.9-.9-2.04-1.31-3.48-1.5C15.91 1 14.1 1 11.8 1v0Zm-3.81 8.5V21.2c-.62-.03-1.16-.07-1.64-.14 -1.28-.18-2.05-.5-2.62-1.07 -.57-.57-.9-1.35-1.07-2.62 -.18-1.3-.18-3.01-.18-5.39 0-.83 0-1.58 0-2.25h5.49Z"/></svg>
                            <select id="lw-filter-rooms-max" class="lw-select">
                                <option value="">do</option>
                            </select>
                        </div>
                    </div>
                    <div class="lw-field lw-field--floor">
                        <span class="lw-label-text">Piętro:</span>
                        <div class="lw-inline-select">
                            <svg class="lw-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M6.44444 2.75C5.32453 2.75 4.41667 3.65787 4.41667 4.77778V21.25H6.25V10.3333C6.25 8.99865 7.33198 7.91667 8.66667 7.91667H15.3333C16.668 7.91667 17.75 8.99865 17.75 10.3333V21.25H19.5833V4.77778C19.5833 3.65787 18.6755 2.75 17.5556 2.75H6.44444ZM21.0833 21.25V4.77778C21.0833 2.82944 19.5039 1.25 17.5556 1.25H6.44444C4.49611 1.25 2.91667 2.82944 2.91667 4.77778V21.25H2C1.58579 21.25 1.25 21.5858 1.25 22C1.25 22.4142 1.58579 22.75 2 22.75H22C22.4142 22.75 22.75 22.4142 22.75 22C22.75 21.5858 22.4142 21.25 22 21.25H21.0833ZM16.25 21.25V10.3333C16.25 9.82707 15.8396 9.41667 15.3333 9.41667H12.75V21.25H16.25ZM11.25 21.25V9.41667H8.66667C8.16041 9.41667 7.75 9.82707 7.75 10.3333V21.25H11.25ZM9.58333 5.33333C9.58333 4.91912 9.91912 4.58333 10.3333 4.58333H13.6667C14.0809 4.58333 14.4167 4.91912 14.4167 5.33333C14.4167 5.74755 14.0809 6.08333 13.6667 6.08333H10.3333C9.91912 6.08333 9.58333 5.74755 9.58333 5.33333Z" fill="#B8A080"/></svg>
                            <select id="lw-filter-floor-min" class="lw-select">
                                <option value="">od</option>
                            </select>
                        </div>
                        <div class="lw-inline-select">
                            <svg class="lw-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M6.44444 2.75C5.32453 2.75 4.41667 3.65787 4.41667 4.77778V21.25H6.25V10.3333C6.25 8.99865 7.33198 7.91667 8.66667 7.91667H15.3333C16.668 7.91667 17.75 8.99865 17.75 10.3333V21.25H19.5833V4.77778C19.5833 3.65787 18.6755 2.75 17.5556 2.75H6.44444ZM21.0833 21.25V4.77778C21.0833 2.82944 19.5039 1.25 17.5556 1.25H6.44444C4.49611 1.25 2.91667 2.82944 2.91667 4.77778V21.25H2C1.58579 21.25 1.25 21.5858 1.25 22C1.25 22.4142 1.58579 22.75 2 22.75H22C22.4142 22.75 22.75 22.4142 22.75 22C22.75 21.5858 22.4142 21.25 22 21.25H21.0833ZM16.25 21.25V10.3333C16.25 9.82707 15.8396 9.41667 15.3333 9.41667H12.75V21.25H16.25ZM11.25 21.25V9.41667H8.66667C8.16041 9.41667 7.75 9.82707 7.75 10.3333V21.25H11.25ZM9.58333 5.33333C9.58333 4.91912 9.91912 4.58333 10.3333 4.58333H13.6667C14.0809 4.58333 14.4167 4.91912 14.4167 5.33333C14.4167 5.74755 14.0809 6.08333 13.6667 6.08333H10.3333C9.91912 6.08333 9.58333 5.74755 9.58333 5.33333Z" fill="#B8A080"/></svg>
                            <select id="lw-filter-floor-max" class="lw-select">
                                <option value="">do</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Row 3: SZUKAJ -->
                <div class="lw-search-actions">
                    <button type="button" id="lw-search-btn" class="lw-btn">SZUKAJ</button>
                </div>
            </div>

            <!-- Results toolbar: status filter + view toggle -->
            <div class="lw-results-toolbar">
                <div class="lw-toolbar-left">
                    <div class="lw-dropdown" id="lw-dropdown-status">
                        <button type="button" class="lw-toolbar-btn lw-toolbar-btn--active" id="lw-btn-status">
                            <span class="lw-toolbar-btn__label">Dostępne</span>
                            <svg width="10" height="6" viewBox="0 0 10 6" fill="none"><path d="M1 1L5 5L9 1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </button>
                        <div class="lw-dropdown__menu">
                            <button type="button" class="lw-dropdown__item" data-value="">Wszystkie statusy</button>
                            <button type="button" class="lw-dropdown__item lw-dropdown__item--active" data-value="dostepne">Dostępne</button>
                            <button type="button" class="lw-dropdown__item" data-value="zarezerwowane">Zarezerwowane</button>
                            <button type="button" class="lw-dropdown__item" data-value="sprzedane">Sprzedane</button>
                        </div>
                    </div>
                    <div class="lw-dropdown" id="lw-dropdown-sort">
                        <button type="button" class="lw-toolbar-btn" id="lw-btn-sort">
                            <span class="lw-toolbar-btn__label">Sortowanie</span>
                            <svg width="10" height="6" viewBox="0 0 10 6" fill="none"><path d="M1 1L5 5L9 1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </button>
                        <div class="lw-dropdown__menu">
                            <button type="button" class="lw-dropdown__item lw-dropdown__item--active" data-value="">Domyślne</button>
                            <button type="button" class="lw-dropdown__item" data-value="price-asc">Cena: od najniższej</button>
                            <button type="button" class="lw-dropdown__item" data-value="price-desc">Cena: od najwyższej</button>
                            <button type="button" class="lw-dropdown__item" data-value="title-asc">Nazwa: A-Z</button>
                            <button type="button" class="lw-dropdown__item" data-value="title-desc">Nazwa: Z-A</button>
                        </div>
                    </div>
                </div>
                <div class="lw-view-toggle">
                    <button type="button" class="lw-view-btn lw-view-btn--active" data-view="grid" data-tooltip="Widok siatki">
                        <easier-icon name="grid-view" variant="stroke" size="18"></easier-icon>
                    </button>
                    <button type="button" class="lw-view-btn" data-view="table" data-tooltip="Widok tabeli">
                        <easier-icon name="menu-01" variant="stroke" size="18"></easier-icon>
                    </button>
                </div>
            </div>

            <div class="lw-results" id="lw-results">
                <!-- Grid view -->
                <div class="lw-grid" id="lw-grid"></div>

                <!-- Table view -->
                <table class="lw-table" id="lw-table" style="display:none;">
                    <thead>
                        <tr>
                            <th>Numer</th>
                            <th>Piętro</th>
                            <th>Pokoje</th>
                            <th>Powierzchnia</th>
                            <th>Status</th>
                            <th>Cena</th>
                            <th>Inwestycja</th>
                            <th>Akcje</th>
                        </tr>
                    </thead>
                    <tbody id="lw-table-body"></tbody>
                </table>
                <p class="lw-no-results" id="lw-no-results" style="display:none;">Brak wyników spełniających kryteria.</p>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
}
