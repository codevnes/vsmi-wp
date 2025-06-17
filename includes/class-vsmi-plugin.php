<?php
/**
 * Class chính của plugin
 * 
 * @package VSMI
 */

// Chặn truy cập trực tiếp
if (!defined('ABSPATH')) exit;

/**
 * Class chính của plugin VSMI
 */
class VSMI_Plugin {
    
    /**
     * Phiên bản plugin
     *
     * @var string
     */
    public $version = '1.0.0';

    /**
     * Instance của plugin
     *
     * @var VSMI_Plugin
     */
    private static $instance = null;

    /**
     * Instance của API bài viết
     *
     * @var VSMI_Posts_API
     */
    public $posts_api;

    /**
     * Instance của API danh mục
     *
     * @var VSMI_Categories_API
     */
    public $categories_api;
    
    /**
     * Lấy instance của plugin
     *
     * @return VSMI_Plugin
     */
    public static function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Hàm khởi tạo
     */
    public function __construct() {
        $this->define_constants();
        $this->includes();
        $this->init_hooks();
    }

    /**
     * Định nghĩa các hằng số của plugin
     */
    private function define_constants() {
        $this->define('VSMI_VERSION', $this->version);
        $this->define('VSMI_PLUGIN_FILE', plugin_dir_path(dirname(__FILE__)) . 'vsmi.php');
        $this->define('VSMI_PLUGIN_DIR', plugin_dir_path(dirname(__FILE__)));
        $this->define('VSMI_PLUGIN_URL', plugin_dir_url(dirname(__FILE__)));
    }

    /**
     * Định nghĩa hằng số nếu chưa được đặt
     *
     * @param string $name
     * @param string|bool $value
     */
    private function define($name, $value) {
        if (!defined($name)) {
            define($name, $value);
        }
    }

    /**
     * Bao gồm các file cần thiết
     */
    private function includes() {
        // Các class API
        require_once VSMI_PLUGIN_DIR . 'includes/api/class-vsmi-posts-api.php';
        require_once VSMI_PLUGIN_DIR . 'includes/api/class-vsmi-categories-api.php';
    }

    /**
     * Khởi tạo các hook
     */
    private function init_hooks() {
        // Khởi tạo REST API
        add_action('rest_api_init', array($this, 'init_rest_api'));
    }

    /**
     * Khởi tạo REST API
     */
    public function init_rest_api() {
        $this->posts_api = new VSMI_Posts_API();
        $this->posts_api->register_routes();
        
        $this->categories_api = new VSMI_Categories_API();
        $this->categories_api->register_routes();
    }
}