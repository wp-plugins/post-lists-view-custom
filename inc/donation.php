<?php
$donatedKey = get_option( $this->Record["donate"] );
if( $donatedKey == $this->DonateKey ) : 
?>
			<span class="description"><?php _e( 'Thank you for your donation.' , $this->ltd_p ); ?></span>

<?php else: ?>

			<div class="stuffbox" id="donationbox">
				<div class="inside">
					<p style="color: #FFFFFF; font-size: 20px;"><?php _e( 'Please donation.' , $this->ltd_p ); ?></p>
					<p style="color: #FFFFFF;"><?php _e( 'You are contented with this plugin?<br />By the laws of Japan, Japan\'s new paypal user can not make a donation button.<br />So i would like you to buy this plugin as the replacement for the donation.' , $this->ltd_p ); ?></p>
					<p>&nbsp;</p>
					<p style="text-align: center;">
						<a href="http://gqevu6bsiz.chicappa.jp/line-break-first-and-end/?utm_source=use_plugin&utm_medium=donate&utm_content=<?php echo $this->ltd; ?>&utm_campaign=<?php echo str_replace( '.' , '_' , $this->Ver ); ?>" class="button-primary" target="_blank">Line Break First and End</a>
					</p>
					<p>&nbsp;</p>
					<div class="donation_memo">
						<p><strong><?php _e( 'Features' , $this->ltd_p ); ?></strong></p>
						<p><?php _e( 'Line Break First and End plugin is In the visual editor TinyMCE, It is a plugin that will help when you will not be able to enter a line break.' , $this->ltd_p ); ?></p>
					</div>
					<div class="donation_memo">
						<p><strong><?php _e( 'The primary use of donations' , $this->ltd_p ); ?></strong></p>
						<ul>
							<li>- <?php _e( 'Liquidation of time and value' , $this->ltd_p ); ?></li>
							<li>- <?php _e( 'Additional suggestions feature' , $this->ltd_p ); ?></li>
							<li>- <?php _e( 'Maintain motivation' , $this->ltd_p ); ?></li>
							<li>- <?php _e( 'Ensure time as the father of Sunday' , $this->ltd_p ); ?></li>
						</ul>
					</div>

					<form id="donation_form" class="plvc_form" method="post" action="<?php echo self_admin_url( 'admin.php?page=' . $this->PageSlug ); ?>">
						<h4 style="color: #FFF;"><?php _e( 'If you have already donated to.' , $this->ltd_p ); ?></h4>
						<p style="color: #FFF;"><?php _e( 'Please enter the \'Donation delete key\' that have been described in the \'Line Break First and End download page\'.' , $this->ltd_p ); ?></p>
						<input type="hidden" name="<?php echo $this->UPFN; ?>" value="Y" />
						<?php wp_nonce_field(); ?>
						<label for="donate_key"><span style="color: #FFF; "><?php _e( 'Donation delete key' , $this->ltd_p ); ?></span></label>
						<input type="text" name="donate_key" id="donate_key" value="" class="small-text" />
						<input type="submit" class="button-secondary" name="submit" value="<?php _e( 'Submit' ); ?>" />
					</form>

				</div>
			</div>

<?php endif; ?>

			<?php if( $donatedKey == $this->DonateKey ) : ?>
				<div class="toggle-plugin"><p class="icon"><a href="#"><?php echo esc_html__( 'Collapse' ); ?></a></p></div>
			<?php endif; ?>

			<div class="stuffbox" id="aboutbox">
				<h3><span class="hndle"><?php _e( 'About plugin' , $this->ltd_p ); ?></span></h3>
				<div class="inside">
					<p><?php _e( 'Version check' , $this->ltd_p ); ?> : 3.4.2 - 3.6 beta3</p>
					<ul>
						<li><a href="http://gqevu6bsiz.chicappa.jp/?utm_source=use_plugin&utm_medium=side&utm_content=<?php echo $this->ltd; ?>&utm_campaign=<?php echo str_replace( '.' , '_' , $this->Ver ); ?>" target="_blank"><?php _e( 'Developer\'s site' , $this->ltd_p ); ?></a></li>
						<li><a href="http://wordpress.org/support/plugin/post-lists-view-custom" target="_blank"><?php _e( 'Support Forums' ); ?></a></li>
						<li><a href="http://wordpress.org/support/view/plugin-reviews/post-lists-view-custom" target="_blank"><?php _e( 'Reviews' , $this->ltd_p ); ?></a></li>
						<li><a href="https://twitter.com/gqevu6bsiz" target="_blank">twitter</a></li>
						<li><a href="http://www.facebook.com/pages/Gqevu6bsiz/499584376749601" target="_blank">facebook</a></li>
					</ul>
					<p>I am looking for a translator for this plugin.<br />
					<a href="http://gqevu6bsiz.chicappa.jp/please-translation/?utm_source=use_plugin&utm_medium=side&utm_content=<?php echo $this->ltd; ?>&utm_campaign=<?php echo str_replace( '.' , '_' , $this->Ver ); ?>" target="_blank">Please translation.</a>
					</p>
					<p><?php echo sprintf( __( 'Do you have a proposal you want to improve? Please contact to %s if it is necessary.' , $this->ltd_p ) , '<a href="http://wordpress.org/support/plugin/post-lists-view-custom" target="_blank">' . __( 'Support Forums' ) . '</a>' ); ?></p>
				</div>
			</div>

			<div class="stuffbox" id="usefulbox">
				<h3><span class="hndle"><?php _e( 'Useful plugins' , $this->ltd_p ); ?></span></h3>
				<div class="inside">
					<p><strong><span style="color: orange;">new</span> <a href="http://gqevu6bsiz.chicappa.jp/post-lists-view-custom-for-multiple-setups-add-on/?utm_source=use_plugin&utm_medium=side&utm_content=<?php echo $this->ltd; ?>&utm_campaign=<?php echo str_replace( '.' , '_' , $this->Ver ); ?>" target="_blank">Post Lists View Custom for Multiple setups Add-on</a></strong></p>
					<p class="description"><?php _e( 'This add-on is can be different setting for each user roles.' , $this->ltd_p ); ?></p>
					<p><strong><a href="http://wpadminuicustomize.com/?utm_source=use_plugin&utm_medium=side&utm_content=<?php echo $this->ltd; ?>&utm_campaign=<?php echo str_replace( '.' , '_' , $this->Ver ); ?>" target="_blank">WP Admin UI Customize</a></strong></p>
					<p class="description"><?php _e( 'Customize a variety of screen management.' , $this->ltd_p ); ?></p>
					<p><strong><a href="http://wordpress.org/extend/plugins/announce-from-the-dashboard/" target="_blank">Announce from the Dashboard</a></strong></p>
					<p class="description"><?php _e( 'Announce to display the dashboard. Change the display to a different user role.' , $this->ltd_p ); ?></p>
					<p><strong><a href="http://wordpress.org/extend/plugins/custom-options-plus-post-in/" target="_blank">Custom Options Plus Post in</a></strong></p>
					<p class="description"><?php _e( 'The plugin that allows you to add the value of the options. Option value that you have created, can be used in addition to the template tag, Short code can be used in the body of the article.' , $this->ltd_p ); ?></p>
					<p>&nbsp;</p>
				</div>
			</div>
