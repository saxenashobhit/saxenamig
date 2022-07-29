<?php
defined( 'ABSPATH' ) or die();
require_once( WL_EHRM_PLUGIN_DIR_PATH . '/admin/inc/helpers/wl-ehrm-helper.php' );

/* Fetching Location Post(Shortcode) Id */
extract( shortcode_atts( array( ), $attr ) );

$save_settings = get_option( 'ehrm_settings_data' );
if ( ! empty ( $save_settings ) ) {
    date_default_timezone_set( EHRMHelperClass::get_setting_timezone() );
    $current_time = date( "H:i:s" );
    if ( $current_time < '12:00:00' ) {
        $greetings = esc_html__('Good Morning, ', 'employee-&-hr-management' );
    }
    if ( $current_time > '12:00:00' && $current_time < '17:00:00') {
        $greetings = esc_html__('Good Afternoon, ', 'employee-&-hr-management' );
    }
    if ( $current_time > '17:00:00' && $current_time < '21:00:00') {
        $greetings = esc_html__('Good Evening, ', 'employee-&-hr-management' );
    }
    if ( $current_time > '21:00:00' && $current_time < '04:00:00') {
        $greetings = esc_html__('Good Night, ', 'employee-&-hr-management' );
    }
}
if( is_user_logged_in() ) {
?>
<div class="wl_ehrm container">
    <div class="login-form">
        <form action="" method="post">
            <div class="avatar">
                <img src="<?php echo esc_url( get_avatar_url( get_current_user_id() ) ); ?>" alt="Avatar">
            </div>
            <h2 class="text-center"><?php esc_html_e( 'Staff Attendance', 'employee-&-hr-management' ); ?></h2>

            <h3 class="text-center"><?php esc_html_e( $greetings, 'employee-&-hr-management' ); echo esc_html( EHRMHelperClass::get_current_user_data( get_current_user_id(), 'Fullname' ) ); ?></h3>
            
            <div class="current_time_clock">
                <div class="card bg-dark text-white">
                    <h3 class="card-title text-center">
                        <div class="d-flex flex-wrap justify-content-center mt-2">
                            <a><span class="badge hours"></span></a> :
                            <a><span class="badge min"></span></a> :
                            <a><span class="badge sec"></span></a>
                        </div>
                    </h3>
                </div>
            </div>

            <div id="ehrm-login-portal">
                <?php 
                    $attendences  = get_option( 'ehrm_staff_attendence_data' );
                    $user_id      = get_current_user_id();
                    $html         = '';
                    $current_date = date( 'Y-m-d' );

                    if ( ! empty ( $attendences ) ) {
						
                        foreach ( $attendences as $key => $attendence ) {							
                            if ( $attendence['date'] == $current_date && $attendence['staff_id'] == $user_id && ! empty ( $attendence['office_in'] ) && $attendence['late'] == 'Late' ) {
								echo '<h3 class="">'.esc_html__( 'Your Office In Time is', 'employee-&-hr-management' ).' '.esc_html( date( EHRMHelperClass::get_time_format(), strtotime( $attendence['office_in'] ) ) ).'</h3>';

                            } elseif ( $attendence['date'] == $current_date && $attendence['staff_id'] == $user_id && ! empty ( $attendence['office_in'] ) && $attendence['late'] != "Late" ) {
                                echo '<h3 class="">'.esc_html__( 'You are late today!', 'employee-&-hr-management' ).'</strong> '.esc_html__( 'Your Office In Time is', 'employee-&-hr-management' ).' '.esc_html( date( EHRMHelperClass::get_time_format(), strtotime( $attendence['office_in'] ) ) ).'</h3>';
                            }
            
                            if ( $attendence['date'] == $current_date && $attendence['staff_id'] == $user_id && ! empty ( $attendence['office_in'] ) && ! empty ( $attendence['lunch_in'] ) ) {
                                echo '<h3 class="">'.esc_html__( 'Success!', 'employee-&-hr-management' ).'</strong> '.esc_html__( 'Your Lunch In Time is', 'employee-&-hr-management' ).' '.esc_html( date( EHRMHelperClass::get_time_format(), strtotime( $attendence['lunch_in'] ) ) ).'</h3>';
                            }
            
                            if ( $attendence['date'] == $current_date && $attendence['staff_id'] == $user_id && ! empty ( $attendence['office_in'] ) && ! empty ( $attendence['lunch_out'] ) ) {
                                echo '<h3 class="">'.esc_html__( 'Success!', 'employee-&-hr-management' ).'</strong> '.esc_html__( 'Your Lunch Out Time is', 'employee-&-hr-management' ).' '.esc_html( date( EHRMHelperClass::get_time_format(), strtotime( $attendence['lunch_out'] ) ) ).'</h3>';
                            }
            
                            if ( $attendence['date'] == $current_date && $attendence['staff_id'] == $user_id && ! empty ( $attendence['office_in'] ) && ! empty ( $attendence['office_out'] ) ) {
                                echo '<h3 class="">'.esc_html__( 'Success!', 'employee-&-hr-management' ).'</strong> '.esc_html__( 'Your Office Out Time is', 'employee-&-hr-management' ).' '.esc_html( date( EHRMHelperClass::get_time_format(), strtotime( $attendence['office_out'] ) ) ).'</h3>';
                            }
            
                        }
                    }
                ?>
            </div>

            <?php echo wp_kses_post( EHRMHelperClass::frondent_login_portal() ); ?>
        </form>
    </div>

    <!-- Late Reson Modal -->
    <div class="modal fade" id="LateReson" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-notify modal-info">
        <div class="modal-content">
          <div class="card">
            <div class="card-body">
              <h4 class="card-title"><?php esc_html_e( 'Submit you reson', 'employee-&-hr-management' ); ?></h4>
              <form class="forms-sample" method="post" id="late_reson_form">
                <div class="form-group">
                  <label for="late_resonn"><?php esc_html_e( 'Enter your reson to come late today', 'employee-&-hr-management' ); ?></label>
                  <textarea class="form-control" rows="6" id="late_resonn" name="late_resonn" placeholder="<?php esc_html_e( 'Content....', 'employee-&-hr-management' ); ?>"></textarea>
                </div>
                <input type="hidden" name="staff_id" id="staff_id" value="<?php echo esc_attr( get_current_user_id() ); ?>">
                <input type="button" class="btn btn-gradient-primary mr-2" id="late_reson_submit_btn" value="<?php esc_html_e( 'Submit', 'employee-&-hr-management' ); ?>">
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Daily Report Modal -->
    <div class="modal fade" id="DailyReport" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-notify modal-info">
        <div class="modal-content">
          <div class="card">
            <div class="card-body">
              <h4 class="card-title"><?php esc_html_e( 'Daily Report', 'employee-&-hr-management' ); ?></h4>
              <form class="forms-sample" method="post" id="daily_report_form">
                <div class="form-group">
                  <label for="daily_report"><?php esc_html_e( 'Submit your daily report', 'employee-&-hr-management' ); ?></label>
                  <textarea class="form-control" rows="6" id="daily_report" name="daily_report" placeholder="<?php esc_html_e( 'Content....', 'employee-&-hr-management' ); ?>"></textarea>
                </div>
                <input type="hidden" name="user_id" id="user_id" value="<?php echo esc_attr( get_current_user_id() ); ?>">
                <input type="button" class="btn btn-gradient-primary mr-2" id="daily_report_btn" value="<?php esc_html_e( 'Submit', 'employee-&-hr-management' ); ?>">
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>

</div>
<?php
} else {
  $link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 
				"https" : "http") . "://" . $_SERVER['HTTP_HOST'] .  
				$_SERVER['REQUEST_URI']; 

				$args = array(
					'echo'           => true,
					'redirect'       => $link, 
					'form_id'        => 'loginform',
					'label_username' => '',
					'label_password' => '',
					'label_remember' => __( 'Remember Me' ),
					'label_log_in'   => __( 'Login' ),
					'id_username'    => 'user_login',
					'id_password'    => 'user_pass',
					'id_remember'    => 'rememberme',
					'id_submit'      => 'wp-submit',
					'remember'       => true,
					'value_username' => NULL,
					'value_remember' => true
				); 
  wp_login_form( $args );
}