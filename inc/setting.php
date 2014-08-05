<?php

global $Plvc;

$all_user_roles = $Plvc->ClassConfig->get_all_user_roles();
$Data = $Plvc->ClassData->get_data_user_role();
?>
<div class="wrap">
	<div class="icon32" id="icon-tools"></div>
	<h2><?php echo $this->page_title; ?></h2>

	<p><?php _e( 'Apply the settings of Post Lists View Custom only user roles selected.' , $Plvc->Plugin['ltd'] ); ?></p>
	<p><?php _e ( 'Please select the user roles that all settings will apply to.' , $Plvc->Plugin['ltd'] ); ?></p>
	<p><strong><span style="color: orange;">new</span> <a href="<?php echo $Plvc->ClassInfo->author_url( array( 'add-on' => 1 , 'tp' => 'use_plugin' , 'lc' => 'header' ) ); ?>" target="_blank"><?php echo $Plvc->Plugin['name']; ?> for Multiple setups Add-on</a></strong></p>

	<?php $class = $Plvc->ClassInfo->get_width_class(); ?>
	<div class="metabox-holder columns-2 <?php echo $class; ?>">

		<div id="postbox-container-1" class="postbox-container">

			<?php include_once $Plvc->Plugin['dir'] . 'inc/information.php'; ?>
		
		</div>

		<div id="postbox-container-2" class="postbox-container">

			<form id="<?php echo $Plvc->Plugin['ltd']; ?>_user_role_form" class="<?php echo $Plvc->Plugin['ltd']; ?>_form" method="post" action="<?php echo $this->get_action_link(); ?>">
				<input type="hidden" name="<?php echo $Plvc->Plugin['ltd']; ?>_settings" value="Y">
				<?php wp_nonce_field( $Plvc->Plugin['nonces']['value'] , $Plvc->Plugin['nonces']['field'] ); ?>
				<input type="hidden" name="record_field" value="<?php echo $Plvc->Plugin['record']['user_role']; ?>" />

				<div class="postbox">
					<h3 class="hndle"><span><?php _e( 'User Roles' ); ?></span></h3>
					<div class="inside">
						<?php foreach( $all_user_roles as $role_name => $user_role ) : ?>
							<?php $val = false; ?>
							<?php if( !empty( $Data[$role_name] ) ) $val = 1; ?>
							<p>
								<label>
									<input type="checkbox" name="data[user_role][<?php echo $role_name; ?>]" value="1" <?php checked( $val , 1 ); ?> />
									<?php echo $user_role['label']; ?>
								</label>
							</p>
						<?php endforeach; ?>
					</div>
				</div>

				<?php submit_button( __( 'Save' ) ); ?>

			</form>

			<form id="<?php echo $Plvc->Plugin['ltd']; ?>_user_role_reset_form" class="<?php echo $Plvc->Plugin['ltd']; ?>_form" method="post" action="<?php echo $this->get_action_link(); ?>">
				<input type="hidden" name="<?php echo $Plvc->Plugin['ltd']; ?>_settings" value="Y">
				<?php wp_nonce_field( $Plvc->Plugin['nonces']['value'] , $Plvc->Plugin['nonces']['field'] ); ?>
				<input type="hidden" name="record_field" value="<?php echo $Plvc->Plugin['record']['user_role']; ?>" />
				<input type="hidden" name="reset" value="1" />
				<p class="description"><?php _e( 'Reset all settings?' , $Plvc->Plugin['ltd'] ); ?></p>
				<?php submit_button( __( 'Reset settings' , $Plvc->Plugin['ltd'] ) , 'delete' ); ?>
	
			</form>
			
			<h3>Translate Help</h3>
			<p>
				<?php echo _e( 'Would you like to translate?' , $Plvc->Plugin['ltd'] ); ?>
				<a href="<?php echo $Plvc->ClassInfo->author_url( array( 'translate' => 1 , 'tp' => 'use_plugin' , 'lc' => 'footer' ) ); ?>" target="_blank">Translation</a>
			</p>
			<p><strong><?php _e( 'Bug reports and suggestions' , $Plvc->Plugin['ltd'] ); ?></strong></p>
			<p><?php echo sprintf( __( 'Do you have a proposal you want to improve? Please contact to %s if it is necessary.' , $Plvc->Plugin['ltd'] ) , '<a href="' . $Plvc->ClassInfo->links['forum'] . '" target="_blank">' . __( 'Support Forums' ) . '</a>' ); ?></p>

		</div>

		<div class="clear"></div>

	</div>

</div>
