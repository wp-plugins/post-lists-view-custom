<?php
$donatedKey = get_option( $this->Record["donate"] );
if( $donatedKey == $this->DonateKey ) : 
?>
			<span class="description"><?php _e( 'Thank you for your donation.' , $this->ltd ); ?></span>

<?php else: ?>

			<div class="stuffbox" id="donationbox">
				<div class="inside">
					<h4 style="color: #FFFFFF;"><?php _e( 'Thank you for considering donate.' , $this->ltd ); ?></h4>
					<p style="color: #ffffff;"><?php _e( 'Thank you very much for your support.' , $this->ltd ); ?></p>
					<p style="text-align: center;">
						<a href="<?php echo $this->AuthorUrl; ?>please-donation/?utm_source=use_plugin&utm_medium=donate&utm_content=<?php echo $this->ltd; ?>&utm_campaign=<?php echo str_replace( '.' , '_' , $this->Ver ); ?>" class="button-primary" target="_blank"><?php _e( 'Donate' , $this->ltd ); ?></a>
					</p>
					<form id="donation_form" class="plvc_form" method="post" action="<?php echo remove_query_arg( $this->MsgQ ); ?>">
						<p style="color: #FFF;"><?php _e( 'Please enter the \'Donation delete key\' that have been described in the \'Line Break First and End download page\'.' , $this->ltd ); ?></p>
						<input type="hidden" name="<?php echo $this->UPFN; ?>" value="Y" />
						<?php wp_nonce_field( $this->Nonces["value"] , $this->Nonces["field"] ); ?>
						<input type="hidden" name="record_field" value="donate" />
						<label for="donate_key"><span style="color: #FFF; "><?php _e( 'Donation delete key' , $this->ltd ); ?></span></label>
						<input type="text" name="donate_key" id="donate_key" value="" class="small-text" />
						<input type="submit" class="button-secondary" name="submit" value="<?php _e( 'Submit' ); ?>" />
					</form>
					<h4 style="color: #FFFFFF;"><?php _e( 'I\'m looking forward to your proposal.' , $this->ltd ); ?></h4>
					<p style="color: #FFFFFF;"><?php _e( 'Please contact me if you have any good idea.' , $this->ltd ); ?></p>
				</div>
			</div>

<?php endif; ?>

			<?php if( $donatedKey == $this->DonateKey ) : ?>
				<div class="toggle-plugin"><p class="icon"><a href="#"><?php echo esc_html__( 'Collapse' ); ?></a></p></div>
			<?php endif; ?>

			<div class="stuffbox" style="border-color: #FFC426;">
				<h3 style="background: #FFF2D0; border-color: #FFC426;"><span class="hndle"><?php _e( 'Have you want to customize?' , $this->ltd ); ?></span></h3>
				<div class="inside">
					<p style="float: right;">
						<img src="<?php echo $this->Schema; ?>www.gravatar.com/avatar/7e05137c5a859aa987a809190b979ed4?s=46" width="46" /><br />
						<a href="<?php echo $this->AuthorUrl; ?>contact-us/utm_source=use_plugin&utm_medium=side&utm_content=<?php echo $this->ltd; ?>&utm_campaign=<?php echo str_replace( '.' , '_' , $this->Ver ); ?>" target="_blank">gqevu6bsiz</a>
					</p>
					<p><?php _e( 'I am good at Admin Screen Customize.' , $this->ltd ); ?></p>
					<p><?php _e ( 'Please consider the request to me if it is good.' , $this->ltd ); ?></p>
					<p>
						<a href="<?php echo $this->AuthorUrl; ?>contact-us/utm_source=use_plugin&utm_medium=side&utm_content=<?php echo $this->ltd; ?>&utm_campaign=<?php echo str_replace( '.' , '_' , $this->Ver ); ?>" target="_blank"><?php _e( 'Contact' , $this->ltd ); ?></a>
						 | 
						<a href="http://wpadminuicustomize.com/blog/category/example/?utm_source=use_plugin&utm_medium=side&utm_content=<?php echo $this->ltd; ?>&utm_campaign=<?php echo str_replace( '.' , '_' , $this->Ver ); ?>" target="_blank"><?php _e( 'Example Customize' , $this->ltd ); ?></a>
					</p>
				</div>
			</div>

			<div class="stuffbox" id="aboutbox">
				<h3><span class="hndle"><?php _e( 'About plugin' , $this->ltd ); ?></span></h3>
				<div class="inside">
					<p><?php _e( 'Version checked' , $this->ltd ); ?> : 3.6.1 - 3.8</p>
					<ul>
						<li><a href="<?php echo $this->AuthorUrl; ?>?utm_source=use_plugin&utm_medium=side&utm_content=<?php echo $this->ltd; ?>&utm_campaign=<?php echo str_replace( '.' , '_' , $this->Ver ); ?>" target="_blank"><?php _e( 'Developer\'s site' , $this->ltd ); ?></a></li>
						<li><a href="http://wordpress.org/support/plugin/<?php echo $this->PluginSlug; ?>" target="_blank"><?php _e( 'Support Forums' ); ?></a></li>
						<li><a href="http://wordpress.org/support/view/plugin-reviews/<?php echo $this->PluginSlug; ?>" target="_blank"><?php _e( 'Reviews' , $this->ltd ); ?></a></li>
						<li><a href="https://twitter.com/gqevu6bsiz" target="_blank">twitter</a></li>
						<li><a href="http://www.facebook.com/pages/Gqevu6bsiz/499584376749601" target="_blank">facebook</a></li>
					</ul>
					<p><strong>Translate Help</strong></p>
					<p><?php echo sprintf( __( 'Would you like to translate?' , $this->ltd ) , '<a href="http://wordpress.org/support/plugin/' . $this->PluginSlug . '" target="_blank">' . __( 'Support Forums' ) . '</a>' ); ?></p>
					<p>
						<a href="<?php echo $this->AuthorUrl; ?>please-translation/?utm_source=use_plugin&utm_medium=side&utm_content=<?php echo $this->ltd; ?>&utm_campaign=<?php echo str_replace( '.' , '_' , $this->Ver ); ?>" target="_blank">Translate</a>
					</p>
					<p><?php echo sprintf( __( 'Do you have a proposal you want to improve? Please contact to %s if it is necessary.' , $this->ltd ) , '<a href="http://wordpress.org/support/plugin/' . $this->PluginSlug . '" target="_blank">' . __( 'Support Forums' ) . '</a>' ); ?></p>
				</div>
			</div>

			<div class="stuffbox" id="usefulbox">
				<h3><span class="hndle"><?php _e( 'Useful plugins' , $this->ltd ); ?></span></h3>
				<div class="inside">
					<p><strong><span style="color: orange;">new</span> <a href="<?php echo $this->AuthorUrl; ?>post-lists-view-custom-for-multiple-setups-add-on/?utm_source=use_plugin&utm_medium=side&utm_content=<?php echo $this->ltd; ?>&utm_campaign=<?php echo str_replace( '.' , '_' , $this->Ver ); ?>" target="_blank">Post Lists View Custom for Multiple setups Add-on</a></strong></p>
					<p class="description"><?php _e( 'This add-on is can be different setting for each user roles.' , $this->ltd ); ?></p>
					<p><strong><a href="http://wpadminuicustomize.com/?utm_source=use_plugin&utm_medium=side&utm_content=<?php echo $this->ltd; ?>&utm_campaign=<?php echo str_replace( '.' , '_' , $this->Ver ); ?>" target="_blank">WP Admin UI Customize</a></strong></p>
					<p class="description"><?php _e( 'Customize a variety of screen management.' , $this->ltd ); ?></p>
					<p><strong><a href="http://wordpress.org/extend/plugins/announce-from-the-dashboard/" target="_blank">Announce from the Dashboard</a></strong></p>
					<p class="description"><?php _e( 'Announce to display the dashboard. Change the display to a different user role.' , $this->ltd ); ?></p>
					<p><strong><a href="http://wordpress.org/extend/plugins/custom-options-plus-post-in/" target="_blank">Custom Options Plus Post in</a></strong></p>
					<p class="description"><?php _e( 'The plugin that allows you to add the value of the options. Option value that you have created, can be used in addition to the template tag, Short code can be used in the body of the article.' , $this->ltd ); ?></p>
					<p>&nbsp;</p>
				</div>
			</div>
