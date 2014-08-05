<?php if( $Plvc->ClassInfo->is_donated() ) : ?>

	<p class="donated_message description"><?php _e( 'Thank you for your donation.' , $Plvc->Plugin['ltd'] ); ?></p>
	<div class="toggle-width">
		<a href="javascript:void(0);" class="collapse-sidebar button-secondary">
			<span class="collapse-sidebar-arrow"></span>
			<span class="collapse-sidebar-label"><?php echo esc_html__( 'Collapse' ); ?></span>
		</a>
	</div>

<?php else: ?>

	<div class="stuffbox" id="donationbox">
		<h3><span class="hndle"><?php _e( 'Please consider making a donation.' , $Plvc->Plugin['ltd'] ); ?></span></h3>
		<div class="inside">
			<p><?php _e( 'Thank you very much for your support.' , $Plvc->Plugin['ltd'] ); ?></p>
			<p><a href="<?php echo $Plvc->ClassInfo->author_url( array( 'donate' => 1 , 'tp' => 'use_plugin' , 'lc' => 'donate' ) ); ?>" class="button button-primary" target="_blank"><?php _e( 'Donate' , $Plvc->Plugin['ltd'] ); ?></a></p>
			<p><?php _e( 'Please enter the \'Donation delete key\' that have been described in the \'Line Break First and End download page\'.' , $Plvc->Plugin['ltd'] ); ?></p>
			<form id="<?php echo $Plvc->Plugin['ltd']; ?>_donation_form" class="<?php echo $Plvc->Plugin['ltd']; ?>_form" method="post" action="<?php echo $Plvc->ClassManager->get_action_link(); ?>">
				<input type="hidden" name="<?php echo $Plvc->Plugin['ltd']; ?>_settings" value="Y">
				<?php wp_nonce_field( $Plvc->Plugin['nonces']['value'] , $Plvc->Plugin['nonces']['field'] ); ?>
				<input type="hidden" name="record_field" value="donate" />
				<label for="donate_key"><?php _e( 'Donation delete key' , $Plvc->Plugin['ltd'] ); ?></label>
				<input type="text" name="donate_key" id="donate_key" value="" class="large-text" />
				<?php submit_button( __( 'Submit' ) , 'secondary' ); ?>
			</form>

			<h4><?php _e( 'I\'m looking forward to your proposal.' , $Plvc->Plugin['ltd'] ); ?></h4>
			<p><?php _e( 'Please contact me if you have any good idea.' , $Plvc->Plugin['ltd'] ); ?></p>
		</div>
	</div>

<?php endif; ?>

<div class="stuffbox" id="considerbox">
	<h3><span class="hndle"><?php _e( 'Have you want to customize?' , $Plvc->Plugin['ltd'] ); ?></span></h3>
	<div class="inside">
		<p style="float: right;">
			<a href="<?php echo $Plvc->ClassInfo->author_url( array( 'contact' => 1 , 'tp' => 'use_plugin' , 'lc' => 'side' ) ); ?>" target="_blank">
				<img src="<?php echo $Plvc->ClassInfo->get_gravatar_src( '46' ); ?>" width="46" />
			</a>
		</p>
		<p><?php _e( 'I am good at Admin Screen Customize.' , $Plvc->Plugin['ltd'] ); ?></p>
		<p><?php _e( 'Please consider the request to me if it is good.' , $Plvc->Plugin['ltd'] ); ?></p>
		<p>
			<a href="<?php echo $Plvc->ClassInfo->author_url( array( 'contact' => 1 , 'tp' => 'use_plugin' , 'lc' => 'side' ) ); ?>" target="_blank"><?php _e( 'Contact' , $Plvc->Plugin['ltd'] ); ?></a>
			| 
			<a href="http://wpadminuicustomize.com/blog/category/example/<?php echo $Plvc->ClassInfo->get_utm_link( array( 'tp' => 'use_plugin' , 'lc' => 'side' ) ); ?>" target="_blank"><?php _e( 'Example Customize' , $Plvc->Plugin['ltd'] ); ?></a>
	</div>
</div>

<div class="stuffbox" id="aboutbox">
	<h3><span class="hndle"><?php _e( 'About plugin' , $Plvc->Plugin['ltd'] ); ?></span></h3>
	<div class="inside">
		<p><?php _e( 'Version checked' , $Plvc->Plugin['ltd'] ); ?> : <?php echo $Plvc->ClassInfo->version_checked(); ?></p>
		<ul>
			<li><a href="<?php echo $Plvc->ClassInfo->author_url( array( 'tp' => 'use_plugin' , 'lc' => 'side' ) ); ?>" target="_blank"><?php _e( 'Developer\'s site' , $Plvc->Plugin['ltd'] ); ?></a></li>
			<li><a href="<?php echo $Plvc->ClassInfo->links['forum']; ?>" target="_blank"><?php _e( 'Support Forums' ); ?></a></li>
			<li><a href="<?php echo $Plvc->ClassInfo->links['review']; ?>" target="_blank"><?php _e( 'Reviews' , $Plvc->Plugin['ltd'] ); ?></a></li>
			<li><a href="https://twitter.com/gqevu6bsiz" target="_blank">twitter</a></li>
			<li><a href="http://www.facebook.com/pages/Gqevu6bsiz/499584376749601" target="_blank">facebook</a></li>
		</ul>
		<h4>Translate Help</h4>
		<p><a href="<?php echo $Plvc->ClassInfo->author_url( array( 'translate' => 1 , 'tp' => 'use_plugin' , 'lc' => 'side' ) ); ?>" target="_blank"><?php _e( 'Would you like to translate?' , $Plvc->Plugin['ltd'] ); ?></a></p>
		<p><?php echo sprintf( __( 'Do you have a proposal you want to improve? Please contact to %s if it is necessary.' , $Plvc->Plugin['ltd'] ) , '<a href="' . $Plvc->ClassInfo->links['forum'] . '" target="_blank">' . __( 'Support Forums' ) . '</a>' ); ?></p>
	</div>
</div>

<div class="stuffbox" id="usefulbox">
	<h3><span class="hndle"><?php _e( 'Useful plugins' , $Plvc->Plugin['ltd'] ); ?></span></h3>
	<div class="inside">
		<p><strong><span style="color: orange;">new</span> <a href="<?php echo $Plvc->ClassInfo->author_url( array( 'add-on' => 1 , 'tp' => 'use_plugin' , 'lc' => 'side' ) ); ?>" target="_blank"><?php echo $Plvc->Plugin['name']; ?> for Multiple setups Add-on</a></strong></p>
		<p class="description"><?php _e( 'This add-on is can be different setting for each user roles.' , $Plvc->Plugin['ltd'] ); ?></p>
		<p><strong><a href="http://wpadminuicustomize.com/<?php echo $Plvc->ClassInfo->get_utm_link( array( 'tp' => 'use_plugin' , 'lc' => 'side' ) ); ?>" target="_blank">WP Admin UI Customize</a></strong></p>
		<p class="description"><?php _e( 'Customize a variety of screen management.' , $Plvc->Plugin['ltd'] ); ?></p>
		<p><strong><a href="http://wordpress.org/extend/plugins/announce-from-the-dashboard/" target="_blank">Announce from the Dashboard</a></strong></p>
		<p class="description"><?php _e( 'Announce to display the dashboard. Change the display to a different user role.' , $Plvc->Plugin['ltd'] ); ?></p>
		<p><strong><a href="http://wordpress.org/extend/plugins/custom-options-plus-post-in/" target="_blank">Custom Options Plus Post in</a></strong></p>
		<p class="description"><?php _e( 'The plugin that allows you to add the value of the options. Option value that you have created, can be used in addition to the template tag, Short code can be used in the body of the article.' , $Plvc->Plugin['ltd'] ); ?></p>
		<p>&nbsp;</p>
		<p><a href="<?php echo $Plvc->ClassInfo->links['profile']; ?>" target="_blank"><?php _e( 'Plugins' ); ?></a></p>
	</div>
</div>
