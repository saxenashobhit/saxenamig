<?php 
defined( 'ABSPATH' ) || die();

global $wpdb;

/**
 * Database table name
 */
define( 'EHRM_DEPARTMENTS', $wpdb->base_prefix . 'ehrm_departments' );
define( 'EHRM_DESIGNATION', $wpdb->base_prefix . 'ehrm_designation' );
define( 'EHRM_SHIFTS',  $wpdb->base_prefix . 'ehrm_shifts' );
define( 'EHRM_STAFF',  $wpdb->base_prefix . 'ehrm_staff' );
define( 'EHRM_STAFF_ATTENDANCE',  $wpdb->base_prefix . 'ehrm_staff_attendance' );
define( 'EHRM_CLIENTS',  $wpdb->base_prefix . 'ehrm_clients' );
define( 'EHRM_PROJECTS',  $wpdb->base_prefix . 'ehrm_projects' );
define( 'EHRM_SETTINGS',  $wpdb->base_prefix . 'ehrm_settings' );
define( 'EHRM_EMAIL_TEMPLATES',  $wpdb->base_prefix . 'ehrm_email_templates' );
define( 'EHRM_SMS_TEMPLATES',  $wpdb->base_prefix . 'ehrm_sms_templates' );
define( 'EHRM_LEAVE_REQUESTS',  $wpdb->base_prefix . 'ehrm_leave_request' );
define( 'EHRM_TASK',  $wpdb->base_prefix . 'ehrm_task' );
define( 'EHRM_EVENTS',  $wpdb->base_prefix . 'ehrm_events' );
define( 'EHRM_NOTICE',  $wpdb->base_prefix . 'ehrm_notice' );
define( 'EHRM_HOLIDAY',  $wpdb->base_prefix . 'ehrm_holiday' );
define( 'EHRM_BREAK',  $wpdb->base_prefix . 'ehrm_breaks' );
define( 'EHRM_USER_TABLE',  $wpdb->base_prefix . 'users' );
// define( 'EHRM_',  $wpdb->base_prefix . '' );