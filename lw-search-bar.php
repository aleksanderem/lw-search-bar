<?php
/**
 * Plugin Name: LW Search Bar
 * Description: Wyszukiwarka mieszkań dla Rezydencji Liwskiej — łączy dane z dwóch inwestycji (RL2/RL3) przez REST API.
 * Version: 1.5.8
 * Author: Alex M.
 * Update URI: https://github.com/aleksanderem/lw-search-bar
 * Requires PHP: 7.4
 */

if (!defined('ABSPATH')) exit;

define('LW_SEARCH_VERSION', '1.5.8');
define('LW_SEARCH_DIR', plugin_dir_path(__FILE__));
define('LW_SEARCH_URL', plugin_dir_url(__FILE__));
define('LW_SEARCH_GITHUB_REPO', 'aleksanderem/lw-search-bar');

require_once LW_SEARCH_DIR . 'includes/class-lw-shortcode.php';
require_once LW_SEARCH_DIR . 'includes/class-lw-github-updater.php';

// Enqueue CSS AFTER theme so plugin styles win over theme globals
add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style('lw-search-css', LW_SEARCH_URL . 'assets/css/lw-search.css', [], LW_SEARCH_VERSION);
    wp_register_script('lw-search-js', LW_SEARCH_URL . 'assets/js/lw-search.js', [], LW_SEARCH_VERSION, true);
    wp_register_script('easiericons-sdk', 'https://ezicons.com/sdk.js', [], null, false);
}, 50);

// Shortcode
add_action('init', function () {
    new LW_Shortcode();
});

// Elementor widgets
add_action('plugins_loaded', function () {
    if (did_action('elementor/loaded')) {
        require_once LW_SEARCH_DIR . 'includes/class-lw-elementor.php';
        new LW_Elementor();
    }
});

// GitHub Updater
new LW_GitHub_Updater(__FILE__);

// Settings page
add_action('admin_menu', function () {
    add_options_page(
        'LW Search Bar',
        'LW Search Bar',
        'manage_options',
        'lw-search-bar',
        'lw_search_bar_settings_page'
    );
});

add_action('admin_init', function () {
    register_setting('lw_search_bar', 'lw_sources', [
        'type'              => 'array',
        'sanitize_callback' => 'lw_sanitize_sources',
        'default'           => [],
    ]);

    add_settings_section('lw_search_bar_main', 'Ustawienia', null, 'lw-search-bar');

    add_settings_field('lw_sources', 'Zdalne źródła danych', function () {
        $sources = get_option('lw_sources', []);
        if (!is_array($sources)) $sources = [];
        ?>
        <div id="lw-sources-list">
            <?php foreach ($sources as $i => $src): ?>
                <div class="lw-source-row" style="margin-bottom:10px;padding:10px;background:#f9f9f9;border:1px solid #ddd;border-radius:4px;">
                    <label>URL: <input type="url" name="lw_sources[<?php echo $i; ?>][url]" value="<?php echo esc_attr($src['url'] ?? ''); ?>" class="regular-text" placeholder="https://example.com"></label><br>
                    <label style="margin-top:4px;display:inline-block;">Nazwa inwestycji: <input type="text" name="lw_sources[<?php echo $i; ?>][name]" value="<?php echo esc_attr($src['name'] ?? ''); ?>" class="regular-text" placeholder="np. RL3"></label>
                    <button type="button" class="button lw-remove-source" style="margin-left:8px;color:#a00;">Usuń</button>
                </div>
            <?php endforeach; ?>
        </div>
        <button type="button" class="button" id="lw-add-source">+ Dodaj źródło</button>
        <p class="description">Adresy zdalnych instalacji WordPress z zainstalowanym pluginem LW Search Bar Helper. Każde źródło musi mieć aktywny endpoint <code>/wp-json/lw/v1/apartments</code>.</p>
        <script>
        (function(){
            var list = document.getElementById('lw-sources-list');
            var idx = list.querySelectorAll('.lw-source-row').length;

            document.getElementById('lw-add-source').addEventListener('click', function(){
                var div = document.createElement('div');
                div.className = 'lw-source-row';
                div.style.cssText = 'margin-bottom:10px;padding:10px;background:#f9f9f9;border:1px solid #ddd;border-radius:4px;';
                div.appendChild(Object.assign(document.createElement('label'), {innerHTML: 'URL: <input type="url" name="lw_sources['+idx+'][url]" value="" class="regular-text" placeholder="https://example.com">'}));
                div.appendChild(document.createElement('br'));
                var label2 = Object.assign(document.createElement('label'), {innerHTML: 'Nazwa inwestycji: <input type="text" name="lw_sources['+idx+'][name]" value="" class="regular-text" placeholder="np. RL3">'});
                label2.style.cssText = 'margin-top:4px;display:inline-block;';
                div.appendChild(label2);
                var btn = Object.assign(document.createElement('button'), {type:'button', className:'button lw-remove-source', textContent:'Usuń'});
                btn.style.cssText = 'margin-left:8px;color:#a00;';
                btn.addEventListener('click', function(){ div.remove(); });
                div.appendChild(btn);
                list.appendChild(div);
                idx++;
            });

            list.addEventListener('click', function(e){
                if (e.target.classList.contains('lw-remove-source')) {
                    e.target.closest('.lw-source-row').remove();
                }
            });
        })();
        </script>
        <?php
    }, 'lw-search-bar', 'lw_search_bar_main');
});

function lw_sanitize_sources($input) {
    if (!is_array($input)) return [];
    $clean = [];
    foreach ($input as $src) {
        $url = isset($src['url']) ? esc_url_raw(trim($src['url'])) : '';
        $name = isset($src['name']) ? sanitize_text_field(trim($src['name'])) : '';
        if ($url) {
            $clean[] = ['url' => untrailingslashit($url), 'name' => $name];
        }
    }
    return $clean;
}

function lw_search_bar_settings_page() {
    ?>
    <div class="wrap">
        <h1>LW Search Bar</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('lw_search_bar');
            do_settings_sections('lw-search-bar');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}
