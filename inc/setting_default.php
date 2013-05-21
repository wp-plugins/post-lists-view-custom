<?php


if( !empty( $_POST["donate_key"] ) ) {
	$this->DonatingCheck();
} elseif( !empty( $_POST["reset"] ) ) {
	$this->update_reset( "user_role" );
} elseif( !empty( $_POST["update"] ) ) {
	$this->update_userrole();
}


$Data = $this->get_data( 'user_role' );
$UserRoles = $this->get_user_role();

// include js css
$ReadedJs = array( 'jquery' , 'jquery-ui-sortable' );
wp_enqueue_script( $this->PageSlug ,  $this->Dir . dirname( dirname( plugin_basename( __FILE__ ) ) ) . '.js', $ReadedJs , $this->Ver );
wp_enqueue_style( $this->PageSlug , $this->Dir . dirname( dirname( plugin_basename( __FILE__ ) ) ) . '.css', array() , $this->Ver );

?>

<div class="wrap">
	<div class="icon32" id="icon-tools"></div>
	<h2><?php echo $this->Name; ?></h2>
	<?php echo $this->Msg; ?>
	<p><?php _e( 'Apply the settings of Post Lists View Custom only user roles selected.' , $this->ltd ); ?>
	<p><?php _e ( 'Please select the user roles you want to apply the settings.' , $this->ltd ); ?></p>

	<p><strong><span style="color: orange;">new</span> <a href="http://gqevu6bsiz.chicappa.jp/post-lists-view-custom-for-multiple-setups-add-on/?utm_source=use_plugin&utm_medium=head&utm_content=<?php echo $this->ltd; ?>&utm_campaign=<?php echo str_replace( '.' , '_' , $this->Ver ); ?>" target="_blank">Post Lists View Custom for Multiple setups Add-on</a></strong></p>

	<?php $class = $this->ltd; ?>
	<?php if( get_option( $this->Record["donate_width"] ) ) $class .= ' full-width'; ?>
	<div class="metabox-holder columns-2 <?php echo $class; ?>">

		<div id="postbox-container-1" class="postbox-container">

			<form id="plvc_setting_default" class="plvc_form" method="post" action="">
				<input type="hidden" name="<?php echo $this->UPFN; ?>" value="Y" />
				<?php wp_nonce_field(); ?>

				<div class="postbox">
					<h3 class="hndle"><span><?php _e( 'User Roles' ); ?></span></h3>
					<div class="inside">
						<?php $field = 'user_role'; ?>
						<?php foreach($UserRoles as $key => $val) : ?>
							<?php $Checked = ''; ?>
							<?php if( !empty( $Data[$key] ) ) : $Checked = 'checked="checked"'; endif; ?>
							<p>
								<label>
									<input type="checkbox" name="data[<?php echo $field; ?>][<?php echo $key; ?>]" value="1" <?php echo $Checked; ?> />
									<?php echo $val; ?>
								</label>
							</p>
						<?php endforeach; ?>
					</div>
				</div>

				<p class="submit">
					<input type="submit" class="button-primary" name="update" value="<?php _e( 'Save' ); ?>" />
				</p>
		
				<p class="submit reset">
					<span class="description"><?php _e( 'Would initialize?' , $this->ltd ); ?></span>
					<input type="submit" class="button-secondary" name="reset" value="<?php _e('Reset'); ?>" />
				</p>

				<p>&nbsp;</p>
				
			</form>

			<p><?php echo sprintf( __( 'Do you have a proposal you want to improve? Please contact to %s if it is necessary.' , $this->ltd_p ) , '<a href="http://wordpress.org/support/plugin/post-lists-view-custom" target="_blank">' . __( 'Support Forums' ) . '</a>' ); ?></p>

		</div>

		<div id="postbox-container-2" class="postbox-container">

			<?php include_once 'donation.php'; ?>

		</div>

	</div>

</div>
