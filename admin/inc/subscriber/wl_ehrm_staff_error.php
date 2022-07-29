<?php
defined( 'ABSPATH' ) or die();
?>
<!-- partial -->
<div class="main-panel main-dashboard staff-dashboard">
  <div class="content-wrapper">
    <div class="page-header">
      <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white mr-2">
          <i class="mdi mdi-home"></i>
        </span>
        <?php esc_html_e( 'Unauthorized user', 'employee-&-hr-management' ); ?>
      </h3>
    </div>
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card bg-danger card-img-holder text-white">
                <div class="card-body">
                    <img src="<?php echo WL_EHRM_PLUGIN_URL; ?>assets/images/circle.svg" class="card-img-absolute" alt="circle-image" />
                    <div class="row">
                        <div class="col-md-12">
                            <h2 class="mb-5">
                                <?php esc_html_e( 'Unauthorized IP Address', 'employee-&-hr-management' ); ?>
                            </h2>
                            <br>
                            <h4 class="font-weight-normal mb-3">
                                <?php esc_html_e( 'There\'s a problem with 401 Unauthorized, the HTTP status code for authentication errors. And that’s just it’s for authentication, not authorization. Receiving a 401 response is the server telling you, you aren’t authorized at all or authenticated incorrectly.', 'employee-&-hr-management' ); ?>
                            </h4>
                            <h4 class="mb-3"> <?php esc_html_e( 'You can not login from this IP..!!', 'employee-&-hr-management' );?> </h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </div>
</div>