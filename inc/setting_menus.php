<?php


if( !empty( $_POST["SetPage"] ) ) {

	if( $this->SetPage == 'widgets' ) {
		if( !empty( $_POST["reset"] ) ) {
			$this->update_reset( $this->SetPage );
		} elseif( !empty( $_POST["update"] ) ) {
			$this->update_widgets();
		}
	} elseif( $this->SetPage == 'menus' ) {
		if( !empty( $_POST["reset"] ) ) {
			$this->update_reset( $this->SetPage );
		} elseif( !empty( $_POST["update"] ) ) {
			$this->update_menus();
		}
	} elseif( $this->SetPage == 'menus_adv' ) {
		if( !empty( $_POST["reset"] ) ) {
			$this->update_reset( $this->SetPage );
		} elseif( !empty( $_POST["update"] ) ) {
			$this->update_menus_adv();
		}
	}
	
}

// include js css
$ReadedJs = array( 'jquery' , 'jquery-ui-sortable' );
wp_enqueue_script( $this->PageSlug ,  $this->Url . $this->PluginSlug . '.js', $ReadedJs , $this->Ver );
wp_enqueue_style( $this->PageSlug , $this->Url . $this->PluginSlug . '.css', array() , $this->Ver );

// get data
$Data = $this->get_data( $this->SetPage );
$Columns = $this->get_list_columns( $this->SetPage );

?>

<div class="wrap">
	<div class="icon32" id="icon-options-general"></div>
	<h2><?php echo $this->PageTitle; ?></h2>
	<?php echo $this->Msg; ?>

	<p><?php _e( 'Please select the column item to hide.' , $this->ltd ); ?></p>

	<h3 id="plvc-apply-user-roles"><?php echo $this->get_apply_roles(); ?></h3>

	<?php $class = $this->ltd; ?>
	<?php if( get_option( $this->Record["donate_width"] ) ) $class .= ' full-width'; ?>
	<div class="metabox-holder columns-2 <?php echo $class; ?>">

		<div id="postbox-container-1" class="postbox-container">

				<form id="post_lists_view_custom_form" method="post" action="">
					<input type="hidden" name="<?php echo $this->UPFN; ?>" value="Y">
					<?php wp_nonce_field( $this->Nonces["value"] , $this->Nonces["field"] ); ?>
			
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
							<div class="widget plugin"><div class="widget-top"><div class="widget-title"><h4><?php _e( 'Plugin' ); ?></h4></div></div></div>
							<div class="widget custom_fields"><div class="widget-top"><div class="widget-title"><h4><?php _e( 'Custom Fields' ); ?></h4></div></div></div>
							<div class="widget custom_post"><div class="widget-top"><div class="widget-title"><h4><?php _e( 'Custom' ); ?><?php _e( 'Post' ); ?></h4></div></div></div>
							<div class="widget custom_taxonomy"><div class="widget-top"><div class="widget-title"><h4><?php _e( 'Custom' ); ?><?php _e( 'Taxonomy' , $this->ltd ); ?></h4></div></div></div>
							<div class="clear"></div>
						</div>

							<table class="form-table menu-lists">
								<tbody>
									<?php foreach( $Columns as $column_id => $column ) : ?>
										<?php $this->setting_list_menu( $Data , $column_id , $Columns[$column_id] , $this->SetPage ); ?>
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
						<input type="submit" class="button-secondary" name="reset" value="<?php _e( 'Reset' ); ?>" />
					</p>
			
				</form>

		</div>

		<div id="postbox-container-2" class="postbox-container">

			<?php include_once 'donation.php'; ?>

		</div>

		<div class="clear"></div>

	</div>

</div>
