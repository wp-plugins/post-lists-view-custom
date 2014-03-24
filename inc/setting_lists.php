<?php

global $wp_version;

// get list types
$Current_list = $this->current_list_types();

// get data
$Data = $this->get_lists_data();

// include js css
$ReadedJs = array( 'jquery' , 'jquery-ui-sortable' );
wp_enqueue_script( $this->PageSlug ,  $this->Url . $this->PluginSlug . '.js', $ReadedJs , $this->Ver );

if ( version_compare( $wp_version , '3.8' , '<' ) ) {
	wp_enqueue_style( $this->PageSlug , $this->Url . $this->PluginSlug . '-3.7.css', array() , $this->Ver );
} else {
	wp_enqueue_style( $this->PageSlug , $this->Url . $this->PluginSlug . '.css', array() , $this->Ver );
}
?>

<div class="wrap">
	<div class="icon32" id="icon-options-general"></div>
	<h2><?php echo $this->PageTitle; ?></h2>
	<?php echo $this->Msg; ?>

	<?php if( !empty( $Data ) ) : ?>
		<h3 id="plvc-apply-user-roles"><?php echo $this->get_apply_roles(); ?></h3>
	<?php endif; ?>
	
	<?php $class = $this->ltd; ?>
	<?php if( get_option( $this->Record["donate_width"] ) ) $class .= ' full-width'; ?>
	<div class="metabox-holder columns-2 <?php echo $class; ?>">

		<div id="postbox-container-1" class="postbox-container">

			<?php if( empty( $Current_list ) or empty( $Data ) ) : ?>

				<p><?php echo sprintf( __( 'Could not read the columns. Please load the %s.', $this->ltd ) , $Current_list["name"] ); ?></p>
				<p>
					<a href="<?php echo $Current_list["edit_link"]; ?>" id="column_load" class="button button-primary"><?php echo sprintf( __( 'Load the %s', $this->ltd ) , $Current_list["name"] ); ?></a>
				</p>
				<p class="loading">
					<span class="spinner"></span>
					<?php _e( 'Loading&hellip;' ); ?>
				</p>
				
			<?php else: ?>

				<p><?php _e( 'Please rearrange the order in which you want to view by Drag & Drop.' , $this->ltd ); ?></p>

				<form id="post_lists_view_custom_form" method="post" action="<?php echo remove_query_arg( $this->MsgQ ); ?>">
					<input type="hidden" name="<?php echo $this->UPFN; ?>" value="Y">
					<?php wp_nonce_field( $this->Nonces["value"] , $this->Nonces["field"] ); ?>
					<input type="hidden" name="record_field" value="<?php echo $this->SetPage; ?>" />
					<input type="hidden" name="SetPage" value="<?php echo $this->SetPage; ?>" />
					<input type="hidden" name="SetName" value="<?php echo $this->SetName; ?>" />

					<div class="example_widgets">
						<p class="description"><?php _e( 'Description' ); ?></p>
						<div class="widget"><div class="widget-top"><div class="widget-title"><h4><?php _e( 'Default' ); ?></h4></div></div></div>
						<div class="widget plugin"><div class="widget-top"><div class="widget-title"><h4><?php _e( 'Plugin' ); ?> / <?php _e( 'Current Theme' ); ?></h4></div></div></div>
						<?php if( $this->SetPage == 'post' or $this->SetPage == 'page' or $this->SetPage == 'custom_posts' ) : ?>
							<div class="widget custom_fields"><div class="widget-top"><div class="widget-title"><h4><?php _e( 'Custom Fields' ); ?></h4></div></div></div>
						<?php endif; ?>
						<div class="clear"></div>
					</div>

					<hr />
					<p>&nbsp;</p>
					
					<h3><?php _e( 'Show' ); ?> <?php _e( 'Columns' ); ?></h3>
					<div class="widgets-holder-wrap">
						<div class="widgets-sortables">
							<div id="use" class="widget-list">
								<?php foreach( $Data as $column_id => $column ) : ?>
									<?php $this->setting_list_widget( 'use' , $column_id , $column ); ?>
								<?php endforeach; ?>
							</div>
							<div class="clear"></div>
						</div>
					</div>
					<p>&nbsp;</p>
					
					<div class="drag_desc">
						<p class="description"><?php _e ( 'Please drag and drop the columns you want to show.' , $this->ltd ); ?></p>
					</div>
			
					<h3>
						<?php _e( 'Hide' ); ?> <?php _e( 'Columns' ); ?>
						<span id="removing-widget"><?php _e( 'Hide' ); ?> <span></span></span>
					</h3>
					<div class="widgets-holder">
						<div id="not_use" class="widget-list">
								<?php foreach( $Data as $column_id => $column ) : ?>
									<?php $this->setting_list_widget( 'not_use' , $column_id , $column ); ?>
								<?php endforeach; ?>
						</div>
						<div class="clear"></div>
					</div>
			
					<p class="submit">
						<input type="submit" class="button-primary" name="update" value="<?php _e( 'Save' ); ?>" />
					</p>
					<p class="submit reset">
						<span class="description"><?php _e( 'Reset all settings?' , $this->ltd ); ?></span>
						<input type="submit" class="button-secondary" name="reset" value="<?php _e( 'Reset settings' , $this->ltd ); ?>" />
					</p>
			
				</form>

			<?php endif; ?>

		</div>

		<div id="postbox-container-2" class="postbox-container">

			<?php include_once 'donation.php'; ?>

		</div>

		<div class="clear"></div>

	</div>

</div>
