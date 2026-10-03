<?php
/**
 * Main plugin class. Start building here.
 *
 * Included as working examples you can keep or delete:
 * - A settings page under Settings > Plugin Starter with one saved option.
 * - A [plugin_starter] shortcode that loads the plugin's CSS and JS only when used.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Plugin_Starter {

	const OPTION = 'plugin_starter_settings';

	public static function init() {
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'register_assets' ) );
		add_action( 'admin_menu', array( __CLASS__, 'admin_menu' ) );
		add_action( 'admin_init', array( __CLASS__, 'register_settings' ) );
		add_shortcode( 'plugin_starter', array( __CLASS__, 'shortcode' ) );
		add_filter( 'plugin_action_links_' . plugin_basename( PLUGIN_STARTER_FILE ), array( __CLASS__, 'action_links' ) );
		register_activation_hook( PLUGIN_STARTER_FILE, array( __CLASS__, 'activate' ) );
	}

	/**
	 * Runs once when the plugin is activated.
	 */
	public static function activate() {
		add_option( self::OPTION, self::defaults() );
	}

	public static function defaults() {
		return array(
			'message' => 'Hello from Plugin Starter.',
		);
	}

	public static function settings() {
		return wp_parse_args( (array) get_option( self::OPTION, array() ), self::defaults() );
	}

	/* ---------- Front end ---------- */

	public static function register_assets() {
		wp_register_style( 'plugin-starter', PLUGIN_STARTER_URL . 'assets/plugin-starter.css', array(), PLUGIN_STARTER_VERSION );
		wp_register_script( 'plugin-starter', PLUGIN_STARTER_URL . 'assets/plugin-starter.js', array(), PLUGIN_STARTER_VERSION, true );
	}

	public static function shortcode( $atts ) {
		wp_enqueue_style( 'plugin-starter' );
		wp_enqueue_script( 'plugin-starter' );
		$s = self::settings();
		return '<div class="plugin-starter">' . esc_html( $s['message'] ) . '</div>';
	}

	/* ---------- Admin ---------- */

	public static function admin_menu() {
		add_options_page( 'Plugin Starter', 'Plugin Starter', 'manage_options', 'plugin-starter', array( __CLASS__, 'render_settings' ) );
	}

	public static function register_settings() {
		register_setting(
			'plugin_starter',
			self::OPTION,
			array(
				'type'              => 'array',
				'sanitize_callback' => array( __CLASS__, 'sanitize' ),
				'default'           => self::defaults(),
			)
		);
		add_settings_section( 'plugin_starter_main', '', '__return_false', 'plugin-starter' );
		add_settings_field(
			'message',
			'Message',
			array( __CLASS__, 'field_message' ),
			'plugin-starter',
			'plugin_starter_main',
			array( 'label_for' => 'plugin-starter-message' )
		);
	}

	public static function sanitize( $input ) {
		$input = is_array( $input ) ? $input : array();
		return array(
			'message' => isset( $input['message'] ) ? sanitize_text_field( $input['message'] ) : '',
		);
	}

	public static function field_message() {
		$s = self::settings();
		printf(
			'<input type="text" class="regular-text" id="plugin-starter-message" name="%s[message]" value="%s">',
			esc_attr( self::OPTION ),
			esc_attr( $s['message'] )
		);
	}

	public static function render_settings() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		echo '<div class="wrap"><h1>Plugin Starter</h1><form method="post" action="options.php">';
		settings_fields( 'plugin_starter' );
		do_settings_sections( 'plugin-starter' );
		submit_button();
		echo '</form></div>';
	}

	public static function action_links( $links ) {
		array_unshift( $links, '<a href="' . esc_url( admin_url( 'options-general.php?page=plugin-starter' ) ) . '">Settings</a>' );
		return $links;
	}
}
