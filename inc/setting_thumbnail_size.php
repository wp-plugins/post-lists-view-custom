<?php

if( !empty( $_POST["reset"] ) ) {
	$this->update_reset( $this->SetPage );
} elseif( !empty( $_POST["update"] ) ) {
	$this->update_thunmbnail( $this->SetPage );
}

// include js css
$ReadedJs = array( 'jquery' );
wp_enqueue_script( $this->PageSlug ,  $this->Dir . dirname( dirname( plugin_basename( __FILE__ ) ) ) . '.js', $ReadedJs , $this->Ver );
wp_enqueue_style( $this->PageSlug , $this->Dir . dirname( dirname( plugin_basename( __FILE__ ) ) ) . '.css', array() , $this->Ver );

// get data
$Data = $this->get_data( $this->SetPage );

?>

<div class="wrap">
	<div class="icon32" id="icon-options-general"></div>
	<h2><?php _e( 'Setting Thumbnail size' , $this->ltd ); ?></h2>
	<?php echo $this->Msg; ?>

	<p><?php _e( 'Change the size of thumbnails displayed in the lists.' , $this->ltd ); ?></p>
	<p class="description"><?php _e( 'Post, Page, and Custom Post type the applies.' , $this->ltd ); ?></p>

	<?php $class = $this->ltd; ?>
	<?php if( get_option( $this->Record["donate_width"] ) ) $class .= ' full-width'; ?>
	<div class="metabox-holder columns-2 <?php echo $class; ?>">

		<div id="postbox-container-1" class="postbox-container">

			<form id="post_lists_view_custom_form" method="post" action="">
				<input type="hidden" name="<?php echo $this->UPFN; ?>" value="Y">
				<?php wp_nonce_field(); ?>
		
				<input type="hidden" name="SetPage" value="<?php echo $this->SetPage; ?>">
				<table class="form-table">
					<tbody>
						<tr>
							<th>
								<?php _e( 'Thumbnail size' ); ?>
							</th>
							<td>
								<label for="width"><?php _e( 'Width' ); ?></label>
								<?php $val = ''; if( !empty( $Data["width"] ) ) : $val = intval( $Data["width"] ); endif; ?>
								<input type="number" step="1" min="0" name="width" id="width" class="small-text" value="<?php echo $val; ?>" />px
								<p class="description"><?php echo sprintf( __( 'It will be displayed at <strong>%s px</strong> If there is no setting.' , $this->ltd ) , $this->ThumbnailSize ); ?></p>
								<p class="description"><?php _e( 'Height will be displayed in proportion to the width.' , $this->ltd ); ?></p>
							</td>
						</tr>
					</tbody>
				</table>
		
				<p class="submit">
					<input type="submit" class="button-primary" name="update" value="<?php _e( 'Save' ); ?>" />
				</p>
				<p class="submit reset">
					<span class="description"><?php _e( 'Would initialize?' , $this->ltd ); ?></span>
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
