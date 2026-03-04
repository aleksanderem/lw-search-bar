<?php
if (!defined('ABSPATH')) exit;

class LW_Search_Form_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'lw-search-form';
    }

    public function get_title() {
        return 'LW Search Form';
    }

    public function get_icon() {
        return 'eicon-search';
    }

    public function get_categories() {
        return ['lw-search-bar'];
    }

    public function get_keywords() {
        return ['search', 'filter', 'apartments', 'lw'];
    }

    protected function _register_controls() {
        // ── Content ──
        $this->start_controls_section('section_content', [
            'label' => 'Treść',
            'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('button_text', [
            'label'   => 'Tekst przycisku',
            'type'    => \Elementor\Controls_Manager::TEXT,
            'default' => 'SZUKAJ',
        ]);

        $this->end_controls_section();

        // ── Style: Form container ──
        $this->start_controls_section('section_style_form', [
            'label' => 'Formularz',
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('form_bg', [
            'label'     => 'Kolor tła',
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .lw-search-form' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('form_padding', [
            'label'      => 'Padding',
            'type'       => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px'],
            'selectors'  => ['{{WRAPPER}} .lw-search-form' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Border::get_type(), [
            'name'     => 'form_border',
            'selector' => '{{WRAPPER}} .lw-search-form',
        ]);

        $this->add_control('form_border_radius', [
            'label'      => 'Zaokrąglenie',
            'type'       => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px'],
            'selectors'  => ['{{WRAPPER}} .lw-search-form' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Box_Shadow::get_type(), [
            'name'     => 'form_shadow',
            'selector' => '{{WRAPPER}} .lw-search-form',
        ]);

        $this->end_controls_section();

        // ── Style: Labels ──
        $this->start_controls_section('section_style_labels', [
            'label' => 'Etykiety',
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name'     => 'label_typography',
            'selector' => '{{WRAPPER}} .lw-label-text',
        ]);

        $this->add_control('label_color', [
            'label'     => 'Kolor',
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .lw-label-text' => 'color: {{VALUE}};'],
        ]);

        $this->end_controls_section();

        // ── Style: Selects ──
        $this->start_controls_section('section_style_selects', [
            'label' => 'Pola wyboru',
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name'     => 'select_typography',
            'selector' => '{{WRAPPER}} .lw-select',
        ]);

        $this->add_control('select_color', [
            'label'     => 'Kolor tekstu',
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .lw-select' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('select_bg', [
            'label'     => 'Kolor tła',
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .lw-select' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_control('select_border_color', [
            'label'     => 'Kolor obramowania',
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .lw-select' => 'border-bottom-color: {{VALUE}};'],
        ]);

        $this->add_control('select_focus_border_color', [
            'label'     => 'Kolor obramowania (focus)',
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .lw-select:focus' => 'border-bottom-color: {{VALUE}};'],
        ]);

        $this->end_controls_section();

        // ── Style: Range slider ──
        $this->start_controls_section('section_style_range', [
            'label' => 'Suwak zakresu',
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('range_track_color', [
            'label'     => 'Kolor ścieżki (aktywna)',
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .lw-range::-webkit-slider-thumb' => 'border-color: {{VALUE}};',
                '{{WRAPPER}} .lw-range::-moz-range-thumb' => 'border-color: {{VALUE}};',
            ],
        ]);

        $this->add_control('range_thumb_bg', [
            'label'     => 'Kolor uchwytu',
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .lw-range::-webkit-slider-thumb' => 'background-color: {{VALUE}};',
                '{{WRAPPER}} .lw-range::-moz-range-thumb' => 'background-color: {{VALUE}};',
            ],
        ]);

        $this->add_control('range_val_color', [
            'label'     => 'Kolor wartości',
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .lw-range-val' => 'color: {{VALUE}};'],
        ]);

        $this->end_controls_section();

        // ── Style: Button ──
        $this->start_controls_section('section_style_button', [
            'label' => 'Przycisk',
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name'     => 'btn_typography',
            'selector' => '{{WRAPPER}} .lw-btn',
        ]);

        $this->add_control('btn_normal_heading', [
            'label' => 'Normalny',
            'type'  => \Elementor\Controls_Manager::HEADING,
        ]);

        $this->add_control('btn_color', [
            'label'     => 'Kolor tekstu',
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .lw-btn' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('btn_bg', [
            'label'     => 'Kolor tła',
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .lw-btn' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Border::get_type(), [
            'name'     => 'btn_border',
            'selector' => '{{WRAPPER}} .lw-btn',
        ]);

        $this->add_group_control(\Elementor\Group_Control_Box_Shadow::get_type(), [
            'name'     => 'btn_shadow',
            'selector' => '{{WRAPPER}} .lw-btn',
        ]);

        $this->add_control('btn_hover_heading', [
            'label'     => 'Hover',
            'type'      => \Elementor\Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_control('btn_color_hover', [
            'label'     => 'Kolor tekstu (hover)',
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .lw-btn:hover' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('btn_bg_hover', [
            'label'     => 'Kolor tła (hover)',
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .lw-btn:hover' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_control('btn_border_color_hover', [
            'label'     => 'Kolor obramowania (hover)',
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .lw-btn:hover' => 'border-color: {{VALUE}};'],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Box_Shadow::get_type(), [
            'name'     => 'btn_shadow_hover',
            'label'    => 'Cień (hover)',
            'selector' => '{{WRAPPER}} .lw-btn:hover',
        ]);

        $this->add_control('btn_layout_heading', [
            'label'     => 'Układ',
            'type'      => \Elementor\Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_control('btn_border_radius', [
            'label'      => 'Zaokrąglenie',
            'type'       => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px'],
            'selectors'  => ['{{WRAPPER}} .lw-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('btn_padding', [
            'label'      => 'Padding',
            'type'       => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px'],
            'selectors'  => ['{{WRAPPER}} .lw-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $button_text = esc_html($settings['button_text']);

        // Enqueue shared assets and localize data
        LW_Shortcode::enqueue_shared_assets();
        ?>
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

            <!-- Row 3: Button -->
            <div class="lw-search-actions">
                <button type="button" id="lw-search-btn" class="lw-btn"><?php echo $button_text; ?></button>
            </div>
        </div>
        <?php
    }
}
