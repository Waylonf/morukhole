<?php
/**
 * Sample implementation of the Custom Header feature
 * http://codex.wordpress.org/Custom_Headers
 *
 * You can add an optional custom header image to header.php like so ...

    <?php $header_image = get_header_image();
    if ( ! empty( $header_image ) ) { ?>
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
            <img src="<?php header_image(); ?>" width="<?php echo get_custom_header()->width; ?>" height="<?php echo get_custom_header()->height; ?>" alt="" />
        </a>
    <?php } // if ( ! empty( $header_image ) ) ?>

 *
 * @package     WordPress
 * @subpackage  {THEME-NAME}
 * @since       {THEME-NAME} {THEME-VERSION}
 */

/**
 * Setup the WordPress core custom header feature.
 *
 * Use add_theme_support to register support for WordPress 3.4+
 * as well as provide backward compatibility for previous versions.
 * Use feature detection of wp_get_theme() which was introduced
 * in WordPress 3.4.
 *
 * @uses grafipress_header_style()
 * @uses grafipress_admin_header_style()
 * @uses grafipress_admin_header_image()
 *
 * @package Shape
 */
function grafipress_custom_header_setup() {
    $args = array(
        'default-image'          => get_stylesheet_directory_uri() . '/img/defaults/header_image_1600_x_500.jpg',
        'default-text-color'     => 'e9e0e1',
        'width'                  => 1600,
        'height'                 => 300,
        'flex-height'            => true,
        'flex-width'             => false,
        'uploads'                => true,
        'random-default'         => false,
        'header-text'            => true,
        'wp-head-callback'       => 'grafipress_header_style',
        'admin-head-callback'    => 'grafipress_admin_header_style',
        'admin-preview-callback' => 'grafipress_admin_header_image',
    );

    $args = apply_filters( 'grafipress_custom_header_args', $args );

    if ( function_exists( 'wp_get_theme' ) ) :
        add_theme_support( 'custom-header', $args );
    else :
        define( 'HEADER_TEXTCOLOR',    $args['default-text-color'] );
        define( 'HEADER_IMAGE',        $args['default-image'] );
        define( 'HEADER_IMAGE_WIDTH',  $args['width'] );
        define( 'HEADER_IMAGE_HEIGHT', $args['height'] );

        add_custom_image_header(
            $args['wp-head-callback'],
            $args['admin-head-callback'],
            $args['admin-preview-callback']
        );
    endif;
}
add_action( 'after_setup_theme', 'grafipress_custom_header_setup' );

/**
 * Shiv for get_custom_header().
 *
 * get_custom_header() was introduced to WordPress
 * in version 3.4. To provide backward compatibility
 * with previous versions, we will define our own version
 * of this function.
 *
 * @todo Remove this function when WordPress 3.6 is released.
 * @return stdClass All properties represent attributes of the curent header image.
 *
 * @package Shape
 * @since Shape 1.1
 */

if ( ! function_exists( 'get_custom_header' ) ) {
    function get_custom_header() {
        return (object) array(
            'url'           => get_header_image(),
            'thumbnail_url' => get_header_image(),
            'width'         => HEADER_IMAGE_WIDTH,
            'height'        => HEADER_IMAGE_HEIGHT,
        );
    }
}

/**
 * Styles the header image and text displayed on the blog
 *
 * @see grafipress_custom_header_setup().
 *
 * @since Shape 1.0
 */
if ( ! function_exists( 'grafipress_header_style' ) ) :
    function grafipress_header_style() {

        // If no custom options for text are set, let's bail
        // get_header_textcolor() options: HEADER_TEXTCOLOR is default, hide text (returns 'blank') or any hex value
        if ( HEADER_TEXTCOLOR == get_header_textcolor() && '' == get_header_image() )
            return;
        // If we get this far, we have custom styles. Let's do this.
        ?>
        <style type="text/css">

        /* Do we have a custom header image? */
        <?php if ( '' != get_header_image() ) : ?>
            .site-header {
                display: block;
                padding-top: <?php echo HEADER_IMAGE_HEIGHT / 3 ?>px;
                text-align: center;
            }
        <?php endif;

        // Has the text been hidden?
        if ( 'blank' == get_header_textcolor() ) : ?>
            .site-title, .site-description, .page-title {
                position: absolute !important;
                clip: rect(1px 1px 1px 1px); /* IE6, IE7 */
                clip: rect(1px, 1px, 1px, 1px);
            }
            .site-header hgroup {
                background: none;
                padding: 0;
            }

        /* If the user has set a custom color for the text use that */
        <?php else : ?>
            .site-title a, .site-title, .site-description, .page-title {
                color: #<?php echo get_header_textcolor(); ?> !important;
            }
        <?php endif; ?>

        </style>
        <?php
    }
endif; // grafipress_header_style

if ( ! function_exists( 'grafipress_admin_header_style' ) ) :
/**
 * Styles the header image displayed on the Appearance > Header admin panel.
 *
 * @see grafipress_custom_header_setup().
 *
 * @since Shape 1.0
 */
function grafipress_admin_header_style() {
?>
    <style type="text/css">
    .appearance_page_custom-header #headimg { /* This is the container for the Custom Header preview. */
        background: #33605a;
        border: none;
        min-height: 0 !important
    }
    #headimg h1 { /* This is the site title displayed in the preview */
        font-size: 45px;
        font-family: Georgia, 'Times New Roman', serif;
        font-style: italic;
        font-weight: normal;
        padding: 0.8em 0.5em 0;
    }
    #desc { /* This is the site description (tagline) displayed in the preview */
        padding: 0 2em 2em;
    }
    #headimg h1 a,
    #desc {
        color: #e9e0d1;
        text-decoration: none;
    }
    </style>
<?php
}
endif; // grafipress_admin_header_style

if ( ! function_exists( 'grafipress_admin_header_image' ) ) :
/**
 * Custom header image markup displayed on the Appearance > Header admin panel.
 *
 * @see grafipress_custom_header_setup().
 *
 * @since Shape 1.0
 */
function grafipress_admin_header_image() { ?>
    <div id="headimg">
        <?php
        if ( 'blank' == get_header_textcolor() || '' == get_header_textcolor() )
            $style = ' style="display:none;"';
        else
            $style = ' style="color:#' . get_header_textcolor() . ';"';
        ?>
        <h1><a id="name"<?php echo $style; ?> onclick="return false;" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a></h1>
        <div id="desc"<?php echo $style; ?>><?php bloginfo( 'description' ); ?></div>
        <?php $header_image = get_header_image();
        if ( ! empty( $header_image ) ) : ?>
            <img src="<?php echo esc_url( $header_image ); ?>" alt="" />
        <?php endif; ?>
    </div>
<?php }
endif; // grafipress_admin_header_image