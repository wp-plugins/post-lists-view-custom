<?php

global $wp_version;

// get data
$Data = $this->get_menus_data();

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
	<h2><?php echo $this->PageTitle; ?></h2>
	<?php echo $this->Msg; ?>

	<h3 id="plvc-apply-user-roles"><?php echo $this->get_apply_roles(); ?></h3>

	<?php $class = $this->ltd; ?>
	<?php if( get_option( $this->Record["donate_width"] ) ) $class .= ' full-width'; ?>
	<div class="metabox-holder columns-2 <?php echo $class; ?>">

		<div id="postbox-container-1" class="postbox-container">

			<p><?php _e( 'Please select the column item to hide.' , $this->ltd ); ?></p>

				<form id="post_lists_view_custom_form" method="post" action="<?php echo remove_query_arg( $this->MsgQ ); ?>">
					<input type="hidden" name="<?php echo $this->UPFN; ?>" value="Y">
					<?php wp_nonce_field( $this->Nonces["value"] , $this->Nonces["field"] ); ?>
					<input type="hidden" name="record_field" value="<?php echo $this->SetPage; ?>" />
					<input type="hidden" name="SetPage" value="<?php echo $this->SetPage; ?>" />
	
					<div class="stuffbox">
						<h3 class="hndle">
							<span>
								<?php if( $this->SetPage == 'widgets' ) : ?>
									<?php _e( 'Available Widgets' ); ?>
								<?php elseif( $this->SetPage == 'menus' ) : ?>
									<?php _e( 'Menus' ); ?>
								<?php elseif( $this->SetPage == 'menus_adv' ) : ?>
									<?php _e( 'Show advanced menu properties' ); ?>
								<?php endif; ?>
							</span>
						</h3>
						<div class="inside">

						<br />
						<div class="example_widgets">
							<p class="description"><?php _e( 'Description' ); ?></p>
							<div class="widget"><div class="widget-top"><div class="widget-title"><h4><?php _e( 'Default' ); ?></h4></div></div></div>
							<div class="widget plugin"><div class="widget-top"><div class="widget-title"><h4><?php _e( 'Plugin' ); ?> / <?php _e( 'Current Theme' ); ?></h4></div></div></div>
							<div class="widget custom_post"><div class="widget-top"><div class="widget-title"><h4><?php _e( 'Custom' ); ?><?php _e( 'Posts' ); ?></h4></div></div></div>
							<div class="widget custom_taxonomy"><div class="widget-top"><div class="widget-title"><h4><?php _e( 'Custom' ); ?><?php _e( 'Taxonomy' , $this->ltd ); ?></h4></div></div></div>
							<div class="clear"></div>
						</div>

							<table class="form-table menu-lists">
								<tbody>
									<?php foreach( $Data as $menu_id => $menu_set ) : ?>
										<?php $this->setting_list_menu( $menu_id , $menu_set ); ?>
									<?php endforeach; ?>
								</tbody>
							</table>
							<br />
						</div>
					</div>

					<p class="submit">
						<input type="submit" class="button-primary" name="update" value="<?php _e( 'Save' ); ?>" />
					</p>
					<p class="submit reset">
						<span class="description"><?php _e( 'Reset all settings?' , $this->ltd ); ?></span>
						<input type="submit" class="button-secondary" name="reset" value="<?php _e( 'Reset settings' , $this->ltd ); ?>" />
					</p>
			
				</form>

		</div>

		<div id="postbox-container-2" class="postbox-container">

			<?php include_once 'donation.php'; ?>

		</div>

		<div class="clear"></div>

	</div>

</div>
