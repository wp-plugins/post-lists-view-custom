<?php

global $Plvc;

$all_user_roles = $Plvc->ClassConfig->get_all_user_roles();
$Data = $Plvc->ClassData->get_data_user_role();

?>
<div class="wrap <?php echo $Plvc->Plugin['ltd']; ?>">

	<h2><?php echo $this->page_title; ?></h2>
	
	<div class="metabox-holder columns-1">
	
		<div id="postbox-container" class="postbox-container">
		
			<div id="user-role-settings">

				<p><?php _e( 'Apply the settings of Post Lists View Custom only user roles selected.' , $Plvc->Plugin['ltd'] ); ?></p>
				<p><?php _e ( 'Please select the user roles that all settings will apply to.' , $Plvc->Plugin['ltd'] ); ?></p>
				<p><strong><a href="<?php echo $Plvc->ClassInfo->author_url( array( 'add-on' => 1 , 'tp' => 'use_plugin' , 'lc' => 'header' ) ); ?>" target="_blank"><?php echo $Plvc->Plugin['name']; ?> for Multiple setups Add-on</a></strong></p>
	
				<form id="<?php echo $Plvc->Plugin['ltd']; ?>_user_role_form" class="<?php echo $Plvc->Plugin['ltd']; ?>_form" method="post" action="<?php echo $this->get_action_link(); ?>">
					<input type="hidden" name="<?php echo $Plvc->Plugin['form']['field']; ?>" value="Y">
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
					<input type="hidden" name="<?php echo $Plvc->Plugin['form']['field']; ?>" value="Y">
					<?php wp_nonce_field( $Plvc->Plugin['nonces']['value'] , $Plvc->Plugin['nonces']['field'] ); ?>
					<input type="hidden" name="record_field" value="<?php echo $Plvc->Plugin['record']['user_role']; ?>" />
					<input type="hidden" name="reset" value="1" />
					<p class="description"><?php _e( 'Reset all settings?' , $Plvc->Plugin['ltd'] ); ?></p>
					<?php submit_button( __( 'Reset settings' , $Plvc->Plugin['ltd'] ) , 'delete' ); ?>
		
				</form>

			</div>

		</div>
		
		<div class="clear"></div>
	
	</div>
	
</div>

<?php include_once $Plvc->Plugin['dir'] . 'inc/information.php'; ?>
