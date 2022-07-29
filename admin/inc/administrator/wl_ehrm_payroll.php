<?php
defined( 'ABSPATH' ) or die();
require_once( WL_EHRM_PLUGIN_DIR_PATH . '/admin/inc/helpers/wl-ehrm-helper.php' );
?>
<!-- partial -->
<div class="main-panel">
  	<div class="content-wrapper">
	    <div class="page-header">
	      	<h3 class="page-title">
	        	<span class="page-title-icon bg-gradient-primary text-white mr-2">
	          	<i class="mdi mdi-receipt"></i>                 
	        	</span>
	        	<?php esc_html_e( 'Pay Roll', 'employee-&-hr-management' ); ?>
            </h3>
            <nav aria-label="breadcrumb" class="report">
                <form method="post" id="payroll_form" action="">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item" aria-current="page">
                            <input type="text" class="form-control" id="payroll_first" placeholder="<?php esc_html_e( 'Select starting date', 'employee-&-hr-management' ); ?>" data-toggle="datetimepicker" data-target="#payroll_first">
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            <input type="text" class="form-control" id="payroll_last" placeholder="<?php esc_html_e( 'Select last date', 'employee-&-hr-management' ); ?>" data-toggle="datetimepicker" data-target="#payroll_last">
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            <button type="button" class="btn btn-block btn-lg btn-gradient-primary custom-btn" id="payroll_btn">
                                <i class="mdi mdi-note-text"></i> <?php esc_html_e( 'Generate', 'employee-&-hr-management' ); ?>
                            </button>
                        </li>
                    </ul>
                </form>
            </nav> 
	    </div>
	    <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
              	<div class="card table_card">
                	<div class="card-body">
                		<div class="table-responsive">
		                  	<h4 class="card-title"><?php esc_html_e( 'Salary pay roll', 'employee-&-hr-management' ); ?></h4>
		                  	<table class="table table-striped payroll_table" id="payroll_table">
		                    	<thead>
			                      	<tr>
				                        <th><?php esc_html_e( 'No.', 'employee-&-hr-management' ); ?></th>
			                        	<th><?php esc_html_e( 'Staff Name', 'employee-&-hr-management' ); ?></th>
			                        	<th><?php esc_html_e( 'Month', 'employee-&-hr-management' ); ?></th>
			                        	<th><?php esc_html_e( 'Working days', 'employee-&-hr-management' ); ?></th>
			                        	<th><?php esc_html_e( 'Total Present days', 'employee-&-hr-management' ); ?></th>
			                        	<th><?php esc_html_e( 'Total absent days', 'employee-&-hr-management' ); ?></th>
			                        	<th><?php esc_html_e( 'Total working hours', 'employee-&-hr-management' ); ?></th>
			                        	<th><?php esc_html_e( 'Salary paid by', 'employee-&-hr-management' ); ?></th>
			                        	<th><?php esc_html_e( 'Staff salary', 'employee-&-hr-management' ); ?></th>
			                        	<th><?php esc_html_e( 'Calculated salary', 'employee-&-hr-management' ); ?></th>
			                     	</tr>
		                    	</thead>
		                    	<tbody id="payroll_tbody">
				                    
		                    	</tbody>
		                    	<tfoot>
			                      	<tr>
				                        <th><?php esc_html_e( 'No.', 'employee-&-hr-management' ); ?></th>
										<th><?php esc_html_e( 'Staff Name', 'employee-&-hr-management' ); ?></th>
										<th><?php esc_html_e( 'Month', 'employee-&-hr-management' ); ?></th>
			                        	<th><?php esc_html_e( 'Working days', 'employee-&-hr-management' ); ?></th>
			                        	<th><?php esc_html_e( 'Total Present days', 'employee-&-hr-management' ); ?></th>
			                        	<th><?php esc_html_e( 'Total absent days', 'employee-&-hr-management' ); ?></th>
                                        <th><?php esc_html_e( 'Total working hours', 'employee-&-hr-management' ); ?></th>
                                        <th><?php esc_html_e( 'Salary paid by', 'employee-&-hr-management' ); ?></th>
                                        <th><?php esc_html_e( 'Staff salary', 'employee-&-hr-management' ); ?></th>
			                        	<th><?php esc_html_e( 'Calculated salary', 'employee-&-hr-management' ); ?></th>
			                     	</tr>
		                    	</tfoot>
		                  	</table>
						</div>
						  
						<div class="payrol_total_div">
							<h3 class="card-title"><?php esc_html_e( 'Total salary you have to pay :-', 'employee-&-hr-management' ); ?>
								<span id="total_ammount_payrol"></span>
							</h3> 
						</div>
                	</div>
              	</div>
            </div>
        </div>
      </div>
</div>