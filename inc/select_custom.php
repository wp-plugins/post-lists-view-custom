<?php

$args = array( 'public' => true, '_builtin' => false );
$PostType = get_post_types( $args );

// include js css
$ReadedJs = array( 'jquery' , 'jquery-ui-sortable' );
wp_enqueue_script( $this->Slug ,  $this->Dir . dirname( dirname( plugin_basename( __FILE__ ) ) ) . '.js', $ReadedJs , $this->Ver );
wp_enqueue_style( $this->Slug , $this->Dir . dirname( dirname( plugin_basename( __FILE__ ) ) ) . '.css', array() , $this->Ver );

?>

<div class="wrap">
	<div class="icon32" id="icon-options-general"></div>
	<h2><?php _e( 'Custom Post Type Lists View Customize' , $this->ltd ); ?></h2>
	<?php echo $this->Msg; ?>

	<div class="metabox-holder columns-2 plvc">

		<div id="postbox-container-1" class="postbox-container">

			<?php if( !empty( $PostType ) && is_array( $PostType ) ) : ?>
		
				<p><?php _e( 'Please choose a custom post type you want to change the list\'s view.' , $this->ltd ); ?></p>
				<p>&nbsp;</p>
		
				<form id="post_lists_view_custom_selectform" method="post" action="admin.php?page=custom_post<?php echo $this->RecordBaseName; ?>">
					<?php wp_nonce_field(); ?>
		
					<select name="CustomSelect" id="CustomSelect">
						<?php foreach($PostType as $val) : ?>
							<?php $val_name = $val; ?>
							<?php $PostTypeObject = get_post_type_object($val); ?>
							<?php if( !empty( $PostTypeObject->labels->name ) ) : ?>
								<?php $val_name = '(' . $val . ') ' . $PostTypeObject->labels->name; ?>
							<?php endif; ?>
							<option value="<?php echo $val; ?>"><?php echo $val_name; ?></option>
						<?php endforeach; ?>
					</select>
		
					<input type="submit" class="button-primary" value="<?php _e( 'Lists View Customize' , $this->ltd ); ?>" />
			
					<input type="hidden" name="SetPage" value="<?php echo $this->SetPage; ?>">
		
				</form>
		
			<?php else: ?>
		
				<p><?php _e( 'No custom post type found.' , $this->ltd ); ?>
		
			<?php endif; ?>

		</div>

		<div id="postbox-container-2" class="postbox-container">

			<?php include_once 'donation.php'; ?>

		</div>

		<div class="clear"></div>

	</div>

</div>
