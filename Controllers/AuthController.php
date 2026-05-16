<?php
// Định nghĩa lớp điều khiển AdminController để quản lý phân hệ quản trị
class AuthController {
    // Biến nội bộ dùng để lưu trữ cổng kết nối Cơ sở dữ liệu PDO
    private $conn;

    /**
     * Hàm khởi tạo (Constructor)
     * Nhận đối tượng kết nối Cơ sở dữ liệu từ bên ngoài truyền vào khi khởi tạo lớp
     */
    public function __construct($db_connection) {
        $this->conn = $db_connection;
    }
}
?>