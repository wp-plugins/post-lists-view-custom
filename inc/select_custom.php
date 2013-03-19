<?php

$args = array( 'public' => true, '_builtin' => false );
$PostType = get_post_types( $args );

?>

<div class="wrap">
	<div class="icon32" id="icon-options-general"></div>
	<h2><?php _e( 'Custom Post Type Lists View Customize' , 'plvc' ); ?></h2>
	<?php echo $this->Msg; ?>
	<p>&nbsp;</p>

	<?php if( !empty( $PostType ) && is_array( $PostType ) ) : ?>

		<p><?php _e( 'Please choose a custom post type you want to change the list\'s view.' , 'plvc' ); ?></p>
		<p>&nbsp;</p>

		<form id="post_lists_view_custom_selectform" method="post" action="admin.php?page=custom_post<?php echo $this->RecordBaseName; ?>">
			<?php wp_nonce_field(); ?>

			<select name="CustomSelect" id="CustomSelect">
				<?php foreach($PostType as $val) : ?>
					<option value="<?php echo $val; ?>"><?php echo $val; ?></option>
				<?php endforeach; ?>
			</select>

			<input type="submit" class="button-primary" value="<?php _e( 'Lists View Customize' , 'plvc' ); ?>" />
	
			<input type="hidden" name="SetPage" value="<?php echo $this->SetPage; ?>">

		</form>

	<?php else: ?>

		<p><?php _e( 'No custom post type found.' , 'plvc' ); ?>

	<?php endif; ?>

</div>

