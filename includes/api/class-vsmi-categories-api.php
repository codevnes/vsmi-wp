<?php
/**
 * Các endpoint API cho danh mục
 * 
 * @package VSMI
 */

// Chặn truy cập trực tiếp
if (!defined('ABSPATH')) exit;

/**
 * Class để xử lý các endpoint API cho danh mục
 */
class VSMI_Categories_API {
    
    /**
     * Đăng ký các route API cho danh mục
     */
    public function register_routes() {
        // API lấy danh sách danh mục có phân trang
        register_rest_route('vsmi/v1', '/categories', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_categories'),
            'permission_callback' => '__return_true',
        ));
    }

    /**
     * Lấy danh sách danh mục có phân trang
     * 
     * @param WP_REST_Request $request Đối tượng yêu cầu
     * @return WP_REST_Response Đối tượng phản hồi
     */
    public function get_categories($request) {
        $page = isset($request['page']) ? (int) $request['page'] : 1;
        $per_page = isset($request['per_page']) ? (int) $request['per_page'] : 10;
        
        $args = array(
            'hide_empty' => false,
            'number' => $per_page,
            'offset' => ($page - 1) * $per_page,
        );
        
        $categories = get_categories($args);
        $total_categories = wp_count_terms('category', array('hide_empty' => false));
        
        $data = array();
        foreach ($categories as $category) {
            $data[] = array(
                'id' => $category->term_id,
                'name' => $category->name,
                'slug' => $category->slug,
                'description' => $category->description,
                'count' => $category->count,
            );
        }
        
        $result = array(
            'categories' => $data,
            'pagination' => array(
                'total_categories' => $total_categories,
                'total_pages' => ceil($total_categories / $per_page),
                'current_page' => $page,
                'per_page' => $per_page,
            )
        );
        
        return rest_ensure_response($result);
    }
} 