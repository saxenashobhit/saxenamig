<?php
defined( 'ABSPATH' ) or die();
require_once( WL_EHRM_PLUGIN_DIR_PATH . '/admin/inc/helpers/wl-ehrm-helper.php' );

/**
 *  Action calls for Shotcode
 */
class EHRMShortcode {

	/* Login Portal */
	public static function login_portal( $attr ) {
		ob_start();
		require_once( 'inc/controllers/wl_ehrm_login_portal.php' );
		return ob_get_clean();
	}

	public static function goto_login_portal(){
		//echo wp_login_url();
		//wp_redirect( admin_url( ) );
		 // wp_redirect( wp_login_url() );
		//if(!is_user_logged_in()) {
		   // wp_redirect( wp_login_url() );
		     //wp_redirect('/login/?redirect_to=' . $_SERVER["REQUEST_URI"]);
		     //login.php?redirect_to=/clock-in-page
		     //wp_redirect('login.php?redirect_to=/clock-in-page');
		//}

		if(!is_user_logged_in()) {
			$login_url = wp_login_url();
			?>
			<script type="text/javascript">
				window.location.replace('<?php echo $login_url; ?>');
			</script>
			<?php
		}
	}

	
	
	/* get_time_format */
	public function get_time_format() {
		$save_settings    = get_option('ehrm_settings_data');
		$time_format      = isset($save_settings['time_format']) ? sanitize_text_field($save_settings['time_format']) : 'g:i A';
		return $time_format;
	}

	public static function shortcode_enqueue_assets() {

		/* Enqueue styles */
		wp_enqueue_style( 'wl-ehrm-bootstrap-custom', WL_EHRM_PLUGIN_URL . 'public/css/custom-bootstrap.css' );
		wp_enqueue_style( 'toastr', WL_EHRM_PLUGIN_URL . 'assets/css/toastr.min.css' );
        wp_enqueue_style( 'wl-ehrm-front-end', WL_EHRM_PLUGIN_URL . 'public/css/front_end_css.css' );
        
		/* Enqueue scripts */
        wp_enqueue_script( 'jquery' );
        wp_enqueue_script( 'popper-js', WL_EHRM_PLUGIN_URL . 'assets/js/popper.min.js', array( 'jquery' ), true, true );
		wp_enqueue_script( 'bootstrap-js', WL_EHRM_PLUGIN_URL . 'assets/js/bootstrap.min.js', array( 'jquery' ), true, true );
		wp_enqueue_script( 'toastr-js', WL_EHRM_PLUGIN_URL . 'assets/js/toastr.min.js', array( 'jquery' ), true, true );
        
        /* Staff dash board ajax js */
		wp_enqueue_script( 'wl-ehrm-login-ajax-js', WL_EHRM_PLUGIN_URL . 'public/js/wl-ehrm-login-ajax.js', array( 'jquery' ), true, true );
		wp_localize_script( 'wl-ehrm-login-ajax-js', 'ajax_login', array(
			'ajax_url'      => admin_url( 'admin-ajax.php' ),
			'login_nonce'   => wp_create_nonce( 'login_ajax_nonce' ),
			'ehrm_timezone' => EHRMHelperClass::get_setting_timezone(),
		));
        
	}

}