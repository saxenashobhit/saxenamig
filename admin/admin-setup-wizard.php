<?php
/**
 * Setup Wizard Class
 *
 * Takes new users through some basic steps to setup their store.
 *
 * @package  Employee & HR Management
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * ehrm_Admin_Setup_Wizard class.
 */
class EHRM_AdminSetupWizard {

    /**
	 * Current step
	 *
	 * @var string
	 */
    private $step = '';
    
    /**
	 * Shift status
	 *
	 * @var string
	 */
	private $shift_status = 0;

	/**
	 * Department status
	 *
	 * @var string
	 */
	private $dept_status = 0;

	/**
	 * Steps for the setup wizard
	 *
	 * @var array
	 */
    private $steps = array();
    
    /**
	 * Hook in tabs.
	 */
	public function __construct() {
        add_action( 'admin_menu', array( $this, 'admin_menus' ) );
        add_action( 'admin_init', array( $this, 'setup_wizard' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'ehrm_setup_setup_footer', array( $this, 'add_footer_scripts' ) );
    }
    
    /**
	 * Add admin menus/screens.
	 */
	public function admin_menus() {
		add_dashboard_page( '', '', 'manage_options', 'ehrm-setup-wizard', '' );
	}
	
	/**
	 * Add footer scripts to OBW via woocommerce_setup_footer
	 */
	public function add_footer_scripts() {
		wp_print_scripts();
    }

    /**
	 * Register/enqueue scripts and styles for the Setup Wizard.
	 *
	 * Hooked onto 'admin_enqueue_scripts'.
	 */
	public function enqueue_scripts() {
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_style( 'wl-ehrm-bootstrap-custom', WL_EHRM_PLUGIN_URL . 'public/css/custom-bootstrap.css' );
		wp_enqueue_style( 'datetimepicker', WL_EHRM_PLUGIN_URL . 'assets/css/tempusdominus-bootstrap-4.min.css' );
		wp_enqueue_style( 'font-awesome', WL_EHRM_PLUGIN_URL . 'assets/css/font-awesome.min.css' );
		wp_enqueue_style( 'ehrm-setup-css', WL_EHRM_PLUGIN_URL . '/admin/css/admin-setup-wizard.css');

		/* Add the color picker css file */
		wp_enqueue_style( 'wp-color-picker' );

		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'wp-color-picker' );
		wp_enqueue_script( 'popper-js', WL_EHRM_PLUGIN_URL . 'assets/js/popper.min.js', array( 'jquery' ), true, true );
        wp_enqueue_script( 'bootstrap-js', WL_EHRM_PLUGIN_URL . 'assets/js/bootstrap.min.js', array( 'jquery' ), true, true );
        wp_enqueue_script( 'moment-js', WL_EHRM_PLUGIN_URL . 'assets/js/moment.min.js', array( 'jquery' ), true, true );
		wp_enqueue_script( 'datetimepicker-js', WL_EHRM_PLUGIN_URL . 'assets/js/tempusdominus-bootstrap-4.min.js', array( 'jquery' ), true, true );
        wp_enqueue_script( 'ehrm-setup-js', WL_EHRM_PLUGIN_URL . '/admin/js/admin-setup.js', array( 'jquery' ), true, true );
    }

     /**
	 * Show the setup wizard.
	 */
	public function setup_wizard() {
		if ( empty( $_GET['page'] ) || 'ehrm-setup-wizard' !== $_GET['page'] ) { 
			return;
		}
		$default_steps = array(
            'key_activation' => array(
				'name'    => __( 'Activation', 'employee-&-hr-management' ),
				'view'    => array( $this, 'ehrm_activation_setup' ),
				'handler' => '',
			),
			'shifts' => array(
				'name'    => __( 'Create Shift', 'employee-&-hr-management' ),
				'view'    => array( $this, 'ehrm_setup_shift_setup' ),
				'handler' => '',
            ),
            'department'     => array(
				'name'    => __( 'Create Department', 'employee-&-hr-management' ),
				'view'    => array( $this, 'ehrm_setup_depart' ),
				'handler' => array( $this, 'ehrm_setup_depart_save' ),
			),
			'designation'     => array(
				'name'    => __( 'Create Designation', 'employee-&-hr-management' ),
				'view'    => array( $this, 'ehrm_setup_desig' ),
				'handler' => '',
			),
			'settings'    => array(
				'name'    => __( 'Configure Settings', 'employee-&-hr-management' ),
				'view'    => array( $this, 'ehrm_setup_settings' ),
				'handler' => array( $this, 'ehrm_setup_settings_save' ),
			),
			'next_steps'  => array(
				'name'    => __( 'Ready!', 'employee-&-hr-management' ),
				'view'    => array( $this, 'ehrm_setup_ready' ),
				'handler' => '',
			),
		);

		$this->steps = apply_filters( 'ehrm_setup_wizard_steps', $default_steps );
		$this->step  = isset( $_GET['step'] ) ? sanitize_key( $_GET['step'] ) : current( array_keys( $this->steps ) );

		// @codingStandardsIgnoreStart
		if ( ! empty( $_POST['save_step'] ) && isset( $this->steps[ $this->step ]['handler'] ) ) {
			call_user_func( $this->steps[ $this->step ]['handler'], $this );
		}
		// @codingStandardsIgnoreEnd

		ob_start();
		$this->setup_wizard_header();
		$this->setup_wizard_steps();
		$this->setup_wizard_content();
		$this->setup_wizard_footer();
		exit;
    }
    
    /** Next step function **/
	public function get_next_step_link( $step = '' ) {
		if ( ! $step ) {
			$step = $this->step;
		}

		$keys = array_keys( $this->steps );
		if ( end( $keys ) === $step ) {
			return admin_url();
		}

		$step_index = array_search( $step, $keys, true );
		if ( false === $step_index ) {
			return '';
		}

		return add_query_arg( 'step', $keys[ $step_index + 1 ], remove_query_arg( 'activate_error' ) );
	}
    
    /**
	 * Setup Wizard Header.
	 */
	public function setup_wizard_header() {
		set_current_screen();
		?>
		<!DOCTYPE html>
		<html <?php language_attributes(); ?>>
		<head>
			<meta name="viewport" content="width=device-width" />
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
			<title><?php esc_html_e( 'Employee & HR Management &rsaquo; Setup Wizard', 'employee-&-hr-management' ); ?></title>
			<?php do_action( 'admin_enqueue_scripts' ); ?>
			<?php wp_print_scripts( 'ehrm-setup-wizard' ); ?>
			<?php do_action( 'admin_print_styles' ); ?>
			<?php do_action( 'admin_head' ); ?>
		</head>
		<body class="ehrm-setup-wizard wp-core-ui wl_custom wl_ehrm">
            <div class="main-panel">
  	            <div class="content-wrapper container" style="position: relative">
                    <div class="logo">
                        <img style="width: 55%;height: auto;margin-bottom: 2%;" src="<?php echo WL_EHRM_PLUGIN_URL; ?>assets/images/logo.png" alt="logo">
                    </div>
		<?php
    }

    /**
	 * Output the steps.
	 */
	public function setup_wizard_steps() {
		$output_steps = $this->steps;
		?>
		<ol class="ehrm-setup-steps">
			<?php
			foreach ( $output_steps as $step_key => $step ) {
				$is_completed = array_search( $this->step, array_keys( $this->steps ), true ) > array_search( $step_key, array_keys( $this->steps ), true );

				if ( $step_key === $this->step ) {
					?>
					<li class="active"><?php echo esc_html( $step['name'] ); ?></li>
					<?php
				} elseif ( $is_completed ) {
					?>
					<li class="done">
						<a href="<?php echo esc_url( add_query_arg( 'step', $step_key, remove_query_arg( 'activate_error' ) ) ); ?>"><?php echo esc_html( $step['name'] ); ?></a>
					</li>
					<?php
				} else {
					?>
					<li><?php echo esc_html( $step['name'] ); ?></li>
					<?php
				}
			}
			?>
		</ol>
		<?php
    }
    
    /**
	 * Setup Wizard Footer.
	 */
	public function setup_wizard_footer() {
		?>
			<a class="ehrm-setup-footer-links" href="<?php echo esc_url( admin_url() ); ?>"><?php esc_html_e( 'Not right now', 'employee-&-hr-management' ); ?></a>
            <?php do_action( 'ehrm_setup_setup_footer' ); ?>
                    </div>
                </div>
			</body>
		</html>
		<?php
	}

	/**
	 * Output the content for the current step.
	 */
	public function setup_wizard_content() {
		echo '<div class="ehrm-setup-content">';
		if ( ! empty( $this->steps[ $this->step ]['view'] ) ) {
			call_user_func( $this->steps[ $this->step ]['view'], $this );
		}
		echo '</div>';
    }
    
    public function ehrm_activation_setup() {
		?>
		<div class="wrap license-container">
			<div class="top_head">
				<div class="column-3">
					<div class="logo-section text-center">
						<img class="logo" src="<?php echo WL_EHRM_PLUGIN_URL . '/admin/core/inc/images/logo.png'; ?>">
					</div>
				</div>
				<div class="column-9 text-center">
					<h2><?php _e( "Thank you for choosing Employee & HR Management Plugin", 'employee-&-hr-management' ); ?>!</h2>
					<p class="license_info"><?php _e( "Please activate this plugin with a license key. If you don’t have a license yet, you can purchase it from ", 'employee-&-hr-management' ); ?>
						<a href="https://weblizar.com/amember/signup/employee-and-hr-management" target="_blank"><?php _e( "here", 'employee-&-hr-management' ); ?></a>
					</p>
				</div>
				
			</div>
			<div class="clearfix"></div>
			<div class="license-section text-center">
				<div class="license-section-inner">
				<h2 class="text-center"><?php _e( "Let’s get some work done!", 'employee-&-hr-management' ); ?> </h2>
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
					$key = get_option( 'wl-ehrm-code' );
					$first_letters = substr( $key, 0, 3 );
					$last_letters = substr( $key, -3 );
				?>
						<h3 class="license-message success">
							<?php _e( "License Key applied", 'employee-&-hr-management' ); ?>
						</h3>

						<div class="label">
							<label for="license_key"><?php _e( "License Key", 'employee-&-hr-management' ); ?>:</label>
						</div>
						<div class="input-box">
							<input id="license_key" name="key" type="text" class="regular-text key-success" value="<?php echo "{$first_letters}****************{$last_letters}"; ?>" disabled>
						</div>
						<div class="Configuration_btn">
							<h2 class="success-congrates"><?php _e("Congratulation! Employee & HR Management Plugin is activated.", 'employee-&-hr-management'); ?></h2>
							<div class="">
								<br>
								<a class="conf_btn licensi_next_btn" href="<?php echo $this->get_next_step_link(); ?>"><?php _e( "Next", 'employee-&-hr-management' ); ?></a>
							</div>
						</div>
				<?php
				} else {
					if ( $wl_ehrm_lm->error_message ) { ?>
						<h3 class="license-message danger"><?php echo $wl_ehrm_lm->error_message; ?></h3>
					<?php
					} ?>
						<form method='post' class="license_key_form">
							<label for="license_key"><?php _e( "License Key", 'employee-&-hr-management' ); ?>:</label>
							<input id="license_key" name="key" type="text" class="regular-text">
							<input type="submit" class="btn btn-success button-primary button button-large button-next" value="Register plugin">
						</form>
				<?php
				} ?>
					</div>
				</div>
			</div>
		</div>
		<?php
    }

    /** Shift step **/
	public function ehrm_setup_shift_setup() {

		if ( isset( $_POST['save_shift_step'] ) ) {
			$name   = isset( $_POST['shift_name'] ) ? sanitize_text_field( $_POST['shift_name'] ) : '';
			$start  = isset( $_POST['start_time'] ) ? sanitize_text_field( $_POST['start_time'] ) : '';
			$end    = isset( $_POST['end_time'] ) ? sanitize_text_field( $_POST['end_time'] ) : '';
			$late   = isset( $_POST['late_time'] ) ? sanitize_text_field( $_POST['late_time'] ) : '';
			$shifts = get_option( 'ehrm_shifts_data' );
			$data   = array(
				'name'   => $name,
				'start'  => $start,
				'end'    => $end,
				'late'   => $late,
				'status' => 'Active',
			);
			
			if ( empty ( $shifts ) ) {
				$shifts = array();
			}
			array_push( $shifts, $data );

			if ( update_option( 'ehrm_shifts_data', $shifts ) ) {
				$this->shift_status++;
			}
		}
		
		?>
		<form method="post" class="shifts-step" aria-hidden="true" autocomplete="off">
			<p class="store-setup"><?php esc_html_e( 'The following wizard will help you to create multiple shift for your employees.', 'employee-&-hr-management' ); ?></p>
			<hr>
			<div class="form-body">
                <div class="form-group">
                    <label for="shift_name"><?php esc_html_e( 'Shift Name', 'employee-&-hr-management' ); ?></label>
                    <input type="text" class="form-control" name="shift_name" id="shift_name" placeholder="<?php esc_html_e( 'Name', 'employee-&-hr-management' ); ?>">
                </div>
                <div class="form-group" >
                    <label><?php esc_html_e( 'Starting Time', 'employee-&-hr-management' ); ?></label>
                    <input type="text" class="form-control datetimepicker-input" placeholder="<?php esc_html_e( 'Format:- 10:00 AM', 'employee-&-hr-management' ); ?>" id="start_time" name="start_time" data-toggle="datetimepicker" data-target="#start_time"/>
                </div>
                <div class="form-group" >
                    <label><?php esc_html_e( 'Ending Time', 'employee-&-hr-management' ); ?></label>
                    <input type="text" id="end_time" name="end_time" placeholder="<?php esc_html_e( 'Format:- 1:39 PM', 'employee-&-hr-management' ); ?>" class="form-control datetimepicker-input" data-toggle="datetimepicker" data-target="#end_time">
                </div>
                <div class="form-group" >
                    <label><?php esc_html_e( 'Late Time', 'employee-&-hr-management' ); ?></label>
                    <input type="text" id="late_time" name="late_time" placeholder="<?php esc_html_e( 'Format:- 10:15 AM', 'employee-&-hr-management' ); ?>" class="form-control datetimepicker-input" data-toggle="datetimepicker" data-target="#late_time">
                </div>
			</div>
			<hr>
			<p class="ehrm-setup-actions step">
                <?php if ( $this->shift_status != 0 ) { ?>
                    <button type="submit" class="btn btn-gradient-primary"  name="save_shift_step"><?php esc_html_e( "Add more !", 'employee-&-hr-management' ); ?></button>
                    <a href="<?php echo $this->get_next_step_link(); ?>" class="btn button-primary"  name=""><?php esc_html_e( "Next", 'employee-&-hr-management' ); ?></a>
                <?php } else { ?>
                    <button type="submit" class="btn btn-gradient-primary"  name="save_shift_step"><?php esc_html_e( "Create !", 'employee-&-hr-management' ); ?></button>
                <?php } ?>
			</p>
			<?php wp_nonce_field( 'ehrm-setup-wizard' ); ?>
		</form>
		<?php
    }
    
    /** Department step **/
	public function ehrm_setup_depart() {
		?>
		<form method="post" class="designation-step">
			<p class="store-setup"><?php esc_html_e( 'The following wizard will help you to create multiple departments for your employees.', 'employee-&-hr-management' ); ?></p>
			<hr>
			<div class="form-group dynamic_input_js dynamic_department">
				<label for="location_name"><?php esc_html_e( 'Department Name', 'employee-&-hr-management') ; ?></label>
				<br>
				<input type="text" id="department_name_-1" class="form-control department_name" name="department_name[]" placeholder="<?php esc_html_e( 'Name', 'employee-&-hr-management' ); ?>" required>
				<input type="text" id="department_description_-1" class="form-control department_description" name="department_description[]" placeholder="<?php esc_html_e( 'Department Description', 'employee-&-hr-management' ); ?>">
				<div id="dynamic_depart_fields" class="dynamic_input_js dynamic_department"></div>
				<br>
				<button class="btn btn-success btn-sm add_depart_fields"><?php esc_html_e( 'Add More', 'employee-&-hr-management' ); ?></button>
				<button class="btn btn-danger btn-sm remove_depart_fields"><?php esc_html_e( 'Remove', 'employee-&-hr-management' ); ?></button>
			</div>
			<hr>
			<p class="ehrm-setup-actions step">
				<button type="submit" class="button-primary button button-large button-next" value="<?php esc_attr_e( "Next", 'employee-&-hr-management' ); ?>" name="save_step"><?php esc_html_e( "Next", 'employee-&-hr-management' ); ?></button>
			</p>
			<?php wp_nonce_field( 'ehrm-setup-wizard' ); ?>
		</form>
		<?php
	}

	public function ehrm_setup_depart_save() {
		require_once WL_EHRM_PLUGIN_DIR_PATH . 'includes/constants.php';
		check_admin_referer( 'ehrm-setup-wizard' );

		$department_name = isset( $_POST['department_name'] ) ? sanitize_text_field( $_POST['department_name'] ) : '';
		$description 	 = isset( $_POST['description'] ) ? sanitize_text_field( $_POST['description'] ) : '';
		$head 			 = isset( $_POST['head'] ) ? sanitize_text_field( $_POST['head'] ) : '';
		
		global $wpdb;
		//$query = "INSERT INTO EHRM_DEPARTMENTS (`id`, `title`, `description`, `head`, `creation_date`, `image_id`, `status`) VALUES ('','','','','','','')";
		$department_data = array(
			'title'			=> $department_name,
			'description'	=> $description,
			'head' 		  	=> $head,
			'creation_date'	=> $creation_date,
			'image_id'		=> $image_id,
			'status'		=> $status,
		);
		$success = $wpdb->insert(EHRM_DEPARTMENTS, $department_data);
		$wpdb->query('COMMIT;');
		wp_safe_redirect( esc_url_raw( $this->get_next_step_link() ) );
		exit;
		/*if ( ! empty ( $_POST['department_name'] ) ) {
			$deparment   = serialize( $_POST['department_name'] );
			$departments = unserialize( $deparment );

			update_option( 'ehrm_departments_data', $departments );
			wp_safe_redirect( esc_url_raw( $this->get_next_step_link() ) );
			exit;
		}*/
	}

	/** Designation step **/
	public function ehrm_setup_desig() {
		$all_departments  = get_option( 'ehrm_departments_data' );

		if ( isset( $_POST['save_desig_step'] ) ) {
			$depart = isset( $_POST['staff_department'] ) ? sanitize_text_field( $_POST['staff_department'] ) : '';
			$name   = isset( $_POST['designation_name'] ) ? sanitize_text_field( $_POST['designation_name'] ) : '';
			$color  = isset( $_POST['designation_color'] ) ? sanitize_text_field( $_POST['designation_color'] ) : '';
			$design = get_option( 'ehrm_designations_data' );
			$data   = array(
				'deparment' => $depart,
				'name'      => $name,
				'color'     => $color,
				'status'    => 'Active',
			);
			
			if ( empty ( $design ) ) {
				$design = array();
			}
			array_push( $design, $data );

			if ( update_option( 'ehrm_designations_data', $design ) ) {
				$this->dept_status++;
			}
		}

		?>
		<form method="post" class="designation-step" autocomplete="off">
			<p class="store-setup"><?php esc_html_e( 'The following wizard will help you to create multiple Designations for you employees.', 'employee-&-hr-management' ); ?></p>
			<hr>
			<div class="form-group">
				<label for="staff_department"><?php esc_html_e( 'Department', 'employee-&-hr-management' ); ?></label>
				<select name="staff_department" id="staff_department" class="form-control">
					<option value="">----------<?php esc_html_e( 'Select Department99', 'employee-&-hr-management' ); ?>----------</option>
					<?php if ( ! empty ( $all_departments ) ) {
					foreach ( $all_departments as $depat_key => $department ) {
					?>
					<option value="<?php echo esc_attr( $department ); ?>"><?php esc_html_e( $department, 'employee-&-hr-management' ); ?></option>
					<?php } } ?>
				</select>
			</div>
			<hr>
			<div class="form-group">
				<label for="designation_name"><?php esc_html_e( 'Designation Name', 'employee-&-hr-management' ); ?></label>
				<input type="text" class="form-control" name="designation_name" id="designation_name" placeholder="<?php esc_html_e( 'Designation Type', 'employee-&-hr-management' ); ?>">
			</div>
			<hr>
			<div class="form-group">
				<label for="designation_color"><?php esc_html_e( 'Designation Color', 'employee-&-hr-management' ); ?></label>
				<input type="text" class="form-control color-field" name="designation_color" id="designation_color" placeholder="#ffffff">
			</div>
			<hr>
			<p class="ehrm-setup-actions step">
                <?php if ( $this->dept_status != 0 ) { ?>
                    <button type="submit" class="btn btn-gradient-primary"  name="save_desig_step"><?php esc_html_e( "Add more !", 'employee-&-hr-management' ); ?></button>
                    <a href="<?php echo $this->get_next_step_link(); ?>" class="btn button-primary"  name=""><?php esc_html_e( "Next", 'employee-&-hr-management' ); ?></a>
                <?php } else { ?>
                    <button type="submit" class="btn btn-gradient-primary"  name="save_desig_step"><?php esc_html_e( "Create !", 'employee-&-hr-management' ); ?></button>
                <?php } ?>
			</p>
			<?php wp_nonce_field( 'ehrm-setup-wizard' ); ?>
		</form>
		<?php
	}

	/** setting step **/
	public function ehrm_setup_settings() {
		require_once( WL_EHRM_PLUGIN_DIR_PATH . '/admin/inc/helpers/wl-ehrm-helper.php' );
		$timezone_list    = EHRMHelperClass::timezone_list();
		$save_settings    = get_option( 'ehrm_settings_data' );
		$TimeZone         = isset( $save_settings['timezone'] ) ? sanitize_text_field( $save_settings['timezone'] ) : 'Asia/Kolkata';
		$date_format      = isset( $save_settings['date_format'] ) ? sanitize_text_field( $save_settings['date_format'] ) : 'F j Y';
		$time_format      = isset( $save_settings['time_format'] ) ? sanitize_text_field( $save_settings['time_format'] ) : 'g:i A';
		$monday_status    = isset( $save_settings['monday_status'] ) ? sanitize_text_field( $save_settings['monday_status'] ) : 'Working';
		$tuesday_status   = isset( $save_settings['tuesday_status'] ) ? sanitize_text_field( $save_settings['tuesday_status'] ) : 'Working';
		$wednesday_status = isset( $save_settings['wednesday_status'] ) ? sanitize_text_field( $save_settings['wednesday_status'] ) : 'Working';
		$thursday_status  = isset( $save_settings['thursday_status'] ) ? sanitize_text_field( $save_settings['thursday_status'] ) : 'Working';
		$friday_status    = isset( $save_settings['friday_status'] ) ? sanitize_text_field( $save_settings['friday_status'] ) : 'Working';
		$saturday_status  = isset( $save_settings['saturday_status'] ) ? sanitize_text_field( $save_settings['saturday_status'] ) : 'Working';
		$sunday_status    = isset( $save_settings['sunday_status'] ) ? sanitize_text_field( $save_settings['sunday_status'] ) : 'Off';
		$halfday_start    = isset( $save_settings['halfday_start'] ) ? sanitize_text_field( $save_settings['halfday_start'] ) : '';
		$halfday_end      = isset( $save_settings['halfday_end'] ) ? sanitize_text_field( $save_settings['halfday_end'] ) : '';
		$lunch_start      = isset( $save_settings['lunch_start'] ) ? sanitize_text_field( $save_settings['lunch_start'] ) : '';
		$lunch_end        = isset( $save_settings['lunch_end'] ) ? sanitize_text_field( $save_settings['lunch_end'] ) : '';
		$cur_symbol       = isset( $save_settings['cur_symbol'] ) ? sanitize_text_field( $save_settings['cur_symbol'] ) : '₹';
		$ehrm_gmap_api    = isset( $save_settings['ehrm_gmap_api'] ) ? sanitize_text_field( $save_settings['ehrm_gmap_api'] ) : '';
		$cur_position     = isset( $save_settings['cur_position'] ) ? sanitize_text_field( $save_settings['cur_position'] ) : 'Right';
		$salary_method    = isset( $save_settings['salary_method'] ) ? sanitize_text_field( $save_settings['salary_method'] ) : 'Monthly';
		$lunchtime        = isset( $save_settings['lunchtime'] ) ? sanitize_text_field( $save_settings['lunchtime'] ) : 'Include';
		$shoot_mail       = isset( $save_settings['shoot_mail'] ) ? sanitize_text_field( $save_settings['shoot_mail'] ) : 'Yes';
		$ip_restriction   = isset( $save_settings['ip_restriction'] ) ? sanitize_text_field( $save_settings['ip_restriction'] ) : 'No';
		$ip_rest_type     = isset( $save_settings['ip_rest_type'] ) ? sanitize_text_field( $save_settings['ip_rest_type'] ) : 'single';
		$restrict_ips     = isset( $save_settings['restrict_ips'] ) ? sanitize_text_field( $save_settings['restrict_ips'] ) : '';
		$show_holiday     = isset( $save_settings['show_holiday'] ) ? sanitize_text_field( $save_settings['show_holiday'] ) : 'Yes';
		$show_report      = isset( $save_settings['show_report'] ) ? sanitize_text_field( $save_settings['show_report'] ) : 'Yes';
		$show_notice      = isset( $save_settings['show_notice'] ) ? sanitize_text_field( $save_settings['show_notice'] ) : 'Yes';
		$late_reson       = isset( $save_settings['late_reson'] ) ? sanitize_text_field( $save_settings['late_reson'] ) : 'Yes';
		$salary_status    = isset( $save_settings['salary_status'] ) ? sanitize_text_field( $save_settings['salary_status'] ) : 'Yes';
		$show_projects    = isset( $save_settings['show_projects'] ) ? sanitize_text_field( $save_settings['show_projects'] ) : 'Yes';
		$geo_location     = isset( $save_settings['geo_location'] ) ? sanitize_text_field( $save_settings['geo_location'] ) : 'Yes';
		$user_roles       = isset( $save_settings['user_roles'] ) ? sanitize_text_field( $save_settings['user_roles'] ) : array('subscriber');
		$mail_logo        = isset( $save_settings['mail_logo'] ) ? sanitize_text_field( $save_settings['mail_logo'] ) : '';
		$office_in_sub    = isset( $save_settings['office_in_sub'] ) ? sanitize_text_field( $save_settings['office_in_sub'] ) : __( 'Login Alert From Employee & HR Management', 'employee-&-hr-management' );
		$office_out_sub   = isset( $save_settings['office_out_sub'] ) ? sanitize_text_field( $save_settings['office_out_sub'] ) : __( 'Logout Alert From Employee & HR Management', 'employee-&-hr-management' );
		$mail_heading     = isset( $save_settings['mail_heading'] ) ? sanitize_text_field( $save_settings['mail_heading'] ) : __( 'Staff Login/Logout Details', 'employee-&-hr-management' );

		?>
		<form method="post" class="designation-step" autocomplete="off">
			<p class="store-setup"><?php esc_html_e( 'General settings', 'employee-&-hr-management' ); ?></p>

			<div class="form-group row">
				<label class="col-sm-12 col-form-label"><?php esc_html_e('TimeZone', 'employee-&-hr-management'); ?></label>
				<div class="col-sm-12">
					<select class="form-control" id="timezone" name="timezone">
						<option value=""><?php esc_html_e('----------------------------------------------------------Select timezone----------------------------------------------------------', 'employee-&-hr-management'); ?></option>
					<?php foreach ( $timezone_list as $timezone ) { ?>
						<option value="<?php echo esc_attr( $timezone ); ?>" <?php selected( $TimeZone, $timezone ); ?>><?php echo esc_html( $timezone ); ?></option>
					<?php } ?>
					</select>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-6 col-md-12">
					<div class="form-group row">
						<label class="col-sm-12 col-form-label"><?php esc_html_e('Date Format', 'employee-&-hr-management'); ?></label>
						<div class="col-sm-12">
							<select class="form-control" id="date_format" name="date_format">
								<option value="F j Y" <?php selected( $date_format, 'F j Y' ); ?>><?php echo esc_html( date( 'F j Y' ) . ' ( F j Y ) '); ?></option>
								<option value="Y-m-d" <?php selected( $date_format, 'Y-m-d' ); ?>><?php echo esc_html( date( 'Y-m-d' ) . ' ( YYYY-MM-DD )'); ?></option>
								<option value="m/d/Y" <?php selected( $date_format, 'm/d/Y' ); ?>><?php echo esc_html( date( 'm/d/Y' ) . ' ( MM/DD/YYYY )'); ?></option>
								<option value="d-m-Y" <?php selected( $date_format, 'd-m-Y' ); ?>><?php echo esc_html( date( 'd-m-Y' ) . ' ( DD-MM-YYYY )'); ?></option>
								<option value="m-d-Y" <?php selected( $date_format, 'm-d-Y' ); ?>><?php echo esc_html( date( 'm-d-Y' ) . ' ( MM-DD-YYYY )'); ?></option>
								<option value="jS F Y" <?php selected( $date_format, 'jS F Y' ); ?>><?php echo esc_html( date( 'jS F Y' ) . ' ( d M YYYY )'); ?></option>
							</select>
						</div>
					</div>
				</div>
				<div class="col-lg-6 col-md-12">
					<div class="form-group row">
						<label class="col-sm-12 col-form-label"><?php esc_html_e('Time Format', 'employee-&-hr-management'); ?></label>
						<div class="col-sm-12">
							<select class="form-control" id="time_format" name="time_format">
								<option value="g:i a" <?php selected( $time_format, 'g:i a' ); ?>><?php echo esc_html( date( 'g:i a' ) . ' (  g:i a  )' ); ?></option>
								<option value="g:i A" <?php selected( $time_format, 'g:i A' ); ?>><?php echo esc_html( date( 'g:i A' ) . ' (  g:i A  )' ); ?></option>
								<option value="H:i" <?php selected( $time_format, 'H:i' ); ?>><?php echo esc_html( date( 'H:i' ) . ' (  H:i  )' ); ?></option>
								<option value="H:i:s" <?php selected( $time_format, 'H:i:s' ); ?>><?php echo esc_html( date( 'H:i:s' ) . ' (  H:i:s  )' ); ?></option>
							</select>
						</div>
					</div>
				</div>
			</div>
			<h4 class="card-title week_days"><?php esc_html_e( 'Week days status', 'hr-management-lite'); ?></h4>
              <div class="row">   
                <div class="col-lg-4 col-md-12">
                  <div class="form-group row">
                    <label class="col-sm-12 col-form-label"><?php esc_html_e( 'Monday', 'hr-management-lite'); ?></label>
                    <div class="col-sm-12">
                      <select class="form-control" id="monday_status" name="monday_status">
                        <option value="Working" <?php selected( $monday_status, 'Working' ); ?>><?php esc_html_e( 'Working', 'hr-management-lite' ); ?></option>
                        <option value="Half Day" <?php selected( $monday_status, 'Half Day' ); ?>><?php esc_html_e( 'Half Day', 'hr-management-lite' ); ?></option>
                        <option value="Off" <?php selected( $monday_status, 'Off' ); ?>><?php esc_html_e( 'Off', 'hr-management-lite' ); ?></option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="col-lg-4 col-md-12">
                  <div class="form-group row">
                    <label class="col-sm-12 col-form-label"><?php esc_html_e( 'Tuesday', 'hr-management-lite'); ?></label>
                    <div class="col-sm-12">
                      <select class="form-control" id="tuesday_status" name="tuesday_status">
                        <option value="Working" <?php selected( $tuesday_status, 'Working' ); ?>><?php esc_html_e( 'Working', 'hr-management-lite' ); ?></option>
                        <option value="Half Day" <?php selected( $tuesday_status, 'Half Day' ); ?>><?php esc_html_e( 'Half Day', 'hr-management-lite' ); ?></option>
                        <option value="Off" <?php selected( $tuesday_status, 'Off' ); ?>><?php esc_html_e( 'Off', 'hr-management-lite' ); ?></option>
                      </select>
                    </div>
                  </div>
                </div>
				<div class="col-lg-4 col-md-12">
                  <div class="form-group row">
                    <label class="col-sm-12 col-form-label"><?php esc_html_e( 'Wednesday', 'hr-management-lite'); ?></label>
                    <div class="col-sm-12">
                      <select class="form-control" id="wednesday_status" name="wednesday_status">
                        <option value="Working" <?php selected( $wednesday_status, 'Working' ); ?>><?php esc_html_e( 'Working', 'hr-management-lite' ); ?></option>
                        <option value="Half Day" <?php selected( $wednesday_status, 'Half Day' ); ?>><?php esc_html_e( 'Half Day', 'hr-management-lite' ); ?></option>
                        <option value="Off" <?php selected( $wednesday_status, 'Off' ); ?>><?php esc_html_e( 'Off', 'hr-management-lite' ); ?></option>
                      </select>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
			    <div class="col-lg-4 col-md-12">
                  <div class="form-group row">
                    <label class="col-sm-12 col-form-label"><?php esc_html_e( 'Thursday', 'hr-management-lite'); ?></label>
                    <div class="col-sm-12">
                      <select class="form-control" id="thursday_status" name="thursday_status">
                        <option value="Working" <?php selected( $thursday_status, 'Working' ); ?>><?php esc_html_e( 'Working', 'hr-management-lite' ); ?></option>
                        <option value="Half Day" <?php selected( $thursday_status, 'Half Day' ); ?>><?php esc_html_e( 'Half Day', 'hr-management-lite' ); ?></option>
                        <option value="Off" <?php selected( $thursday_status, 'Off' ); ?>><?php esc_html_e( 'Off', 'hr-management-lite' ); ?></option>
                      </select>
                    </div>
                  </div>
                </div>
			    <div class="col-lg-4 col-md-12">
                  <div class="form-group row">
                    <label class="col-sm-12 col-form-label"><?php esc_html_e( 'Friday', 'hr-management-lite'); ?></label>
                    <div class="col-sm-12">
                      <select class="form-control" id="friday_status" name="friday_status">
                        <option value="Working" <?php selected( $friday_status, 'Working' ); ?>><?php esc_html_e( 'Working', 'hr-management-lite' ); ?></option>
                        <option value="Half Day" <?php selected( $friday_status, 'Half Day' ); ?>><?php esc_html_e( 'Half Day', 'hr-management-lite' ); ?></option>
                        <option value="Off" <?php selected( $friday_status, 'Off' ); ?>><?php esc_html_e( 'Off', 'hr-management-lite' ); ?></option>
                      </select>
                    </div>
                  </div>
                </div>
			   	<div class="col-lg-4 col-md-12">
                  <div class="form-group row">
                    <label class="col-sm-12 col-form-label"><?php esc_html_e( 'Saturday', 'hr-management-lite'); ?></label>
                    <div class="col-sm-12">
                      <select class="form-control" id="saturday_status" name="saturday_status">
                        <option value="Working" <?php selected( $saturday_status, 'Working' ); ?>><?php esc_html_e( 'Working', 'hr-management-lite' ); ?></option>
                        <option value="Half Day" <?php selected( $saturday_status, 'Half Day' ); ?>><?php esc_html_e( 'Half Day', 'hr-management-lite' ); ?></option>
                        <option value="Off" <?php selected( $saturday_status, 'Off' ); ?>><?php esc_html_e( 'Off', 'hr-management-lite' ); ?></option>
                      </select>
                    </div>
                  </div>
                </div>  
            </div>
			<div class="row">
				<div class="col-lg-4 col-md-12">
                  <div class="form-group row">
                    <label class="col-sm-12 col-form-label"><?php esc_html_e( 'Sunday', 'hr-management-lite'); ?></label>
                    <div class="col-sm-12">
                      <select class="form-control" id="sunday_status" name="sunday_status">
                        <option value="Working" <?php selected( $sunday_status, 'Working' ); ?>><?php esc_html_e( 'Working', 'hr-management-lite' ); ?></option>
                        <option value="Half Day" <?php selected( $sunday_status, 'Half Day' ); ?>><?php esc_html_e( 'Half Day', 'hr-management-lite' ); ?></option>
                        <option value="Off" <?php selected( $sunday_status, 'Off' ); ?>><?php esc_html_e( 'Off', 'hr-management-lite' ); ?></option>
                      </select>
                    </div>
                  </div>
                </div>
			</div>
			<hr>
			<h4 class="card-title week_days"><?php esc_html_e( 'Half Day Timing', 'hr-management-lite'); ?></h4>
			<div class="row">
				<div class="col-lg-6 col-md-12">
					<div class="form-group row">
						<label class="col-sm-12 col-form-label"><?php esc_html_e( 'Halfday Start Time', 'employee-&-hr-management' ); ?></label>
						<div class="col-sm-12">
							<input type="text" name="halfday_start" id="halfday_start" class="form-control" placeholder="<?php esc_html_e( 'Format:- 10:00 AM', 'employee-&-hr-management' ); ?>" data-toggle="datetimepicker" data-target="#halfday_start" value="<?php echo esc_attr( $halfday_start ); ?>">
						</div>
					</div>
				</div>
				<div class="col-lg-6 col-md-12">
					<div class="form-group row">
						<label class="col-sm-12 col-form-label"><?php esc_html_e('Halfday End Time', 'employee-&-hr-management'); ?></label>
						<div class="col-sm-12">
							<input type="text" name="halfday_end" id="halfday_end" class="form-control" placeholder="<?php esc_html_e('Format:- 03:00 PM', 'employee-&-hr-management'); ?>" data-toggle="datetimepicker" data-target="#halfday_end" value="<?php echo esc_attr($halfday_end); ?>">
						</div>
					</div>
				</div>
			</div>
			<hr>
			<h4 class="card-title week_days"><?php esc_html_e( 'Lunch Timing', 'hr-management-lite'); ?></h4>
			<div class="row">
				<div class="col-lg-6 col-md-12">
					<div class="form-group row">
						<label class="col-sm-12 col-form-label"><?php esc_html_e('Lunch Start Time', 'employee-&-hr-management'); ?></label>
						<div class="col-sm-12">
							<input type="text" name="lunch_start" id="lunch_start" class="form-control" placeholder="<?php esc_html_e('Format:- 02:00 PM', 'employee-&-hr-management'); ?>" data-toggle="datetimepicker" data-target="#lunch_start" value="<?php echo esc_attr($lunch_start); ?>">
						</div>
					</div>
				</div>
				<div class="col-lg-6 col-md-12">
					<div class="form-group row">
						<label class="col-sm-12 col-form-label"><?php esc_html_e('Lunch End Time', 'employee-&-hr-management'); ?></label>
						<div class="col-sm-12">
							<input type="text" name="lunch_end" id="lunch_end" class="form-control" placeholder="<?php esc_html_e('Format:- 02:30 PM', 'employee-&-hr-management'); ?>" data-toggle="datetimepicker" data-target="#lunch_end" value="<?php echo esc_attr($lunch_end); ?>">
						</div>
					</div>
				</div>
			</div>
			<hr>
			<h4 class="card-title week_days"><?php esc_html_e( 'Currency & Salary settings', 'hr-management-lite'); ?></h4>
			<div class="row">
				<div class="col-lg-6 col-md-12">
					<div class="form-group row">
						<label class="col-sm-12 col-form-label"><?php esc_html_e( 'Currency Symbol', 'employee-&-hr-management' ); ?></label>
						<div class="col-sm-12">
							<input type="text" class="form-control" placeholder="$" id="currency_symbol" name="currency_symbol" value="<?php echo esc_attr( $cur_symbol ); ?>">
						</div>
					</div>
				</div>
				<div class="col-lg-6 col-md-12">
					<div class="form-group row">
						<label class="col-sm-12 col-form-label"><?php esc_html_e( 'Currency Position', 'employee-&-hr-management' ); ?></label>
						<div class="col-sm-12">
							<select class="form-control" id="currency_position" name="currency_position">
							<option value="Right" <?php selected( $cur_position, 'Right' ); ?>><?php esc_html_e( 'Right', 'employee-&-hr-management' ); ?></option>
							<option value="Left" <?php selected( $cur_position, 'Left' ); ?>><?php esc_html_e( 'Left', 'employee-&-hr-management' ); ?></option>
							</select>
						</div>
					</div>
				</div>
			</div>
			<div class="form-group row">
				<label class="col-sm-6 col-form-label"><?php esc_html_e( 'Salary paid by', 'employee-&-hr-management' ); ?></label>
				<div class="col-sm-3">
					<div class="form-check form-check-success">
					<label class="form-check-label">
						<input type="radio" class="form-check-input" name="salary_method" value="Monthly" checked="" <?php checked( $salary_method, 'Monthly' ); ?>>
						<?php esc_html_e( 'Monthly', 'employee-&-hr-management' ); ?>
						<i class="input-helper"></i></label>
					</div>
				</div>
				<div class="col-sm-3">
					<div class="form-check form-check-success">
					<label class="form-check-label">
						<input type="radio" class="form-check-input" name="salary_method" value="Hourly" <?php checked( $salary_method, 'Hourly' ); ?>>
						<?php esc_html_e( 'Hourly', 'employee-&-hr-management' ); ?>
						<i class="input-helper"></i></label>
					</div>
				</div>
			</div>
			<div class="form-group row">
				<label class="col-sm-6 col-form-label"><?php esc_html_e('Include/Exclude Lunch time from Working Hours', 'employee-&-hr-management'); ?></label>
				<div class="col-sm-3">
					<div class="form-check form-check-success">
					<label class="form-check-label">
						<input type="radio" class="form-check-input" name="lunch_time_status" value="Include" checked="" <?php checked($lunchtime, 'Include'); ?>>
						<?php esc_html_e('Include', 'employee-&-hr-management'); ?>
						<i class="input-helper"></i></label>
					</div>
				</div>
				<div class="col-sm-3">
					<div class="form-check form-check-danger">
					<label class="form-check-label">
						<input type="radio" class="form-check-input" name="lunch_time_status" value="Exclude" <?php checked($lunchtime, 'Exclude'); ?>>
						<?php esc_html_e('Exclude', 'employee-&-hr-management'); ?>
						<i class="input-helper"></i></label>
					</div>
				</div>
			</div>
			<hr>
			<h4 class="card-title week_days"><?php esc_html_e( 'Geo location', 'hr-management-lite'); ?></h4>
			<div class="form-group row">
				<label class="col-sm-6 col-form-label"><?php esc_html_e( 'GEO Location enable', 'employee-&-hr-management' ); ?></label>
				<div class="col-sm-3">
					<div class="form-check form-check-success">
					<label class="form-check-label">
						<input type="radio" class="form-check-input" name="geo_location" value="Yes" checked="" <?php checked( $geo_location, 'Yes' ); ?>>
						<?php esc_html_e( 'Yes', 'employee-&-hr-management' ); ?>
						<i class="input-helper"></i></label>
					</div>
				</div>
				<div class="col-sm-3">
					<div class="form-check form-check-danger">
					<label class="form-check-label">
						<input type="radio" class="form-check-input" name="geo_location" value="No" <?php checked( $geo_location, 'No' ); ?>>
						<?php esc_html_e( 'No', 'employee-&-hr-management' ); ?>
						<i class="input-helper"></i></label>
					</div>
				</div>
			</div>
			<div class="form-group row">
				<label class="col-sm-3 col-form-label"><?php esc_html_e( 'Google API Key', 'employee-&-hr-management' ); ?></label>
				<div class="col-sm-9">
					<input type="text" class="form-control" placeholder="<?php esc_html_e( 'Enter API key', 'employee-&-hr-management' ); ?>" id="ehrm_gmap_api" name="ehrm_gmap_api" value="<?php echo esc_attr( $ehrm_gmap_api ); ?>">
				</div>
			</div>
			<hr>
			<h4 class="card-title week_days"><?php esc_html_e( 'Employee roles', 'hr-management-lite'); ?></h4>
			<div class="form-group row">
				<label class="col-sm-3 col-form-label"><?php esc_html_e('Select roles for staff\'s.', 'employee-&-hr-management'); ?></label>
				<?php if ( ! empty( $save_settings['user_roles'] ) ) {
					$user_roles = unserialize( $save_settings['user_roles'] );
				} else {
					$user_roles = array();
				}
				?>
				<div class="col-sm-3">
					<div class="form-check form-check-success">
					<label class="form-check-label">
						<input type="checkbox" class="form-check-input" <?php if ( is_array( $user_roles ) ) { if ( in_array( 'subscriber', $user_roles ) ) { echo 'checked'; } } ?> name="user_roles[]" value="subscriber">
						<?php esc_html_e( 'Subscriber', 'employee-&-hr-management' ); ?>
						<i class="input-helper"></i></label>
					</div>
					<div class="form-check form-check-success">
					<label class="form-check-label">
						<input type="checkbox" class="form-check-input" name="user_roles[]" value="contributor" <?php if ( is_array( $user_roles ) ) { if ( in_array( 'contributor', $user_roles ) ) { echo 'checked'; } } ?>>
						<?php esc_html_e( 'Contributor', 'employee-&-hr-management' ); ?>
						<i class="input-helper"></i></label>
					</div>
				</div>
				<div class="col-sm-3">
					<div class="form-check form-check-success">
					<label class="form-check-label">
						<input type="checkbox" class="form-check-input" name="user_roles[]" value="author" <?php if ( is_array( $user_roles ) ) { if ( in_array( 'author', $user_roles ) ) { echo 'checked'; } } ?>>
						<?php esc_html_e( 'Author', 'employee-&-hr-management' ); ?>
						<i class="input-helper"></i></label>
					</div>
					<div class="form-check form-check-success">
					<label class="form-check-label">
						<input type="checkbox" class="form-check-input" name="user_roles[]" value="editor" <?php if ( is_array( $user_roles ) ) { if ( in_array( 'editor', $user_roles ) ) { echo 'checked'; } } ?>>
						<?php esc_html_e( 'Editor', 'employee-&-hr-management' ); ?>
						<i class="input-helper"></i></label>
					</div>
				</div>
				<span class="option-info-text">
					<i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
					<?php esc_html_e( 'Staff\'s login dashboard shows only for selected user roles.', 'employee-&-hr-management' ); ?>
				</span>
			</div>
			<hr>
			<p class="ehrm-setup-actions step">
				<button type="submit" class="button-primary button button-large button-next" value="<?php esc_attr_e( "Next", 'employee-&-hr-management' ); ?>" name="save_step"><?php esc_html_e( "Next", 'employee-&-hr-management' ); ?></button>
			</p>
			<?php wp_nonce_field( 'ehrm-setup-wizard' ); ?>
		</form>
		<?php
	}

	/** setings save step **/
	public function ehrm_setup_settings_save() {
		check_admin_referer( 'ehrm-setup-wizard' );

		$timezone         = isset( $_POST['timezone'] ) ? sanitize_text_field( $_POST['timezone'] ) : 'Asia/Kolkata';
		$date_format      = isset( $_POST['date_format'] ) ? sanitize_text_field( $_POST['date_format'] ) : 'F j Y';
		$time_format      = isset( $_POST['time_format'] ) ? sanitize_text_field( $_POST['time_format'] ) : 'g:i A';
		$monday_status    = isset( $_POST['monday_status'] ) ? sanitize_text_field( $_POST['monday_status'] ) : 'Working';
		$tuesday_status   = isset( $_POST['tuesday_status'] ) ? sanitize_text_field( $_POST['tuesday_status'] ) : 'Working';
		$wednesday_status = isset( $_POST['wednesday_status'] ) ? sanitize_text_field( $_POST['wednesday_status'] ) : 'Working';
		$thursday_status  = isset( $_POST['thursday_status'] ) ? sanitize_text_field( $_POST['thursday_status'] ) : 'Working';
		$friday_status    = isset( $_POST['friday_status'] ) ? sanitize_text_field( $_POST['friday_status'] ) : 'Working';
		$saturday_status  = isset( $_POST['saturday_status'] ) ? sanitize_text_field( $_POST['saturday_status'] ) : 'Working';
		$sunday_status    = isset( $_POST['sunday_status'] ) ? sanitize_text_field( $_POST['sunday_status'] ) : 'Off';
		$halfday_start    = isset( $_POST['halfday_start'] ) ? sanitize_text_field( $_POST['halfday_start'] ) : '';
		$halfday_end      = isset( $_POST['halfday_end'] ) ? sanitize_text_field( $_POST['halfday_end'] ) : '';
		$lunch_start      = isset( $_POST['lunch_start'] ) ? sanitize_text_field( $_POST['lunch_start'] ) : '';
		$lunch_end        = isset( $_POST['lunch_end'] ) ? sanitize_text_field( $_POST['lunch_end'] ) : '';
		$cur_symbol       = isset( $_POST['currency_symbol'] ) ? sanitize_text_field( $_POST['currency_symbol'] ) : '₹';
		$ehrm_gmap_api    = isset( $_POST['ehrm_gmap_api'] ) ? sanitize_text_field( $_POST['ehrm_gmap_api'] ) : '';
		$cur_position     = isset( $_POST['currency_position'] ) ? sanitize_text_field( $_POST['currency_position'] ) : 'Right';
		$salary_method    = isset( $_POST['salary_method'] ) ? sanitize_text_field( $_POST['salary_method'] ) : 'Monthly';
		$lunchtime        = isset( $_POST['lunch_time_status'] ) ? sanitize_text_field( $_POST['lunch_time_status'] ) : 'Include';
		$shoot_mail       = isset( $_POST['shoot_mail'] ) ? sanitize_text_field( $_POST['shoot_mail'] ) : 'Yes';
		$ip_restriction   = isset( $_POST['ip_restriction'] ) ? sanitize_text_field( $_POST['ip_restriction'] ) : 'No';
		$ip_rest_type     = isset( $_POST['ip_rest_type'] ) ? sanitize_text_field( $_POST['ip_rest_type'] ) : 'single';
		$restrict_ips     = isset( $_POST['restrict_ips'] ) ? sanitize_text_field( $_POST['restrict_ips'] ) : '';
		$show_holiday     = isset( $_POST['show_holiday'] ) ? sanitize_text_field( $_POST['show_holiday'] ) : 'Yes';
		$show_report      = isset( $_POST['report_submission'] ) ? sanitize_text_field( $_POST['report_submission'] ) : 'Yes';
		$show_notice      = isset( $_POST['show_notice'] ) ? sanitize_text_field( $_POST['show_notice'] ) : 'Yes';
		$late_reson       = isset( $_POST['late_reson'] ) ? sanitize_text_field( $_POST['late_reson'] ) : 'Yes';
		$salary_status    = isset( $_POST['salary_status'] ) ? sanitize_text_field( $_POST['salary_status'] ) : 'Yes';
		$show_projects    = isset( $_POST['show_projects'] ) ? sanitize_text_field( $_POST['show_projects'] ) : 'Yes';
		$geo_location     = isset( $_POST['geo_location'] ) ? sanitize_text_field( $_POST['geo_location'] ) : 'Yes';
		$user_roles       = isset( $_POST['user_roles'] ) ? ( $_POST['user_roles'] ) : '';
		$mail_logo        = isset( $_POST['mail_logo'] ) ? sanitize_text_field( $_POST['mail_logo'] ) : '';
		$office_in_sub    = isset( $_POST['office_in_sub'] ) ? sanitize_text_field( $_POST['office_in_sub'] ) : __( 'Login Alert From Employee & HR Management', 'employee-&-hr-management' );
		$office_out_sub   = isset( $_POST['office_out_sub'] ) ? sanitize_text_field( $_POST['office_out_sub'] ) : __( 'Logout Alert From Employee & HR Management', 'employee-&-hr-management' );
		$mail_heading     = isset( $_POST['mail_heading'] ) ? sanitize_text_field( $_POST['mail_heading'] ) : __( 'Staff Login/Logout Details', 'employee-&-hr-management' );

		$ehrm_settings_data = array(
			'timezone'         => $timezone,
			'date_format'      => $date_format,
			'time_format'      => $time_format,
			'monday_status'    => $monday_status,
			'tuesday_status'   => $tuesday_status,
			'wednesday_status' => $wednesday_status,
			'thursday_status'  => $thursday_status,
			'friday_status'    => $friday_status,
			'saturday_status'  => $saturday_status,
			'sunday_status'    => $sunday_status,
			'halfday_start'    => $halfday_start,
			'halfday_end'      => $halfday_end,
			'lunch_start'      => $lunch_start,
			'lunch_end'        => $lunch_end,
			'cur_symbol'       => $cur_symbol,
			'cur_position'     => $cur_position,
			'ehrm_gmap_api'    => $ehrm_gmap_api,
			'salary_method'    => $salary_method,
			'lunchtime'        => $lunchtime,
			'shoot_mail'       => $shoot_mail,
			'ip_restriction'   => $ip_restriction,
			'ip_rest_type'     => $ip_rest_type,
			'restrict_ips'     => $restrict_ips,
			'show_holiday'     => $show_holiday,
			'show_report'      => $show_report,
			'show_notice'      => $show_notice,
			'late_reson'       => $late_reson,
			'salary_status'    => $salary_status,
			'show_projects'    => $show_projects,
			'mail_logo'        => $mail_logo,
			'office_in_sub'    => $office_in_sub,
			'office_out_sub'   => $office_out_sub,
			'mail_heading'     => $mail_heading,
			'geo_location'     => $geo_location,
			'user_roles'       => serialize( $user_roles ),
		);

		update_option( 'ehrm_settings_data', $ehrm_settings_data );
		wp_safe_redirect( esc_url_raw( $this->get_next_step_link() ) );
		exit;
	}
    
    /** Final step **/
	public function ehrm_setup_ready() {
		?>
		<div class="final-setup text-center">
			<h3 class="main-heading text-center">You're ready to start!</h3>
			<h4 class="sub-heading text-center">All configurations are done..!! Now you just need to add your staff into system</h4>
			<a href="<?php echo admin_url( '/admin.php?page=employee-and-hr-management-staff/' ); ?>" class="btn btn-success final-step_btn"> Add staff</a>
		</div>
		<?php
	}
}

new EHRM_AdminSetupWizard();