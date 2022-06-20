<?php

/*

    Plugin Name: gioDev First Plugin
    Description: not good plugin
    Version: 1.0
    Author: Giovanni
    Author URI: giodev.org
    Text Domain: wcpdomain
    Domain Path: /languages

*/

class WordCountPlugin {

    function __construct() {
        add_action( 'admin_init', array( $this, 'settings' ) );
        add_action( 'admin_menu', array( $this, 'admin_page' ) );
        add_action( 'init', array( $this, 'languages') );
        
        add_filter( 'the_content', array($this, 'filter_post') );
    }


    function settings() {

        add_settings_section( 'wcp_main_section', null, null, 'word-count-settings' );
        
        // Dispaly Location
        register_setting( 'wordcountplugin', 'wcp_location', array( 
            'sanitize_callback' => array( $this, 'sanitize_location' ),
            'default'           => '0'
        ) );
        add_settings_field( 'wcp_location', 'Display Location', array( $this, 'location_html' ), 'word-count-settings', 'wcp_main_section' );

        // Headline
        register_setting( 'wordcountplugin', 'wcp_headline', array( 
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => 'Post Statistics'
        ) );
        add_settings_field( 'wcp_headline', 'Headline', array( $this, 'headline_html' ), 'word-count-settings', 'wcp_main_section' );

        // Word Count
        register_setting( 'wordcountplugin', 'wcp_wordcount', array( 
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => '1'
        ) );
        add_settings_field( 'wcp_wordcount', 'Word Count', array( $this, 'checkbox_html' ), 'word-count-settings', 'wcp_main_section', array( 
            'name' => 'wcp_wordcount'
        ) );

        // Character Count
        register_setting( 'wordcountplugin', 'wcp_charcount', array( 
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => '1'
        ) );
        add_settings_field( 'wcp_charcount', 'Character Count', array( $this, 'checkbox_html' ), 'word-count-settings', 'wcp_main_section', array( 
            'name' => 'wcp_charcount'
        ) );

        // Read Time
        register_setting( 'wordcountplugin', 'wcp_readtime', array( 
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => '1'
        ) );
        add_settings_field( 'wcp_readtime', 'Read Time', array( $this, 'checkbox_html' ), 'word-count-settings', 'wcp_main_section', array( 
            'name' => 'wcp_readtime'
        ) );
        
    }

    function sanitize_location( $input ) {
        if( $input != 0 && $input != '1' ){
            add_settings_error( 'wcp_location', 'wcp_location_invalid', 'Display Location must be beginning or end' );
            return get_option( 'wcp_location' );
        }

        return $input;
    }

    function location_html() {
        ?>
        <select name="wcp_location">
            <option value="0" <?php selected( get_option( 'wcp_location' ), '0' ); ?> >Beginning of post</option>
            <option value="1" <?php selected( get_option( 'wcp_location' ), '1' ); ?> >End of post</option>
        </select>
        <?php
    }

    function headline_html() {
        ?>
        <input type="text" name="wcp_headline" value="<?php echo esc_attr( get_option( 'wcp_headline' ) ); ?>">
        <?php
    }

    function checkbox_html( $args ) {
        ?>
        <input type="checkbox" name="<?php echo $args['name']; ?>" value="1" <?php checked( get_option( $args['name'] ), '1' ); ?> >
        <?php
    }


    function admin_page() {
        add_options_page( 'Word Count Settings', __( 'Word Count', 'wcpdomain' ), 'manage_options', 'word-count-settings', array( $this, 'admin_page_html' ) );    
    }

    function admin_page_html() {
        ?>
            <div class="wrap">
                <h1>Word Count Settings</h1>
                <form action="options.php" method="POST">
                    <?php 
                    settings_fields( 'wordcountplugin' );
                    do_settings_sections( 'word-count-settings' );
                    submit_button();
                    ?>
                </form>
            </div>
        <?php
    }


    function languages() {
        load_plugin_textdomain( 'wcpdomain', false, dirname( plugin_basename(__FILE__) ) . '/languages' );
    }


    function filter_post( $content ) {
        if( ! $this->should_filter() ) {
            return $content;
        }

        $html = '<h3>' . esc_html( get_option( 'wcp_headline', 'Post Statistics' ) ) . '</h3><p>';

        
        if ( get_option( 'wcp_wordcount', '1' ) OR get_option( 'wcp_readtime', '1' ) ) {
            $word_count = str_word_count( strip_tags( $content ) );
        }

        if( get_option( 'wcp_wordcount', '1' ) ) {
            $html .= $word_count . ' ' . esc_html__( 'words', 'wcpdomain' ) . '.<br>';
        }

        if( get_option( 'wcp_charcount', '1' ) ) {
            $html .= strlen( strip_tags( $content ) ) . ' ' . esc_html__( 'characters', 'wcpdomain' ) . '.<br>';
        }

        if( get_option( 'wcp_readtime', '1' ) ) {
            $html .= esc_html__( 'About', 'wcpdomain' ) . ' ' . round($word_count/225) . ' ' . esc_html__( 'minutes to read', 'wcpdomain' ) . '.<br>';
        }

        $html .= "</p>";

        if ( get_option( 'wcp_location', '0' ) == '0') {
            return $html . $content;
        } else {
            return $content . $html;
        }
    }

    function should_filter() {
        $valid_post = is_main_query() && is_single();
        $any_filters_checked = get_option( 'wcp_wordcount', '1' ) || get_option( 'wcp_charcount', '1' ) || get_option( 'wcp_readtime', '1' );

        return $valid_post && $any_filters_checked;
    }

}

$wordCountPlugin = new WordCountPlugin();


