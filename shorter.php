<?php
/**
 * Plugin Name:       Shorter
 * Plugin URI:        https://github.com/donohoe/shorter
 * Description:       Basic URL shortener with Post ID
 * Version:           1.0
 * Requires at least: 4.9
 * Requires PHP:      5.6
 * Author:            Michael Donohoe
 * Author URI:        https://donohoe.dev
 * License:           None
 */

// If called without WordPress, exit.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Shorter {

	public function __construct() {
		$this->base_path = '/s/';
		add_filter('template_include', array( $this, 'template_include_by_url' ) );
	}

	public function template_include_by_url ( $template ) {
		$uri_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
		if (strpos( $uri_path, $this->base_path ) === 0){
			$post_id = (int) str_replace( $this->base_path, '', $uri_path );
			if (!empty($post_id) && $post_id > 0) {
				$permalink = get_permalink( $post_id );
				if (!empty( $permalink )) {
					if ( wp_redirect( $permalink ) ) {
						exit; 
					}
				}
			}
		}
		return $template;
	}

}

function run_shorter() {
	$plugin = new Shorter();
}
run_shorter();
