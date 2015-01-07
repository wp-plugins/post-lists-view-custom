<?php

global $Plvc;

$menus = $this->get_data_menus( $this->menu_type );
?>
<div class="wrap">

	<h2><?php echo $this->page_title; ?></h2>
	
	<h3 id="plvc-apply-user-roles" class="nav-tab-wrapper"><?php echo $this->get_apply_roles_html(); ?></h3>

	<?php $class = $Plvc->ClassInfo->get_width_class(); ?>
	<div class="metabox-holder columns-2 <?php echo $class; ?>">

		<div id="postbox-container-1" class="postbox-container">

			<?php include_once $Plvc->Plugin['dir'] . 'inc/information.php'; ?>
		
		</div>

		<div id="postbox-container-2" class="postbox-container">

			<?php if( !empty( $menus ) ) : ?>

				<?php do_action( 'plvc_form_before' ); ?>

				<p><?php _e( 'Please select the column item to hide.' , $Plvc->Plugin['ltd'] ); ?></p>

				<form id="<?php echo $Plvc->Plugin['ltd']; ?>_<?php echo $this->menu_type; ?>_form" class="<?php echo $Plvc->Plugin['ltd']; ?>_form" method="post" action="<?php echo $this->get_action_link(); ?>">
	
					<input type="hidden" name="<?php echo $Plvc->Plugin['form']['field']; ?>" value="Y">
					<?php wp_nonce_field( $Plvc->Plugin['nonces']['value'] , $Plvc->Plugin['nonces']['field'] ); ?>
					<input type="hidden" name="record_field" value="<?php echo $Plvc->Plugin['record'][$this->menu_type]; ?>" />
					<input type="hidden" name="menu_type" value="<?php echo $this->menu_type; ?>" />
					<?php do_action( 'plvc_form_items' ); ?>
	
					<h3 class="hndle">
						<?php if( $this->menu_type == 'widgets' ) : ?>
							<?php _e( 'Available Widgets' ); ?>
						<?php elseif( $this->menu_type == 'menus' ) : ?>
							<?php _e( 'Menus' ); ?>
						<?php elseif( $this->menu_type == 'menus_adv' ) : ?>
							<?php _e( 'Show advanced menu properties' ); ?>
						<?php endif; ?>
					</h3>

					<table class="form-table menu-lists <?php echo $this->menu_type; ?>">
						<tbody>
							<?php foreach( $menus as $use_type => $menu ) : ?>
								<?php foreach( $menu as $menu_id => $menu_setting ) : ?>
									<?php $class = ''; ?>
									<?php if( !empty( $menu_setting['group'] ) ) : ?>
										<?php $class = strip_tags( $menu_setting['group'] ); ?>
									<?php endif; ?>
									<tr id="<?php echo $menu_id; ?>" class="<?php echo $class; ?>">
										<?php $Plvc->setting_list_menus( $this->menu_type , $use_type , $menu_id , $menu_setting ); ?>
									</tr>
								<?php endforeach; ?>
							<?php endforeach; ?>
						</tbody>
					</table>
					
					<p>&nbsp;</p>
	
					<?php submit_button(); ?>
	
				</form>
	
				<p>&nbsp;</p>
				<p>&nbsp;</p>
	
				<form id="<?php echo $Plvc->Plugin['ltd']; ?>_<?php echo $this->menu_type; ?>_reset_form" class="<?php echo $Plvc->Plugin['ltd']; ?>_form" method="post" action="<?php echo $this->get_action_link(); ?>">
	
					<input type="hidden" name="<?php echo $Plvc->Plugin['form']['field']; ?>" value="Y">
					<?php wp_nonce_field( $Plvc->Plugin['nonces']['value'] , $Plvc->Plugin['nonces']['field'] ); ?>
					<input type="hidden" name="record_field" value="<?php echo $Plvc->Plugin['record'][$this->menu_type]; ?>" />
					<input type="hidden" name="menu_type" value="<?php echo $this->menu_type; ?>" />
					<input type="hidden" name="reset" value="1" />
					<?php do_action( 'plvc_form_items' ); ?>
					<p class="description"><?php _e( 'Reset all settings?' , $Plvc->Plugin['ltd'] ); ?></p>
					<?php submit_button( __( 'Reset settings' , $Plvc->Plugin['ltd'] ) , 'delete' ); ?>
		
				</form>
				
			<?php else: ?>
			
				<?php _e( 'No items.' ); ?>
			
			<?php endif; ?>
			
		</div>

		<div class="clear"></div>

	</div>

</div>
