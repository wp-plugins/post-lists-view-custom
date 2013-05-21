<?php

$args = array( 'public' => true, '_builtin' => false );
$PostTypes = get_post_types( $args , 'objects' );

// include js css
$ReadedJs = array( 'jquery' , 'jquery-ui-sortable' );
wp_enqueue_script( $this->PageSlug ,  $this->Dir . dirname( dirname( plugin_basename( __FILE__ ) ) ) . '.js', $ReadedJs , $this->Ver );
wp_enqueue_style( $this->PageSlug , $this->Dir . dirname( dirname( plugin_basename( __FILE__ ) ) ) . '.css', array() , $this->Ver );

?>

<div class="wrap">
	<div class="icon32" id="icon-options-general"></div>
	<h2><?php _e( 'Custom Posts Type List Customize' , $this->ltd ); ?></h2>
	<?php echo $this->Msg; ?>

	<?php $class = $this->ltd; ?>
	<?php if( get_option( $this->Record["donate_width"] ) ) $class .= ' full-width'; ?>
	<div class="metabox-holder columns-2 <?php echo $class; ?>">

		<div id="postbox-container-1" class="postbox-container">

			<?php if( !empty( $PostTypes ) && is_array( $PostTypes ) ) : ?>
		
				<p><?php _e( 'Please choose a custom post type you want to change the list\'s view.' , $this->ltd ); ?></p>
				<p>&nbsp;</p>

				<ul>
					<?php foreach($PostTypes as $name => $Type) : ?>
						<li><a href="<?php echo self_admin_url( 'admin.php?page=' . $this->Record["custom_posts"] . '&setname=custom_posts&name=' . esc_html( $name ) ); ?>"><?php echo esc_html( $Type->labels->name ); ?></a> <span class="description">[ <?php echo esc_html( $name ); ?> ]</span></li>
					<?php endforeach; ?>
				</ul>

			<?php else: ?>
		
				<p><?php _e( 'No custom post type found.' , $this->ltd ); ?>
		
			<?php endif; ?>

		</div>

		<div id="postbox-container-2" class="postbox-container">

			<?php include_once 'donation.php'; ?>

		</div>

		<div class="clear"></div>

	</div>

</div>
