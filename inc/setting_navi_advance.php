<?php

$PageTitle = __( 'Menu Advance View Customize' , 'plvc' );


if( !empty( $_POST["reset"] ) ) {
	$this->update_reset();
} elseif( !empty( $_POST["update"] ) ) {
	$this->update();
}

// include js css
$ReadedJs = array( 'jquery' , 'jquery-ui-sortable' );
wp_enqueue_script( $this->Slug ,  $this->Dir . dirname( dirname( plugin_basename( __FILE__ ) ) ) . '.js', $ReadedJs , $this->Ver );
wp_enqueue_style( $this->Slug , $this->Dir . dirname( dirname( plugin_basename( __FILE__ ) ) ) . '.css', array() , $this->Ver );

// get data
$Data = $this->get_data_navi_advance($this->SetPage);

?>

<div class="wrap">
	<div class="icon32" id="icon-options-general"></div>
	<h2><?php echo $PageTitle; ?></h2>
	<?php echo $this->Msg; ?>
	<p><?php _e( 'Please rearrange the order in which you want to view by drag and drop.' , 'plvc' ); ?></p>
	<p>&nbsp;</p>

	<form id="post_lists_view_custom_form" method="post" action="">
		<input type="hidden" name="<?php echo $this->UPFN; ?>" value="Y">
		<?php wp_nonce_field(); ?>

		<input type="hidden" name="SetPage" value="<?php echo $this->SetPage; ?>">
		<table cellspacing="0" class="widefat fixed">
			<thead>
				<tr>
					<th><?php _e( 'Show' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>
						<div id="use" class="widget-list">
							<?php if(!empty($Data)): ?>
								<?php echo $this->get_lists( 'use' , $Data ); ?>
							<?php endif; ?>
						</div>
						<div class="clear"></div>
					</td>
				</tr>
			</tbody>
		</table>

		<p>&nbsp;</p>

		<table cellspacing="0" class="widefat fixed">
			<thead>
				<tr>
					<th><?php _e('Hide'); ?></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>
						<div id="not_use" class="widget-list">
							<?php if(!empty($Data)): ?>
								<?php echo $this->get_lists( 'not_use' , $Data ); ?>
							<?php endif; ?>
						</div>
						<div class="clear"></div>
					</td>
				</tr>
			</tbody>
		</table>

		<p class="submit">
			<input type="submit" class="button-primary" name="update" value="<?php _e( 'Save' ); ?>" />
		</p>
		<p class="submit reset">
			<span class="description"><?php _e( 'Would initialize?' , 'plvc' ); ?></span>
			<input type="submit" class="button-secondary" name="reset" value="<?php _e( 'Reset' ); ?>" />
		</p>

	</form>
</div>
