<?php

if ( ! $attributes['imageURL'] ) {
    $attributes['imageURL'] = get_theme_file_uri( 'images/library-hero.jpg' );
}
if( $attributes['themeImage'] ) {
    $attributes['imageURL'] = get_theme_file_uri( '/images/' . $attributes['themeImage'] );
}
?>

<div class="hero-slider__slide" style="background-image: url('<?php echo $attributes['imageURL']; ?>')">
    <div class="hero-slider__interior container">
        <div class="hero-slider__overlay t-center">
            <?php echo $content ?>
        </div>
    </div>
</div>
