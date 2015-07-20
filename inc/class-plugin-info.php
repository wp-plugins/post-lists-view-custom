<?php

if ( !class_exists( 'Plvc_Plugin_Info' ) ) :

class Plvc_Plugin_Info
{

	function __construct() {}

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
		$url .= '&utm_campaign=' . str_replace( '.' , '_' , $Plvc->Ver );

		return $url;

	}

	function get_gravatar_src( $size = 40 ) {
		
		global $Plvc;

		$img_src = $Plvc->Current['schema'] . 'www.gravatar.com/avatar/7e05137c5a859aa987a809190b979ed4?s=' . $size;

		return $img_src;

	}

}

endif;
