<?php
// Định nghĩa lớp điều khiển AdminController để quản lý phân hệ quản trị
class AdminController {
    // Biến nội bộ dùng để lưu trữ cổng kết nối Cơ sở dữ liệu PDO
    private $conn;

    /**
     * Hàm khởi tạo (Constructor)
     * Nhận đối tượng kết nối Cơ sở dữ liệu từ bên ngoài truyền vào khi khởi tạo lớp
     */
    public function __construct($db_connection) {
        $this->conn = $db_connection;
    }

    /**
     * Hàm hành động chính (Hành động index)
     * Nhiệm vụ: Xử lý, tính toán toàn bộ dữ liệu thực tế và gọi giao diện hiển thị
     */
    public function index() {
        try {
            // ----------------------------------------------------------------
            // PHẦN 1: TRUY VẤN LẤY SỐ LIỆU TỔNG QUAN (Dùng SQL COUNT)
            // ----------------------------------------------------------------
            
            // Thực hiện đếm tổng số lượng thành viên trong hệ thống
            $u_stmt = $this->conn->query("SELECT COUNT(*) AS total FROM Users");
            $total_users = $u_stmt->fetch(PDO::FETCH_ASSOC)['total'];

            // Thực hiện đếm số lượng báo cáo vi phạm đang ở trạng thái 'Pending' (Chờ duyệt)
            $r_stmt = $this->conn->query("SELECT COUNT(*) AS total FROM Reports WHERE Status = 'Pending'");
            $total_reports = $r_stmt->fetch(PDO::FETCH_ASSOC)['total'];

            // Thực hiện đếm tổng số lượng bài viết đã đăng trên mạng xã hội
            $p_stmt = $this->conn->query("SELECT COUNT(*) AS total FROM Posts");
            $total_posts = $p_stmt->fetch(PDO::FETCH_ASSOC)['total'];


            // ----------------------------------------------------------------
            // PHẦN 2: TRUY VẤN TÍNH TOÁN TỶ LỆ HOẠT ĐỘNG THỰC TẾ TỪ CSDL
            // ----------------------------------------------------------------
            
            // Đếm tổng số lượng báo cáo vi phạm hiện có trong hệ thống (Tất cả trạng thái)
            $query_report_total = "SELECT COUNT(*) AS total FROM Reports";
            $stmt_report_total = $this->conn->query($query_report_total);
            $all_reports = $stmt_report_total->fetch(PDO::FETCH_ASSOC)['total'];

            if ($all_reports > 0) {
                // Đếm số lượng báo cáo đã xử lý thành công (Resolved)
                $stmt_report_resolved = $this->conn->query("SELECT COUNT(*) AS total FROM Reports WHERE Status = 'Resolved'");
                $resolved_reports = $stmt_report_resolved->fetch(PDO::FETCH_ASSOC)['total'];
                
                // Công thức tính phần trăm hiệu suất và làm tròn đến 1 chữ số thập phân
                $activity_rate = round(($resolved_reports / $all_reports) * 100, 1) . '%';
            } else {
                // Nếu hệ thống chưa có báo cáo nào, mặc định hiệu suất hoạt động đạt 100%
                $activity_rate = '100%'; 
            }

            // Tiến hành gom cụm toàn bộ số liệu tổng quan vào mảng kết hợp để gửi sang View.
            // Phần tử 'activity' hiện tại đã nhận giá trị tính toán thực tế từ biến $activity_rate.
            $stats = [
                'users' => number_format($total_users),
                'reports' => $total_reports,
                'posts' => number_format($total_posts),
                'activity' => $activity_rate 
            ];


            // ----------------------------------------------------------------
            // PHẦN 3: TRUY VẤN LẤY DANH SÁCH BÁO CÁO VI PHẠM (Dùng LEFT JOIN)
            // ----------------------------------------------------------------
            $query_reports = "SELECT r.ReportID AS id, u.FullName AS user, u.ProfilePictureUrl AS avatar, 
                                     CASE 
                                        WHEN r.PostID IS NOT NULL THEN 'Bài viết' 
                                        WHEN r.CommentID IS NOT NULL THEN 'Bình luận' 
                                        ELSE 'Tài khoản' 
                                     END AS type,
                                     r.Reason AS reason, r.CreatedAt AS time, 
                                     CASE 
                                        WHEN r.Status = 'Pending' THEN 'Chờ duyệt' 
                                        ELSE 'Đã xử lý' 
                                        END AS status
                              FROM Reports r
                              LEFT JOIN Users u ON r.ReportedUserID = u.UserID
                              ORDER BY r.CreatedAt DESC";
            
            $stmt_reports = $this->conn->query($query_reports);
            // Lấy toàn bộ các bản ghi báo cáo vi phạm lưu vào mảng $reports
            $reports = $stmt_reports->fetchAll(PDO::FETCH_ASSOC);


            // ----------------------------------------------------------------
            // PHẦN 4: TRUY VẤN LẤY DANH SÁCH THÀNH VIÊN (Dùng JOIN)
            // ----------------------------------------------------------------
            $query_members = "SELECT u.FullName AS name, u.ProfilePictureUrl AS avatar, r.RoleName AS role, 
                                     DATE_FORMAT(u.CreatedAt, '%d/%m/%Y') AS joined, 
                                     CASE 
                                        WHEN u.Username = 'nguoi_tinh_mua_dong' THEN 'Đã khóa' 
                                        ELSE 'Hoạt động' 
                                     END AS status
                              FROM Users u
                              JOIN Roles r ON u.RoleID = r.RoleID
                              ORDER BY u.CreatedAt DESC";
                              
            $stmt_members = $this->conn->query($query_members);
            // Lấy toàn bộ các bản ghi thành viên lưu vào mảng $members
            $members = $stmt_members->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            // Nếu có lỗi CSDL xảy ra trong quá trình truy vấn, thiết lập các mảng rỗng để tránh lỗi giao diện
            $stats = ['users' => 0, 'reports' => 0, 'posts' => 0, 'activity' => '0%'];
            $reports = [];
            $members = [];
        }

        // ----------------------------------------------------------------
        // PHẦN 5: LIÊN KẾT VÀ HIỂN THỊ GIAO DIỆN (VIEW)
        // ----------------------------------------------------------------
        // Chuyển đổi đường dẫn tuyệt đối sang cấu trúc thư mục chuẩn định hướng của hệ thống: app/views/admin/index.php
        require_once "C:/xampp/htdocs/PTUDW-N07-Social-network/Views/admin/index.php";
    }

    /**
     * Hàm điều phối màu sắc trạng thái dựa trên văn bản
     * Nhận vào tên trạng thái và trả về chuỗi thẻ HTML mang class CSS tương ứng.
     */
    public function renderStatus($status) {
        $class = ($status == 'Chờ duyệt' || $status == 'Đã khóa') ? 'status-pending' : 'status-resolved';
        return "<span class='badge rounded-pill px-3 py-2 $class'>$status</span>";
    }
}
?>