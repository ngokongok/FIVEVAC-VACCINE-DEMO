# Fivevac MVC – Website Quản lý Tiêm chủng

## Giới thiệu
**Fivevac** là một website quản lý tiêm chủng và đặt lịch tiêm vắc-xin được xây dựng theo mô hình **MVC** với ngôn ngữ **PHP** và cơ sở dữ liệu **MySQL**. Ứng dụng giúp khách hàng tra cứu vắc-xin, đặt lịch tiêm và theo dõi lịch sử tiêm chủng một cách dễ dàng, đồng thời hỗ trợ nhân viên và quản trị viên quản lý tồn kho, hồ sơ, lịch hẹn và đơn hàng. Giao diện sử dụng **Bootstrap 5** nên thân thiện với cả máy tính và thiết bị di động.

---

## Mục tiêu
- **Số hóa quy trình tiêm chủng:** Thay thế việc đăng ký tiêm bằng giấy sang đặt lịch trực tuyến, giảm thời gian chờ đợi và hạn chế sai sót.  
- **Quản lý tập trung:** Các module quản lý đơn hàng, hồ sơ bệnh nhân, tồn kho, lịch hẹn và báo cáo nằm trong một hệ thống thống nhất.  
- **Giao diện thân thiện:** Sử dụng Bootstrap giúp website responsive và dễ sử dụng với người dùng cuối.  

---

## Kiến trúc & Công nghệ

| Thành phần | Mô tả |
|---|---|
| Front controller | `public/index.php` xử lý mọi yêu cầu và định tuyến tới controller tương ứng |
| Router / App.php | Định nghĩa quy tắc `Controller/action` và phân tách URL hợp lệ |
| Giao diện | Bootstrap 5 (CDN), HTML/CSS và JavaScript |
| Cơ sở dữ liệu | MySQL truy cập qua PDO; file tạo DB: `sql/fivevac_db.sql` |

---

## Cài đặt cục bộ
1. Cài đặt Apache + PHP + MySQL (khuyến nghị dùng **XAMPP** hoặc **Laragon**).  
2. Giải nén dự án vào thư mục gốc của web server (ví dụ: `htdocs/fivevac`).  
3. Tạo cơ sở dữ liệu mới (ví dụ: `fivevac_db`) và import file `sql/fivevac_db.sql`.  
4. Cập nhật thông tin kết nối DB trong `app/config/config.php` (host, tên DB, user, password) hoặc thiết lập biến môi trường phù hợp.  
5. Nếu sử dụng VirtualHost, cấu hình biến `BASE_URL` hoặc `FIVEVAC_BASE_URL` trỏ tới thư mục `public` (ví dụ: `http://localhost/fivevac/public`).  
6. Truy cập địa chỉ trên trình duyệt để bắt đầu sử dụng.

