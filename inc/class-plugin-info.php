<?php

if ( !class_exists( 'Plvc_Plugin_Info' ) ) :

class Plvc_Plugin_Info
{

	var $links = array();
	var $DonateKey = 'd77aec9bc89d445fd54b4c988d090f03';
	var $DonateRecord = '';
	var $DonateOptionRecord = '';

	function __construct() {
		
		add_action( 'plugins_loaded' , array( $this , 'set_links' ) , 20 );
		add_action( 'plugins_loaded' , array( $this , 'setup' ) , 20 );
		add_action( 'plugins_loaded' , array( $this , 'set_ajax' ) , 20 );
		
	}

	function set_links() {
		
		global $Plvc;

		$this->links['author'] = 'http://gqevu6bsiz.chicappa.jp/';
		$this->links['forum'] = 'http://wordpress.org/support/plugin/' . $Plvc->Plugin['plugin_slug'];
		$this->links['review'] = 'http://wordpress.org/support/view/plugin-reviews/' . $Plvc->Plugin['plugin_slug'];
		$this->links['profile'] = 'http://profiles.wordpress.org/gqevu6bsiz';
		
		$this->links['setting'] = admin_url( 'admin.php?page=' . $Plvc->Plugin['page_slug'] );
		
	}

	function setup() {
		
		global $Plvc;
		
		$this->DonateRecord = $Plvc->Plugin['ltd'] . '_donated';
		$this->DonateOptionRecord = $Plvc->Plugin['ltd'] . '_donate_width';
		
	}
	
	function set_ajax() {
		
		global $Plvc;
		
		if( $Plvc->Current['admin'] && $Plvc->Current['ajax'] ) {
			add_action( 'wp_ajax_plvc_donation_toggle' , array( $this , 'wp_ajax_donation_toggle' ) );
		}

	}

	function wp_ajax_donation_toggle() {
		
		global $Plvc;

		if( isset( $_POST['f'] ) ) {

			$val = intval( $_POST['f'] );
			$Plvc->ClassData->update_donate_toggle( $val );

		}
		
		die();
		
	}

	function version_checked() {

		global $Plvc;

		$readme = file_get_contents( $Plvc->Plugin['dir'] . 'readme.txt' );
		$items = explode( "\n" , $readme );
		$version_checked = '';
		foreach( $items as $key => $line ) {
			if( strpos( $line , 'Requires at least: ' ) !== false ) {
				$version_checked .= str_replace( 'Requires at least: ' , '' ,  $line );
				$version_checked .= ' - ';
			} elseif( strpos( $line , 'Tested up to: ' ) !== false ) {
				$version_checked .= str_replace( 'Tested up to: ' , '' ,  $line );
				break;
			}
		}
		
		return $version_checked;
		
	}

	function author_url( $args ) {
		
		global $Plvc;

		$url = 'http://gqevu6bsiz.chicappa.jp/';
		
		if( !empty( $args['translate'] ) ) {
			$url .= 'please-translation/';
		} elseif( !empty( $args['donate'] ) ) {
			$url .= 'please-donation/';
		} elseif( !empty( $args['contact'] ) ) {
			$url .= 'contact-us/';
		} elseif( !empty( $args['add-on'] ) ) {
			$url .= 'post-lists-view-custom-for-multiple-setups-add-on/';
		}
		
		$url .= $this->get_utm_link( $args );

		return $url;

	}

	function get_utm_link( $args ) {
		
		global $Plvc;

		$url = '?utm_source=' . $args['tp'];
		$url .= '&utm_medium=' . $args['lc'];
		$url .= '&utm_content=' . $Plvc->Plugin['ltd'];
		$url .= '&utm_campaign=' . str_replace( '.' , '_' , $Plvc->Plugin['ver'] );

		return $url;

	}

	function is_donate_key_check( $key ) {
		
		$check = false;
		$key = md5( strip_tags( $key ) );
		if( $this->DonateKey == $key )
			$check = $key;

		return $check;

	}

	function is_donated() {
		
		global $Plvc;

		$donated = false;
		$donateKey = $Plvc->ClassData->get_donate_key( $this->DonateRecord );

		if( !empty( $donateKey ) && $donateKey == $this->DonateKey ) {
			$donated = true;
		}

		return $donated;

	}

	function get_width_class() {
		
		global $Plvc;

		$class = $Plvc->Plugin['ltd'];
		
		if( $this->is_donated() ) {
			$width_option = $Plvc->ClassData->get_donate_width();
			if( !empty( $width_option ) ) {
				$class .= ' full-width';
			}
		}
		
		return $class;

	}
	
	function get_gravatar_src( $size = 40 ) {
		
		global $Plvc;

		$img_src = $Plvc->Current['schema'] . 'www.gravatar.com/avatar/7e05137c5a859aa987a809190b979ed4?s=' . $size;

		return $img_src;

	}

	function admin_footer_text() {
		
		$author_url = $this->author_url( array( 'tp' => 'use_plugin' , 'lc' => 'footer' ) );
		$text = sprintf( '<a href="%1$s" target="_blank"><img src="%2$s" width="18" /></a>' ,  $author_url , $this->get_gravatar_src( '18' ) );
		$text .= sprintf( 'Plugin developer : <a href="%s" target="_blank">gqevu6bsiz</a>' , $author_url );

		return $text;
		
	}

	function donate_notice() {
		
		global $Plvc;
		
		$is_donated = $this->is_donated();
		if( empty( $is_donated ) )
			printf( '<div class="updated"><p><strong><a href="%1$s" target="_blank">%2$s</a></strong></p></div>' , $this->author_url( array( 'donate' => 1 , 'tp' => 'use_plugin' , 'lc' => 'footer' ) ) , __( 'Please consider making a donation.' , $Plvc->Plugin['ltd'] ) );

	}
	
}

endif;
