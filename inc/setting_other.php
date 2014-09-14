<?php

global $wp_version;
global $Plvc;

$Data = $Plvc->ClassData->get_data_others();
$all_user_roles = $Plvc->ClassConfig->get_all_user_roles();
$capabilities = $all_user_roles['administrator']['capabilities'];
ksort( $capabilities );

$list_table_cell_auto = $Plvc->ClassConfig->get_list_table_cell_auto();
$default_thumbnail_size = $Plvc->ClassConfig->get_default_thumbnail_size();
?>
<div class="wrap">
	<div class="icon32" id="icon-tools"></div>
	<h2><?php echo $this->page_title; ?></h2>

	<?php $class = $Plvc->ClassInfo->get_width_class(); ?>
	<div class="metabox-holder columns-2 <?php echo $class; ?>">

		<div id="postbox-container-1" class="postbox-container">

			<?php include_once $Plvc->Plugin['dir'] . 'inc/information.php'; ?>
		
		</div>

		<div id="postbox-container-2" class="postbox-container">

			<form id="<?php echo $Plvc->Plugin['ltd']; ?>_other_form" class="<?php echo $Plvc->Plugin['ltd']; ?>_form" method="post" action="<?php echo $this->get_action_link(); ?>">
				<input type="hidden" name="<?php echo $Plvc->Plugin['form']['field']; ?>" value="Y">
				<?php wp_nonce_field( $Plvc->Plugin['nonces']['value'] , $Plvc->Plugin['nonces']['field'] ); ?>
				<input type="hidden" name="record_field" value="<?php echo $Plvc->Plugin['record']['other']; ?>" />

				<h3><?php _e( 'Other Settings' , $Plvc->Plugin['ltd'] ); ?></h3>

				<table class="form-table">
					<tbody>
						<tr>
							<th>
								<label for="capability"><?php _e( 'Plugin' ); ?><?php _e( 'Settings' ); ?><?php _e( 'Capabilities' ); ?></label>
							</th>
							<td>
								<p><?php printf( __( 'Please choose the minimum role that can modify %s settings.' , $Plvc->Plugin['ltd'] ) , $Plvc->Plugin['name'] ); ?></p>
								<select name="data[other][capability]" id="capability">
									<?php $selected_cap = $this->get_manager_user_role(); ?>
									<?php if( !empty( $Data['capability'] ) ) $selected_cap = strip_tags( $Data['capability'] ); ?>
									<?php foreach( $capabilities as $capability => $v ): ?>
										<option value="<?php echo $capability; ?>" <?php selected( $selected_cap , $capability ); ?>><?php echo $capability; ?></option>
									<?php endforeach; ?>
								</select>
							</td>
						</tr>
						<tr>
							<th>
								<label for="cell_auto"><?php _e( 'Automatic width conversion of List table' , $Plvc->Plugin['ltd'] ); ?></label>
							</th>
							<td>
								<p><?php _e( 'This means is include the CSS for auto width settings on List page.' , $Plvc->Plugin['ltd'] ); ?></p>
								<p>
									<?php _e( 'Width setting of cell' , $Plvc->Plugin['ltd'] ); ?>: 
									<select name="data[other][cell_auto]" id="cell_auto">
										<?php $selected_cell_auto = 0; ?>
										<?php if( !empty( $Data['cell_auto'] ) ) $selected_cell_auto = intval( $Data['cell_auto'] ); ?>
										<?php foreach( $list_table_cell_auto as $val => $label ): ?>
											<option value="<?php echo $val; ?>" <?php selected( $selected_cell_auto , $val ); ?>>[<?php echo  $val; ?>] <?php echo $label; ?></option>
										<?php endforeach; ?>
									</select>
									<a href="javascript:void(0);" class="button button-secondary help_width_auto_cell_description"><?php _e( 'Help' ); ?></a>
									<a href="<?php echo $Plvc->Plugin['dir_admin_assets']; ?>list-table.css" target="_blank"><?php _e( 'Automatic width setting CSS file' , $Plvc->Plugin['ltd'] ); ?></a>
								</p>
								<div class="width_auto_cell_description">

									<h4>[0] <?php echo $list_table_cell_auto[0]; ?></h4>
									<?php if( version_compare( $wp_version , '3.8' , '<' ) ) : ?>
										<a href="<?php echo $Plvc->Plugin['url']; ?>images/cell_width_auto_image-37.png" target="_blank"><img src="<?php echo $Plvc->Plugin['url']; ?>images/cell_width_auto_image-37.png" /></a>
									<?php else: ?>
										<a href="<?php echo $Plvc->Plugin['url']; ?>images/cell_width_auto_image.png" target="_blank"><img src="<?php echo $Plvc->Plugin['url']; ?>images/cell_width_auto_image.png" /></a>
									<?php endif; ?>

									<p>&nbsp;</p>

									<h4>[1] <?php echo $list_table_cell_auto[1]; ?> (<?php _e( 'Default' ); ?>)</h4>
									<?php if( version_compare( $wp_version , '3.8' , '<' ) ) : ?>
										<a href="<?php echo $Plvc->Plugin['url']; ?>images/cell_width_not_auto_image-37.png" target="_blank"><img src="<?php echo $Plvc->Plugin['url']; ?>images/cell_width_not_auto_image-37.png" /></a>
									<?php else: ?>
										<a href="<?php echo $Plvc->Plugin['url']; ?>images/cell_width_not_auto_image.png" target="_blank"><img src="<?php echo $Plvc->Plugin['url']; ?>images/cell_width_not_auto_image.png" /></a>
									<?php endif; ?>

								</div>
							</td>
						</tr>
						<tr>
							<th>
								<label for="thumbnail_width"><?php _e( 'Thumbnail size' ); ?></label>
							</th>
							<td>
								<p><?php _e( 'Change the Thumbnail size for Post, Page, and Custom Post type.' , $Plvc->Plugin['ltd'] ); ?></p>
								<p>
									<?php $val = ''; if( !empty( $Data['thumbnail']['width'] ) ) : $val = intval( $Data['thumbnail']['width'] ); endif; ?>
									<input type="number" step="1" min="0" name="data[other][thumbnail][width]" id="thumbnail_width" class="small-text" value="<?php echo $val; ?>" placeholder="<?php echo $default_thumbnail_size; ?>" />px<br />
									<span class="description"><?php _e( 'Height will be displayed in proportion to the width.' , $Plvc->Plugin['ltd'] ); ?></span>
								</p>
							</td>
						</tr>
					</tbody>
				</table>

				<?php submit_button( __( 'Save' ) ); ?>
	
			</form>

			<p>&nbsp;</p>

			<form id="<?php echo $Plvc->Plugin['ltd']; ?>_other_reset_form" class="<?php echo $Plvc->Plugin['ltd']; ?>_form" method="post" action="<?php echo $this->get_action_link(); ?>">
				<input type="hidden" name="<?php echo $Plvc->Plugin['form']['field']; ?>" value="Y">
				<?php wp_nonce_field( $Plvc->Plugin['nonces']['value'] , $Plvc->Plugin['nonces']['field'] ); ?>
				<input type="hidden" name="record_field" value="<?php echo $Plvc->Plugin['record']['other']; ?>" />
				<input type="hidden" name="reset" value="1" />
				<p class="description"><?php _e( 'Reset all settings?' , $Plvc->Plugin['ltd'] ); ?></p>
				<?php submit_button( __( 'Reset settings' , $Plvc->Plugin['ltd'] ) , 'delete' ); ?>
	
			</form>

		</div>

		<div class="clear"></div>

	</div>

</div>
<script>
jQuery(document).ready(function($) {

	$('#plvc_other_form .help_width_auto_cell_description').on('click', function( ev ) {
		$('#plvc_other_form .width_auto_cell_description').slideDown();
	});

});
</script>