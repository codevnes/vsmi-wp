<?php
/**
 * Các endpoint API cho bài viết
 * 
 * @package VSMI
 */

// Chặn truy cập trực tiếp
if (!defined('ABSPATH')) exit;

/**
 * Class để xử lý các endpoint API cho bài viết
 */
class VSMI_Posts_API {
    
    /**
     * Đăng ký các route API cho bài viết
     */
    public function register_routes() {
        // API lấy danh sách bài viết có phân trang
        register_rest_route('vsmi/v1', '/posts', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_posts'),
            'permission_callback' => '__return_true',
        ));
        
        // API lấy nội dung chi tiết của một bài viết
        register_rest_route('vsmi/v1', '/posts/(?P<id>\d+)', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_post_content'),
            'permission_callback' => '__return_true',
        ));

        // API lấy nội dung chi tiết của một bài viết bằng slug
        register_rest_route('vsmi/v1', '/posts/slug/(?P<slug>[a-zA-Z0-9-]+)', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_post_content_by_slug'),
            'permission_callback' => '__return_true',
        ));
    }

    /**
     * Lấy danh sách bài viết có phân trang
     * 
     * @param WP_REST_Request $request Đối tượng yêu cầu
     * @return WP_REST_Response Đối tượng phản hồi
     */
    public function get_posts($request) {
        $page = isset($request['page']) ? (int) $request['page'] : 1;
        $per_page = isset($request['per_page']) ? (int) $request['per_page'] : 10;
        
        $args = array(
            'post_type' => 'post',
            'post_status' => 'publish',
            'posts_per_page' => $per_page,
            'paged' => $page,
        );
        
        // Thêm bộ lọc theo danh mục nếu có
        if (isset($request['category'])) {
            $args['cat'] = (int) $request['category'];
        }
        
        $query = new WP_Query($args);
        $posts = array();
        
        foreach ($query->posts as $post) {
            $posts[] = array(
                'id' => $post->ID,
                'title' => $post->post_title,
                'excerpt' => get_the_excerpt($post),
                'date' => get_the_date('Y-m-d H:i:s', $post),
                'thumbnail' => get_the_post_thumbnail_url($post, 'medium'),
                'categories' => wp_get_post_categories($post->ID, array('fields' => 'names')),
                'url' => get_permalink($post->ID),
            );
        }
        
        $result = array(
            'posts' => $posts,
            'pagination' => array(
                'total_posts' => $query->found_posts,
                'total_pages' => $query->max_num_pages,
                'current_page' => $page,
                'per_page' => $per_page,
            )
        );
        
        return rest_ensure_response($result);
    }

    /**
     * Lấy nội dung chi tiết của một bài viết
     * 
     * @param WP_REST_Request $request Đối tượng yêu cầu
     * @return WP_REST_Response Đối tượng phản hồi
     */
    public function get_post_content($request) {
        $post_id = $request['id'];
        $post = get_post($post_id);
        
        if (empty($post)) {
            return new WP_Error('post_not_found', 'Không tìm thấy bài viết', array('status' => 404));
        }
        
        return $this->prepare_post_response($post);
    }

    /**
     * Lấy nội dung chi tiết của một bài viết bằng slug
     * 
     * @param WP_REST_Request $request Đối tượng yêu cầu
     * @return WP_REST_Response Đối tượng phản hồi
     */
    public function get_post_content_by_slug($request) {
        $post_slug = $request['slug'];
        $post = get_page_by_path($post_slug, OBJECT, 'post');
        
        if (empty($post)) {
            return new WP_Error('post_not_found', 'Không tìm thấy bài viết', array('status' => 404));
        }
        
        return $this->prepare_post_response($post);
    }

    /**
     * Chuẩn bị dữ liệu trả về cho một bài viết
     *
     * @param WP_Post $post
     * @return WP_REST_Response
     */
    private function prepare_post_response($post) {
        $author_id = $post->post_author;
        
        $data = array(
            'id' => $post->ID,
            'title' => $post->post_title,
            'content' => apply_filters('the_content', $post->post_content),
            'author' => array(
                'id' => $author_id,
                'name' => get_the_author_meta('display_name', $author_id),
            ),
            'date' => get_the_date('Y-m-d H:i:s', $post),
            'modified_date' => get_the_modified_date('Y-m-d H:i:s', $post),
            'thumbnail' => get_the_post_thumbnail_url($post, 'full'),
            'categories' => wp_get_post_categories($post->ID, array('fields' => 'all')),
            'tags' => wp_get_post_tags($post->ID, array('fields' => 'all')),
        );
        
        return rest_ensure_response($data);
    }
} 