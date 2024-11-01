<?php
/*
Plugin Name: WP Server & Theme Info
Plugin URI:  http://plugins.wordpress.org/wp-server-theme-info/
Description: Show the current versions from WordPress | PHP | Theme | Webserver | MySQL in the Backend Dashboard footer area.
Version:     1.0
Author:      JAMOS Web Service
Author URI:  http://www.jamos.ch/plugins/wp-server-theme-info
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: WP Server & Theme Info
*/

/* wp_get_theme( $stylesheet, $theme_root ); */

if ( is_admin() ) {	

class wpstInfo {

	private $wpst;
	
	public function __construct( \wpdb $wpdb ) {
		$this->wpst = $wpdb;
		add_action( 'plugins_loaded', array( $this, 'load_text_domain' ) );
		add_filter( 'update_footer', array( $this, 'version_in_footer' ), 11 ); 
	}

	public function load_text_domain() {
		load_plugin_textdomain( 'version-info' );
	}

	public function version_in_footer() {
		$update     = core_update_footer();
		$wp_version = strpos( $update, '<strong>' ) === 0 ? get_bloginfo( 'version' ) . ' (' . $update . ')' : get_bloginfo( 'version' );
		
		return sprintf( esc_attr__( 'WordPress: %s  | PHP: %s | THEME: %s | Server: %s | MySQL: %s', 'version-info' ), $wp_version, phpversion(), wp_get_theme(), $_SERVER['SERVER_SOFTWARE'], $this->wpst->get_var('SELECT VERSION();') );
	}
}

global $wpdb;
new wpstInfo( $wpdb );

}