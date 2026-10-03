<?php
/**
 * Plugin Name:       Plugin Starter
 * Description:       Short description of what Plugin Starter does.
 * Version:           0.1.0
 * Author:            Novoslate
 * Author URI:        https://novoslate.com
 * Text Domain:       plugin-starter
 * Requires at least: 6.0
 * Requires PHP:      7.4
 * License:           GPL-2.0-or-later
 * Update URI:        https://github.com/novoslate/plugin-starter
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'PLUGIN_STARTER_VERSION', '0.1.0' );
define( 'PLUGIN_STARTER_FILE', __FILE__ );
define( 'PLUGIN_STARTER_PATH', plugin_dir_path( __FILE__ ) );
define( 'PLUGIN_STARTER_URL', plugin_dir_url( __FILE__ ) );

/*
 * Updates from GitHub releases. Change this if the org or repo name differs.
 * For a private repo, define PLUGIN_STARTER_GITHUB_TOKEN in wp-config.php with a read-only token.
 */
if ( ! defined( 'PLUGIN_STARTER_GITHUB_REPO' ) ) {
	define( 'PLUGIN_STARTER_GITHUB_REPO', 'https://github.com/novoslate/plugin-starter/' );
}
require_once PLUGIN_STARTER_PATH . 'lib/plugin-update-checker/plugin-update-checker.php';
$plugin_starter_updater = YahnisElsts\PluginUpdateChecker\v5\PucFactory::buildUpdateChecker(
	PLUGIN_STARTER_GITHUB_REPO,
	__FILE__,
	'plugin-starter'
);
$plugin_starter_updater->getVcsApi()->enableReleaseAssets( '/plugin-starter\.zip$/' );
if ( defined( 'PLUGIN_STARTER_GITHUB_TOKEN' ) && PLUGIN_STARTER_GITHUB_TOKEN ) {
	$plugin_starter_updater->setAuthentication( PLUGIN_STARTER_GITHUB_TOKEN );
}

require_once PLUGIN_STARTER_PATH . 'includes/class-plugin-starter.php';
Plugin_Starter::init();
