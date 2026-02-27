<?php

if (!defined('ABSPATH')) {
    exit;
}

class LCE_Loan_Calculator_Widget extends \Elementor\Widget_Base
{
    public function get_name()
    {
        return 'lce_loan_calculator';
    }

    public function get_title()
    {
        return esc_html__('Loan Calculator', 'loan-calculator-elementor');
    }

    public function get_icon()
    {
        return 'eicon-calculator';
    }

    public function get_categories()
    {
        return ['general'];
    }

    public function get_keywords()
    {
        return ['loan', 'calculator', 'finance', 'mortgage'];
    }

    protected function register_controls()
    {
        $this->start_controls_section(
            'section_defaults',
            [
                'label' => esc_html__('Default Values', 'loan-calculator-elementor'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'default_country',
            [
                'label' => esc_html__('Default Country', 'loan-calculator-elementor'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'US',
                'options' => [
                    'US' => esc_html__('United States', 'loan-calculator-elementor'),
                    'GB' => esc_html__('United Kingdom', 'loan-calculator-elementor'),
                    'EU' => esc_html__('Euro Area', 'loan-calculator-elementor'),
                    'CA' => esc_html__('Canada', 'loan-calculator-elementor'),
                    'AU' => esc_html__('Australia', 'loan-calculator-elementor'),
                    'IN' => esc_html__('India', 'loan-calculator-elementor'),
                    'JP' => esc_html__('Japan', 'loan-calculator-elementor'),
                    'MY' => esc_html__('Malaysia', 'loan-calculator-elementor'),
                    'SG' => esc_html__('Singapore', 'loan-calculator-elementor'),
                ],
            ]
        );

        $this->add_control(
            'default_amount',
            [
                'label' => esc_html__('Loan Amount', 'loan-calculator-elementor'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 100000,
                'min' => 0,
            ]
        );

        $this->add_control(
            'default_rate',
            [
                'label' => esc_html__('Interest Rate (%)', 'loan-calculator-elementor'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 5,
                'min' => 0,
                'step' => 0.01,
            ]
        );

        $this->add_control(
            'default_years',
            [
                'label' => esc_html__('Term (Years)', 'loan-calculator-elementor'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 30,
                'min' => 1,
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        wp_enqueue_style('lce-widget-style');
        wp_enqueue_script('lce-widget-script');

        $settings = $this->get_settings_for_display();

        $amount = isset($settings['default_amount']) ? (float) $settings['default_amount'] : 100000;
        $rate = isset($settings['default_rate']) ? (float) $settings['default_rate'] : 5;
        $years = isset($settings['default_years']) ? (float) $settings['default_years'] : 30;
        $country = isset($settings['default_country']) ? $settings['default_country'] : 'US';
        ?>
        <div class="lce-loan-calculator"
            data-default-amount="<?php echo esc_attr($amount); ?>"
            data-default-rate="<?php echo esc_attr($rate); ?>"
            data-default-years="<?php echo esc_attr($years); ?>"
            data-default-country="<?php echo esc_attr($country); ?>">
            <div class="lce-field-group">
                <label for="lce-country-<?php echo esc_attr($this->get_id()); ?>"><?php echo esc_html__('Country', 'loan-calculator-elementor'); ?></label>
                <select id="lce-country-<?php echo esc_attr($this->get_id()); ?>" class="lce-country">
                    <option value="US" <?php selected($country, 'US'); ?>><?php echo esc_html__('United States (USD)', 'loan-calculator-elementor'); ?></option>
                    <option value="GB" <?php selected($country, 'GB'); ?>><?php echo esc_html__('United Kingdom (GBP)', 'loan-calculator-elementor'); ?></option>
                    <option value="EU" <?php selected($country, 'EU'); ?>><?php echo esc_html__('Euro Area (EUR)', 'loan-calculator-elementor'); ?></option>
                    <option value="CA" <?php selected($country, 'CA'); ?>><?php echo esc_html__('Canada (CAD)', 'loan-calculator-elementor'); ?></option>
                    <option value="AU" <?php selected($country, 'AU'); ?>><?php echo esc_html__('Australia (AUD)', 'loan-calculator-elementor'); ?></option>
                    <option value="IN" <?php selected($country, 'IN'); ?>><?php echo esc_html__('India (INR)', 'loan-calculator-elementor'); ?></option>
                    <option value="JP" <?php selected($country, 'JP'); ?>><?php echo esc_html__('Japan (JPY)', 'loan-calculator-elementor'); ?></option>
                    <option value="MY" <?php selected($country, 'MY'); ?>><?php echo esc_html__('Malaysia (MYR)', 'loan-calculator-elementor'); ?></option>
                    <option value="SG" <?php selected($country, 'SG'); ?>><?php echo esc_html__('Singapore (SGD)', 'loan-calculator-elementor'); ?></option>
                </select>
            </div>

            <div class="lce-field-group">
                <label for="lce-amount-<?php echo esc_attr($this->get_id()); ?>"><?php echo esc_html__('Loan Amount', 'loan-calculator-elementor'); ?></label>
                <input id="lce-amount-<?php echo esc_attr($this->get_id()); ?>" type="number" min="0" step="100" class="lce-amount" value="<?php echo esc_attr($amount); ?>" />
            </div>

            <div class="lce-field-group">
                <label for="lce-rate-<?php echo esc_attr($this->get_id()); ?>"><?php echo esc_html__('Annual Interest Rate (%)', 'loan-calculator-elementor'); ?></label>
                <input id="lce-rate-<?php echo esc_attr($this->get_id()); ?>" type="number" min="0" step="0.01" class="lce-rate" value="<?php echo esc_attr($rate); ?>" />
            </div>

            <div class="lce-field-group">
                <label for="lce-years-<?php echo esc_attr($this->get_id()); ?>"><?php echo esc_html__('Term (Years)', 'loan-calculator-elementor'); ?></label>
                <input id="lce-years-<?php echo esc_attr($this->get_id()); ?>" type="number" min="1" step="1" class="lce-years" value="<?php echo esc_attr($years); ?>" />
            </div>

            <button class="lce-calculate-button" type="button"><?php echo esc_html__('Calculate Payment', 'loan-calculator-elementor'); ?></button>

            <div class="lce-results" aria-live="polite">
                <p><strong><?php echo esc_html__('Currency:', 'loan-calculator-elementor'); ?></strong> <span class="lce-currency-code">USD ($)</span></p>
                <p><strong><?php echo esc_html__('Monthly Payment:', 'loan-calculator-elementor'); ?></strong> <span class="lce-monthly-payment">—</span></p>
                <p><strong><?php echo esc_html__('Total Payment:', 'loan-calculator-elementor'); ?></strong> <span class="lce-total-payment">—</span></p>
                <p><strong><?php echo esc_html__('Total Interest:', 'loan-calculator-elementor'); ?></strong> <span class="lce-total-interest">—</span></p>
            </div>
        </div>
        <?php
    }
}
