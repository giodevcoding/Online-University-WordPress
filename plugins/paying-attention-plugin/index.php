<?php
/* 

    Plugin Name: Paying Attention
    Description: Does stuff wow
    Version: 1.0
    Author: Giodude
    Author URI: giodev.org
    Text Domain: paying-attention
    Domain Path: /languages
*/

// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ){
    exit;
}

require_once plugin_dir_path( __FILE__ ) . 'inc/generate_professor_HTML.php';
require_once plugin_dir_path( __FILE__ ) . 'inc/related_posts_HTML.php';

class PayingAttention {

    function __construct() {
        add_action( 'init', array( $this, 'init' ) );
        add_action( 'rest_api_init', array( $this, 'professor_HTML' ) );

        add_filter( 'the_content', array( $this, 'add_related_posts' ) );
    }


    function init() {
        load_plugin_textdomain( 'paying-attention', false, dirname( plugin_basename( __FILE__ )) . '/languages' ); 
        wp_set_script_translations( 'featured-professor', 'paying-attention', plugin_dir_path(__FILE__) . '/languages' );

        register_block_type( __DIR__ . '/src/quiz-block', array(
            'render_callback'   => array( $this, 'render_quiz_block' )
        ) );

        register_block_type( __DIR__ . '/src/featured-professor-block', array(
            'render_callback'   => array( $this, 'render_featured_professor_block' )
        ) );
        
        register_meta( 'post', 'featuredprofessor', array(
            'show_in_rest'  => true,
            'type'          => 'number',
            'single'        => false
        ) );
    }

    function professor_HTML() {
        register_rest_route( 'featured-professor/v1', 'get-HTML', array(
            'methods' => WP_REST_SERVER::READABLE,
            'callback' => array( $this, 'get_professor_HTML' )
        ));
    }

    function get_professor_HTML( $data ) {
        return generate_professor_HTML( $data['profId'] );
    }


    function add_related_posts( $content ) {
        if ( is_singular( 'professor' ) && in_the_loop() && is_main_query() ) {
            return $content . related_posts_HTML( get_the_ID() );
        }

        return $content;
    }


    function render_quiz_block( $attributes ) {
        if ( ! is_admin() ) {
            wp_enqueue_script( 'paying-attention-quiz-render', plugin_dir_url( __FILE__ ) . 'build/quiz-render.js', array( 'wp-element' ), false, true );
        }

        ob_start();
        ?>

        <div class="paying-attention-quiz"><pre style="display: none;"><?php echo wp_json_encode( $attributes ); ?></pre></div>

        <?php
        return ob_get_clean();
    }


    function render_featured_professor_block( $attributes ) {
        if ( $attributes['profID'] ) {
            return generate_professor_HTML( $attributes['profID'] );
        } else {
            return NULL;
        }
    }

}

$paying_attention = new PayingAttention();