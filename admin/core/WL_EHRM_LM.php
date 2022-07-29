<?php
defined('ABSPATH') or die();

require_once WL_EHRM_PLUGIN_DIR_PATH . '/admin/core/WL_EHRM_LC.php';

final class WL_EHRM_LM
{
    private $api_url = 'https://weblizar.com/members/softsale/api';
    private $key = null;
    public $error_message = null;
    private static $instance = null;

    private function __construct()
    {
        $this->license_key = trim(get_option('wl-ehrm-key'));
    }

    public static function get_instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function validate($key)
    {
        $this->license_key = $key;
        return $this->is_valid();
    }

    private function key_exists()
    {
        return !!strlen($this->license_key);
    }

    public function is_valid()
    {
        update_option('wl-ehrm-valid', true);
        if (get_option('wl-ehrm-valid')) {
            return true;
        }
        if ($this->key_exists()) {
            $checker = new WL_EHRM_LC($this->license_key, $this->api_url, md5($this->license_key));
            if (!$checker->checkLicenseKey()) {
                $this->error_message = $checker->getMessage();
                return false;
            } else {
                $this->error_message = null;
                $activation_cache = trim(get_option('wl-ehrm-cache'));
                $prev_activation_cache = $activation_cache;
                $checker = new WL_EHRM_LC($this->license_key, $this->api_url, md5($this->license_key));
                $ret = empty($activation_cache) ? $checker->activate($activation_cache) : $checker->checkActivation($activation_cache);
                if (!$ret) {
                    $this->error_message = $checker->getMessage();
                    return false;
                }
                update_option('wl-ehrm-key', $this->license_key);
                update_option('wl-ehrm-valid', true);
                if ($prev_activation_cache != $activation_cache) {
                    update_option('wl-ehrm-cache', $activation_cache);
                }
                return true;
            }
        }
        $this->error_message = __('Please provide a license key.');
        return false;
    }
}
