<?php
/**
 * Plugin Name: Simple Accessible Spoilers
 * Plugin URI: https://wordpress.org/plugins/simple-accessible-spoilers/
 * Description: Create fully accessible content spoilers with a shortcode.
 * Version: 1.0.13
 * Author: AlumniOnline Web Services
 * Author URI: https://www.alumnionlineservices.com/php-scripts/simple-accessible-spoilers/
 * Text Domain: simple-accessible-spoilers
 * License: GPLv3
 *
 * @package Inline Spoilers
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Create spoiler shortcode
 **/
function simple_accessible_spoilers_shortcode( $atts, $content ) {
	$output = '';
	$head   = '';
	$body   = '';
	$extra  = '';

	// validate input and sanitize.
	$atts = simple_accessible_spoilers_validate_input( $atts );

	$attributes = shortcode_atts(
		array(
			'title'         => '&nbsp;',
			'initial_state' => 'collapsed',
			'group'         => '',
			'tag'           => 'div',
		),
		$atts,
		'spoiler'
	);

	$title         = esc_attr( $attributes['title'] );
	$initial_state = $attributes['initial_state'];
	$tag           = esc_attr( $attributes['tag'] );
	$group         = esc_attr( $attributes['group'] );
	$accordionid   = uniqid();

	if ( '' !== $group ) {
		$groupedclass     = 'accordion_group_' . $group;
		$groupedclassbody = 'accordion_group_body_' . $group;
	} else {
		$groupedclass     = '';
		$groupedclassbody = '';
	}

	if ( 'expanded' === $initial_state ) {
		$head_class = 'expanded';
		$arrowclass = 'dashicons-arrow-down-alt2';
	} else {
		$head_class = 'collapsed';
		$arrowclass = 'dashicons-arrow-right-alt2';
	}

	if ( 'expanded' === $initial_state ) {
		$aria_expand_state = 'true';
	} else {
		$aria_expand_state = 'false';
	}

	if ( 'expanded' === $initial_state ) {
		$aria_hidden_state = 'false';
	} else {
		$aria_hidden_state = 'true';
	}

	if ( 'expanded' === $initial_state ) {
		$body_atts = 'style="display: block;"';
	} else {
		$body_atts = 'style="display: none;"';
	}

	$head .= '<' . esc_attr( $tag ) . ' >';
	$head .= '<button data-group="' . esc_attr( $group ) . '" aria-controls="accordion_' . esc_attr( $accordionid ) . '" class="spoiler-head no-icon ' . esc_attr( $head_class ) . ' ' . esc_attr( $groupedclass ) . '" aria-expanded="' . esc_attr( $aria_expand_state ) . '" >';
	$head .= $title;

	$head .= '<span class="dashicons ' . esc_attr( $arrowclass ) . '" aria-hidden></span></button></' . esc_attr( $tag ) . '>';

	$body .= '<div aria-hidden="' . esc_attr( $aria_hidden_state ) . '" id="accordion_' . esc_attr( $accordionid ) . '" class="spoiler-body ' . esc_attr( $groupedclassbody ) . '" ' . $body_atts . '>';
	$body .= balanceTags( do_shortcode( $content ), true );
	$body .= '</div>';

	$extra .= '<div class="spoiler-body ' . esc_attr( $groupedclassbody ) . '">';
	$extra .= balanceTags( do_shortcode( $content ), true );
	$extra .= '</div>';

	$output .= '<div class="spoiler-wrap">';
	$output .= $head;
	$output .= $body;
	$output .= '<noscript>';
	$output .= ( 'collapsed' === esc_attr( $initial_state ) ) ? $extra : '';
	$output .= '</noscript>';
	$output .= '</div>';

	return $output;
}
$shortcode_option = get_option( 'simple_accessible_spoilers_shortcode', 'spoiler' );
add_shortcode( $shortcode_option, 'simple_accessible_spoilers_shortcode' );

/**
 * Import admin styles
 **/
function simple_accessible_spoilers_admin_styles() {
	wp_register_style( 'simple-accessible-spoilers-admin-style', plugins_url( 'styles/simple-accessible-spoilers-admin.css', __FILE__ ), null, false );

		wp_enqueue_style( 'simple-accessible-spoilers-admin-style' );
}
add_action( 'admin_enqueue_scripts', 'simple_accessible_spoilers_admin_styles' );

/**
 * Import public styles
 **/
function simple_accessible_spoilers_scripts() {

	$shortcode_option = get_option( 'simple_accessible_spoilers_shortcode', 'spoiler' );
	wp_register_style( 'simple-accessible-spoilers-style', plugins_url( 'styles/simple-accessible-spoilers-default.css', __FILE__ ), null, false );

	wp_register_script( 'simple-accessible-spoilers-scripts', plugin_dir_url( __FILE__ ) . 'scripts/simple-accessible-spoilers-scripts.js', array( 'jquery' ), filemtime( plugin_dir_path( __FILE__ ) . 'scripts/simple-accessible-spoilers-scripts.js' ), '', true );

	wp_enqueue_style( 'simple-accessible-spoilers-style' );
	wp_enqueue_script( 'simple-accessible-spoilers-scripts' );

	wp_enqueue_style( 'dashicons' );
}
add_action( 'wp_enqueue_scripts', 'simple_accessible_spoilers_scripts' );

/**
 * Add dashboard widget
 **/
function simple_accessible_spoilers_notices() {
	wp_add_dashboard_widget( 'simple_accessible_spoilers_notice', 'Plugin Recommendations', 'simple_accessible_spoilers_notice_display' );
}
add_action( 'wp_dashboard_setup', 'simple_accessible_spoilers_notices' );

/**
 * Display Notice
 */
function simple_accessible_spoilers_notice_display() {
	echo '<div class="simple_accessible_spoilers_widget">';
	esc_html_e( 'Improve the accessibility of your website using the ', 'simple-accessible-spoilers' );
	echo '<a href="https://wordpress.org/plugins/wp-ada-compliance-check-basic/">';
	esc_html_e( 'WP ADA Compliance Check Plugin.', 'simple-accessible-spoilers' );
	echo '</a>';
	echo '</div>';
}


/**
 * Validate input
 */
function simple_accessible_spoilers_validate_input( $atts ) {

	foreach ( $atts as $key => $value ) {
		$atts[ $key ] = sanitize_text_field( $value );

		if ( 'initial_state' === $key && ! in_array( $value, array( 'collapsed', 'expanded' ) ) ) {
			$atts[ $key ] = 'collapsed';
		}
		if ( 'tag' === $key && ! preg_match( '/^[a-z0-9]+$/i', $value ) ) {
			$atts[ $key ] = 'h2';
		}
		if ( 'group' === $key && ! preg_match( '/^[a-z0-9]+$/i', $value ) ) {
			$atts[ $key ] = '';
		}
		if ( 'title' === $key && ! preg_match( "/^([[:alnum:]]|-|[[:space:]]|[[:punct:]]|'||[À-ÿ]|ő|ű)+$/D", $value ) ) {
			$atts[ $key ] = '';
		}
	}

	return $atts;
}

/**
 * Add admin menu
 */
function simple_accessible_spoilers_admin_menu() {
	add_options_page( __( 'Simple Accessible Spoliers Settings', 'simple-accessible-spoilers' ), __( 'Simple Accessible Spoliers Settings', 'simple-accessible-spoilers' ), 'manage_options', 'simple-accessible-spoilers-options', 'simple_accessible_spoilers_options_page' );
}
add_action( 'admin_menu', 'simple_accessible_spoilers_admin_menu' );

/**
 * Create options page
 **/
function simple_accessible_spoilers_options_page() {

	?>
<form action='options.php' method='post'>

	<h2> <?php esc_html_e( 'Simple Accessible Spoliers Settings', 'simple-accessible-spoilers' ); ?>
	</h2>

	<?php
		settings_fields( 'simple_accessible_spoilers_options_page' );
		simple_accessible_spoilers_do_settings_sections( 'simple_accessible_spoilers_options_page' );
		submit_button();
	?>

</form>
<?php
}

/**
 * Create settings
 **/
function simple_accessible_spoilers_settings_init() {

	add_settings_section(
		'simple_accessible_spoilers_options_page_section',
		'',
		'simple_accessible_spoilers_text',
		'simple_accessible_spoilers_options_page'
	);

	register_setting(
		'simple_accessible_spoilers_options_page',
		'simple_accessible_spoilers_shortcode',
		array(
			'type'              => 'array',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	add_settings_field(
		'simple_accessible_spoilers_shortcode',
		__( 'Shortcode', 'simple-accessible-spoilers' ),
		'simple_accessible_spoilers_shortcode_render',
		'simple_accessible_spoilers_options_page',
		'simple_accessible_spoilers_options_page_section'
	);
}
add_action( 'admin_init', 'simple_accessible_spoilers_settings_init' );

/**
 * Display settings section
 **/
function simple_accessible_spoilers_do_settings_sections( $page ) {
	global $wp_settings_sections, $wp_settings_fields;

	if ( ! isset( $wp_settings_sections[ $page ] ) ) {
		return;
	}

	foreach ( (array) $wp_settings_sections[ $page ] as $section ) {
		if ( $section['title'] ) {
			echo '<h2>';
			echo esc_attr( $section['title'] );
			echo '</h2>';
		}

		if ( $section['callback'] ) {
			call_user_func( $section['callback'], $section );
		}

		if ( ! isset( $wp_settings_fields ) || ! isset( $wp_settings_fields[ $page ] ) || ! isset( $wp_settings_fields[ $page ][ $section['id'] ] ) ) {
			continue;
		}
		echo '<div>';
		simple_accessible_spoilers_do_settings_fields( $page, $section['id'] );
		echo '</div>';
	}
}
/**
 * Display WordPress settings without table
 **/
function simple_accessible_spoilers_do_settings_fields( $page, $section ) {
	global $wp_settings_fields;

	if ( ! isset( $wp_settings_fields[ $page ][ $section ] ) ) {
		return;
	}

	foreach ( (array) $wp_settings_fields[ $page ][ $section ] as $field ) {
		echo '<div>';

		call_user_func( $field['callback'], $field['args'] );

		echo '</div>';
	}
}
/**
 * Display section text.
 **/
function simple_accessible_spoilers_text() {
}
/**
 * Display shortcode field
 **/
function simple_accessible_spoilers_shortcode_render() {
	$option = get_option( 'simple_accessible_spoilers_shortcode', 'spoiler' );
	echo '<p>';
	echo '<label for="simple_accessible_spoilers_shortcode">';
	esc_html_e( 'Enter the shortcode to be used: (default: spoiler)', 'simple-accessible-spoilers' );
	echo '</label>';
	echo '<br />';
	echo '<input type="text" size="65" name="simple_accessible_spoilers_shortcode" id="simple_accessible_spoilers_shortcode" value="' . esc_attr( $option ) . '" />';
	echo '</p>';
}
?>