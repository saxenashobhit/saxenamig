<?php 
defined( 'ABSPATH' ) || die();

require_once WL_EHRM_PLUGIN_DIR_PATH . 'includes/constants.php';

class EHRM_DATABASE {
    public static function activation() {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';

        /**
         * Create Department table
         */
         $department_table = "CREATE TABLE IF NOT EXISTS " . EHRM_DEPARTMENTS . "(
            id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            title varchar(255) DEFAULT NULL,
            description text DEFAULT NULL,
            head int(11) DEFAULT NULL,
            creation_date date NULL DEFAULT NULL,
            image_id int(11) DEFAULT NULL,
            status int(1) DEFAULT NULL,
            PRIMARY KEY (ID)
         ) ENGINE=InnoDB " . $charset_collate;
        dbDelta($department_table);

        /**
         * Designation
         */

         $designation_table = "CREATE TABLE IF NOT EXISTS " . EHRM_DESIGNATION . "(
            id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            name varchar(150) DEFAULT NULL,
            color varchar(20) DEFAULT NULL,
            description TEXT DEFAULT NULL,
            status int(1) DEFAULT NULL,
            department_id bigint(20) UNSIGNED NOT NULL,
            PRIMARY KEY (id),
            FOREIGN KEY (department_id) REFERENCES " . EHRM_DEPARTMENTS . " (id) ON DELETE CASCADE
         ) ENGINE=InnoDB " . $charset_collate;
        dbDelta($designation_table);

        /**
         * Shifts
         */

        $shift_table = "CREATE TABLE IF NOT EXISTS " . EHRM_SHIFTS . "(
            id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            name varchar(100) DEFAULT NULL,
            start_time time DEFAULT NULL,
            end_time time DEFAULT NULL,
            late_time time DEFAULT NULL,
            color varchar(20) DEFAULT NULL,
            description TEXT DEFAULT NULL,
            status int(1) DEFAULT NULL,
            PRIMARY KEY (id)
        ) ENGINE=InnoDB " . $charset_collate;
        dbDelta($shift_table);
        
         /**
         * Staff table
         */
        $staff_table = "CREATE TABLE IF NOT EXISTS " . EHRM_STAFF . "(
            id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            first_name varchar(120) DEFAULT NULL,
            last_name varchar(120) DEFAULT NULL,
            email varchar(255) DEFAULT NULL,
            phone_no varchar(80) DEFAULT NULL,
            dob date DEFAULT NULL,
            address text DEFAULT NULL,
            shift_id bigint(20) UNSIGNED DEFAULT NULL,
            designation_id bigint(20) UNSIGNED DEFAULT NULL,
            pay_type int(2) DEFAULT NULL,
            amount decimal(12,2) UNSIGNED DEFAULT '0.00',
            leaves text DEFAULT NULL,            
            description TEXT DEFAULT NULL,
            user_id bigint(20) UNSIGNED NOT NULL, 
            status int(1) DEFAULT NULL,
            PRIMARY KEY (id),
            FOREIGN KEY (shift_id) REFERENCES " . EHRM_SHIFTS . " (id) ON DELETE CASCADE,
            FOREIGN KEY (designation_id) REFERENCES " . EHRM_DESIGNATION . " (id) ON DELETE CASCADE
        ) ENGINE=InnoDB " . $charset_collate;
        dbDelta($staff_table);
        
        /**
         * Staff attendance table
         */
        $staff_attendance_table = "CREATE TABLE IF NOT EXISTS " . EHRM_STAFF_ATTENDANCE . "(
            id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            staff_id bigint(20) UNSIGNED DEFAULT NULL,
            office_in time DEFAULT NULL,
            office_out time DEFAULT NULL,
            lunch_in time DEFAULT NULL,
            lunch_out time DEFAULT NULL,
            late int(1) DEFAULT NULL,
            late_reason text DEFAULT NULL,
            report text DEFAULT NULL,
            ip_address text DEFAULT NULL,
            location varchar(100) DEFAULT NULL,
            working_hours varchar(20) DEFAULT NULL,
            attendance_date date DEFAULT NULL,
            PRIMARY KEY (id),
            FOREIGN KEY (staff_id) REFERENCES " . EHRM_STAFF . " (id) ON DELETE CASCADE
        ) ENGINE=InnoDB " . $charset_collate;
        dbDelta($staff_attendance_table);

         /**
         * Event table
         */
        $event_table = "CREATE TABLE IF NOT EXISTS " . EHRM_EVENTS . "(
            id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            title varchar(150) DEFAULT NULL,
            description TEXT DEFAULT NULL,
            event_date date DEFAULT NULL,
            event_time time DEFAULT NULL,
            PRIMARY KEY (id)
        ) ENGINE=InnoDB " . $charset_collate;
        dbDelta($event_table);

        /**
         *  Client table
         */
        $client_table = "CREATE TABLE IF NOT EXISTS " . EHRM_CLIENTS . "(
            id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            first_name varchar(120) DEFAULT NULL,
            last_name varchar(120) DEFAULT NULL,
            email varchar(255) DEFAULT NULL,
            phone_no varchar(80) DEFAULT NULL,
            dob date DEFAULT NULL,
            address text DEFAULT NULL,
            description TEXT DEFAULT NULL,
            join_date date DEFAULT NULL,
            status int(1) DEFAULT NULL,
            PRIMARY KEY (id)
        ) ENGINE=InnoDB " . $charset_collate;
        dbDelta($client_table);

        /**
         *  Project table
         */
        $projects_table = "CREATE TABLE IF NOT EXISTS " . EHRM_PROJECTS . "(
            id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            title varchar(150) DEFAULT NULL,
            description TEXT DEFAULT NULL,
            staff_ids text DEFAULT NULL,
            project_cost decimal(12,2) UNSIGNED DEFAULT '0.00',
            client_id bigint(20) UNSIGNED DEFAULT NULL,
            state_date date DEFAULT NULL,
            status int(1) DEFAULT NULL,
            PRIMARY KEY (id),
            FOREIGN KEY (client_id) REFERENCES " . EHRM_CLIENTS . " (id) ON DELETE CASCADE
        ) ENGINE=InnoDB " . $charset_collate;
        dbDelta($projects_table);

        /**
         *  Task table
         */
        $task_table = "CREATE TABLE IF NOT EXISTS " . EHRM_TASK . "(
            id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            project_id bigint(20) UNSIGNED DEFAULT NULL,
            title varchar(150) DEFAULT NULL,
            description TEXT DEFAULT NULL,
            staff_ids text DEFAULT NULL,
            project_cost decimal(12,2) UNSIGNED DEFAULT '0.00',
            client_id bigint(20) UNSIGNED DEFAULT NULL,
            state_date date DEFAULT NULL,
            status int(1) DEFAULT NULL,
            PRIMARY KEY (id),
            FOREIGN KEY (project_id) REFERENCES " . EHRM_PROJECTS . " (id) ON DELETE CASCADE
        ) ENGINE=InnoDB " . $charset_collate;
        dbDelta($task_table);

         /**
         *  Break points table
         */
        $break_table = "CREATE TABLE IF NOT EXISTS " . EHRM_BREAK . "(
            id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            staff_id bigint(20) UNSIGNED DEFAULT NULL,
            break_in time DEFAULT NULL,
            break_out time DEFAULT NULL,
            break_date date DEFAULT NULL,
            PRIMARY KEY (id),
            FOREIGN KEY (staff_id) REFERENCES " . EHRM_STAFF . " (id) ON DELETE CASCADE
        ) ENGINE=InnoDB " . $charset_collate;
        dbDelta($break_table);

        /**
         * Settings table
         */
        $setting_table = "CREATE TABLE IF NOT EXISTS " . EHRM_SETTINGS . " (
            id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            setting_key varchar(191) DEFAULT NULL,
            setting_value text DEFAULT NULL,
            PRIMARY KEY (id),
            UNIQUE (setting_key)
            ) ENGINE=InnoDB " . $charset_collate;
        dbDelta($setting_table);

         /**
         * Notice table
         */

         /**
         * Holiday table
         */
        $holiday_table = "CREATE TABLE IF NOT EXISTS " . EHRM_HOLIDAY . " (
            id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            holiday_name varchar(191) DEFAULT NULL,
            holiday_from date DEFAULT NULL,
            holiday_to date DEFAULT NULL,
            PRIMARY KEY (id)
            ) ENGINE=InnoDB " . $charset_collate;
        dbDelta($holiday_table);

         /**
         *  table
         */
    }
}