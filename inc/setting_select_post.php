<?php

global $Plvc;

$custom_posts_types = $Plvc->ClassConfig->get_all_custom_posts();

?>
<div class="wrap <?php echo $Plvc->Plugin['ltd']; ?>">

	<h2><?php echo $this->page_title; ?></h2>

	<div class="metabox-holder columns-1">
	
		<div id="postbox-container" class="postbox-container">

			<?php if( !empty( $custom_posts_types ) ) : ?>
			
				<p><?php _e( 'Please choose a custom post type you want to change the list\'s view.' , $Plvc->Plugin['ltd'] ); ?></p>

				<ul>
					<?php foreach( $custom_posts_types as $post_name => $custom_post ) : ?>
						<li>
							<a href="<?php echo esc_url( add_query_arg( array( 'custom_post_type' => $post_name ) ) ); ?>"><?php echo $custom_post['name']; ?></a>
							<span class="description">[ <?php echo $post_name; ?> ]</span>
						</li>
					<?php endforeach; ?>
				</ul>
			
			<?php else: ?>
			
				<p><?php _e( 'No custom post type found.' , $Plvc->Plugin['ltd'] ); ?>
			
			<?php endif; ?>

		</div>

		<div class="clear"></div>

	</div>

</div>
