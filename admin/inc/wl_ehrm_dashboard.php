<?php
defined( 'ABSPATH' ) or die();
require_once( WL_EHRM_PLUGIN_DIR_PATH . '/admin/inc/helpers/wl-ehrm-helper.php' );
$all_staffs    = get_option( 'ehrm_staffs_data' );
$save_settings = get_option( 'ehrm_settings_data' );
?>
<!-- partial -->
<div class="main-panel main-dashboard">
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
                <h2 class="mb-5"><?php echo esc_html( EHRMHelperClass::get_current_user_data( get_current_user_id(), 'fullname') ); ?></h2>
              </div>
              <div class="col-md-1 gravtar_ehrm">
                <?php echo wp_kses_post( get_avatar( EHRMHelperClass::get_current_user_data( get_current_user_id(), 'user_email'), 70) ); ?>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-3 stretch-card grid-margin">
        <div class="card bg-gradient-info card-img-holder text-white">
          <div class="card-body">
            <img src="<?php echo WL_EHRM_PLUGIN_URL; ?>assets/images/circle.svg" class="card-img-absolute" alt="circle-image"/>                  
            <h4 class="font-weight-normal mb-3"><?php esc_html_e( 'Pending Requests', 'employee-&-hr-management' ); ?>
              <i class="mdi mdi-airballoon mdi-24px float-right"></i>
            </h4>
            <?php echo wp_kses_post( EHRMHelperClass::get_pending_requests() ); ?>
          </div>
        </div>
      </div>
      <div class="col-md-3 stretch-card grid-margin">
        <div class="card bg-gradient-success card-img-holder text-white">
          <div class="card-body">
            <img src="<?php echo WL_EHRM_PLUGIN_URL; ?>assets/images/circle.svg" class="card-img-absolute" alt="circle-image"/>                                    
            <h4 class="font-weight-normal mb-3"><?php esc_html_e( 'Shifts', 'employee-&-hr-management' ); ?>
              <i class="mdi mdi-mouse-variant mdi-24px float-right"></i>
            </h4>
            <?php echo wp_kses_post( EHRMHelperClass::get_total_shifts() ); ?>
          </div>
        </div>
      </div>
      <div class="col-md-3 stretch-card grid-margin">
        <div class="card bg-gradient-primary card-img-holder text-white">
          <div class="card-body">
            <img src="<?php echo WL_EHRM_PLUGIN_URL; ?>assets/images/circle.svg" class="card-img-absolute" alt="circle-image"/>                                    
            <h4 class="font-weight-normal mb-3"><?php esc_html_e( 'Staffs', 'employee-&-hr-management' ); ?>
              <i class="mdi mdi-human-greeting mdi-24px float-right"></i>
            </h4>
            <?php echo wp_kses_post( EHRMHelperClass::get_total_satffs() ); ?>
          </div>
        </div>
      </div>
    </div>
    <div class="row dashboard_status_table">
      <div class="col-12 grid-margin">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title"><?php esc_html_e( 'Staff\'s Live Status', 'employee-&-hr-management' ); ?></h4>
            <div class="table-responsive">
              <table class="table table-striped dash_table" id="admin_dash_table" cellspacing="0" style="width:100%">
                <thead>
                  <tr>
                    <th><?php esc_html_e( 'No.', 'employee-&-hr-management' ); ?></th>
                    <th><?php esc_html_e( 'Name', 'employee-&-hr-management' ); ?></th>
                    <th><?php esc_html_e( 'Office In', 'employee-&-hr-management' ); ?></th>
                    <th><?php esc_html_e( 'Office Out', 'employee-&-hr-management' ); ?></th>
                    <th><?php esc_html_e( 'Lunch In', 'employee-&-hr-management' ); ?></th>
                    <th><?php esc_html_e( 'Lunch Out', 'employee-&-hr-management' ); ?></th>
                    <th><?php esc_html_e( 'Working Hour\'s', 'employee-&-hr-management' ); ?></th>
                    <th><?php esc_html_e( 'Puntuality', 'employee-&-hr-management' ); ?></th>
                    <th><?php esc_html_e( 'IP Address', 'employee-&-hr-management' ); ?></th>
                    <th class="none"><?php esc_html_e( 'Location', 'employee-&-hr-management' ); ?></th>
                    <th><?php esc_html_e( 'Status', 'employee-&-hr-management' ); ?></th>
                    <th><?php esc_html_e( 'Action', 'employee-&-hr-management' ); ?></th>
                  </tr>
                </thead>
                <tbody id="staff_tbody">
                  <?php 
                    if ( ! empty ( $all_staffs ) ) {
                      $sno = 1;
                      $user_array = array();
                      foreach ( $all_staffs as $key => $staff ) {
                  ?>
                    <tr>
                      <td><?php echo esc_html( $sno ); ?>.</td>
                        <td><?php echo esc_html( $staff['fullname'] ); ?></td>
                        <?php if ( ! empty ( EHRMHelperClass::ehrm_staff_today_status( $staff['ID'] ) ) && ! in_array( $staff['ID'], $user_array ) ) {
                          echo wp_kses_post( EHRMHelperClass::ehrm_staff_today_status( $staff['ID'] ) );
                          array_push( $user_array, $staff['ID'] );
                        } else { ?>
                          <td>---</td>
                          <td>---</td>
                          <td>---</td>
                          <td>---</td>
                          <td>---</td>
                          <td>---</td>
                          <td>---</td>
                          <td class="none">---</td>
                          <td>---</td>
                        <?php } ?>
                        <td class="designation-action-tools">
                          <ul class="designation-action-tools-ul">
                            <?php if ( empty ( EHRMHelperClass::ehrm_staff_today_status( $staff['ID'] ) ) ) { ?>
                            <li class="designation-action-tools-li">
                              <a href="#" class="designation-action-tools-a admin-staff-edit-a" title="Login" data-value="office-in" data-staff="<?php echo esc_attr( $staff['ID'] ); ?>" data-timezone="<?php echo esc_attr( $save_settings['timezone'] ); ?>" id="dashboard_login">
                                <i class="mdi mdi-login"></i> <?php esc_html_e( 'Login', 'employee-&-hr-management' ); ?>
                              </a>
                            </li>
                            <?php } else { ?>
                            <li class="designation-action-tools-li">
                              <a href="#" class="designation-action-tools-a admin-staff-delete-a" title="Logout" data-value="office-out" data-staff="<?php echo esc_attr( $staff['ID'] ); ?>" data-timezone="<?php echo esc_attr( $save_settings['timezone'] ); ?>" id="dashboard_logout">
                                <i class="mdi mdi-logout"></i><?php esc_html_e( 'Logout', 'employee-&-hr-management' ); ?>
                              </a>
                            </li>
                            <?php } ?>
                          </ul>
                        </td>
                    </tr>
                  <?php $sno++; } } else { ?>
                    <tr>
                      <td><?php esc_html_e( 'No Staff added yet.!', 'employee-&-hr-management' ); ?></td>
                      <td>---</td>
                      <td>---</td>
                      <td>---</td>
                      <td>---</td>
                      <td>---</td>
                      <td>---</td>
                      <td>---</td>
                      <td>---</td>
                      <td>---</td>
                      <td class="none">---</td>
                      <td>---</td>
                    </tr>
                  <?php } ?>
                </tbody>
                <tfoot>
                  <tr>
                    <th><?php esc_html_e( 'No.', 'employee-&-hr-management' ); ?></th>
                    <th><?php esc_html_e( 'Name', 'employee-&-hr-management' ); ?></th>
                    <th><?php esc_html_e( 'Office In', 'employee-&-hr-management' ); ?></th>
                    <th><?php esc_html_e( 'Office Out', 'employee-&-hr-management' ); ?></th>
                    <th><?php esc_html_e( 'Lunch In', 'employee-&-hr-management' ); ?></th>
                    <th><?php esc_html_e( 'Lunch Out', 'employee-&-hr-management' ); ?></th>
                    <th><?php esc_html_e( 'Working Hour\'s', 'employee-&-hr-management' ); ?></th>
                    <th><?php esc_html_e( 'Puntuality', 'employee-&-hr-management' ); ?></th>
                    <th><?php esc_html_e( 'IP Address', 'employee-&-hr-management' ); ?></th>
                    <th class="none"><?php esc_html_e( 'Location', 'employee-&-hr-management' ); ?></th>
                    <th><?php esc_html_e( 'Status', 'employee-&-hr-management' ); ?></th>
                    <th><?php esc_html_e( 'Action', 'employee-&-hr-management' ); ?></th>
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