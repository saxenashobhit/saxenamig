<?php
defined( 'ABSPATH' ) or die();
require_once( WL_EHRM_PLUGIN_DIR_PATH . '/admin/inc/helpers/wl-ehrm-helper.php' );
$save_settings = get_option( 'ehrm_settings_data' );
?>
<!-- partial -->
<div class="main-panel main-dashboard staff-dashboard">
  <div class="content-wrapper">
    <div class="page-header">
      <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white mr-2">
          <i class="mdi mdi-home"></i>
        </span>
        <?php esc_html_e( 'Dashboard', 'employee-&-hr-management' ); ?>
      </h3>
      <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
          <li class="breadcrumb-item active" aria-current="page">
            <span></span><?php esc_html_e( 'Overview', 'employee-&-hr-management' ); ?>
            <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
          </li>
        </ul>
      </nav>
    </div>
    <div class="row"> 
      <div class="col-md-3 stretch-card grid-margin">
        <div class="card bg-gradient-danger card-img-holder text-white">
          <div class="card-body">
            <img src="<?php echo WL_EHRM_PLUGIN_URL; ?>assets/images/circle.svg" class="card-img-absolute" alt="circle-image" />
            <div class="row">
              <div class="col-md-9">
                <h4 class="font-weight-normal mb-3"><?php echo esc_html( EHRMHelperClass::staff_greeting_status() ); ?></h4>
                <h2 class="mb-5"><?php echo esc_html( EHRMHelperClass::get_current_user_data( get_current_user_id(), 'fullname' ) ); ?></h2>
              </div>
              <div class="col-md-1 gravtar_ehrm">
                <?php echo wp_kses_post( get_avatar( EHRMHelperClass::get_current_user_data( get_current_user_id(), 'user_email' ), 70 ) ); ?>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-3 stretch-card grid-margin">
        <div class="card bg-gradient-info card-img-holder text-white">
          <div class="card-body">
            <img src="<?php echo WL_EHRM_PLUGIN_URL; ?>assets/images/circle.svg" class="card-img-absolute" alt="circle-image" />
            <h4 class="font-weight-normal mb-3"><?php esc_html_e( 'Last Day Working Hours', 'employee-&-hr-management' ); ?>
              <i class="mdi mdi-av-timer mdi-24px float-right"></i>
            </h4>
            <h2 class="mb-5"><?php echo esc_html( EHRMHelperClass::ehrm_last_day_working_hour() ); ?></h2>
          </div>
        </div>
      </div>
      <div class="col-md-3 stretch-card grid-margin">
        <div class="card bg-gradient-success card-img-holder text-white">
          <div class="card-body">
            <img src="<?php echo WL_EHRM_PLUGIN_URL; ?>assets/images/circle.svg" class="card-img-absolute" alt="circle-image" />
            <h4 class="font-weight-normal mb-3"><?php esc_html_e('Total Attendance', 'employee-&-hr-management'); ?>
              <i class="mdi mdi-bookmark-plus-outline mdi-24px float-right"></i>
            </h4>
            <h2 class="mb-5"><?php echo esc_html(EHRMHelperClass::ehrm_total_attendance()); ?></h2>
          </div>
        </div>
      </div>
      <div class="col-md-3 stretch-card grid-margin absent">
        <div class="card bg-gradient-primary card-img-holder text-white">
          <div class="card-body">
            <img src="<?php echo WL_EHRM_PLUGIN_URL; ?>assets/images/circle.svg" class="card-img-absolute" alt="circle-image" />
            <h4 class="font-weight-normal mb-3"><?php esc_html_e('Total Absent', 'employee-&-hr-management'); ?>
              <i class="mdi mdi-bookmark-remove mdi-24px float-right"></i>
            </h4>
            <h2 class="mb-5">
              <?php
              $absent_data  = EHRMHelperClass::ehrm_total_absents();
              $absent_dates = $absent_data['dates2'];
              $absent_dates = implode( ", ", $absent_dates );
              ?>
              <a href="#" data-toggle="tooltip" data-placement="right" title="<?php echo esc_html( $absent_dates ); ?>"><?php echo esc_html( $absent_data['days'] ); ?></a>
            </h2>
          </div>
        </div>
      </div>
    </div>

    <div class="row ehrm_clock_div">
      <div class="col-md-9" id="ehrm_clock_btns">
        <nav aria-label="breadcrumb staff_dashboard_btns">
          <ul class="breadcrumb">
            <?php echo wp_kses_post( EHRMHelperClass::ehrm_staff_action_clock_buttons() ); ?>
          </ul>
        </nav>
      </div>
      <!-- Clock -->
      <div class="col-md-3 bootstrap-clock-div">
        <!-- <div class="ehrm_clock" id="ehrm_clock"> -->
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
          <!-- </div> -->
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title"><?php esc_html_e( 'Activitys', 'employee-&-hr-management' ); ?></h4>
            <div class="usser_activity_alerts">
              <?php echo wp_kses_post( EHRMHelperClass::ehrm_staff_action_activity() ); ?>
            </div>
          </div>
        </div>
      </div>
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
                  <textarea class="form-control" rows="6" id="late_resonn" name="late_resonn" placeholder="<?php esc_html_e('Content....', 'employee-&-hr-management'); ?>"></textarea>
                </div>
                <input type="hidden" name="staff_id" id="staff_id" value="<?php echo esc_attr(get_current_user_id()); ?>">
                <input type="button" class="btn btn-gradient-primary mr-2" id="late_reson_submit_btn" value="<?php esc_html_e('Submit', 'employee-&-hr-management'); ?>">
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
</div>


<div class="detail-panel extra-data-panel">
  <div class="content-wrapper">
    <div class="row">
      <div class="col-lg-4 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title"><?php esc_html_e( 'Notice\'s', 'employee-&-hr-management' ); ?></h4>
            <div class="table-responsive">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th><?php esc_html_e( 'Title', 'employee-&-hr-management' ); ?></th>
                    <th><?php esc_html_e( 'Description', 'employee-&-hr-management' ); ?></th>
                  </tr>
                </thead>
                <tbody>
                  <?php echo wp_kses_post( EHRMHelperClass::ehrm_display_notices() ); ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-4 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title"><?php esc_html_e( 'Upcoming Event\'s', 'employee-&-hr-management' ); ?></h4>
            <div class="table-responsive">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th><?php esc_html_e( 'Title', 'employee-&-hr-management' ); ?></th>
                    <th><?php esc_html_e( 'Date', 'employee-&-hr-management' ); ?></th>
                  </tr>
                </thead>
                <tbody>
                  <?php echo wp_kses_post( EHRMHelperClass::ehrm_display_events() ); ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-4 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title"><?php esc_html_e( 'Upcoming Holiday\'s', 'employee-&-hr-management' ); ?></h4>
            <div class="table-responsive">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th><?php esc_html_e( 'Name', 'employee-&-hr-management' ); ?></th>
                    <th><?php esc_html_e( 'Date', 'employee-&-hr-management' ); ?></th>
                    <th><?php esc_html_e( 'Days', 'employee-&-hr-management' ); ?></th>
                  </tr>
                </thead>
                <tbody>
                  <?php echo wp_kses_post( EHRMHelperClass::ehrm_display_holidays() ); ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="detail-panel extra-data-panel second-level-panel">
  <div class="content-wrapper">
    <div class="row">
      <?php if ( ! empty ( $save_settings['salary_status'] ) && $save_settings['salary_status'] == 'Yes' ) { 
          $project_data_for_user = EHRMHelperClass::ehrm_project_based_user_info(get_current_user_id());
          $no_assigned_projects     = 0;
          $no_of_projects_completed = 0;
          if(  isset( $project_data_for_user ) && is_array( $project_data_for_user ) ) {
            $no_assigned_projects     = $project_data_for_user[0];
            $no_of_projects_completed = $project_data_for_user[1];
          }
      ?>
      <div class="col-lg-5 grid-margin stretch-card">
        <div class="card">
          <div class="card-body salary_status_ul">
            <h4 class="card-title"><?php esc_html_e( 'Salary status', 'employee-&-hr-management' ); ?></h4>
            <?php 
              if( $no_of_projects_completed > 0 ) {
                ?>
                  <!-- <h6 class=""><?php esc_html_e( 'Salary Projectwise', 'employee-&-hr-management' ); ?></h6> -->
                  <ul class="nav nav-pills">
                    <li class="nav-item">
                      <a class="nav-link active" data-toggle="pill" href="#"><?php esc_html_e( 'Salary Projectwise', 'employee-&-hr-management' ); ?></a>
                    </li>                  
                  </ul>
                   <div class="table-responsive salary_table_one">
                    <table class="table table-striped">
                      <thead class="thead-dark">
                        <tr>
                          <th>
                          <?php esc_html_e( 'S.No.', 'employee-&-hr-management' ); ?>
                          </th>
                          <th>
                            <?php esc_html_e( 'Assigned Project', 'employee-&-hr-management' ); ?>
                          </th>
                          <th>
                            <?php esc_html_e( 'Project cost', 'employee-&-hr-management' ); ?>
                          </th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php 
                          echo wp_kses_post(EHRMHelperClass::ehrm_project_based_salary_status( get_current_user_id() ));
                        ?>
                      </tbody>
                    </table>
                   </div>
                <?php
              } else {
                ?>
                <!-- Nav pills -->
                <ul class="nav nav-pills">
                  <li class="nav-item">
                    <a class="nav-link active" data-toggle="pill" href="#home"><?php esc_html_e( 'Exact', 'employee-&-hr-management' ); ?></a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" data-toggle="pill" href="#menu1"><?php esc_html_e( 'Estimated', 'employee-&-hr-management' ); ?></a>
                  </li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                  <div class="tab-pane container active" id="home">
                    <div class="table-responsive salary_table_one">
                      <table class="table table-striped">
                        <tbody>
                          <?php if ( isset( $save_settings['salary_method'] ) ) {
                            echo wp_kses_post( EHRMHelperClass::ehrm_exact_salary_status( date('Y-m-01'), date('Y-m-t'), $save_settings['salary_method'], get_current_user_id() ) );
                          }
                          ?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                  <div class="tab-pane container fade" id="menu1">
                    <div class="table-responsive salary_table_one">
                      <table class="table table-striped">
                        <tbody>
                          <?php if ( isset( $save_settings['salary_method'] ) ) {
                            echo wp_kses_post( EHRMHelperClass::ehrm_estimate_salary_status( date('Y-m-01'), date('Y-m-t'), $save_settings['salary_method'], get_current_user_id() ) ); } ?>
                        </tbody>
                      </table>
                    </div>                
                  </div>
                </div>
                <?php
              }
            ?>
          </div>
        </div> 
            
      </div>      
      <?php } 
      
      
      ?>

      <div class="col-lg-7 grid-margin stretch-card">
        <div class="card">
          <div class="card-body salary_status_ul">
            <h4 class="card-title"><?php esc_html_e( 'Assigned Tasks', 'employee-&-hr-management' ); ?></h4>
            <div class="table-responsive">
              <table id="task_staff_table" class="table table-striped task_staff_table" cellspacing="0" style="width:100%">
                <thead>
                  <tr>
                    <th><?php esc_html_e( 'No.', 'employee-&-hr-management' ); ?></th>
                    <th><?php esc_html_e( 'Project', 'employee-&-hr-management' ); ?></th>
                    <th><?php esc_html_e( 'Task', 'employee-&-hr-management' ); ?></th>
                    <th><?php esc_html_e( 'Progress', 'employee-&-hr-management' ); ?></th>
                    <th><?php esc_html_e( 'Due Date', 'employee-&-hr-management' ); ?></th>
                  </tr>
                </thead>
                <tbody>
                    <?php echo wp_kses_post( EHRMHelperClass::get_assigned_tasks() ); ?>
                </tbody>
                <tfoot>
                  <tr>
                    <th><?php esc_html_e( 'No.', 'employee-&-hr-management' ); ?></th>
                    <th><?php esc_html_e( 'Project', 'employee-&-hr-management' ); ?></th>
                    <th><?php esc_html_e( 'Task', 'employee-&-hr-management' ); ?></th>
                    <th><?php esc_html_e( 'Progress', 'employee-&-hr-management' ); ?></th>
                    <th><?php esc_html_e( 'Due Date', 'employee-&-hr-management' ); ?></th>
                  </tr>
                </tfoot>
              </table>
            </div>      
          </div>
        </div>
      </div>

    </div>
  </div>
</div>