<?php
// Nạp file Model vào để chuẩn bị gọi hàm
require_once "./Models/AdminModel.php";

class AdminController {
    private $adminModel;

    /**
     * Hàm khởi tạo nhận kết nối DB và tạo ngay đối tượng AdminModel
     */
    public function __construct($db_connection) {
        $this->adminModel = new AdminModel($db_connection);
    }

    /**
     * Hàm hành động chính: Giờ chỉ lo bắt request, gọi Model lấy data, ném ra View
     */
    public function index() {
        try {
            // Controller ra lệnh cho Model
            $stats   = $this->adminModel->getOverviewStats();
            $reports = $this->adminModel->getReportsList();
            $members = $this->adminModel->getMembersList();
            
        } catch (Exception $e) {
            // Nếu có sự cố (Ví dụ rớt mạng mạng LAN Tailscale), trả mảng rỗng để đỡ sập giao diện
            $stats   = ['users' => 0, 'reports' => 0, 'posts' => 0, 'activity' => '0%'];
            $reports = [];
            $members = [];
        }

        // Bốc đống dữ liệu sạch sẽ từ Model, nạp file View lên để đổ dữ liệu ra
        require_once "C:/xampp/htdocs/PTUDW-N07-Social-network/Views/admin/index.php";
    }

    /**
     * Hàm helper phụ trợ giao diện thì vẫn giữ lại ở Controller bình thường
     */
    public function renderStatus($status) {
        $class = ($status == 'Chờ duyệt' || $status == 'Đã khóa') ? 'status-pending' : 'status-resolved';
        return "<span class='badge rounded-pill px-3 py-2 $class'>$status</span>";
    }
}
?>