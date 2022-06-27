<?php

new JSXBlock( "banner", true, ['fallbackImage' => get_theme_file_uri( 'images/library-hero.jpg' )] );
new JSXBlock( "generic-button" );
new JSXBlock( "generic-heading" );
new JSXBlock( "slideshow" );
new JSXBlock( "slide", true, ['themeImagePath' => get_theme_file_uri('/images/')] );

new PHPBlock( "events-and-blogs" );
new PHPBlock( "header" );
new PHPBlock( "footer" );
new PHPBlock( "single-post" );
new PHPBlock( "single-page" );
new PHPBlock( "blog-index" );
new PHPBlock( "program-archive" );
new PHPBlock( "single-program" );
new PHPBlock( "single-professor" );
new PHPBlock( "my-notes" );

class JSXBlock {

    function __construct( $name, $use_render_callback = true, $data = null ) {
        $this->block_namespace = "online-university";
        
        $this->name = $name;
        $this->use_render_callback = $use_render_callback;
        $this->data = $data;

        add_action( 'init', array( $this, 'init_block' ) );
    }

    function init_block() {

        wp_register_style("{$this->block_namespace}-{$this->name}-style", get_theme_file_uri( "build/{$this->name}.css" ) );
        wp_register_script( "{$this->block_namespace}-{$this->name}-editor-script", get_theme_file_uri( "build/{$this->name}.js" ), array( 'wp-blocks', 'wp-editor' ) );

        if ( $this->data ) {
            wp_localize_script( "{$this->block_namespace}-{$this->name}-editor-script", $this->name, $this->data );
        }

        $args = array(
            "editor_script" => "{$this->block_namespace}-{$this->name}-editor-script", 
        );

        if ( $this->use_render_callback ) {
            $args['render_callback'] = array( $this, 'render_block' );
        }

        register_block_type_from_metadata( __DIR__ . "/blocks/{$this->name}", $args );

    }

    function render_block( $attributes, $content ) {
        ob_start();
        require get_theme_file_path( "blocks/{$this->name}/render.php" );
        return ob_get_clean();
    }
}



class PHPBlock {

    function __construct( $name ) {
        $this->block_namespace = "online-university";
        
        $this->name = $name;

        add_action( 'init', array( $this, 'init_block' ) );
    }

    function init_block() {

        wp_register_style("{$this->block_namespace}-{$this->name}-style", get_theme_file_uri( "build/{$this->name}.css" ) );
        wp_register_script( "{$this->block_namespace}-{$this->name}-editor-script", get_theme_file_uri( "build/{$this->name}.js" ), array( 'wp-blocks', 'wp-editor', 'wp-server-side-render' ) );

        register_block_type_from_metadata( __DIR__ . "/blocks/{$this->name}", array( 
            'editor_script'     => "{$this->block_namespace}-{$this->name}-editor-script", 
            'render_callback'   => array( $this, 'render_block' ),
         ) );

    }

    function render_block( $attributes, $content ) {
        ob_start();
        require get_theme_file_path( "blocks/{$this->name}/render.php" );
        return ob_get_clean();
    }
}