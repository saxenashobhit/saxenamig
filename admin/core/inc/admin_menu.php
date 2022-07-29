<?php defined( 'ABSPATH' ) or die(); ?>
<div class="wrap license-container">

	<div class="top_head">
		<div class="column-3">
			<div class="logo-section">
				<img class="logo" src="<?php echo WL_EHRM_PLUGIN_URL . '/admin/core/inc/images/logo.png'; ?>">
			</div>
		</div>
		<div class="column-9">
			<h1><?php _e( "Thank you for choosing Employee & HR Management Plugin", 'employee-&-hr-management' ); ?>!</h1>
			<p class="license_info"><?php _e( "Please activate this plugin with a license key. If you don’t have a license yet, you can purchase it from ", 'employee-&-hr-management' ); ?>
				<a href="https://weblizar.com/amember/signup/employee-and-hr-management" target="_blank"><?php _e( "here", 'employee-&-hr-management' ); ?></a>
			</p>
		</div>
		
	</div>
	<div class="clearfix"></div>
	<div class="license-section">
		<div class="license-section-inner">
		<h2><?php _e( "Let’s get some work done!", 'employee-&-hr-management' ); ?> </h2>
		<p><?php _e( "We have some useful links to get you started", 'employee-&-hr-management' ); ?>: </p>
		<?php
		require_once WL_EHRM_PLUGIN_DIR_PATH . '/admin/core/WL_EHRM_LM.php';
		$wl_ehrm_lm = WL_EHRM_LM::get_instance();
		$validated = $wl_ehrm_lm->is_valid();

		if ( isset( $_POST['key'] ) && ! empty( $_POST['key'] ) ) {
			$license_key = preg_replace( '/[^A-Za-z0-9-_]/', '', trim( $_POST['key'] ) ); 
			if( $wl_ehrm_lm->validate( $license_key ) ) {
				$validated = true;
			}
		} else {
			$wl_ehrm_lm->error_message = __( "Get Your License Key", 'employee-&-hr-management' ) . ' ' . '<a target="_blank" href="https://weblizar.com/amember/softsale/license">' . __( "Click Here", 'employee-&-hr-management' ) . '</a>';
		} ?>
			<div class="column-6">
		<?php
		if( $validated ) {
			$key = get_option( 'wl-ehrm-key' );
			$first_letters = substr( $key, 0, 3 );
			$last_letters = substr( $key, -3 );
		?>
				<h2 class="license-message">
					<?php _e( "License Key applied", 'employee-&-hr-management' ); ?>
					<span><a href="<?php echo admin_url(); ?>"><?php _e( "Click here to navigate to dashboard", 'employee-&-hr-management' ); ?></a></span>
				</h2>

				<div class="label">
					<label for="license_key"><?php _e( "License Key", 'employee-&-hr-management' ); ?>:</label>
				</div>
				<div class="input-box">
					<input id="license_key" name="key" type="text" class="regular-text" value="<?php echo "{$first_letters}****************{$last_letters}"; ?>" disabled>
				</div>
				<div class="Configuration_btn">
					<h2><?php _e("Congratulation! Employee & HR Management Plugin is activated.", 'employee-&-hr-management'); ?></h2>
					<div class="">
						<a class="conf_btn" href="<?php echo get_admin_url(); ?>admin.php?page=employee-and-hr-management-settings"><?php _e( "Plugin Configuration Click Here", 'employee-&-hr-management' ); ?></a>
					</div>
				</div>
		<?php
		} else {
			if ( $wl_ehrm_lm->error_message ) { ?>
				<h3 class="license-message"><?php echo $wl_ehrm_lm->error_message; ?></h3>
			<?php
			} ?>
				<form method='post'>
					<div class="label">
						<label for="license_key"><?php _e( "License Key", 'employee-&-hr-management' ); ?>:</label>
					</div>
					<div class="input-box">
						<input id="license_key" name="key" type="text" class="regular-text">
					</div>
					<input type="submit" class="button button-primary" value="Register plugin">
				</form>
		<?php
		} ?>
			</div>
			<div class="column-6-right">
				<ul class="weblizar-links">
					<li><h3><?php _e( "Getting Started", 'employee-&-hr-management' ); ?></h3></li>
					<li><i class="dashicons dashicons-video-alt3"></i><a target="_blank" href="https://www.youtube.com/channel/UCFve0DTmWU4OTHXAtUOpQ7Q/playlists"><?php _e( "Video Tutorial", 'employee-&-hr-management' ); ?></a></li>
					<li><i class="dashicons dashicons-portfolio"></i><a target="_blank" href="https://weblizar.com/plugins/"><?php _e( "More Products", 'employee-&-hr-management' ); ?></a></li>
					<li><i class="dashicons dashicons-admin-customizer"></i><a target="_blank" href="https://weblizar.com/complete-website-set-design/"><?php _e( "Customize your site", 'employee-&-hr-management' ); ?></a></li>
					
				</ul>
				<ul class="weblizar-links">
					<li><h3><?php _e( "Guides & Support", 'employee-&-hr-management' ); ?></h3></li>
					<li><i class="dashicons dashicons-welcome-view-site"></i><a target="_blank" href=""><?php _e( "Demo", 'employee-&-hr-management' ); ?></a></li>
					<li><i class="dashicons dashicons-admin-users"></i><a target="_blank" href="https://weblizar.com/documentation/employee-and-hr-management/"><?php _e( "Documentation guide", 'employee-&-hr-management' ); ?></a></li>
					<li><i class="dashicons dashicons-format-status"></i><a target="_blank" href="https://weblizar.com/forum/"><?php _e( "Support forum", 'employee-&-hr-management' ); ?></a></li>			
				</ul>
			</div>
		</div>
	</div>
</div>