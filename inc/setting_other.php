<?php

global $wp_version;

// get data
$Data = $this->get_data( $this->SetPage );

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

	<?php $class = $this->ltd; ?>
	<?php if( get_option( $this->Record["donate_width"] ) ) $class .= ' full-width'; ?>
	<div class="metabox-holder columns-2 <?php echo $class; ?>">

		<div id="postbox-container-1" class="postbox-container">

			<form id="post_lists_view_custom_form" method="post" action="<?php echo remove_query_arg( $this->MsgQ ); ?>">
				<input type="hidden" name="<?php echo $this->UPFN; ?>" value="Y">
				<?php wp_nonce_field( $this->Nonces["value"] , $this->Nonces["field"] ); ?>
				<input type="hidden" name="record_field" value="<?php echo $this->SetPage; ?>" />
				<input type="hidden" name="SetPage" value="<?php echo $this->SetPage; ?>">


				<h3><?php _e( 'Automatic width conversion of List table' , $this->ltd ); ?></h3>
				<table class="form-table">
					<tbody>
						<tr>
							<th>
								<?php _e( 'Width setting of cell' , $this->ltd ); ?>
							</th>
							<td>
								<?php $arr = array( __( 'Automatic' , $this->ltd ) , __( 'Not automatic' , $this->ltd ) ); ?>
								<select name="data[cell_auto]">
									<?php $selected = 0; if( !empty( $Data['cell_auto'] ) ) $selected = 1; ?>
									<?php foreach( $arr as $key => $label ) : ?>
										<option value="<?php echo $key; ?>" <?php selected( $key , $selected ); ?>><?php echo $label; ?></option>
									<?php endforeach; ?>
								</select>
								<p class="description"><?php _e( 'This means is include the CSS for auto width settings on List page.' , $this->ltd ); ?></p>
								<p><a href="<?php echo $this->Url; ?><?php echo $this->PluginSlug; ?>-table.css" target="_blank"><?php _e( 'Automatic width setting CSS file' , $this->ltd ); ?></a></p>
							</td>
						</tr>
					</tbody>
				</table>
				<p>&nbsp;</p>
				
				<h3><?php _e( 'Change the Thumbnail size for Post, Page, and Custom Post type.' , $this->ltd ); ?></h3>
				<table class="form-table">
					<tbody>
						<tr>
							<th>
								<?php _e( 'Thumbnail size' ); ?>
							</th>
							<td>
								<label for="width"><?php _e( 'Width' ); ?></label>
								<?php $val = ''; if( !empty( $Data['thumbnail']['width'] ) ) : $val = intval( $Data['thumbnail']['width'] ); endif; ?>
								<input type="number" step="1" min="0" name="data[thumbnail][width]" id="width" class="small-text" value="<?php echo $val; ?>" placeholder="<?php echo $this->ThumbnailSize; ?>" />px
								<p class="description"><?php _e( 'Height will be displayed in proportion to the width.' , $this->ltd ); ?></p>
							</td>
						</tr>
					</tbody>
				</table>
		
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
