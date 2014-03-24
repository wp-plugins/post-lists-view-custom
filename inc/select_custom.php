<?php

global $wp_version;

// get all custom posts
$Get_all_custom_posts = $this->get_all_customposts();

// include js css
$ReadedJs = array( 'jquery' );
wp_enqueue_script( $this->PageSlug ,  $this->Url . $this->PluginSlug . '.js', $ReadedJs , $this->Ver );

if ( version_compare( $wp_version , '3.8' , '<' ) ) {
	wp_enqueue_style( $this->PageSlug , $this->Url . $this->PluginSlug . '-3.7.css', array() , $this->Ver );
} else {
	wp_enqueue_style( $this->PageSlug , $this->Url . $this->PluginSlug . '.css', array() , $this->Ver );
}
?>

<div class="wrap">
	<div class="icon32" id="icon-options-general"></div>
	<h2><?php echo sprintf( __( '%2$s for %3$s %1$s' , $this->ltd ) , __( 'Customize' ) , __( 'List View' ) , __( 'Custom Post Type' , $this->ltd ) ); ?></h2>
	<?php echo $this->Msg; ?>

	<?php $class = $this->ltd; ?>
	<?php if( get_option( $this->Record["donate_width"] ) ) $class .= ' full-width'; ?>
	<div class="metabox-holder columns-2 <?php echo $class; ?>">

		<div id="postbox-container-1" class="postbox-container">

			<?php if( !empty( $Get_all_custom_posts ) ) : ?>
		
				<p><?php _e( 'Please choose a custom post type you want to change the list\'s view.' , $this->ltd ); ?></p>
				<p>&nbsp;</p>

				<ul>
					<?php foreach( $Get_all_custom_posts as $post_name => $post_set ) : ?>
						<li><a href="<?php echo self_admin_url( 'admin.php?page=' . $this->Record["custom_posts"] . '&setname=custom_posts&name=' . $post_name ); ?>"><?php echo $post_set["name"]; ?></a> <span class="description">[ <?php echo $post_name; ?> ]</span></li>
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
