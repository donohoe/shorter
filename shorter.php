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

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Shorter {

	public function __construct() {
		$this->debug_key = 'xyz';
		$this->base_path = '/s/';
		$this->set_debug_mode();
		add_filter('template_include', array( $this, 'watch_for_short_url' ) );
	}

	public function watch_for_short_url ( $template ) {
		$uri_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
		if (strpos( $uri_path, $this->base_path ) === 0){
			$post_id = (int) str_replace( $this->base_path, '', $uri_path );
			if (!empty($post_id) && $post_id > 0) {
				$this->redirect_to_post( $post_id, $uri_path );
			}
		}
		return $template;
	}

	private function redirect_to_post( $post_id, $uri_path ) {
		$permalink = get_permalink( $post_id );
		if (!empty( $permalink )) {

			$pieces = explode('-',  $uri_path);
			if (isset($pieces[1])) {
				$hash = htmlspecialchars($pieces[1], ENT_QUOTES);
				if (!empty($hash)) {
					$permalink .= '#s=' . substr($hash, 0, 16);
				}
			}

			$redirect_by = $this->getRedirectBy();
			if ($this->debug) {
				header("Content-type: text/javascript");
				print json_encode(array(
					'post_id' => $post_id, 
					'permalink' => $permalink, 
					'redirect_by' => $redirect_by
				), JSON_PRETTY_PRINT);
				exit;
			} else {
				if ( wp_redirect( $permalink, 301, $redirect_by ) ) {
					exit; 
				}
			}

		}
	}

	private function getRedirectBy() {
		$redirect_by = 'Shorter';
		if (isset($_SERVER['HTTP_REFERER'])) {
			$referer = $_SERVER['HTTP_REFERER'];
			if (!filter_var($referer, FILTER_VALIDATE_URL) === false) {
				$domain = parse_url($referer);
				if (!empty($domain['host'])) {
					$redirect_by .= '-' . htmlspecialchars($domain['host'], ENT_QUOTES);
				}
			}
		}
		return $redirect_by;
	}

	private function set_debug_mode() {
		$this->debug = false;
		if (isset($_GET['debug']) && $_GET['debug'] === $this->debug_key ) {
			$this->debug = true;
		}
	}
}

function run_shorter() {
	$plugin = new Shorter();
}
run_shorter();
