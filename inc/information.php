<div class="wrap">

	<div id="<?php echo $Plvc->Plugin['ltd']; ?>-plugin-information" class="metabox-holder">
	
		<div class="meta-box-sortables">
	
			<div class="postbox closed">
		
				<div class="handlediv" title="<?php esc_attr_e( 'Click to toggle' ); ?>"><br /></div>
				<h3 class="hndle"><span><?php echo $Plvc->Plugin['name']; ?></span></h3>
				
				<div class="inside">
				
					<div id="abount-box">
					
						<p class="author-image">
						
							<a href="<?php echo $Plvc->ClassInfo->author_url( array( 'tp' => 'use_plugin' , 'lc' => 'side' ) ); ?>" target="_blank">
								<span class="gravatar"></span>
								gqevu6bsiz
							</a>
						
						</p>
						
						<h4><?php _e( 'About plugin' , $Plvc->Plugin['ltd'] ); ?></h4>
	
						<p>
							<?php _e( 'Version checked' , $Plvc->Plugin['ltd'] ); ?>:
							<code><?php echo $Plvc->ClassInfo->version_checked(); ?></code>
						</p>
	
						<ul>
							<li><span class="dashicons dashicons-admin-plugins"></span> <a href="<?php echo $Plvc->Plugin['links']['forum']; ?>" target="_blank"><?php echo $Plvc->Plugin['name']; ?></a></li>
							<li><span class="dashicons dashicons-format-chat"></span> <a href="<?php echo $Plvc->Plugin['links']['forum']; ?>" target="_blank"><?php _e( 'Support Forums' ); ?></a></li>
							<li><span class="dashicons dashicons-star-half"></span> <a href="<?php echo $Plvc->Plugin['links']['review']; ?>" target="_blank"><?php _e( 'Reviews' , $Plvc->Plugin['ltd'] ); ?></a></li>
						</ul>
	
						<ul>
							<li><span class="dashicons dashicons-smiley"></span><a href="<?php echo $Plvc->ClassInfo->author_url( array( 'tp' => 'use_plugin' , 'lc' => 'footer' ) ); ?>" target="_blank"><?php _e( 'Developer\'s site' , $Plvc->Plugin['ltd'] ); ?></a></li>
							<li><span class="dashicons dashicons-twitter"></span> <a href="https://twitter.com/gqevu6bsiz" target="_blank">twitter</a></li>
							<li><span class="dashicons dashicons-facebook-alt"></span> <a href="http://www.facebook.com/pages/Gqevu6bsiz/499584376749601" target="_blank">facebook</a></li>
						</ul>
						
						<p>&nbsp;</p>
						
						<h4>Translate Help</h4>
						
						<p><?php echo _e( 'Would you like to translate?' , $Plvc->Plugin['ltd'] ); ?></p>
						<ul>
							<li><span class="dashicons dashicons-translation"></span> <a href="<?php echo $Plvc->ClassInfo->author_url( array( 'translate' => 1 , 'tp' => 'use_plugin' , 'lc' => 'footer' ) ); ?>" target="_blank">Translation</a></li>
						</ul>

						<p>&nbsp;</p>
						
						<h4><?php _e( 'Useful plugins' , $Plvc->Plugin['ltd'] ); ?></h4>
	
						<ul>
							<li>
								<span class="dashicons dashicons-admin-plugins"></span>
								<a href="http://wpadminuicustomize.com/<?php echo $Plvc->ClassInfo->author_url( array( 'tp' => 'use_plugin' , 'lc' => 'side' ) ); ?>" target="_blank">WP Admin UI Customize</a>:
								<span class="description"><?php _e( 'Customize a variety of screen management.' , $Plvc->Plugin['ltd'] ); ?></span>
							</li>
							<li>
								<span class="dashicons dashicons-admin-plugins"></span>
								<a href="http://wordpress.org/extend/plugins/announce-from-the-dashboard/" target="_blank">Announce from the Dashboard</a>:
								<span class="description"><?php _e( 'Announce to display the dashboard. Change the display to a different user role.' , $Plvc->Plugin['ltd'] ); ?></span>
							</li>
							<li>
								<span class="dashicons dashicons-admin-plugins"></span>
								<a href="http://wordpress.org/extend/plugins/custom-options-plus-post-in/" target="_blank">Custom Options Plus Post in</a>:
								<span class="description"><?php _e( 'The plugin that allows you to add the value of the options. Option value that you have created, can be used in addition to the template tag, Short code can be used in the body of the article.' , $Plvc->Plugin['ltd'] ); ?></span>
							</li>
						</ul>
						
						<p>&nbsp;</p>
						
						<p><span class="dashicons dashicons-admin-plugins"></span> <a href="<?php echo $Plvc->Plugin['links']['profile']; ?>" target="_blank"><?php _e( 'Plugins' ); ?></a></p>
						
					</div>

				</div>
			
			</div>
		
		</div>
	
	</div>

</div>
<style>
#<?php echo $Plvc->Plugin['ltd']; ?>-plugin-information {
    margin-top: 50px;
}
#<?php echo $Plvc->Plugin['ltd']; ?>-plugin-information .postbox .hndle {
    cursor: default;
}
#<?php echo $Plvc->Plugin['ltd']; ?>-plugin-information .author-image {
    float: right;
    width: 200px;
    text-align: right;
}
#<?php echo $Plvc->Plugin['ltd']; ?>-plugin-information .author-image .gravatar {
    -webkit-transition: all 0.2s linear;
    transition: all 0.2s linear;
    border-radius: 10%;
    background: url(<?php echo $Plvc->Current['schema']; ?>www.gravatar.com/avatar/7e05137c5a859aa987a809190b979ed4?s=72) no-repeat right top;
    width: 72px;
    height: 72px;
    margin-left: auto;
    display: block;
}
#<?php echo $Plvc->Plugin['ltd']; ?>-plugin-information .author-image .gravatar:hover {
    box-shadow: inset 0 0 0 7px rgba(0,0,0,0.5), 0 1px 2px rgba(0,0,0,0.1);
}
</style>
<script>
jQuery(document).ready( function($) {

	$('#<?php echo $Plvc->Plugin['ltd']; ?>-plugin-information .handlediv').on('click', function( ev ) {
		
		$(this).parent().toggleClass('closed');
		
	});

});
</script>
