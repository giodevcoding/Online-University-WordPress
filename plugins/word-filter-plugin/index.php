<?php

/* 

    Plugin Name: Word Filter Plugin
    Description: Does stuff wow
    Version: 1.0
    Author: Giodude
    Author URI: giodev.org

*/


// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ){
    exit;
}

class WordFilterPlugin {

    function __construct() {
        add_action( 'admin_init', array( $this, 'settings' ) );
        add_action( 'admin_menu', array( $this, 'menu' ) );

        add_filter( 'the_content', array( $this, 'filter_words' ) );    
    }

    function settings() {
        add_settings_section( 'replacement-text-section', null, null, 'word-filter-options' );

        register_setting( 'replacement_fields', 'replacement_text' );
        add_settings_field( 'replacement-text', 'Filtered Text', array( $this, 'replacement_field_html'), 'word-filter-options', 'replacement-text-section' );
    }


    function menu() {

        $icon_ascii = "data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHZpZXdCb3g9IjAgMCAyMCAyMCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZmlsbC1ydWxlPSJldmVub2RkIiBjbGlwLXJ1bGU9ImV2ZW5vZGQiIGQ9Ik0xMCAyMEMxNS41MjI5IDIwIDIwIDE1LjUyMjkgMjAgMTBDMjAgNC40NzcxNCAxNS41MjI5IDAgMTAgMEM0LjQ3NzE0IDAgMCA0LjQ3NzE0IDAgMTBDMCAxNS41MjI5IDQuNDc3MTQgMjAgMTAgMjBaTTExLjk5IDcuNDQ2NjZMMTAuMDc4MSAxLjU2MjVMOC4xNjYyNiA3LjQ0NjY2SDEuOTc5MjhMNi45ODQ2NSAxMS4wODMzTDUuMDcyNzUgMTYuOTY3NEwxMC4wNzgxIDEzLjMzMDhMMTUuMDgzNSAxNi45Njc0TDEzLjE3MTYgMTEuMDgzM0wxOC4xNzcgNy40NDY2NkgxMS45OVoiIGZpbGw9IiNGRkRGOEQiLz4KPC9zdmc+";

        $icon_file = plugin_dir_url(__FILE__) . 'custom.svg';

        $main_page_hook = add_menu_page( 
            'Word Filtering',               // Tab Title
            'Word Filter',                  // Sidebar Title
            'manage_options',               // Permissions
            'word-filter',                  // Slug
            array( $this, 'menu_html' ),    // Callback
            $icon_ascii,                    // Sidebar Icon
            100                             // Sidebar Priority
        );

        add_submenu_page(
            'word-filter',
            'Words to Filter',
            'Words List',
            'manage_options',
            'word-filter',
            array( $this, 'menu_html' )
        );
        add_submenu_page(
            'word-filter',
            'Word Filter Options',
            'Options',
            'manage_options',
            'word-filter-options',
            array( $this, 'submenu_html' )
        );

        add_action("load-{$main_page_hook}", array( $this, 'main_page_assets' ) );
    }


    function handle_form() {

        if ( wp_verify_nonce( $_POST['filter_words_nonce'], 'save_filter_words' ) AND current_user_can( 'manage_options' ) ) {
            update_option( 'plugin_words_to_filter', sanitize_text_field( $_POST['plugin_words_to_filter'] ) );
            ?>
            <div class="updated">
                <p>Your filtered words were saved.</p>
            </div>
            <?php
        } else {
            ?>
            <div class="error">
                <p>Sorry, you do not have permission to perform that action.</p>
            </div>
            <?php
        }
        
    }
   
  

    function filter_words( $content ) {
        if( get_option( 'plugin_words_to_filter' ) == '' ){
            return $content;
        }

        $bad_words_raw = explode( ',', get_option( 'plugin_words_to_filter' ) );
        $bad_words = array_map( 'trim', $bad_words_raw );

        return str_ireplace( $bad_words, esc_html( get_option( 'replacement_text', '%*#$@' ) ), $content );

    }

    function menu_html() {
        ?>
        <div class="wrap">
            <h1>Word Filter</h1>
            <?php 
            if ($_POST['just-submitted'] == "true") $this->handle_form();
            ?>
            <form action="" method="post">
                <input type="hidden" name="just-submitted" value="true">
                <?php 
                wp_nonce_field('save_filter_words', 'filter_words_nonce' );
                ?>
                <label for="plugin_words_to_filter">Enter a <strong>comma-separated</strong> list of words to filter from your site's comment</label>
                <div class="word-filter__flex-container">
                    <textarea name="plugin_words_to_filter" id="plugin_words_to_filter" placeholder="h*ck, cr*p, sho*t"><?php echo esc_textarea( get_option( 'plugin_words_to_filter') ); ?></textarea>
                </div>
                <input id="submit" class="button button-primary" type="submit" name="submit" value="Save Changes">
            </form>
        </div>
        <?php
    }

    function main_page_assets() {
        wp_enqueue_style('filter-admin-css', plugin_dir_url( __FILE__ ) . 'styles.css' );
    }


    function submenu_html() {
        ?>
        <div class="wrap">
            <h1>Word Filter Options</h1>
            <form action="options.php" method="post">

                <?php
                settings_errors();
                settings_fields( 'replacement_fields' );
                do_settings_sections( 'word-filter-options' );
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    function replacement_field_html() {
        ?>
        <input type="text" name="replacement_text" value="<?php echo esc_attr( get_option( 'replacement_text', '%*#$@' ) )?>">
        <p class="description">Leave blank to remove filtered words.</p>
        <?php
    }

}

$word_filter_plugin = new WordFilterPlugin();

