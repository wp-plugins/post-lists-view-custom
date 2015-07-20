<?php

global $Plvc;

$columns = $this->get_data_lists( $this->list_name );
$load_list = $this->load_list();

?>
<div class="wrap <?php echo $Plvc->Plugin['ltd']; ?>">

	<h2><?php echo $this->page_title; ?></h2>
	
	<p>&nbsp;</p>
	
	<h3 id="plvc-apply-user-roles" class="nav-tab-wrapper"><?php echo $this->get_apply_roles_html(); ?></h3>

	<div class="metabox-holder columns-1">
	
		<div id="postbox-container" class="postbox-container">

			<?php if( !empty( $columns ) ) : ?>
				
				<?php do_action( 'plvc_form_before' ); ?>

				<p><?php _e( 'Please lists view customize to sort by Drag & Drop.' , $Plvc->Plugin['ltd'] ); ?></p>
				<p>&nbsp;</p>
				
				<div class="possible_lists">
					<input type="hidden" name="list_name" value="<?php echo $this->list_name; ?>" />
					<input type="hidden" name="list_type" value="<?php echo $this->list_type; ?>" />
					<select name="add_column" id="add_column">
						<option value=""><?php _e( 'Add new Column' , $Plvc->Plugin['ltd'] ); ?></option>
						
						<?php if( in_array( $this->list_type , array( 'media' , 'comments' , 'users' ) ) ) : ?>
							<?php $select_arr = array( '' => __( 'Default' ) , 'plugin' => __( 'Plugin' ) . '/' . __( 'Current Theme' ) ); ?>
						<?php else: ?>
							<?php $select_arr = array( '' => __( 'Default' ) , 'plugin' => __( 'Plugin' ) . '/' . __( 'Current Theme' ) , 'custom_field' => __( 'Custom Fields' ) ); ?>
						<?php endif; ?>
						
						<?php foreach( $select_arr as $group => $group_label ) : ?>
							<optgroup label="<?php echo $group_label; ?>">
								<?php foreach( $columns as $use_type => $column ) : ?>
									<?php foreach( $column as $column_id => $column_setting ) : ?>
										<?php $disabled = disabled( $use_type , 'use' , false ); ?>
										<?php if( $column_setting['group'] == $group ) : ?>
											<option value="<?php echo $column_id ?>" <?php echo $disabled; ?>><?php echo strip_tags( $column_setting['default_name'] ); ?> [<?php echo $column_id; ?>]</option>
										<?php endif; ?>
									<?php endforeach; ?>
								<?php endforeach; ?>
							</optgroup>
						<?php endforeach; ?>
					</select>
					<input type="button" id="add_new_btn" class="button button-primary" value="<?php _e( 'Add New' , $Plvc->Plugin['ltd'] ); ?>" />
					<span class="spinner"></span>
				</div>
	
				<form id="<?php echo $Plvc->Plugin['ltd']; ?>_<?php echo $this->list_name; ?>_form" class="<?php echo $Plvc->Plugin['ltd']; ?>_form" method="post" action="<?php echo $this->get_action_link(); ?>">
	
					<input type="hidden" name="<?php echo $Plvc->Plugin['form']['field']; ?>" value="Y">
					<?php wp_nonce_field( $Plvc->Plugin['nonces']['value'] , $Plvc->Plugin['nonces']['field'] ); ?>
					<input type="hidden" name="record_field" value="<?php echo $Plvc->Plugin['record'][$this->list_type]; ?>" />
					<input type="hidden" name="list_name" value="<?php echo $this->list_name; ?>" />
					<input type="hidden" name="list_type" value="<?php echo $this->list_type; ?>" />
					<?php do_action( 'plvc_form_items' ); ?>
	
					<table class="wp-list-table widefat fixed <?php echo $this->list_type; ?>" cellspacing="0">
						<thead>
							<?php if( !empty( $columns['use'] ) ) : ?>
								<tr class="widgets-sortables">
									<?php foreach( $columns['use'] as $column_id => $column_setting ) : ?>
										<?php $Plvc->setting_list_post( $this->list_name , $this->list_type , 'use' , $column_id , $column_setting ); ?>
									<?php endforeach; ?>
								</tr>
							<?php endif; ?>
						</thead>
					</table>
					
					<p>&nbsp;</p>
	
					<?php submit_button(); ?>
	
				</form>
	
				<p>&nbsp;</p>
				<p>&nbsp;</p>

				<form id="<?php echo $Plvc->Plugin['ltd']; ?>_<?php echo $this->list_name; ?>_reset_form" class="<?php echo $Plvc->Plugin['ltd']; ?>_form" method="post" action="<?php echo $this->get_action_link(); ?>">
	
					<input type="hidden" name="<?php echo $Plvc->Plugin['form']['field']; ?>" value="Y">
					<?php wp_nonce_field( $Plvc->Plugin['nonces']['value'] , $Plvc->Plugin['nonces']['field'] ); ?>
					<input type="hidden" name="record_field" value="<?php echo $Plvc->Plugin['record'][$this->list_type]; ?>" />
					<input type="hidden" name="list_name" value="<?php echo $this->list_name; ?>" />
					<input type="hidden" name="list_type" value="<?php echo $this->list_type; ?>" />
					<input type="hidden" name="reset" value="1" />
					<?php do_action( 'plvc_form_items' ); ?>
					<p class="description"><?php _e( 'Reset all settings?' , $Plvc->Plugin['ltd'] ); ?></p>
					<?php submit_button( __( 'Reset settings' , $Plvc->Plugin['ltd'] ) , 'delete' ); ?>
		
				</form>
	
			<?php else: ?>

				<p><?php echo sprintf( __( 'Could not read the columns. Please load the %s.', $Plvc->Plugin['ltd'] ) , $load_list['label'] ); ?></p>
				
			<?php endif; ?>
			
			<p>
				<a href="<?php echo $load_list['link']; ?>" id="column_load" class="button button-primary">
					<?php echo sprintf( __( 'Load the %s', $Plvc->Plugin['ltd'] ) , $load_list['label'] ); ?>
				</a>
			</p>
			<p class="loading">
				<span class="spinner"></span>
				<?php _e( 'Loading&hellip;' ); ?>
			</p>

		</div>

		<div class="clear"></div>

	</div>

</div>

<style>
.possible_lists {
	margin-bottom: 40px;
}
.possible_lists.adding .spinner {
	display: inline-block;
    visibility: visible;
}
.possible_lists .spinner {
    float: none;
}
</style>
<script>
jQuery(document).ready(function($) {
	
		$('.plvc #column_load').on('click', function( ev ) {

		$(this).parent().parent().find('.loading').addClass('active');

		var load_url = $(this).prop('href');
				
		$.ajax({
			url: load_url
		}).done(function( data ) {
			location.reload();
		});
		
		return false;

	}).disableSelection();

	$('.plvc #add_new_btn').on('click', function( ev ) {
		var $possible = $(ev.target).parent();
		var $select = $possible.find('select#add_column option:selected');
		
		if( $select.val() != '' && !$select.prop('disabled') ) {

			$possible.addClass('adding');
		
			var column_id = $possible.find('select#add_column option:selected').val();
			var list_name = $possible.find('input[name=list_name]').val();
			var list_type = $possible.find('input[name=list_type]').val();

			var PostData = { action: 'plvc_add_list' , plvc_field: plvc.plvc_field , column_id: column_id , list_name: list_name , list_type: list_type }

			$.ajax({
				url: ajaxurl,
				type : 'POST',
				data: PostData
			}).done(function( data ) {
				if( data ) {
					$(data).prependTo( $(document).find('.plvc table.wp-list-table thead tr' ) ).hide().fadeIn( 500 );
					$select.prop('disabled', true);
					$possible.find('select#add_column option[value=""]').prop('selected', true);
				}
				$possible.removeClass('adding');
			});
				
		}
			
	});

	$(document).on('click', '.plvc table.wp-list-table thead tr th .show-field .column-toggle', function( ev ) {
		var $Cell = $(ev.target).parent().parent();
		if( $Cell.hasClass('collapse') ) {
			$Cell.parent().find('th').removeClass('collapse');
		} else {
			$Cell.parent().find('th').removeClass('collapse');
			$Cell.addClass('collapse');
			$Cell.find('.edit-field input.input-column-name').focus();
		}
	});

	$(document).on('focusout', '.plvc table.wp-list-table thead tr th .edit-field .input-column-name', function( ev ) {
		var column_name = $(ev.target).val();
		$(ev.target).parent().parent().find('.show-field .column-title').html( column_name );
	});
		
	$('.plvc .wp-list-table thead tr').sortable({
		placeholder: 'widget-placeholder',
		cancel: '.input-column-name, .column-toggle, .sort_label'
	});

	$(document).on('click', '.plvc table.wp-list-table thead tr th .edit-field .remove-action a', function( ev ) {
		var $Cell = $(ev.target).parent().parent().parent();
		var column_id = $(ev.target).prop('title').replace('column-id-', '');
		$Cell.html('');
		$Cell.hide( 'normal', function() {
			$(this).remove();
		});
		$('.plvc .possible_lists select option[value=' + column_id + ']').prop('disabled', false);
	});

});
</script>