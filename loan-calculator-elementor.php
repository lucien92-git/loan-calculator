<?php
/**
 * Plugin Name: Loan Calculator for Elementor
 * Description: Adds an Elementor widget to calculate monthly loan payments.
 * Version: 1.0.1
 * Author: Codex
 * Requires at least: 5.8
 * Requires PHP: 7.4
 * Text Domain: loan-calculator-elementor
 */

if (!defined('ABSPATH')) {
    exit;
}

define('LCE_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('LCE_PLUGIN_URL', plugin_dir_url(__FILE__));
define('LCE_PLUGIN_VERSION', '1.0.1');

/**
 * Register widget assets.
 */
function lce_register_assets()
{
    $style_version = file_exists(LCE_PLUGIN_PATH . 'assets/css/loan-calculator.css')
        ? (string) filemtime(LCE_PLUGIN_PATH . 'assets/css/loan-calculator.css')
        : LCE_PLUGIN_VERSION;

    $script_version = file_exists(LCE_PLUGIN_PATH . 'assets/js/loan-calculator.js')
        ? (string) filemtime(LCE_PLUGIN_PATH . 'assets/js/loan-calculator.js')
        : LCE_PLUGIN_VERSION;

    wp_register_style(
        'lce-widget-style',
        LCE_PLUGIN_URL . 'assets/css/loan-calculator.css',
        [],
        $style_version
    );

    wp_register_script(
        'lce-widget-script',
        LCE_PLUGIN_URL . 'assets/js/loan-calculator.js',
        ['jquery'],
        $script_version,
        true
    );
}
add_action('init', 'lce_register_assets');

/**
 * Register Elementor widget.
 *
 * @param \Elementor\Widgets_Manager $widgets_manager Elementor widgets manager.
 */
function lce_register_elementor_widgets($widgets_manager)
{
    require_once LCE_PLUGIN_PATH . 'includes/class-loan-calculator-widget.php';

    $widgets_manager->register(new \LCE_Loan_Calculator_Widget());
}
add_action('elementor/widgets/register', 'lce_register_elementor_widgets');

/**
 * Check dependencies and show admin notice if Elementor is missing.
 */
function lce_admin_notice_missing_elementor()
{
    if (!current_user_can('activate_plugins')) {
        return;
    }

    if (did_action('elementor/loaded')) {
        return;
    }

    echo '<div class="notice notice-warning is-dismissible"><p>'
        . esc_html__('Loan Calculator for Elementor requires Elementor to be installed and active.', 'loan-calculator-elementor')
        . '</p></div>';
}
add_action('admin_notices', 'lce_admin_notice_missing_elementor');
