<?php

global $Plvc;

$columns = $this->get_data_lists( $this->list_name );
$custom_posts_types = $Plvc->ClassConfig->get_all_custom_posts();

?>
<div class="wrap">
	<div class="icon32" id="icon-options-general"></div>
	<h2><?php echo $this->page_title; ?></h2>
	
	<h3 id="plvc-apply-user-roles" class="nav-tab-wrapper"><?php echo $this->get_apply_roles_html(); ?></h3>

	<?php $class = $Plvc->ClassInfo->get_width_class(); ?>
	<div class="metabox-holder columns-2 <?php echo $class; ?>">

		<div id="postbox-container-1" class="postbox-container">

			<?php include_once $Plvc->Plugin['dir'] . 'inc/information.php'; ?>
		
		</div>

		<div id="postbox-container-2" class="postbox-container">

			<?php if( !empty( $columns ) ) : ?>
				
				<?php do_action( 'plvc_form_before' ); ?>

				<p><?php _e( 'Please lists view customize to sort by Drag & Drop.' , $Plvc->Plugin['ltd'] ); ?></p>
				<p>&nbsp;</p>
				
				<div class="possible_lists">
					<input type="hidden" name="list_name" value="<?php echo $this->list_name; ?>" />
					<input type="hidden" name="list_type" value="<?php echo $this->list_type; ?>" />
					<select name="add_column" id="add_column">
						<option value=""><?php _e( 'Add new Column' , $Plvc->Plugin['ltd'] ); ?></option>
						<optgroup label="<?php _e( 'Default' ); ?>">
							<?php foreach( $columns as $use_type => $column ) : ?>
								<?php foreach( $column as $column_id => $column_setting ) : ?>
									<?php $disabled = ''; if( $use_type == 'use' ) $disabled = 'disabled'; ?>
									<?php if( empty( $column_setting['group'] ) ) : ?>
										<option value="<?php echo $column_id ?>" <?php echo $disabled; ?>>[<?php echo $column_id; ?>] <?php echo strip_tags( $column_setting['default_name'] ); ?></option>
									<?php endif; ?>
								<?php endforeach; ?>
							<?php endforeach; ?>
						</optgroup>
						<optgroup label="<?php _e( 'Plugin' ); ?> / <?php _e( 'Current Theme' ); ?>">
							<?php foreach( $columns as $use_type => $column ) : ?>
								<?php foreach( $column as $column_id => $column_setting ) : ?>
									<?php $disabled = ''; if( $use_type == 'use' ) $disabled = 'disabled'; ?>
									<?php if( !empty( $column_setting['group'] ) && $column_setting['group'] == 'plugin' ) : ?>
										<option value="<?php echo $column_id ?>" <?php echo $disabled; ?>>[<?php echo $column_id; ?>] <?php echo strip_tags( $column_setting['default_name'] ); ?></option>
									<?php endif; ?>
								<?php endforeach; ?>
							<?php endforeach; ?>
						</optgroup>
						<optgroup label="<?php _e( 'Custom Fields' ); ?>">
							<?php foreach( $columns as $use_type => $column ) : ?>
								<?php foreach( $column as $column_id => $column_setting ) : ?>
									<?php $disabled = ''; if( $use_type == 'use' ) $disabled = 'disabled'; ?>
									<?php if( !empty( $column_setting['group'] ) && $column_setting['group'] == 'custom_field' ) : ?>
										<option value="<?php echo $column_id ?>" <?php echo $disabled; ?>>[<?php echo $column_id; ?>] <?php echo strip_tags( $column_setting['default_name'] ); ?></option>
									<?php endif; ?>
								<?php endforeach; ?>
							<?php endforeach; ?>
						</optgroup>
					</select>
					<input type="button" id="add_new_btn" class="button button-primary" value="<?php _e( 'Add New' , $Plvc->Plugin['ltd'] ); ?>" />
					<p class="spinner" style="float: none;"></p>
				</div>
	
				<form id="<?php echo $Plvc->Plugin['ltd']; ?>_<?php echo $this->list_name; ?>_form" class="<?php echo $Plvc->Plugin['ltd']; ?>_form" method="post" action="<?php echo $this->get_action_link(); ?>">
	
					<input type="hidden" name="<?php echo $Plvc->Plugin['ltd']; ?>_settings" value="Y">
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
	
					<?php submit_button( __( 'Save' ) ); ?>
	
				</form>
	
				<p>&nbsp;</p>
				<p>&nbsp;</p>

				<form id="<?php echo $Plvc->Plugin['ltd']; ?>_<?php echo $this->list_name; ?>_reset_form" class="<?php echo $Plvc->Plugin['ltd']; ?>_form" method="post" action="<?php echo $this->get_action_link(); ?>">
	
					<input type="hidden" name="<?php echo $Plvc->Plugin['ltd']; ?>_settings" value="Y">
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
			
				<?php $edit_link = self_admin_url( 'edit.php' ); ?>
				<?php $edit_link_name = __( 'Posts'); ?>

				<?php if( $this->list_type == 'page' ) : ?>
					<?php $edit_link_name = __( 'Pages' ); ?>
					<?php $edit_link = self_admin_url( 'edit.php?post_type=page' ); ?>
				<?php elseif( $this->list_type == 'media' ) : ?>
					<?php $edit_link_name = __( 'Media Library' ); ?>
					<?php $edit_link = self_admin_url( 'upload.php' ); ?>
				<?php elseif( $this->list_type == 'comments' ) : ?>
					<?php $edit_link_name = __( 'Comments' ); ?>
					<?php $edit_link = self_admin_url( 'edit-comments.php' ); ?>
				<?php elseif( $this->list_type == 'custom_posts' ) : ?>
					<?php $edit_link_name = $custom_posts_types[$this->list_name]['name']; ?>
					<?php $edit_link = self_admin_url( 'edit.php?post_type=' . $this->list_name ); ?>
				<?php endif; ?>

				<p><?php echo sprintf( __( 'Could not read the columns. Please load the %s.', $Plvc->Plugin['ltd'] ) , $edit_link_name ); ?></p>
				<p>
					<a href="<?php echo $edit_link; ?>" id="column_load" class="button button-primary">
						<?php echo sprintf( __( 'Load the %s', $Plvc->Plugin['ltd'] ) , $edit_link_name ); ?>
					</a>
				</p>
				<p class="loading">
					<span class="spinner"></span>
					<?php _e( 'Loading&hellip;' ); ?>
				</p>
				
			<?php endif; ?>
			
		</div>

		<div class="clear"></div>

	</div>

</div>

<script>
jQuery(document).ready(function($) {
	
	if( $('.plvc .wp-list-table').size() ) {
		
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
			placeholder: 'widget-placeholder'
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
		
		$('.plvc #add_new_btn').on('click', function( ev ) {
			var $possible = $(ev.target).parent();
			var $select = $possible.find('select#add_column option:selected');
			
			if( $select.val() != '' && !$select.prop('disabled') ) {

				$possible.addClass('adding');
			
				var column_id = $possible.find('select#add_column option:selected').val();
				var list_name = $possible.find('input[name=list_name]').val();
				var list_type = $possible.find('input[name=list_type]').val();
	
				var PostData = { action: 'plvc_add_list' , column_id: column_id , list_name: list_name , list_type: list_type }
	
				$.ajax({
					url: ajaxurl,
					type : 'POST',
					data: PostData
				}).done(function( data ) {
					if( data ) {
						$(data).prependTo( $(document).find('.plvc table.wp-list-table thead tr' ) ).hide().fadeIn( 500 );
						$select.prop('disabled', true);
						$possible.find('select#add_column option[value=]').prop('selected', true);
					}
					$possible.removeClass('adding');
				});
				
			}
			
		});
		
	} else {
		
		$('.plvc #column_load').on('click', function( ev ) {
			var load_url = $(ev.target).prop('href');
				
			$.ajax({
				url: load_url,
				beforeSend: function( xhr ) {
					$(ev.target).parent().parent().find('.loading').show();
				}
			}).done(function( data ) {
				location.reload();
			});
		
			return false;
		}).disableSelection();
		
	}

});
</script>