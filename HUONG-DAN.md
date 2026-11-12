# Hướng dẫn khôi phục Trust Family Dental

Bạn có thể dùng gói sao lưu Duplicator hiện có để khôi phục website, hoặc cài thủ công bằng mã nguồn trên GitHub và cơ sở dữ liệu trên máy.

## Cách 1. Khôi phục bằng Duplicator

1. Tìm gói `*_archive.zip` và tệp `*_installer-backup.php` đi kèm trong thư mục `app/public/wp-content/backups-dup-pro/imports/` trên máy hiện tại. Giữ hai tệp này trong nơi lưu trữ riêng tư.
2. Tạo một cơ sở dữ liệu MySQL hoặc MariaDB trống. Dùng một thư mục web trống trên môi trường đích để tránh ghi đè website đang chạy.
3. Đưa gói ZIP và tệp cài đặt vào thư mục web đích. Đổi tên bản sao của tệp cài đặt thành `installer.php`, rồi mở `/installer.php` trên tên miền đích. Làm theo trình cài đặt và nhập thông tin cơ sở dữ liệu mới.
4. Đăng nhập vào trang quản trị và dùng chức năng dọn dẹp của Duplicator để xóa tệp cài đặt và gói ZIP khỏi thư mục web.

Gói này khôi phục dữ liệu tại thời điểm tạo bản sao lưu. Nếu website đã có thay đổi sau thời điểm đó, hãy xuất bản sao lưu mới từ website hiện tại trước khi chuyển.

## Cách 2. Khôi phục thủ công từ GitHub

1. Tải kho GitHub về. Đặt nội dung của `app/public/` vào thư mục web đích, hoặc cấu hình máy chủ trỏ thẳng đến `app/public/`.
2. Tạo một cơ sở dữ liệu trống và nhập bản SQL riêng tư `app/sql/local.sql` trên máy hiện tại. Nếu website đã thay đổi kể từ lần xuất đó, hãy xuất lại cơ sở dữ liệu trước khi nhập.
3. Trong `app/public/`, sao chép `wp-config-sample.php` thành `wp-config.php`. Sửa các giá trị `DB_NAME`, `DB_USER`, `DB_PASSWORD` và `DB_HOST` theo thông tin của môi trường đích. Đặt `$table_prefix = 'wp_';` khi dùng bản SQL hiện có và tạo bộ khóa bảo mật mới cho các mục `*_KEY` và `*_SALT`.
4. Cài theme mẹ Flatsome từ gói có bản quyền. Cài Duplicator Pro hoặc All-in-One WP Migration Unlimited Extension từ nguồn cấp phép nếu cần các công cụ di chuyển này.
5. Nếu đổi tên miền, cập nhật URL cũ sang URL mới bằng công cụ WordPress hỗ trợ dữ liệu tuần tự hóa, chẳng hạn `wp search-replace`. Sau đó vào **Settings → Permalinks** trong trang quản trị và lưu lại.

Sau khi khôi phục, kiểm tra trang chủ, ảnh, đường dẫn nội bộ, trang liên hệ và quy trình đặt lịch trên môi trường đích.

`wp-config.php`, bản SQL và các gói sao lưu được giữ ngoài kho GitHub công khai vì chúng có thể chứa mật khẩu, khóa bảo mật hoặc dữ liệu riêng tư.
