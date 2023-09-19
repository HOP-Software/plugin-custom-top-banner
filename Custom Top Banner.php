<?php
/*
Plugin Name: Custom Top Banner
Description: Add a customizable top banner with automatic removal date.
Version: 1.0
Author: Pawel Dabrowa
*/

// Enqueue scripts and styles
function custom_top_banner_enqueue_assets() {
    wp_enqueue_script('custom-top-banner-js', plugin_dir_url(__FILE__) . 'custom-top-banner.js', array('jquery'), '1.0', true);
    wp_enqueue_style('custom-top-banner-css', plugin_dir_url(__FILE__) . 'custom-top-banner.css', array(), '1.0');
}
add_action('wp_enqueue_scripts', 'custom_top_banner_enqueue_assets');

// Add a settings page to the admin menu
function custom_top_banner_menu() {
    add_menu_page(
        'Custom Top Banner',
        'Custom Top Banner',
        'manage_options',
        'custom-top-banner-settings',
        'custom_top_banner_page'
    );
}
add_action('admin_menu', 'custom_top_banner_menu');

// Render the settings page
function custom_top_banner_page() {
    if (isset($_POST['custom_top_banner_submit'])) {
        update_option('custom_top_banner_text', sanitize_text_field($_POST['custom_top_banner_text']));
        update_option('custom_top_banner_bg_color', sanitize_text_field($_POST['custom_top_banner_bg_color']));
        update_option('custom_top_banner_font_color', sanitize_text_field($_POST['custom_top_banner_font_color']));
        update_option('custom_top_banner_removal_datetime', sanitize_text_field($_POST['custom_top_banner_removal_datetime']));
    }

    $banner_text = get_option('custom_top_banner_text');
    $bg_color = get_option('custom_top_banner_bg_color');
    $font_color = get_option('custom_top_banner_font_color');
    $removal_datetime = get_option('custom_top_banner_removal_datetime');
    ?>
    <div class="wrap">
        <h2>Custom Top Banner Settings</h2>
        <form method="post">
            <label for="custom_top_banner_text">Banner Text:</label>
            <input type="text" name="custom_top_banner_text" id="custom_top_banner_text" value="<?php echo esc_attr($banner_text); ?>" /><br />

            <label for="custom_top_banner_bg_color">Background Color:</label>
            <input type="color" name="custom_top_banner_bg_color" id="custom_top_banner_bg_color" value="<?php echo esc_attr($bg_color); ?>" /><br />

            <label for="custom_top_banner_font_color">Font Color:</label>
            <input type="color" name="custom_top_banner_font_color" id="custom_top_banner_font_color" value="<?php echo esc_attr($font_color); ?>" /><br />

            <label for="custom_top_banner_removal_datetime">Removal Date and Time:</label>
            <input type="datetime-local" name="custom_top_banner_removal_datetime" id="custom_top_banner_removal_datetime" value="<?php echo esc_attr($removal_datetime); ?>" /><br />

            <input type="submit" name="custom_top_banner_submit" class="button-primary" value="Save" />
        </form>
    </div>
    <?php
}

// Display the banner
function display_custom_top_banner() {
    $banner_text = get_option('custom_top_banner_text');
    $bg_color = get_option('custom_top_banner_bg_color');
    $font_color = get_option('custom_top_banner_font_color');
    $removal_datetime = get_option('custom_top_banner_removal_datetime');

    if (!empty($banner_text) && strtotime($removal_datetime) > current_time('timestamp')) {
        $style = "background-color: $bg_color; color: $font_color; text-align: center; z-index: 9999999999; position: absolute; padding: 10px; top: 120px; font-weight: 900; width:100%;";
        echo '<div class="custom-top-banner" style="' . esc_attr($style) . '">' . esc_html($banner_text) . '</div>';
    }
}
add_action('wp_head', 'display_custom_top_banner');
