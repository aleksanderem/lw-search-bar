<?php
if (!defined('ABSPATH')) exit;

class LW_Search_Results_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'lw-search-results';
    }

    public function get_title() {
        return 'LW Search Results';
    }

    public function get_icon() {
        return 'eicon-posts-grid';
    }

    public function get_categories() {
        return ['lw-search-bar'];
    }

    public function get_keywords() {
        return ['results', 'apartments', 'grid', 'table', 'lw'];
    }

    public function get_style_depends() {
        return ['lw-search-css'];
    }

    public function get_script_depends() {
        return ['lw-search-js'];
    }

    protected function _register_controls() {
        // ── Content ──
        $this->start_controls_section('section_content', [
            'label' => 'Treść',
            'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('default_view', [
            'label'   => 'Domyślny widok',
            'type'    => \Elementor\Controls_Manager::SELECT,
            'options' => [
                'grid'  => 'Siatka',
                'table' => 'Tabela',
            ],
            'default' => 'grid',
        ]);

        $this->add_control('default_status', [
            'label'   => 'Domyślny filtr statusu',
            'type'    => \Elementor\Controls_Manager::SELECT,
            'options' => [
                ''              => 'Wszystkie statusy',
                'dostepne'      => 'Dostępne',
                'zarezerwowane' => 'Zarezerwowane',
                'sprzedane'     => 'Sprzedane',
            ],
            'default' => 'dostepne',
        ]);

        $this->add_control('hide_before_search', [
            'label'        => 'Ukryj wyniki przed wyszukiwaniem',
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'label_on'     => 'Tak',
            'label_off'    => 'Nie',
            'return_value' => 'yes',
            'default'      => '',
            'description'  => 'Wyniki pokażą się dopiero po kliknięciu SZUKAJ.',
        ]);

        $this->add_control('enable_sticky_bar', [
            'label'        => 'Przypięty pasek na dole',
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'label_on'     => 'Tak',
            'label_off'    => 'Nie',
            'return_value' => 'yes',
            'default'      => 'yes',
            'description'  => 'Pasek z filtrami i sortowaniem pojawiający się po przewinięciu formularza.',
        ]);

        $this->end_controls_section();

        // ── Style: Toolbar ──
        $this->start_controls_section('section_style_toolbar', [
            'label' => 'Pasek narzędzi',
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('toolbar_bg', [
            'label'     => 'Kolor tła',
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .lw-results-toolbar' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('toolbar_padding', [
            'label'      => 'Padding',
            'type'       => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px'],
            'selectors'  => ['{{WRAPPER}} .lw-results-toolbar' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Border::get_type(), [
            'name'     => 'toolbar_border',
            'selector' => '{{WRAPPER}} .lw-results-toolbar',
        ]);

        $this->add_control('toolbar_border_radius', [
            'label'      => 'Zaokrąglenie',
            'type'       => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px'],
            'selectors'  => ['{{WRAPPER}} .lw-results-toolbar' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Box_Shadow::get_type(), [
            'name'     => 'toolbar_shadow',
            'selector' => '{{WRAPPER}} .lw-results-toolbar',
        ]);

        $this->end_controls_section();

        // ── Style: Dropdown buttons ──
        $this->start_controls_section('section_style_dropdown_btns', [
            'label' => 'Przyciski filtrów',
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name'     => 'dropdown_btn_typography',
            'selector' => '{{WRAPPER}} .lw-toolbar-btn',
        ]);

        $this->add_control('dropdown_btn_normal_heading', [
            'label' => 'Normalny',
            'type'  => \Elementor\Controls_Manager::HEADING,
        ]);

        $this->add_control('dropdown_btn_color', [
            'label'     => 'Kolor tekstu',
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .lw-toolbar-btn' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('dropdown_btn_bg', [
            'label'     => 'Kolor tła',
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .lw-toolbar-btn' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Border::get_type(), [
            'name'     => 'dropdown_btn_border',
            'selector' => '{{WRAPPER}} .lw-toolbar-btn',
        ]);

        $this->add_control('dropdown_btn_border_radius', [
            'label'      => 'Zaokrąglenie',
            'type'       => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px'],
            'selectors'  => ['{{WRAPPER}} .lw-toolbar-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Box_Shadow::get_type(), [
            'name'     => 'dropdown_btn_shadow',
            'selector' => '{{WRAPPER}} .lw-toolbar-btn',
        ]);

        $this->add_control('dropdown_btn_hover_heading', [
            'label'     => 'Hover',
            'type'      => \Elementor\Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_control('dropdown_btn_color_hover', [
            'label'     => 'Kolor tekstu (hover)',
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .lw-toolbar-btn:hover' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('dropdown_btn_bg_hover', [
            'label'     => 'Kolor tła (hover)',
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .lw-toolbar-btn:hover' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_control('dropdown_btn_border_color_hover', [
            'label'     => 'Kolor obramowania (hover)',
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .lw-toolbar-btn:hover' => 'border-color: {{VALUE}};'],
        ]);

        $this->add_control('dropdown_btn_active_heading', [
            'label'     => 'Aktywny',
            'type'      => \Elementor\Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_control('dropdown_btn_active_color', [
            'label'     => 'Kolor tekstu (aktywny)',
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .lw-toolbar-btn--active' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('dropdown_btn_active_bg', [
            'label'     => 'Kolor tła (aktywny)',
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .lw-toolbar-btn--active' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_control('dropdown_btn_active_border_color', [
            'label'     => 'Kolor obramowania (aktywny)',
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .lw-toolbar-btn--active' => 'border-color: {{VALUE}};'],
        ]);

        $this->end_controls_section();

        // ── Style: Karty — wspólne ──
        $this->start_controls_section('section_style_cards', [
            'label' => 'Karty — wspólne',
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('card_border_radius', [
            'label'      => 'Zaokrąglenie',
            'type'       => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px'],
            'selectors'  => ['{{WRAPPER}} .lw-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('card_padding', [
            'label'      => 'Padding',
            'type'       => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px'],
            'selectors'  => ['{{WRAPPER}} .lw-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Box_Shadow::get_type(), [
            'name'     => 'card_shadow',
            'label'    => 'Cień',
            'selector' => '{{WRAPPER}} .lw-card',
        ]);

        $this->add_group_control(\Elementor\Group_Control_Box_Shadow::get_type(), [
            'name'     => 'card_shadow_hover',
            'label'    => 'Cień (hover)',
            'selector' => '{{WRAPPER}} .lw-card:hover',
        ]);

        $this->add_responsive_control('card_margin', [
            'label'      => 'Margines',
            'type'       => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px'],
            'selectors'  => ['{{WRAPPER}} .lw-card' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_control('card_gap_heading', [
            'label'     => 'Siatka',
            'type'      => \Elementor\Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_responsive_control('grid_gap', [
            'label'      => 'Odstęp między kartami',
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 0, 'max' => 60]],
            'selectors'  => ['{{WRAPPER}} .lw-grid' => 'gap: {{SIZE}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        // ── Style: Karty — Dostępne ──
        $this->start_controls_section('section_style_card_dostepne', [
            'label' => 'Karty — Dostępne',
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('card_dostepne_bg', [
            'label'     => 'Kolor tła',
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .lw-card--dostepne' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_control('card_dostepne_border_color', [
            'label'     => 'Kolor obramowania',
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .lw-card--dostepne' => 'border-color: {{VALUE}};'],
        ]);

        $this->add_control('card_dostepne_border_width', [
            'label'      => 'Grubość obramowania',
            'type'       => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px'],
            'selectors'  => ['{{WRAPPER}} .lw-card--dostepne' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; border-style: solid;'],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Box_Shadow::get_type(), [
            'name'     => 'card_dostepne_shadow',
            'selector' => '{{WRAPPER}} .lw-card--dostepne',
        ]);

        $this->add_control('card_dostepne_hover_heading', [
            'label'     => 'Hover',
            'type'      => \Elementor\Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_control('card_dostepne_bg_hover', [
            'label'     => 'Kolor tła (hover)',
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .lw-card--dostepne:hover' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_control('card_dostepne_border_color_hover', [
            'label'     => 'Kolor obramowania (hover)',
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .lw-card--dostepne:hover' => 'border-color: {{VALUE}};'],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Box_Shadow::get_type(), [
            'name'     => 'card_dostepne_shadow_hover',
            'label'    => 'Cień (hover)',
            'selector' => '{{WRAPPER}} .lw-card--dostepne:hover',
        ]);

        $this->add_control('card_dostepne_overlay_heading', [
            'label'     => 'Nakładka',
            'type'      => \Elementor\Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_control('card_dostepne_overlay_enable', [
            'label'        => 'Włącz nakładkę',
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'default'      => '',
            'return_value' => 'flex',
            'selectors'    => ['{{WRAPPER}} .lw-card--dostepne .lw-card__overlay' => 'display: {{VALUE}};'],
        ]);

        $this->add_control('card_dostepne_overlay_text', [
            'label'     => 'Tekst nakładki',
            'type'      => \Elementor\Controls_Manager::TEXT,
            'default'   => 'dostępne',
            'condition' => ['card_dostepne_overlay_enable' => 'flex'],
        ]);

        $this->add_control('card_dostepne_overlay_stripe_color1', [
            'label'     => 'Kolor paska 1',
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => 'rgba(200, 200, 200, 0.15)',
            'selectors' => ['{{WRAPPER}} .lw-card--dostepne .lw-card__overlay' => '--lw-stripe1: {{VALUE}};'],
            'condition' => ['card_dostepne_overlay_enable' => 'flex'],
        ]);

        $this->add_control('card_dostepne_overlay_stripe_color2', [
            'label'     => 'Kolor paska 2',
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => 'transparent',
            'selectors' => ['{{WRAPPER}} .lw-card--dostepne .lw-card__overlay' => '--lw-stripe2: {{VALUE}};'],
            'condition' => ['card_dostepne_overlay_enable' => 'flex'],
        ]);

        $this->add_control('card_dostepne_overlay_stripe_width', [
            'label'     => 'Szerokość paska (px)',
            'type'      => \Elementor\Controls_Manager::SLIDER,
            'range'     => ['px' => ['min' => 2, 'max' => 60]],
            'default'   => ['size' => 10, 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .lw-card--dostepne .lw-card__overlay' => '--lw-stripe-w: {{SIZE}}px;'],
            'condition' => ['card_dostepne_overlay_enable' => 'flex'],
        ]);

        $this->add_control('card_dostepne_overlay_stripe_angle', [
            'label'     => 'Kąt pasków (deg)',
            'type'      => \Elementor\Controls_Manager::SLIDER,
            'range'     => ['px' => ['min' => -180, 'max' => 180]],
            'default'   => ['size' => -45, 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .lw-card--dostepne .lw-card__overlay' => '--lw-stripe-angle: {{SIZE}}deg;'],
            'condition' => ['card_dostepne_overlay_enable' => 'flex'],
        ]);

        $this->add_control('card_dostepne_overlay_text_color', [
            'label'     => 'Kolor tekstu',
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .lw-card--dostepne .lw-card__overlay span' => 'color: {{VALUE}};'],
            'condition' => ['card_dostepne_overlay_enable' => 'flex'],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name'      => 'card_dostepne_overlay_typography',
            'selector'  => '{{WRAPPER}} .lw-card--dostepne .lw-card__overlay span',
            'condition' => ['card_dostepne_overlay_enable' => 'flex'],
        ]);

        $this->end_controls_section();

        // ── Style: Karty — Zarezerwowane ──
        $this->start_controls_section('section_style_card_zarezerwowane', [
            'label' => 'Karty — Zarezerwowane',
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('card_zarezerwowane_bg', [
            'label'     => 'Kolor tła',
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .lw-card--zarezerwowane' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_control('card_zarezerwowane_border_color', [
            'label'     => 'Kolor obramowania',
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .lw-card--zarezerwowane' => 'border-color: {{VALUE}};'],
        ]);

        $this->add_control('card_zarezerwowane_border_width', [
            'label'      => 'Grubość obramowania',
            'type'       => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px'],
            'selectors'  => ['{{WRAPPER}} .lw-card--zarezerwowane' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; border-style: solid;'],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Box_Shadow::get_type(), [
            'name'     => 'card_zarezerwowane_shadow',
            'selector' => '{{WRAPPER}} .lw-card--zarezerwowane',
        ]);

        $this->add_control('card_zarezerwowane_hover_heading', [
            'label'     => 'Hover',
            'type'      => \Elementor\Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_control('card_zarezerwowane_bg_hover', [
            'label'     => 'Kolor tła (hover)',
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .lw-card--zarezerwowane:hover' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_control('card_zarezerwowane_border_color_hover', [
            'label'     => 'Kolor obramowania (hover)',
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .lw-card--zarezerwowane:hover' => 'border-color: {{VALUE}};'],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Box_Shadow::get_type(), [
            'name'     => 'card_zarezerwowane_shadow_hover',
            'label'    => 'Cień (hover)',
            'selector' => '{{WRAPPER}} .lw-card--zarezerwowane:hover',
        ]);

        $this->add_control('card_zarezerwowane_overlay_heading', [
            'label'     => 'Nakładka',
            'type'      => \Elementor\Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_control('card_zarezerwowane_overlay_enable', [
            'label'        => 'Włącz nakładkę',
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'default'      => '',
            'return_value' => 'flex',
            'selectors'    => ['{{WRAPPER}} .lw-card--zarezerwowane .lw-card__overlay' => 'display: {{VALUE}};'],
        ]);

        $this->add_control('card_zarezerwowane_overlay_text', [
            'label'     => 'Tekst nakładki',
            'type'      => \Elementor\Controls_Manager::TEXT,
            'default'   => 'zarezerwowane',
            'condition' => ['card_zarezerwowane_overlay_enable' => 'flex'],
        ]);

        $this->add_control('card_zarezerwowane_overlay_stripe_color1', [
            'label'     => 'Kolor paska 1',
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => 'rgba(200, 200, 200, 0.15)',
            'selectors' => ['{{WRAPPER}} .lw-card--zarezerwowane .lw-card__overlay' => '--lw-stripe1: {{VALUE}};'],
            'condition' => ['card_zarezerwowane_overlay_enable' => 'flex'],
        ]);

        $this->add_control('card_zarezerwowane_overlay_stripe_color2', [
            'label'     => 'Kolor paska 2',
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => 'transparent',
            'selectors' => ['{{WRAPPER}} .lw-card--zarezerwowane .lw-card__overlay' => '--lw-stripe2: {{VALUE}};'],
            'condition' => ['card_zarezerwowane_overlay_enable' => 'flex'],
        ]);

        $this->add_control('card_zarezerwowane_overlay_stripe_width', [
            'label'     => 'Szerokość paska (px)',
            'type'      => \Elementor\Controls_Manager::SLIDER,
            'range'     => ['px' => ['min' => 2, 'max' => 60]],
            'default'   => ['size' => 10, 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .lw-card--zarezerwowane .lw-card__overlay' => '--lw-stripe-w: {{SIZE}}px;'],
            'condition' => ['card_zarezerwowane_overlay_enable' => 'flex'],
        ]);

        $this->add_control('card_zarezerwowane_overlay_stripe_angle', [
            'label'     => 'Kąt pasków (deg)',
            'type'      => \Elementor\Controls_Manager::SLIDER,
            'range'     => ['px' => ['min' => -180, 'max' => 180]],
            'default'   => ['size' => -45, 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .lw-card--zarezerwowane .lw-card__overlay' => '--lw-stripe-angle: {{SIZE}}deg;'],
            'condition' => ['card_zarezerwowane_overlay_enable' => 'flex'],
        ]);

        $this->add_control('card_zarezerwowane_overlay_text_color', [
            'label'     => 'Kolor tekstu',
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .lw-card--zarezerwowane .lw-card__overlay span' => 'color: {{VALUE}};'],
            'condition' => ['card_zarezerwowane_overlay_enable' => 'flex'],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name'      => 'card_zarezerwowane_overlay_typography',
            'selector'  => '{{WRAPPER}} .lw-card--zarezerwowane .lw-card__overlay span',
            'condition' => ['card_zarezerwowane_overlay_enable' => 'flex'],
        ]);

        $this->end_controls_section();

        // ── Style: Karty — Sprzedane ──
        $this->start_controls_section('section_style_card_sprzedane', [
            'label' => 'Karty — Sprzedane',
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('card_sprzedane_bg', [
            'label'     => 'Kolor tła',
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .lw-card--sprzedane' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_control('card_sprzedane_border_color', [
            'label'     => 'Kolor obramowania',
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .lw-card--sprzedane' => 'border-color: {{VALUE}};'],
        ]);

        $this->add_control('card_sprzedane_border_width', [
            'label'      => 'Grubość obramowania',
            'type'       => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px'],
            'selectors'  => ['{{WRAPPER}} .lw-card--sprzedane' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; border-style: solid;'],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Box_Shadow::get_type(), [
            'name'     => 'card_sprzedane_shadow',
            'selector' => '{{WRAPPER}} .lw-card--sprzedane',
        ]);

        $this->add_control('card_sprzedane_hover_heading', [
            'label'     => 'Hover',
            'type'      => \Elementor\Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_control('card_sprzedane_bg_hover', [
            'label'     => 'Kolor tła (hover)',
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .lw-card--sprzedane:hover' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_control('card_sprzedane_border_color_hover', [
            'label'     => 'Kolor obramowania (hover)',
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .lw-card--sprzedane:hover' => 'border-color: {{VALUE}};'],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Box_Shadow::get_type(), [
            'name'     => 'card_sprzedane_shadow_hover',
            'label'    => 'Cień (hover)',
            'selector' => '{{WRAPPER}} .lw-card--sprzedane:hover',
        ]);

        $this->add_control('card_sprzedane_overlay_heading', [
            'label'     => 'Nakładka',
            'type'      => \Elementor\Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_control('card_sprzedane_overlay_enable', [
            'label'        => 'Pokaż nakładkę',
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'return_value' => 'flex',
            'default'      => '',
            'selectors'    => ['{{WRAPPER}} .lw-card--sprzedane .lw-card__overlay' => 'display: {{VALUE}};'],
        ]);

        $this->add_control('card_sprzedane_overlay_text', [
            'label'     => 'Tekst nakładki',
            'type'      => \Elementor\Controls_Manager::TEXT,
            'default'   => 'sprzedane',
            'condition' => ['card_sprzedane_overlay_enable' => 'flex'],
        ]);

        $this->add_control('card_sprzedane_overlay_stripe_color1', [
            'label'     => 'Kolor paska 1',
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => 'rgba(200, 200, 200, 0.15)',
            'selectors' => ['{{WRAPPER}} .lw-card--sprzedane .lw-card__overlay' => '--lw-stripe1: {{VALUE}};'],
            'condition' => ['card_sprzedane_overlay_enable' => 'flex'],
        ]);

        $this->add_control('card_sprzedane_overlay_stripe_color2', [
            'label'     => 'Kolor paska 2',
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => 'transparent',
            'selectors' => ['{{WRAPPER}} .lw-card--sprzedane .lw-card__overlay' => '--lw-stripe2: {{VALUE}};'],
            'condition' => ['card_sprzedane_overlay_enable' => 'flex'],
        ]);

        $this->add_control('card_sprzedane_overlay_stripe_width', [
            'label'     => 'Szerokość paska',
            'type'      => \Elementor\Controls_Manager::SLIDER,
            'range'     => ['px' => ['min' => 2, 'max' => 50]],
            'default'   => ['size' => 10, 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .lw-card--sprzedane .lw-card__overlay' => '--lw-stripe-w: {{SIZE}}px;'],
            'condition' => ['card_sprzedane_overlay_enable' => 'flex'],
        ]);

        $this->add_control('card_sprzedane_overlay_stripe_angle', [
            'label'     => 'Kąt pasków',
            'type'      => \Elementor\Controls_Manager::SLIDER,
            'range'     => ['px' => ['min' => -180, 'max' => 180]],
            'default'   => ['size' => -45, 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .lw-card--sprzedane .lw-card__overlay' => '--lw-stripe-angle: {{SIZE}}deg;'],
            'condition' => ['card_sprzedane_overlay_enable' => 'flex'],
        ]);

        $this->add_control('card_sprzedane_overlay_text_color', [
            'label'     => 'Kolor tekstu',
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .lw-card--sprzedane .lw-card__overlay span' => 'color: {{VALUE}};'],
            'condition' => ['card_sprzedane_overlay_enable' => 'flex'],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name'      => 'card_sprzedane_overlay_typography',
            'selector'  => '{{WRAPPER}} .lw-card--sprzedane .lw-card__overlay span',
            'condition' => ['card_sprzedane_overlay_enable' => 'flex'],
        ]);

        $this->end_controls_section();

        // ── Style: Thumbnail ──
        $this->start_controls_section('section_style_thumb', [
            'label' => 'Miniatura',
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('thumb_source', [
            'label'        => 'Źródło — Desktop',
            'type'         => \Elementor\Controls_Manager::SELECT,
            'options'      => [
                'rzut' => 'Rzut',
                'viz'  => 'Wizualizacja 3D',
            ],
            'default'      => 'rzut',
            'prefix_class' => 'lw-thumb-',
        ]);

        $this->add_control('thumb_source_tablet', [
            'label'        => 'Źródło — Tablet',
            'type'         => \Elementor\Controls_Manager::SELECT,
            'options'      => [
                ''     => 'Jak Desktop',
                'rzut' => 'Rzut',
                'viz'  => 'Wizualizacja 3D',
            ],
            'default'      => '',
            'prefix_class' => 'lw-thumb-tablet-',
        ]);

        $this->add_control('thumb_source_mobile', [
            'label'        => 'Źródło — Mobile',
            'type'         => \Elementor\Controls_Manager::SELECT,
            'options'      => [
                ''     => 'Jak Desktop',
                'rzut' => 'Rzut',
                'viz'  => 'Wizualizacja 3D',
            ],
            'default'      => '',
            'prefix_class' => 'lw-thumb-mobile-',
        ]);

        $this->add_control('thumb_width_mode', [
            'label'   => 'Szerokość',
            'type'    => \Elementor\Controls_Manager::SELECT,
            'options' => [
                'auto'   => 'Auto',
                'custom' => 'Własna',
            ],
            'default'   => 'custom',
            'selectors' => [
                '{{WRAPPER}} .lw-card__thumb' => '{{VALUE}};',
            ],
            'selectors_dictionary' => [
                'auto'   => 'width: auto',
                'custom' => '',
            ],
        ]);

        $this->add_responsive_control('thumb_width', [
            'label'      => 'Szerokość (px)',
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 0, 'max' => 600]],
            'selectors'  => ['{{WRAPPER}} .lw-card__thumb' => 'width: {{SIZE}}{{UNIT}};'],
            'condition'  => ['thumb_width_mode' => 'custom'],
        ]);

        $this->add_control('thumb_height_mode', [
            'label'   => 'Wysokość',
            'type'    => \Elementor\Controls_Manager::SELECT,
            'options' => [
                'auto'   => 'Auto',
                'custom' => 'Własna',
            ],
            'default'   => 'custom',
            'selectors' => [
                '{{WRAPPER}} .lw-card__thumb' => '{{VALUE}};',
            ],
            'selectors_dictionary' => [
                'auto'   => 'height: auto',
                'custom' => '',
            ],
        ]);

        $this->add_responsive_control('thumb_height', [
            'label'      => 'Wysokość (px)',
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 0, 'max' => 600]],
            'selectors'  => ['{{WRAPPER}} .lw-card__thumb' => 'height: {{SIZE}}{{UNIT}};'],
            'condition'  => ['thumb_height_mode' => 'custom'],
        ]);

        $this->add_responsive_control('thumb_padding', [
            'label'      => 'Padding',
            'type'       => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px'],
            'selectors'  => ['{{WRAPPER}} .lw-card__thumb' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('thumb_margin', [
            'label'      => 'Margines',
            'type'       => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px'],
            'selectors'  => ['{{WRAPPER}} .lw-card__thumb' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_control('thumb_border_radius', [
            'label'      => 'Zaokrąglenie',
            'type'       => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px'],
            'selectors'  => ['{{WRAPPER}} .lw-card__thumb' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_control('thumb_object_fit', [
            'label'   => 'Dopasowanie obrazka',
            'type'    => \Elementor\Controls_Manager::SELECT,
            'options' => [
                'cover'      => 'Cover',
                'contain'    => 'Contain',
                'fill'       => 'Fill',
                'none'       => 'None',
                'scale-down' => 'Scale Down',
            ],
            'default'   => 'cover',
            'selectors' => ['{{WRAPPER}} .lw-card__thumb img' => 'object-fit: {{VALUE}};'],
        ]);

        $this->add_control('thumb_object_position', [
            'label'       => 'Pozycja obrazka',
            'type'        => \Elementor\Controls_Manager::TEXT,
            'default'     => 'center center',
            'placeholder' => 'center center',
            'selectors'   => ['{{WRAPPER}} .lw-card__thumb img' => 'object-position: {{VALUE}};'],
        ]);

        $this->end_controls_section();

        // ── Style: Info container ──
        $this->start_controls_section('section_style_info', [
            'label' => 'Kontener info',
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('info_padding', [
            'label'      => 'Padding',
            'type'       => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px'],
            'selectors'  => ['{{WRAPPER}} .lw-card__info' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('info_margin', [
            'label'      => 'Margines',
            'type'       => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px'],
            'selectors'  => ['{{WRAPPER}} .lw-card__info' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        // ── Style: Card title ──
        $this->start_controls_section('section_style_card_title', [
            'label' => 'Tytuł karty',
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name'     => 'card_title_typography',
            'selector' => '{{WRAPPER}} .lw-card__title',
        ]);

        $this->add_control('card_title_color', [
            'label'     => 'Kolor',
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .lw-card__title' => 'color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('card_title_padding', [
            'label'      => 'Padding',
            'type'       => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px'],
            'selectors'  => ['{{WRAPPER}} .lw-card__title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('card_title_margin', [
            'label'      => 'Margines',
            'type'       => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px'],
            'selectors'  => ['{{WRAPPER}} .lw-card__title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        // ── Style: Investment badge ──
        $this->start_controls_section('section_style_inv_badge', [
            'label' => 'Badge inwestycji',
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name'     => 'inv_badge_typography',
            'selector' => '{{WRAPPER}} .lw-card__investment',
        ]);

        $this->add_control('inv_badge_color', [
            'label'     => 'Kolor tekstu',
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .lw-card__investment' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('inv_badge_bg', [
            'label'     => 'Kolor tła',
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .lw-card__investment' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Border::get_type(), [
            'name'     => 'inv_badge_border',
            'selector' => '{{WRAPPER}} .lw-card__investment',
        ]);

        $this->add_control('inv_badge_border_radius', [
            'label'      => 'Zaokrąglenie',
            'type'       => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px'],
            'selectors'  => ['{{WRAPPER}} .lw-card__investment' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('inv_badge_padding', [
            'label'      => 'Padding',
            'type'       => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px'],
            'selectors'  => ['{{WRAPPER}} .lw-card__investment' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('inv_badge_margin', [
            'label'      => 'Margines',
            'type'       => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px'],
            'selectors'  => ['{{WRAPPER}} .lw-card__investment' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        // ── Style: Detail list ──
        $this->start_controls_section('section_style_details', [
            'label' => 'Szczegóły karty',
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name'     => 'details_typography',
            'selector' => '{{WRAPPER}} .lw-card__details',
        ]);

        $this->add_control('details_color', [
            'label'     => 'Kolor tekstu',
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .lw-card__details' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('details_bullet_color', [
            'label'     => 'Kolor znacznika',
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .lw-card__details li::before' => 'color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('details_padding', [
            'label'      => 'Padding',
            'type'       => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px'],
            'selectors'  => ['{{WRAPPER}} .lw-card__details' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('details_margin', [
            'label'      => 'Margines',
            'type'       => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px'],
            'selectors'  => ['{{WRAPPER}} .lw-card__details' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        // ── Style: Action buttons ──
        $this->start_controls_section('section_style_action_btns', [
            'label' => 'Przyciski akcji',
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name'     => 'action_btn_typography',
            'selector' => '{{WRAPPER}} .lw-card__btn',
        ]);

        $this->add_control('action_btn_normal_heading', [
            'label' => 'Normalny',
            'type'  => \Elementor\Controls_Manager::HEADING,
        ]);

        $this->add_control('action_btn_color', [
            'label'     => 'Kolor tekstu',
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .lw-card__btn--viz' => 'color: {{VALUE}};',
                '{{WRAPPER}} .lw-card__btn--view' => 'color: {{VALUE}};',
                '{{WRAPPER}} .lw-card__btn--rzuty' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('action_btn_bg', [
            'label'     => 'Kolor tła',
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .lw-card__btn--viz' => 'background-color: {{VALUE}};',
                '{{WRAPPER}} .lw-card__btn--view' => 'background-color: {{VALUE}};',
                '{{WRAPPER}} .lw-card__btn--rzuty' => 'background-color: {{VALUE}};',
            ],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Border::get_type(), [
            'name'     => 'action_btn_border',
            'selector' => '{{WRAPPER}} .lw-card__btn--viz, {{WRAPPER}} .lw-card__btn--view, {{WRAPPER}} .lw-card__btn--rzuty',
        ]);

        $this->add_control('action_btn_border_radius', [
            'label'      => 'Zaokrąglenie',
            'type'       => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px'],
            'selectors'  => ['{{WRAPPER}} .lw-card__btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('action_btn_padding', [
            'label'      => 'Padding',
            'type'       => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px'],
            'selectors'  => ['{{WRAPPER}} .lw-card__btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('action_btn_margin', [
            'label'      => 'Margines przycisku',
            'type'       => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px'],
            'selectors'  => ['{{WRAPPER}} .lw-card__btn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('actions_padding', [
            'label'      => 'Padding kontenera',
            'type'       => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px'],
            'selectors'  => ['{{WRAPPER}} .lw-card__actions' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('actions_margin', [
            'label'      => 'Margines kontenera',
            'type'       => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px'],
            'selectors'  => ['{{WRAPPER}} .lw-card__actions' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Box_Shadow::get_type(), [
            'name'     => 'action_btn_shadow',
            'selector' => '{{WRAPPER}} .lw-card__btn--viz, {{WRAPPER}} .lw-card__btn--view, {{WRAPPER}} .lw-card__btn--rzuty',
        ]);

        $this->add_control('action_btn_hover_heading', [
            'label'     => 'Hover',
            'type'      => \Elementor\Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_control('action_btn_color_hover', [
            'label'     => 'Kolor tekstu (hover)',
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .lw-card__btn--viz:hover' => 'color: {{VALUE}};',
                '{{WRAPPER}} .lw-card__btn--view:hover' => 'color: {{VALUE}};',
                '{{WRAPPER}} .lw-card__btn--rzuty:hover' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('action_btn_bg_hover', [
            'label'     => 'Kolor tła (hover)',
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .lw-card__btn--viz:hover' => 'background-color: {{VALUE}};',
                '{{WRAPPER}} .lw-card__btn--view:hover' => 'background-color: {{VALUE}};',
                '{{WRAPPER}} .lw-card__btn--rzuty:hover' => 'background-color: {{VALUE}};',
            ],
        ]);

        $this->add_control('action_btn_border_color_hover', [
            'label'     => 'Kolor obramowania (hover)',
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .lw-card__btn--viz:hover' => 'border-color: {{VALUE}};',
                '{{WRAPPER}} .lw-card__btn--view:hover' => 'border-color: {{VALUE}};',
                '{{WRAPPER}} .lw-card__btn--rzuty:hover' => 'border-color: {{VALUE}};',
            ],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Box_Shadow::get_type(), [
            'name'     => 'action_btn_shadow_hover',
            'label'    => 'Cień (hover)',
            'selector' => '{{WRAPPER}} .lw-card__btn--viz:hover, {{WRAPPER}} .lw-card__btn--view:hover, {{WRAPPER}} .lw-card__btn--rzuty:hover',
        ]);

        $this->add_control('inquiry_btn_heading', [
            'label'     => 'Przycisk zapytania',
            'type'      => \Elementor\Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_control('inquiry_btn_color', [
            'label'     => 'Kolor tekstu',
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .lw-card__btn--inquiry' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('inquiry_btn_bg', [
            'label'     => 'Kolor tła',
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .lw-card__btn--inquiry' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_control('inquiry_btn_border_color', [
            'label'     => 'Kolor obramowania',
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .lw-card__btn--inquiry' => 'border-color: {{VALUE}};'],
        ]);

        $this->add_control('inquiry_btn_color_hover', [
            'label'     => 'Kolor tekstu (hover)',
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .lw-card__btn--inquiry:hover' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('inquiry_btn_bg_hover', [
            'label'     => 'Kolor tła (hover)',
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .lw-card__btn--inquiry:hover' => 'background-color: {{VALUE}};'],
        ]);

        $this->end_controls_section();

        // ── Style: Status badges ──
        $this->start_controls_section('section_style_status', [
            'label' => 'Badge statusu',
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name'     => 'status_typography',
            'selector' => '{{WRAPPER}} .lw-card__status',
        ]);

        $this->add_control('status_text_color', [
            'label'     => 'Kolor tekstu',
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .lw-card__status' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('status_border_radius', [
            'label'      => 'Zaokrąglenie',
            'type'       => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px'],
            'selectors'  => ['{{WRAPPER}} .lw-card__status' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('status_padding', [
            'label'      => 'Padding',
            'type'       => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px'],
            'selectors'  => ['{{WRAPPER}} .lw-card__status' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('status_margin', [
            'label'      => 'Margines',
            'type'       => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px'],
            'selectors'  => ['{{WRAPPER}} .lw-card__status' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_control('status_position_heading', [
            'label'     => 'Pozycjonowanie',
            'type'      => \Elementor\Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_control('status_position', [
            'label'        => 'Pozycja — Desktop',
            'type'         => \Elementor\Controls_Manager::SELECT,
            'options'      => [
                'relative' => 'Relative',
                'absolute' => 'Absolute',
            ],
            'default'      => 'relative',
            'prefix_class' => 'lw-status-pos-',
        ]);

        $this->add_control('status_position_tablet', [
            'label'        => 'Pozycja — Tablet',
            'type'         => \Elementor\Controls_Manager::SELECT,
            'options'      => [
                ''         => 'Jak Desktop',
                'relative' => 'Relative',
                'absolute' => 'Absolute',
            ],
            'default'      => '',
            'prefix_class' => 'lw-status-pos-tablet-',
        ]);

        $this->add_control('status_position_mobile', [
            'label'        => 'Pozycja — Mobile',
            'type'         => \Elementor\Controls_Manager::SELECT,
            'options'      => [
                ''         => 'Jak Desktop',
                'relative' => 'Relative',
                'absolute' => 'Absolute',
            ],
            'default'      => '',
            'prefix_class' => 'lw-status-pos-mobile-',
        ]);

        $this->add_control('status_pos_top', [
            'label'      => 'Góra (top)',
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => -200, 'max' => 200]],
            'selectors'  => ['{{WRAPPER}} .lw-card__status' => 'top: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_control('status_pos_right', [
            'label'      => 'Prawo (right)',
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => -200, 'max' => 200]],
            'selectors'  => ['{{WRAPPER}} .lw-card__status' => 'right: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_control('status_pos_bottom', [
            'label'      => 'Dół (bottom)',
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => -200, 'max' => 200]],
            'selectors'  => ['{{WRAPPER}} .lw-card__status' => 'bottom: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_control('status_pos_left', [
            'label'      => 'Lewo (left)',
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => -200, 'max' => 200]],
            'selectors'  => ['{{WRAPPER}} .lw-card__status' => 'left: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_control('status_z_index', [
            'label'      => 'Z-index',
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'range'      => ['px' => ['min' => 0, 'max' => 100]],
            'selectors'  => ['{{WRAPPER}} .lw-card__status' => 'z-index: {{SIZE}};'],
        ]);

        $this->add_control('status_dostepne_heading', [
            'label'     => 'Dostępne',
            'type'      => \Elementor\Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_control('status_dostepne_bg', [
            'label'     => 'Kolor tła',
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .lw-status--dostepne' => 'background-color: {{VALUE}};',
                '{{WRAPPER}} .lw-card--dostepne' => 'border-color: {{VALUE}};',
            ],
        ]);

        $this->add_control('status_dostepne_color', [
            'label'     => 'Kolor tekstu',
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .lw-status--dostepne' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('status_zarezerwowane_heading', [
            'label'     => 'Zarezerwowane',
            'type'      => \Elementor\Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_control('status_zarezerwowane_bg', [
            'label'     => 'Kolor tła',
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .lw-status--zarezerwowane' => 'background-color: {{VALUE}};',
                '{{WRAPPER}} .lw-card--zarezerwowane' => 'border-color: {{VALUE}};',
            ],
        ]);

        $this->add_control('status_zarezerwowane_color', [
            'label'     => 'Kolor tekstu',
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .lw-status--zarezerwowane' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('status_sprzedane_heading', [
            'label'     => 'Sprzedane',
            'type'      => \Elementor\Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_control('status_sprzedane_bg', [
            'label'     => 'Kolor tła',
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .lw-status--sprzedane' => 'background-color: {{VALUE}};',
                '{{WRAPPER}} .lw-card--sprzedane' => 'border-color: {{VALUE}};',
            ],
        ]);

        $this->add_control('status_sprzedane_color', [
            'label'     => 'Kolor tekstu',
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .lw-status--sprzedane' => 'color: {{VALUE}};'],
        ]);

        $this->end_controls_section();

        // ── Style: Table ──
        $this->start_controls_section('section_style_table', [
            'label' => 'Tabela',
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(\Elementor\Group_Control_Border::get_type(), [
            'name'     => 'table_border',
            'selector' => '{{WRAPPER}} .lw-table',
        ]);

        $this->add_control('table_border_radius', [
            'label'      => 'Zaokrąglenie',
            'type'       => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px'],
            'selectors'  => ['{{WRAPPER}} .lw-table' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Box_Shadow::get_type(), [
            'name'     => 'table_shadow',
            'selector' => '{{WRAPPER}} .lw-table',
        ]);

        $this->add_control('table_header_heading', [
            'label'     => 'Nagłówek',
            'type'      => \Elementor\Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name'     => 'table_header_typography',
            'selector' => '{{WRAPPER}} .lw-table thead th',
        ]);

        $this->add_control('table_header_color', [
            'label'     => 'Kolor tekstu',
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .lw-table thead th' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('table_header_bg', [
            'label'     => 'Kolor tła',
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .lw-table thead th' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_control('table_header_border_color', [
            'label'     => 'Kolor obramowania',
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .lw-table thead th' => 'border-bottom-color: {{VALUE}};'],
        ]);

        $this->add_control('table_rows_heading', [
            'label'     => 'Wiersze',
            'type'      => \Elementor\Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name'     => 'table_row_typography',
            'selector' => '{{WRAPPER}} .lw-table tbody td',
        ]);

        $this->add_control('table_row_color', [
            'label'     => 'Kolor tekstu',
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .lw-table tbody td' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('table_row_bg', [
            'label'     => 'Kolor tła',
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .lw-table tbody tr' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_control('table_row_border_color', [
            'label'     => 'Kolor obramowania wiersza',
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .lw-table tbody tr' => 'border-bottom-color: {{VALUE}};'],
        ]);

        $this->add_control('table_row_hover_bg', [
            'label'     => 'Kolor tła (hover)',
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .lw-table tbody tr:hover' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_control('table_link_color', [
            'label'     => 'Kolor linku',
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .lw-table tbody td a' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('table_link_color_hover', [
            'label'     => 'Kolor linku (hover)',
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .lw-table tbody td a:hover' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('table_actions_heading', [
            'label'     => 'Przyciski akcji',
            'type'      => \Elementor\Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_control('table_action_size', [
            'label'      => 'Rozmiar',
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'range'      => ['px' => ['min' => 24, 'max' => 48]],
            'selectors'  => ['{{WRAPPER}} .lw-table-action' => 'width: {{SIZE}}px; height: {{SIZE}}px;'],
        ]);

        $this->add_control('table_action_icon_size', [
            'label'      => 'Rozmiar ikony',
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'range'      => ['px' => ['min' => 10, 'max' => 32]],
            'selectors'  => ['{{WRAPPER}} .lw-table-action easier-icon' => 'font-size: {{SIZE}}px;'],
        ]);

        $this->add_control('table_action_bg', [
            'label'     => 'Kolor tła',
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .lw-table-action' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_control('table_action_color', [
            'label'     => 'Kolor ikony',
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .lw-table-action' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('table_action_border_color', [
            'label'     => 'Kolor obramowania',
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .lw-table-action' => 'border-color: {{VALUE}};'],
        ]);

        $this->add_control('table_action_radius', [
            'label'      => 'Zaokrąglenie',
            'type'       => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px'],
            'selectors'  => ['{{WRAPPER}} .lw-table-action' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_control('table_action_bg_hover', [
            'label'     => 'Kolor tła (hover)',
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .lw-table-action:hover' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_control('table_action_color_hover', [
            'label'     => 'Kolor ikony (hover)',
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .lw-table-action:hover' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('table_action_inquiry_heading', [
            'label'     => 'Przycisk zapytania',
            'type'      => \Elementor\Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_control('table_action_inquiry_bg', [
            'label'     => 'Kolor tła',
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .lw-table-action--inquiry' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_control('table_action_inquiry_color', [
            'label'     => 'Kolor ikony',
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .lw-table-action--inquiry' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('table_action_inquiry_bg_hover', [
            'label'     => 'Kolor tła (hover)',
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .lw-table-action--inquiry:hover' => 'background-color: {{VALUE}};'],
        ]);

        $this->end_controls_section();

        // ── Style: Sticky bar ──
        $this->start_controls_section('section_style_sticky_bar', [
            'label'     => 'Przypięty pasek',
            'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
            'condition' => ['enable_sticky_bar' => 'yes'],
        ]);

        $this->add_responsive_control('sticky_bar_width', [
            'label'      => 'Szerokość paska',
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px', '%', 'vw'],
            'range'      => [
                'px' => ['min' => 300, 'max' => 1920],
                '%'  => ['min' => 30, 'max' => 100],
                'vw' => ['min' => 30, 'max' => 100],
            ],
            'selectors'  => [
                '{{WRAPPER}} .lw-sticky-bar' => 'width: {{SIZE}}{{UNIT}}; left: 50% !important; right: auto !important; transform: translateX(-50%);',
                '.lw-sticky-bar' => 'width: {{SIZE}}{{UNIT}}; left: 50% !important; right: auto !important; transform: translateX(-50%);',
            ],
        ]);

        $this->add_control('sticky_bar_bg', [
            'label'     => 'Kolor tła',
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .lw-sticky-bar' => 'background-color: {{VALUE}};',
                '.lw-sticky-bar' => 'background-color: {{VALUE}};',
            ],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Border::get_type(), [
            'name'     => 'sticky_bar_border',
            'selector' => '{{WRAPPER}} .lw-sticky-bar, .lw-sticky-bar',
        ]);

        $this->add_control('sticky_bar_border_radius', [
            'label'      => 'Zaokrąglenie',
            'type'       => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px'],
            'selectors'  => [
                '{{WRAPPER}} .lw-sticky-bar' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                '.lw-sticky-bar' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Box_Shadow::get_type(), [
            'name'     => 'sticky_bar_shadow',
            'selector' => '{{WRAPPER}} .lw-sticky-bar, .lw-sticky-bar',
        ]);

        $this->add_responsive_control('sticky_bar_bottom', [
            'label'      => 'Odstęp od dołu',
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 0, 'max' => 100]],
            'selectors'  => [
                '{{WRAPPER}} .lw-sticky-bar' => 'bottom: {{SIZE}}{{UNIT}} !important;',
                '.lw-sticky-bar' => 'bottom: {{SIZE}}{{UNIT}} !important;',
            ],
        ]);

        $this->add_responsive_control('sticky_bar_padding', [
            'label'      => 'Padding',
            'type'       => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em'],
            'selectors'  => [
                '{{WRAPPER}} .lw-sticky-bar__inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                '.lw-sticky-bar__inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        // ── Toolbar buttons (status, sort) ──
        $this->add_control('sticky_toolbar_heading', [
            'label'     => 'Przyciski filtrów w pasku',
            'type'      => \Elementor\Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name'     => 'sticky_toolbar_typography',
            'selector' => '{{WRAPPER}} .lw-sticky-bar .lw-toolbar-btn, .lw-sticky-bar .lw-toolbar-btn',
        ]);

        // ── Przycisk Filtry ──
        $this->add_control('sticky_filter_heading', [
            'label'     => 'Przycisk Filtry',
            'type'      => \Elementor\Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name'     => 'sticky_filter_typography',
            'selector' => '{{WRAPPER}} .lw-sticky-bar__btn--filter, .lw-sticky-bar__btn--filter',
        ]);

        $this->add_control('sticky_filter_border_radius', [
            'label'      => 'Zaokrąglenie',
            'type'       => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px'],
            'selectors'  => [
                '{{WRAPPER}} .lw-sticky-bar__btn--filter' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                '.lw-sticky-bar__btn--filter' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('sticky_filter_padding', [
            'label'      => 'Padding',
            'type'       => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em'],
            'selectors'  => [
                '{{WRAPPER}} .lw-sticky-bar__btn--filter' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                '.lw-sticky-bar__btn--filter' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('sticky_filter_margin', [
            'label'      => 'Margines',
            'type'       => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em'],
            'selectors'  => [
                '{{WRAPPER}} .lw-sticky-bar__btn--filter' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                '.lw-sticky-bar__btn--filter' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->start_controls_tabs('sticky_filter_tabs');

        $this->start_controls_tab('sticky_filter_tab_normal', [
            'label' => 'Normalny',
        ]);

        $this->add_control('sticky_filter_bg', [
            'label'     => 'Kolor tła',
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .lw-sticky-bar__btn--filter' => 'background-color: {{VALUE}};',
                '.lw-sticky-bar__btn--filter' => 'background-color: {{VALUE}};',
            ],
        ]);

        $this->add_control('sticky_filter_color', [
            'label'     => 'Kolor tekstu',
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .lw-sticky-bar__btn--filter' => 'color: {{VALUE}};',
                '.lw-sticky-bar__btn--filter' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Border::get_type(), [
            'name'     => 'sticky_filter_border',
            'selector' => '{{WRAPPER}} .lw-sticky-bar__btn--filter, .lw-sticky-bar__btn--filter',
        ]);

        $this->add_group_control(\Elementor\Group_Control_Box_Shadow::get_type(), [
            'name'     => 'sticky_filter_shadow',
            'selector' => '{{WRAPPER}} .lw-sticky-bar__btn--filter, .lw-sticky-bar__btn--filter',
        ]);

        $this->end_controls_tab();

        $this->start_controls_tab('sticky_filter_tab_hover', [
            'label' => 'Hover',
        ]);

        $this->add_control('sticky_filter_bg_hover', [
            'label'     => 'Kolor tła',
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .lw-sticky-bar__btn--filter:hover' => 'background-color: {{VALUE}};',
                '.lw-sticky-bar__btn--filter:hover' => 'background-color: {{VALUE}};',
            ],
        ]);

        $this->add_control('sticky_filter_color_hover', [
            'label'     => 'Kolor tekstu',
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .lw-sticky-bar__btn--filter:hover' => 'color: {{VALUE}};',
                '.lw-sticky-bar__btn--filter:hover' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Border::get_type(), [
            'name'     => 'sticky_filter_border_hover',
            'selector' => '{{WRAPPER}} .lw-sticky-bar__btn--filter:hover, .lw-sticky-bar__btn--filter:hover',
        ]);

        $this->add_group_control(\Elementor\Group_Control_Box_Shadow::get_type(), [
            'name'     => 'sticky_filter_shadow_hover',
            'selector' => '{{WRAPPER}} .lw-sticky-bar__btn--filter:hover, .lw-sticky-bar__btn--filter:hover',
        ]);

        $this->end_controls_tab();

        $this->end_controls_tabs();

        // ── Przycisk Szukaj ──
        $this->add_control('sticky_search_heading', [
            'label'     => 'Przycisk Szukaj',
            'type'      => \Elementor\Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name'     => 'sticky_search_typography',
            'selector' => '{{WRAPPER}} .lw-sticky-bar__btn--search, .lw-sticky-bar__btn--search',
        ]);

        $this->add_control('sticky_search_border_radius', [
            'label'      => 'Zaokrąglenie',
            'type'       => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px'],
            'selectors'  => [
                '{{WRAPPER}} .lw-sticky-bar__btn--search' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                '.lw-sticky-bar__btn--search' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('sticky_search_padding', [
            'label'      => 'Padding',
            'type'       => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em'],
            'selectors'  => [
                '{{WRAPPER}} .lw-sticky-bar__btn--search' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                '.lw-sticky-bar__btn--search' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('sticky_search_margin', [
            'label'      => 'Margines',
            'type'       => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em'],
            'selectors'  => [
                '{{WRAPPER}} .lw-sticky-bar__btn--search' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                '.lw-sticky-bar__btn--search' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->start_controls_tabs('sticky_search_tabs');

        $this->start_controls_tab('sticky_search_tab_normal', [
            'label' => 'Normalny',
        ]);

        $this->add_control('sticky_search_bg', [
            'label'     => 'Kolor tła',
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .lw-sticky-bar__btn--search' => 'background-color: {{VALUE}};',
                '.lw-sticky-bar__btn--search' => 'background-color: {{VALUE}};',
            ],
        ]);

        $this->add_control('sticky_search_color', [
            'label'     => 'Kolor tekstu',
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .lw-sticky-bar__btn--search' => 'color: {{VALUE}};',
                '.lw-sticky-bar__btn--search' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Border::get_type(), [
            'name'     => 'sticky_search_border',
            'selector' => '{{WRAPPER}} .lw-sticky-bar__btn--search, .lw-sticky-bar__btn--search',
        ]);

        $this->add_group_control(\Elementor\Group_Control_Box_Shadow::get_type(), [
            'name'     => 'sticky_search_shadow',
            'selector' => '{{WRAPPER}} .lw-sticky-bar__btn--search, .lw-sticky-bar__btn--search',
        ]);

        $this->end_controls_tab();

        $this->start_controls_tab('sticky_search_tab_hover', [
            'label' => 'Hover',
        ]);

        $this->add_control('sticky_search_bg_hover', [
            'label'     => 'Kolor tła',
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .lw-sticky-bar__btn--search:hover' => 'background-color: {{VALUE}};',
                '.lw-sticky-bar__btn--search:hover' => 'background-color: {{VALUE}};',
            ],
        ]);

        $this->add_control('sticky_search_color_hover', [
            'label'     => 'Kolor tekstu',
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .lw-sticky-bar__btn--search:hover' => 'color: {{VALUE}};',
                '.lw-sticky-bar__btn--search:hover' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Border::get_type(), [
            'name'     => 'sticky_search_border_hover',
            'selector' => '{{WRAPPER}} .lw-sticky-bar__btn--search:hover, .lw-sticky-bar__btn--search:hover',
        ]);

        $this->add_group_control(\Elementor\Group_Control_Box_Shadow::get_type(), [
            'name'     => 'sticky_search_shadow_hover',
            'selector' => '{{WRAPPER}} .lw-sticky-bar__btn--search:hover, .lw-sticky-bar__btn--search:hover',
        ]);

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $default_view   = $settings['default_view'];
        $default_status = $settings['default_status'];

        // Enqueue shared assets (in case only results widget is on the page)
        LW_Shortcode::enqueue_shared_assets();

        // Map status value to display label
        $status_labels = [
            ''              => 'Wszystkie statusy',
            'dostepne'      => 'Dostępne',
            'zarezerwowane' => 'Zarezerwowane',
            'sprzedane'     => 'Sprzedane',
        ];
        $status_label = $status_labels[$default_status] ?? 'Dostępne';
        $hide_before = $settings['hide_before_search'] === 'yes';
        ?>
        <div class="lw-results-toolbar"<?php if ($hide_before) echo ' style="display:none;"'; ?> id="lw-results-toolbar">
            <div class="lw-toolbar-left">
                <div class="lw-dropdown" id="lw-dropdown-status">
                    <button type="button" class="lw-toolbar-btn<?php echo $default_status ? ' lw-toolbar-btn--active' : ''; ?>" id="lw-btn-status">
                        <span class="lw-toolbar-btn__label"><?php echo esc_html($status_label); ?></span>
                        <svg width="10" height="6" viewBox="0 0 10 6" fill="none"><path d="M1 1L5 5L9 1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </button>
                    <div class="lw-dropdown__menu">
                        <button type="button" class="lw-dropdown__item<?php echo $default_status === '' ? ' lw-dropdown__item--active' : ''; ?>" data-value="">Wszystkie statusy</button>
                        <button type="button" class="lw-dropdown__item<?php echo $default_status === 'dostepne' ? ' lw-dropdown__item--active' : ''; ?>" data-value="dostepne">Dostępne</button>
                        <button type="button" class="lw-dropdown__item<?php echo $default_status === 'zarezerwowane' ? ' lw-dropdown__item--active' : ''; ?>" data-value="zarezerwowane">Zarezerwowane</button>
                        <button type="button" class="lw-dropdown__item<?php echo $default_status === 'sprzedane' ? ' lw-dropdown__item--active' : ''; ?>" data-value="sprzedane">Sprzedane</button>
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
                <button type="button" class="lw-view-btn<?php echo $default_view === 'grid' ? ' lw-view-btn--active' : ''; ?>" data-view="grid" data-tooltip="Widok siatki">
                    <easier-icon name="grid-view" variant="stroke" size="18"></easier-icon>
                </button>
                <button type="button" class="lw-view-btn<?php echo $default_view === 'table' ? ' lw-view-btn--active' : ''; ?>" data-view="table" data-tooltip="Widok tabeli">
                    <easier-icon name="menu-01" variant="stroke" size="18"></easier-icon>
                </button>
            </div>
        </div>

        <?php
        $is_editor = \Elementor\Plugin::$instance->editor->is_edit_mode();

        if ($is_editor) {
            // Render server-side preview with real data so styles are visible in editor
            $apartments = LW_Shortcode::get_all_apartments();

            // In editor: show one card per status so all styles are visible
            $by_status = [];
            foreach (['dostepne', 'zarezerwowane', 'sprzedane'] as $st_key) {
                foreach ($apartments as $a) {
                    if (($a['status'] ?? '') === $st_key) {
                        $by_status[] = $a;
                        break;
                    }
                }
            }
            // Fill remaining slots with more apartments
            $shown_titles = array_column($by_status, 'title');
            foreach ($apartments as $a) {
                if (count($by_status) >= 6) break;
                if (!in_array($a['title'] ?? '', $shown_titles, true)) {
                    $by_status[] = $a;
                    $shown_titles[] = $a['title'] ?? '';
                }
            }

            $preview_items = $by_status;
            $status_config = [
                'dostepne'      => ['label' => 'Dostępne',   'cls' => 'lw-status--dostepne'],
                'zarezerwowane' => ['label' => 'Rezerwacja', 'cls' => 'lw-status--zarezerwowane'],
                'sprzedane'     => ['label' => 'Sprzedane',  'cls' => 'lw-status--sprzedane'],
            ];
            $overlay_texts = [
                'dostepne'      => $settings['card_dostepne_overlay_text'] ?? 'dostępne',
                'zarezerwowane' => $settings['card_zarezerwowane_overlay_text'] ?? 'zarezerwowane',
                'sprzedane'     => $settings['card_sprzedane_overlay_text'] ?? 'sprzedane',
            ];

            if ($default_view === 'table') {
                // Table preview
                ?>
                <div class="lw-results">
                    <table class="lw-table">
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
                        <tbody>
                            <?php foreach ($preview_items as $a) :
                                $st = $status_config[$a['status'] ?? ''] ?? ['label' => $a['status_name'] ?? '—', 'cls' => ''];
                                $area_fmt = $a['area'] !== null ? str_replace('.', ',', $a['area']) . ' m²' : '—';
                            ?>
                            <tr>
                                <td><a href="<?php echo esc_url($a['link'] ?? '#'); ?>"><?php echo esc_html($a['title'] ?? ''); ?></a></td>
                                <td><?php echo esc_html($a['floor'] ?? ''); ?></td>
                                <td><?php echo esc_html($a['rooms'] ?? ''); ?></td>
                                <td><?php echo esc_html($area_fmt); ?></td>
                                <td><span class="lw-status <?php echo esc_attr($st['cls']); ?>"><?php echo esc_html($st['label']); ?></span></td>
                                <td><?php echo esc_html($a['price_total_formatted'] ?? '—'); ?></td>
                                <td><?php echo esc_html($a['investment'] ?? ''); ?></td>
                                <td class="lw-table__actions">
                                    <?php if (!empty($a['gallery_3d'])) : ?>
                                    <a class="lw-table-action" href="<?php echo esc_url($a['gallery_3d'][0]); ?>" target="_blank" rel="noopener" data-tooltip="Karta katalogowa"><easier-icon name="3d-scale" size="16"></easier-icon></a>
                                    <?php endif; ?>
                                    <a class="lw-table-action" href="<?php echo esc_url($a['link'] ?? '#'); ?>" target="_blank" rel="noopener" data-tooltip="Obejrzyj mieszkanie"><easier-icon name="eye" size="16"></easier-icon></a>
                                    <?php if (!empty($a['pdf_url'])) : ?>
                                    <a class="lw-table-action" href="<?php echo esc_url($a['pdf_url']); ?>" target="_blank" rel="noopener" data-tooltip="Rzuty 3D"><easier-icon name="floor-plan" size="16"></easier-icon></a>
                                    <?php endif; ?>
                                    <?php if (($a['status'] ?? '') === 'dostepne' && !empty($a['inquiry_url'])) : ?>
                                    <a class="lw-table-action lw-table-action--inquiry" href="<?php echo esc_url($a['inquiry_url']); ?>" data-tooltip="Wyślij zapytanie"><easier-icon name="mail-send-01" size="16"></easier-icon></a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php
            } else {
                // Grid (card) preview
                ?>
                <div class="lw-results">
                    <div class="lw-grid">
                        <?php foreach ($preview_items as $a) :
                            $st = $status_config[$a['status'] ?? ''] ?? ['label' => $a['status_name'] ?? '', 'cls' => ''];
                            $status_class = ($a['status'] ?? '') ? 'lw-card--' . $a['status'] : '';
                            $area_fmt = $a['area'] !== null ? str_replace('.', ',', $a['area']) . 'm²' : null;
                            $floor_label = ($a['floor_num'] ?? 0) === 0 ? 'Parter' : ($a['floor'] ?? '') . ' piętro';
                            $rooms = $a['rooms'] ?? 0;
                            if ($rooms === 1) { $rooms_word = 'pokój'; }
                            elseif ($rooms >= 2 && $rooms <= 4) { $rooms_word = 'pokoje'; }
                            else { $rooms_word = 'pokoi'; }
                        ?>
                        <div class="lw-card <?php echo esc_attr($status_class); ?>">
                            <div class="lw-card__thumb">
                                <?php if (!empty($a['thumbnail'])) : ?>
                                    <img class="lw-card__thumb-rzut" src="<?php echo esc_url($a['thumbnail']); ?>" alt="Rzut - <?php echo esc_attr($a['title'] ?? ''); ?>">
                                <?php endif; ?>
                                <?php if (!empty($a['gallery_3d'])) : ?>
                                    <img class="lw-card__thumb-viz" src="<?php echo esc_url($a['gallery_3d'][0]); ?>" alt="Karta katalogowa - <?php echo esc_attr($a['title'] ?? ''); ?>">
                                <?php endif; ?>
                                <?php if (empty($a['thumbnail']) && empty($a['gallery_3d'])) : ?>
                                    <div class="lw-card__thumb-placeholder">🏠</div>
                                <?php endif; ?>
                            </div>
                            <div class="lw-card__info">
                                <?php if (!empty($a['investment'])) : ?>
                                    <span class="lw-card__investment"><?php echo esc_html($a['investment']); ?></span>
                                <?php endif; ?>
                                <h3 class="lw-card__title">Mieszkanie <?php echo esc_html($a['title'] ?? ''); ?></h3>
                                <ul class="lw-card__details">
                                    <li><?php echo esc_html($floor_label); ?></li>
                                    <?php if ($area_fmt) : ?><li><?php echo esc_html($area_fmt); ?></li><?php endif; ?>
                                    <li><?php echo esc_html($rooms . ' ' . $rooms_word); ?></li>
                                    <?php if (($a['status'] ?? '') !== 'sprzedane') : ?>
                                        <?php if (!empty($a['price_m2_formatted'])) : ?><li>Cena za metr: <?php echo esc_html($a['price_m2_formatted']); ?></li><?php endif; ?>
                                        <?php if (!empty($a['price_total_formatted'])) : ?><li>Cena: <?php echo esc_html($a['price_total_formatted']); ?></li><?php endif; ?>
                                    <?php endif; ?>
                                </ul>
                            </div>
                            <div class="lw-card__actions">
                                <a class="lw-card__btn lw-card__btn--view" href="#">obejrzyj mieszkanie ›</a>
                            </div>
                            <div class="lw-card__status <?php echo esc_attr($st['cls']); ?>"><?php echo esc_html($st['label']); ?></div>
                            <div class="lw-card__overlay"><span><?php echo esc_html($overlay_texts[$a['status'] ?? ''] ?? $st['label']); ?></span></div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php
            }

            // Sticky bar preview in editor
            if (($settings['enable_sticky_bar'] ?? 'yes') === 'yes') : ?>
                <div class="lw-sticky-bar" style="position:sticky; bottom:0; margin-top:24px;">
                    <div class="lw-sticky-bar__inner">
                        <div class="lw-sticky-bar__left">
                            <div class="lw-dropdown">
                                <button type="button" class="lw-toolbar-btn lw-toolbar-btn--active">
                                    <span class="lw-toolbar-btn__label"><?php echo esc_html($status_label); ?></span>
                                    <svg width="10" height="6" viewBox="0 0 10 6" fill="none"><path d="M1 1L5 5L9 1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </button>
                            </div>
                            <div class="lw-dropdown">
                                <button type="button" class="lw-toolbar-btn">
                                    <span class="lw-toolbar-btn__label">Sortowanie</span>
                                    <svg width="10" height="6" viewBox="0 0 10 6" fill="none"><path d="M1 1L5 5L9 1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </button>
                            </div>
                            <div class="lw-view-toggle">
                                <button type="button" class="lw-view-btn lw-view-btn--active" data-view="grid">
                                    <easier-icon name="grid-view" variant="stroke" size="18"></easier-icon>
                                </button>
                                <button type="button" class="lw-view-btn" data-view="table">
                                    <easier-icon name="menu-01" variant="stroke" size="18"></easier-icon>
                                </button>
                            </div>
                        </div>
                        <div class="lw-sticky-bar__right">
                            <button type="button" class="lw-sticky-bar__btn lw-sticky-bar__btn--filter">
                                <easier-icon name="filter" variant="stroke" size="18"></easier-icon>
                                Filtry
                            </button>
                            <button type="button" class="lw-sticky-bar__btn lw-sticky-bar__btn--search">SZUKAJ</button>
                        </div>
                    </div>
                </div>
            <?php endif;

        } else {
            // Frontend: empty containers populated by JS
            ?>
            <div class="lw-results" id="lw-results"<?php if ($settings['hide_before_search'] === 'yes') echo ' style="display:none;"'; ?>>
                <div class="lw-grid" id="lw-grid"></div>
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

            <script>
            (function(){
                <?php if ($default_status !== 'dostepne') : ?>
                window.lwDefaultStatus = <?php echo wp_json_encode($default_status); ?>;
                <?php endif; ?>
                <?php if ($hide_before) : ?>
                window.lwHideBeforeSearch = true;
                <?php endif; ?>
                <?php if (($settings['enable_sticky_bar'] ?? 'yes') !== 'yes') : ?>
                window.lwDisableStickyBar = true;
                <?php endif; ?>
                <?php if ($default_view !== 'grid') : ?>
                window.lwDefaultView = <?php echo wp_json_encode($default_view); ?>;
                <?php endif; ?>
                <?php
                $overlay_texts_js = [
                    'dostepne'      => $settings['card_dostepne_overlay_text'] ?? '',
                    'zarezerwowane' => $settings['card_zarezerwowane_overlay_text'] ?? '',
                    'sprzedane'     => $settings['card_sprzedane_overlay_text'] ?? '',
                ];
                $has_custom = array_filter($overlay_texts_js);
                if ($has_custom) : ?>
                window.lwOverlayTexts = <?php echo wp_json_encode($overlay_texts_js); ?>;
                <?php endif; ?>
            })();
            </script>
            <?php
        }
    }
}
