<?php 
defined( 'ABSPATH' ) || die();

if( !class_exists('EHRM_Helper') ) {
    class EHRM_Helper {
        // Save functions definations

        /**
         * Save the departments
         */
        public static function department_query($deparment, $department_description, $department_head) {
            global $wpdb;
            if( empty($department_head) ) {
                $department_head = get_current_user_id();
            }
            $date = date('Y-m-d');
            $department_arg = [
                'title'         => $deparment,
                'description'   => $department_description,
                'head'          => $department_head,
                'creation_date' => $date,
                'image_id'      => '',
                'status'        => '1'
            ];
            $wpdb->insert( EHRM_DEPARTMENTS, $department_arg );
        }

        /**
         * Save designation 
         */
        public static function add_designation( $department_id, $deisgnation_name, $designation_color, $status ) {
            global $wpdb;
            $designation = [
                'name'          => $deisgnation_name,
                'color'         => $designation_color,
                'description'   => '',
                'status'        => $status,
                'department_id' => $department_id,
            ];
            $wpdb->insert( EHRM_DESIGNATION, $designation );
        }

        /**
         * Update designation 
         */
        public static function update_designation( $deparment_id, $name, $color, $status, $designation_id ) {
            global $wpdb;
            $designation = [
                'name'          => $name,
                'color'         => $color,
                'description'   => '',
                'status'        => $status,
                'department_id' => $deparment_id,
            ];
            $success = $wpdb->update(
                EHRM_DESIGNATION,
                ['name' => $name, 'color' => $color, 'description' => '', 'department_id' => $deparment_id ],
                array( 'id' => $designation_id )
            );
            return $success;
        }

        /**
         * Save the shift
         */
        public static function save_shift( $name, $start, $end, $late, $status ) {
            global $wpdb;
            $shift = [
                'name'          => $name,
                'start_time'    => $start,
                'end_time'      => $end,
                'late_time'     => $late,
                'color'         => '',
                'description'   => '',
                'status'        => $status
            ];
            return $wpdb->insert( EHRM_SHIFTS, $shift );
        }

        /**
         * Update the shift
         */
        public static function update_the_shift( $name, $start, $end, $late, $status, $shift_id ) {
            global $wpdb;
            $shift = [
                'name'          => $name,
                'start_time'    => $start,
                'end_time'      => $end,
                'late_time'     => $late,
                'color'         => '',
                'description'   => '',
                'status'        => $status
            ];
            $success = $wpdb->update(
                EHRM_SHIFTS, $shift, ['id' => $shift_id ]
            );
            return $success;
        }

        /**
         * Add staff
         */
        public static function add_new_staff($first_name, $last_name, $email, $phone_no, $dob, $address, $shift_id, $designation_id, $pay_type, $amount, $leave, $id, $description) {
            global $wpdb;
            $staff = [
                'first_name'     => $first_name, 
                'last_name'      => $last_name, 
                'email'          => $email, 
                'phone_no'       => $phone_no, 
                'dob'            => $dob, 
                'address'        => $address, 
                'shift_id'       => $shift_id, 
                'designation_id' => $designation_id, 
                'pay_type'       => $pay_type, 
                'amount'         => $amount, 
                'leaves'          => $leave, 
                'description'    => $description,
                'user_id'        => $id,
                'status'         => 1,
            ];
            return $wpdb->insert( EHRM_STAFF, $staff );
        }

        //update the staff
        public static function update_staff( $first_name, $last_name, $email, $phone_no, $dob, $address, $shift_id, $designation_id, $pay_type, $amount, $leave, $id, $description, $user_id_ct ) {
            global $wpdb; 
            //return $wpdb->query( $wpdb->prepare( 'UPDATE ' . EHRM_STAFF . ' SET phone_no=' . $phone_no . ', shift_id=' . $shift_id . ', designation_id=' . $designation_id . ', pay_type=' . $pay_type . ', amount=' . $amount . ' WHERE id=%d', $user_id_ct) ) ;
            return $wpdb->query( $wpdb->prepare( 'UPDATE ' . EHRM_STAFF . ' SET phone_no=%s, shift_id=%d, designation_id=%d, pay_type=%d, amount=%d WHERE id=%d', $phone_no, $shift_id, $designation_id,$pay_type, $amount, $user_id_ct) ) ;
        }

    //Fetch functions definations

    /**
     * Fetch the departments
     */
        public static function department_fetch_query() {
            global $wpdb;
            $query = $wpdb->get_results( 'SELECT * FROM ' . EHRM_DEPARTMENTS );
            return $query;
        }

        /**
         * Fetch designation
         */
        public static function fetch_designation() {
            global $wpdb;
            $query = $wpdb->get_results( 'SELECT * FROM ' . EHRM_DESIGNATION );
            $query2 = $wpdb->get_results( 'SELECT dg.id, dg.name, dg.color, dg.status, dg.department_id as designation_info, dept.title FROM ' . EHRM_DESIGNATION . ' as dg LEFT JOIN ' . EHRM_DEPARTMENTS . ' as dept ON dg.department_id = dept.id');
            return $query2;
        }

        /**
         * Fetch designation according to id
         */
        public static function fetch_designation_id( $id ) {
            global $wpdb;
            $query = $wpdb->get_results( 'SELECT * FROM ' . EHRM_DESIGNATION );
            $query2 = $wpdb->get_results( 'SELECT dg.id, dg.name, dg.color, dg.status, dg.department_id as designation_info, dept.title FROM ' . EHRM_DESIGNATION . ' as dg LEFT JOIN ' . EHRM_DEPARTMENTS . ' as dept ON dg.department_id = dept.id WHERE dg.id='.$id);
            // return var_dump($query2);
            return $query2;
        }
        //EHRM_DEPARTMENTS

        public static function print_status($status_code) {
            $status_string = "";
            if( $status_code == 0 ) {
                $status_string = "inactive";
            } 
            else {
                $status_string = "active";
            }
            return $status_string;
        }

        //Fetch the shift data to table
        public static function fetch_shift_data() {
            global $wpdb;
            $query = $wpdb->get_results( 'SELECT * FROM ' . EHRM_SHIFTS );
            return $query;
        }

        //Fetch shift data according to id
        public static function fetch_shiftdata_id( $id ) {
            global $wpdb;
            $query = $wpdb->get_results( 'SELECT * FROM ' . EHRM_SHIFTS . ' WHERE id=' . $id);
            return $query;
        }

        //check the staff is exist or not
        public static function check_staff_existance( $id ) {
            global $wpdb;
            $query = $wpdb->get_results( 'SELECT COUNT(user_id) as total FROM ' . EHRM_STAFF . ' WHERE user_id=' . $id);
            return $query;
        }

        //Fetch the staff list
        // EHRM_SHIFTS
        public static function fetch_the_staff() {
            global $wpdb;
            //$query = $wpdb->get_results( 'SELECT * FROM ' . EHRM_STAFF );
            $query = $wpdb->get_results( $wpdb->prepare('SELECT s.id, s.first_name, s.last_name, s.email, s.phone_no, s.dob, s.address, s.shift_id, s.designation_id, s.pay_type, s.amount, s.leaves, s.description, s.user_id, s.status, sh.name FROM ' . EHRM_STAFF .' as s JOIN ' . EHRM_SHIFTS . ' as sh ON sh.id = s.shift_id'));
            return $query;
        }

        //fetch the designation name using id
        public static function fetch_designation_name( $designation_id ) {
            global $wpdb;
            $query = $wpdb->get_row( $wpdb->prepare( 'SELECT d.name FROM ' . EHRM_DESIGNATION . ' as d WHERE d.id=%d', $designation_id ) );
            return $query;
        }

        public static function fetch_the_staff_edit( $staff_id ) {
            global $wpdb;
            // $query = $wpdb->get_row( $wpdb->prepare( 'SELECT *  FROM ' . EHRM_STAFF . ' as s WHERE s.id=' . $staff_id ) );
            $query = $wpdb->get_row( $wpdb->prepare( 'SELECT s.id, s.first_name, s.last_name, s.email,s.phone_no, s.dob, s.address, s.shift_id, s.designation_id, s.pay_type,s.amount,s.leaves,s.description, s.user_id, s.status, ut.user_login FROM ' . EHRM_STAFF . ' as s JOIN ' . EHRM_USER_TABLE . ' as ut ON ut.ID = s.user_id WHERE s.id=%d', $staff_id) );
            return $query;
        }

        /**
         * @param $id current user id 
         * @return staff table id and get the user_id also it is also needed in some cases abd today is 1 August 2022
         */
        public static function fetch_staff_id_stafftable( $id ) {
            global $wpdb;
            $query = $wpdb->get_row( $wpdb->prepare( 'SELECT id FROM ' . EHRM_STAFF . ' WHERE user_id = %d', $id ) );
            return $query;
        }

        /**
         * @param $id staff id from the staff table id
         * @return object staff attendance data as object
         */
        public static function staff_attendance_data( $id ) {
            global $wpdb;
            $query = $wpdb->get_results( $wpdb->prepare("SELECT * FROM " . EHRM_STAFF_ATTENDANCE . " WHERE staff_id = %d", $id) );
            return $query;
        }

        /**
         * @ get the staff id from the get_current_user_id()
         * @ return staff_id value
         */
        public static function get_staff_id($current_uid) {
            global $wpdb;
            $query = $wpdb->get_var($wpdb->prepare( "SELECT st.id FROM " . EHRM_STAFF . " as st WHERE st.user_id=%d", $current_uid ));
            return $query;
        }

        /**
         * @ update the attendance table with late reason
         * @ return query status
         */
        public static function latereason( $staff_id, $late_reason, $current_date ) {
            global $wpdb;
            $success = $wpdb->update(
                EHRM_STAFF_ATTENDANCE,
                ['late_reason' => $late_reason],
                array( 
                    'staff_id' => $staff_id,
                    'attendance_date' => $current_date
                     )
            );
            return $success;
        }

        /**
         * @ insert the daily report of staff
         * @ staff_id, report text and current date
         * @ return success or failure message
         */
        public function daily_report($staff_id, $report, $currentDate)
        {   
            global $wpdb;
            $report_data = [
                'report' => $report,
                'report_date' => $currentDate,
                'staff_id'    => $staff_id
            ];
            $output = $wpdb->insert(EHRM_REPORT, $report_data);
            return $output;
        }
    }
}