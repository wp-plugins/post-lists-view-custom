<?php

global $wp_version;

$PostType = get_post_type_object( $this->SetPage );

$tmp = $this->get_data( "regist_columns" );
$Regist_Columns = array();
if( !empty( $tmp[$this->SetPage] ) ) {
	$Regist_Columns = $tmp[$this->SetPage];
}
unset( $tmp );


// include js css
$ReadedJs = array( 'jquery' , 'jquery-ui-sortable' );
wp_enqueue_script( $this->PageSlug ,  $this->Url . $this->PluginSlug . '.js', $ReadedJs , $this->Ver );

if ( version_compare( $wp_version , '3.8' , '<' ) ) {
	wp_enqueue_style( $this->PageSlug , $this->Url . $this->PluginSlug . '-3.7.css', array() , $this->Ver );
} else {
	wp_enqueue_style( $this->PageSlug , $this->Url . $this->PluginSlug . '.css', array() , $this->Ver );
}

// get data
$Data = $this->get_data( $this->SetPage );
$Columns = $this->get_list_columns( $this->SetPage );

?>

<div class="wrap">
	<div class="icon32" id="icon-options-general"></div>
	<h2><?php echo $this->PageTitle; ?></h2>
	<?php echo $this->Msg; ?>

	<p><?php _e( 'Please rearrange the order in which you want to view by Drag & Drop.' , $this->ltd ); ?></p>

	<?php if( !empty( $Regist_Columns ) ) : ?>
		<h3 id="plvc-apply-user-roles"><?php echo $this->get_apply_roles(); ?></h3>
	<?php endif; ?>

	<?php $class = $this->ltd; ?>
	<?php if( get_option( $this->Record["donate_width"] ) ) $class .= ' full-width'; ?>
	<div class="metabox-holder columns-2 <?php echo $class; ?>">

		<div id="postbox-container-1" class="postbox-container">

			<?php if( empty( $Regist_Columns ) ) : ?>

				<?php $edit_url = self_admin_url( '/edit.php?post_type=' . $this->SetPage ); ?>
				<p><?php _e( 'Could not read the columns.' , $this->ltd ); ?></p>
				<p><?php echo sprintf( __( 'Columns will be loaded automatically when you <a href="%1$s">%2$s</a>.' , $this->ltd ) , $edit_url , $PostType->labels->all_items ); ?></p>

			<?php else: ?>

				<form id="post_lists_view_custom_form" method="post" action="<?php echo remove_query_arg( $this->MsgQ ); ?>">
					<input type="hidden" name="<?php echo $this->UPFN; ?>" value="Y">
					<?php wp_nonce_field( $this->Nonces["value"] , $this->Nonces["field"] ); ?>
					<input type="hidden" name="record_field" value="<?php echo $this->SetPage; ?>" />
					<input type="hidden" name="SetPage" value="<?php echo $this->SetPage; ?>" />

					<div class="example_widgets">
						<p class="description"><?php _e( 'Description' ); ?></p>
						<div class="widget"><div class="widget-top"><div class="widget-title"><h4><?php _e( 'Default' ); ?></h4></div></div></div>
						<div class="widget plugin"><div class="widget-top"><div class="widget-title"><h4><?php _e( 'Plugin' ); ?></h4></div></div></div>
						<div class="widget custom_fields"><div class="widget-top"><div class="widget-title"><h4><?php _e( 'Custom Fields' ); ?></h4></div></div></div>
						<div class="clear"></div>
					</div>

					<hr />
					<p>&nbsp;</p>
					
					<h3><?php _e( 'Show' ); ?> <?php _e( 'Columns' ); ?></h3>
					<div class="widgets-holder-wrap">
						<div class="widgets-sortables">
							<div id="use" class="widget-list">
								<?php if( empty( $Data ) ) : ?>
									<?php foreach( $Columns as $column_id => $column ) : ?>
										<?php if( !empty( $column["use"] ) ) : ?>
											<?php $this->setting_list_widget( 'use' , $column_id , $column , $this->SetPage ); ?>
										<?php endif; ?>
									<?php endforeach; ?>
								<?php else : ?>
									<?php if( !empty( $Data["use"] ) ) : ?>
										<?php foreach( $Data["use"] as $column_id => $column ) : ?>
											<?php if( !empty( $Columns[$column_id] ) ) : ?>
												<?php $this->setting_list_widget( 'use' , $column_id , $column , $this->SetPage ); ?>
												<?php unset( $Columns[$column_id] ); ?>
											<?php endif; ?>
										<?php endforeach; ?>
									<?php endif; ?>
								<?php endif; ?>
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
							<?php if( empty( $Data ) ) : ?>
								<?php foreach( $Columns as $column_id => $column ) : ?>
									<?php if( !empty( $column["not_use"] ) ) : ?>
										<?php $this->setting_list_widget( 'not_use' , $column_id , $column , $this->SetPage ); ?>
									<?php endif; ?>
								<?php endforeach; ?>
							<?php else : ?>
								<?php foreach( $Columns as $column_id => $column ) : ?>
									<?php $this->setting_list_widget( 'not_use' , $column_id , $column , $this->SetPage ); ?>
								<?php endforeach; ?>
							<?php endif; ?>
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
