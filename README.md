# VSMI WordPress Plugin

Plugin cung cấp các REST API endpoint để lấy dữ liệu bài viết và danh mục cho WordPress.

## Tác giả
Trần Danh Trọng (danhtrong.com - codevnes@gmail.com)

## Tính năng

- Cung cấp API để lấy danh sách bài viết (có phân trang và lọc theo danh mục).
- Cung cấp API để lấy danh sách danh mục (có phân trang).
- Cung cấp API để lấy nội dung chi tiết của một bài viết.
- Dễ dàng cài đặt và sử dụng.
- Code được tổ chức gọn gàng, dễ bảo trì và mở rộng.

## Các Endpoint API

Các endpoint được đăng ký dưới namespace `vsmi/v1`.

### 1. Lấy danh sách bài viết

Endpoint này trả về một danh sách các bài viết đã được xuất bản.

- **Endpoint:** `GET /wp-json/vsmi/v1/posts`
- **Method:** `GET`
- **Tham số (Query Parameters):**
    - `page` (tùy chọn): Số trang hiện tại. Mặc định là `1`.
    - `per_page` (tùy chọn): Số lượng bài viết trên mỗi trang. Mặc định là `10`.
    - `category` (tùy chọn): ID của danh mục để lọc bài viết.

- **Ví dụ:**
    - Lấy 10 bài viết ở trang đầu tiên: 
      `/wp-json/vsmi/v1/posts`
    - Lấy 5 bài viết ở trang thứ 2: 
      `/wp-json/vsmi/v1/posts?page=2&per_page=5`
    - Lấy các bài viết thuộc danh mục có ID là 4:
      `/wp-json/vsmi/v1/posts?category=4`

- **Nội dung trả về:**
  ```json
  {
    "posts": [
      {
        "id": 1,
        "title": "Tiêu đề bài viết",
        "excerpt": "Đoạn trích của bài viết...",
        "date": "2023-10-27 10:00:00",
        "thumbnail": "https://yoursite.com/path/to/thumbnail.jpg",
        "categories": ["Tên danh mục 1", "Tên danh mục 2"],
        "url": "https://yoursite.com/ten-bai-viet/"
      }
    ],
    "pagination": {
      "total_posts": 100,
      "total_pages": 10,
      "current_page": 1,
      "per_page": 10
    }
  }
  ```

### 2. Lấy danh sách danh mục

Endpoint này trả về danh sách các danh mục bài viết.

- **Endpoint:** `GET /wp-json/vsmi/v1/categories`
- **Method:** `GET`
- **Tham số (Query Parameters):**
    - `page` (tùy chọn): Số trang hiện tại. Mặc định là `1`.
    - `per_page` (tùy chọn): Số lượng danh mục trên mỗi trang. Mặc định là `10`.

- **Ví dụ:**
    - Lấy 10 danh mục đầu tiên:
      `/wp-json/vsmi/v1/categories`
    - Lấy 20 danh mục ở trang 1:
      `/wp-json/vsmi/v1/categories?page=1&per_page=20`

- **Nội dung trả về:**
  ```json
  {
    "categories": [
        {
            "id": 1,
            "name": "Tên danh mục",
            "slug": "ten-danh-muc",
            "description": "Mô tả danh mục",
            "count": 10
        }
    ],
    "pagination": {
        "total_categories": 50,
        "total_pages": 5,
        "current_page": 1,
        "per_page": 10
    }
  }
  ```

### 3. Lấy nội dung chi tiết bài viết

Endpoint này trả về thông tin chi tiết của một bài viết cụ thể dựa vào ID.

- **Endpoint:** `GET /wp-json/vsmi/v1/posts/{post_id}`
- **Method:** `GET`
- **Tham số (URL Parameter):**
    - `post_id` (bắt buộc): ID của bài viết cần lấy thông tin.

- **Ví dụ:**
    - Lấy thông tin bài viết có ID là 123:
      `/wp-json/vsmi/v1/posts/123`

- **Nội dung trả về:**
  ```json
  {
    "id": 123,
    "title": "Tiêu đề bài viết",
    "content": "<p>Nội dung đầy đủ của bài viết...</p>",
    "author": {
      "id": 1,
      "name": "Tên tác giả"
    },
    "date": "2023-10-27 10:00:00",
    "modified_date": "2023-10-27 11:00:00",
    "thumbnail": "https://yoursite.com/path/to/full-image.jpg",
    "categories": [
      {
        "term_id": 2,
        "name": "Tên danh mục",
        "slug": "ten-danh-muc",
        "term_group": 0,
        "term_taxonomy_id": 2,
        "taxonomy": "category",
        "description": "Mô tả danh mục",
        "parent": 0,
        "count": 15,
        "filter": "raw",
        "cat_ID": 2,
        "category_count": 15,
        "category_description": "Mô tả danh mục",
        "cat_name": "Tên danh mục",
        "category_nicename": "ten-danh-muc",
        "category_parent": 0
      }
    ],
    "tags": [
        {
            "term_id": 3,
            "name": "Tên thẻ",
            "slug": "ten-the",
            "term_group": 0,
            "term_taxonomy_id": 3,
            "taxonomy": "post_tag",
            "description": "",
            "parent": 0,
            "count": 5,
            "filter": "raw"
        }
    ]
  }
  ```

### 4. Lấy nội dung chi tiết bài viết bằng slug

Endpoint này trả về thông tin chi tiết của một bài viết cụ thể dựa vào slug.

- **Endpoint:** `GET /wp-json/vsmi/v1/posts/slug/{post_slug}`
- **Method:** `GET`
- **Tham số (URL Parameter):**
    - `post_slug` (bắt buộc): Slug của bài viết cần lấy thông tin.

- **Ví dụ:**
    - Lấy thông tin bài viết có slug là `ten-bai-viet`:
      `/wp-json/vsmi/v1/posts/slug/ten-bai-viet`

- **Nội dung trả về:**
  Tương tự như khi lấy bài viết bằng ID.
  
## Cài đặt

1.  Tải plugin về dưới dạng file `.zip`.
2.  Đăng nhập vào trang quản trị WordPress của bạn.
3.  Đi tới `Plugins` -> `Add New` -> `Upload Plugin`.
4.  Chọn file `.zip` đã tải và nhấn `Install Now`.
5.  Sau khi cài đặt thành công, nhấn `Activate Plugin`.

Sau khi kích hoạt, các API endpoint sẽ sẵn sàng để sử dụng.

## Dành cho Lập trình viên

Cấu trúc của plugin được tổ chức như sau:
- `vsmi.php`: File chính của plugin, dùng để khởi tạo.
- `includes/class-vsmi-plugin.php`: Class lõi, quản lý việc load các thành phần và khởi tạo hook.
- `includes/api/`: Thư mục chứa các class xử lý API.
    - `class-vsmi-posts-api.php`: Xử lý các API liên quan đến bài viết.
    - `class-vsmi-categories-api.php`: Xử lý các API liên quan đến danh mục.

Cấu trúc này giúp dễ dàng mở rộng thêm các tính năng hoặc các API endpoint mới trong tương lai. 