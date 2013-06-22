<?php


$PageTitle = '';

if( $this->SetPage == 'post' ) {
	$PageTitle = __( 'All Posts List Customize' , $this->ltd );
} elseif( $this->SetPage == 'page' ) {
	$PageTitle = __( 'All Pages List Customize' , $this->ltd );
} elseif( $this->SetPage == 'media' ) {
	$PageTitle = __( 'Media Library List Customize' , $this->ltd );
} elseif( $this->SetPage == 'comments' ) {
	$PageTitle = __( 'Comments List Customize' , $this->ltd );
} elseif( $this->SetPage == 'widgets' ) {
	$PageTitle = __( 'Available Widgets List Customize' , $this->ltd );
} elseif( $this->SetPage == 'menus' ) {
	$PageTitle = __( 'Menus show screen List Customize' , $this->ltd );
} elseif( $this->SetPage == 'menus_adv' ) {
	$PageTitle = __( 'Menus show advanced properties screen List Customize' , $this->ltd );
} elseif( $this->SetPage == 'custom_posts' ) {
	$PostType = get_post_type_object( strip_tags( $_GET["name"] ) );
	$PageTitle = __( 'Custom Posts Type List Customize' , $this->ltd ) . '( ' . esc_html( $PostType->labels->name ) . ' )';
}



if( !empty( $_POST["SetPage"] ) ) {

	if( strip_tags( $_POST["SetPage"] ) == 'custom_posts' ) {
		
		if( !empty( $_POST["reset"] ) ) {
			$this->update_custom_posts_reset( strip_tags( $_GET["name"] ) );
		} elseif( !empty( $_POST["update"] ) ) {
			$this->update_custom_posts_data( strip_tags( $_GET["name"] ) );
		}

	} else {

		if( !empty( $_POST["reset"] ) ) {
			$this->update_reset( $this->SetPage );
		} elseif( !empty( $_POST["update"] ) ) {
			$this->update_data( $this->SetPage );
		}
	}

}

// include js css
$ReadedJs = array( 'jquery' , 'jquery-ui-sortable' );
wp_enqueue_script( $this->PageSlug ,  $this->Dir . dirname( dirname( plugin_basename( __FILE__ ) ) ) . '.js', $ReadedJs , $this->Ver );
wp_enqueue_style( $this->PageSlug , $this->Dir . dirname( dirname( plugin_basename( __FILE__ ) ) ) . '.css', array() , $this->Ver );

// get data
$Data = $this->get_data_columns( $this->SetPage );

?>

<div class="wrap">
	<div class="icon32" id="icon-options-general"></div>
	<h2><?php echo $PageTitle; ?></h2>
	<?php echo $this->Msg; ?>

	<?php if( $this->SetPage == 'menus' or $this->SetPage == 'menus_adv' or $this->SetPage == 'widgets' ) : ?>
		<p><?php _e( 'Please drag and drop the column to show. <strong>* Order does not affect.</strong>' , $this->ltd ); ?></p>
	<?php else: ?>
		<p><?php _e( 'Please rearrange the order in which you want to view by Drag & Drop.' , $this->ltd ); ?></p>
	<?php endif; ?>

	<h3 id="plvc-apply-user-roles"><?php echo $this->get_apply_roles(); ?></h3>

	<?php $class = $this->ltd; ?>
	<?php if( get_option( $this->Record["donate_width"] ) ) $class .= ' full-width'; ?>
	<div class="metabox-holder columns-2 <?php echo $class; ?>">

		<div id="postbox-container-1" class="postbox-container">

			<form id="post_lists_view_custom_form" method="post" action="">
				<input type="hidden" name="<?php echo $this->UPFN; ?>" value="Y">
				<?php wp_nonce_field(); ?>
		
				<input type="hidden" name="SetPage" value="<?php echo $this->SetPage; ?>" />
				<?php if( !empty( $_GET["name"] ) ) : ?>
					<input type="hidden" name="CustomSelect" value="<?php echo strip_tags( $_GET["name"] ); ?>" />
				<?php endif; ?>

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
									<?php if( !empty( $Data ) ): ?>
										<?php echo $this->get_lists( 'use' , $Data , $this->SetPage ); ?>
									<?php endif; ?>
								</div>
								<div class="clear"></div>
								<img src="<?php echo esc_url( admin_url( 'images/wpspin_light.gif' ) ); ?>" class="ajax-feedback-use" title="" alt="" />
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
									<?php if( !empty( $Data ) ): ?>
										<?php echo $this->get_lists( 'not_use' , $Data , $this->SetPage ); ?>
									<?php endif; ?>
								</div>
								<div class="clear"></div>
								<img src="<?php echo esc_url( admin_url( 'images/wpspin_light.gif' ) ); ?>" class="ajax-feedback-notuse" title="" alt="" />
							</td>
						</tr>
					</tbody>
				</table>
		
				<p class="submit">
					<input type="submit" class="button-primary" name="update" value="<?php _e( 'Save' ); ?>" />
				</p>
				<p class="submit reset">
					<span class="description"><?php _e( 'Reset settings?' , $this->ltd ); ?></span>
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
