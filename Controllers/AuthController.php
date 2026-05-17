<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../Models/User.php';

class AuthController {
    private $conn;
    private $userModel;

    public function __construct($db_connection) {
        $this->conn = $db_connection;
        $this->userModel = new User($db_connection);
    }

    // Xử lý Đăng ký tài khoản
    public function registerProcess() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
           
            $name = trim($_POST['fullname'] ?? $_POST['name'] ?? '');
            $username = trim($_POST['username'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';

           
            if (empty($name) || empty($username) || empty($email) || empty($password)) {
                $_SESSION['error'] = "Vui lòng nhập đầy đủ tất cả các trường.";
                header("Location: " . BASE_URL . "Views/auth/register.php");
                exit();
            }

            if ($password !== $confirm_password) {
                $_SESSION['error'] = "Mật khẩu xác nhận không trùng khớp.";
                header("Location: " . BASE_URL . "Views/auth/register.php");
                exit();
            }

            if ($this->userModel->exists($username, $email)) {
                $_SESSION['error'] = "Tài khoản hoặc Email này đã tồn tại trên hệ thống.";
                header("Location: " . BASE_URL . "Views/auth/register.php");
                exit();
            }

            // Tiến hành lưu vào CSDL
            if ($this->userModel->register($name, $username, $email, $password)) {
                $_SESSION['success'] = "Đăng ký thành công! Hãy đăng nhập.";
                header("Location: " . BASE_URL . "Views/auth/login.php");
                exit();
            } else {
                $_SESSION['error'] = "Đã xảy ra lỗi trong quá trình đăng ký.";
                header("Location: " . BASE_URL . "Views/auth/register.php");
                exit();
            }
        }
    }

    // Xử lý Đăng nhập & Phân quyền
    
    public function loginProcess() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';

            if (empty($username) || empty($password)) {
                $_SESSION['error'] = "Tài khoản và mật khẩu không được để trống.";
                header("Location: " . BASE_URL . "Views/auth/login.php");
                exit();
            }

            $user = $this->userModel->findByCredentials($username);

            // TẠM SỬA TẠI ĐÂY: So sánh chuỗi thuần trực tiếp giống hệt database (Khớp cả cột PasswordHash hoặc Password)
            $db_password = $user['PasswordHash'] ?? $user['Password'] ?? '';

            if ($user && $password === $db_password) {
                
                // Thiết lập Session đăng nhập
                $_SESSION['user_id'] = $user['UserID'];
                $_SESSION['username'] = $user['Username'];
                $_SESSION['user_name'] = $user['FullName'] ?? $user['Name'] ?? '';
                $_SESSION['role_name'] = $user['RoleName'] ?? 'Thành viên'; 

                // LOGIC ĐIỀU HƯỚNG VÀO ADMIN TRỰC TIẾP
                if ($_SESSION['role_name'] === 'Quản trị viên' || (isset($user['RoleID']) && $user['RoleID'] == 1)) {
                    // Nếu là Admin -> Vào khu vực quản trị views/admin/index.php
                    header("Location: " . BASE_URL . "Views/admin/index.php");
                } else {
                    // Nếu là User thường -> Trỏ ra index.php ở gốc
                    header("Location: " . BASE_URL . "Views/feed.php");
                }
                exit();
            } else {
                $_SESSION['error'] = "Tài khoản hoặc mật khẩu không chính xác.";
                header("Location: " . BASE_URL . "Views/auth/login.php");
                exit();
            }
        }
    }

    // Xử lý Quên mật khẩu
    public function forgotPasswordProcess() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $new_password = $_POST['new_password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';

         
            if (empty($email) || empty($new_password)) {
                $_SESSION['error'] = "Vui lòng nhập đầy đủ thông tin.";
                header("Location: " . BASE_URL . "Views/auth/forgotpassword.php");
                exit();
            }

            if ($new_password !== $confirm_password) {
                $_SESSION['error'] = "Mật khẩu mới xác nhận không khớp.";
                header("Location: " . BASE_URL . "Views/auth/forgotpassword.php");
                exit();
            }

            $user = $this->userModel->findByCredentials($email);
            if (!$user) {
                $_SESSION['error'] = "Không tìm thấy tài khoản nào liên kết với Email này.";
                header("Location: " . BASE_URL . "Views/auth/forgotpassword.php");
                exit();
            }

            if ($this->userModel->updatePassword($email, $new_password)) {
                $_SESSION['success'] = "Đổi mật khẩu thành công! Hãy đăng nhập lại bằng mật khẩu mới.";
                header("Location: " . BASE_URL . "Views/auth/login.php");
                exit();
            } else {
                $_SESSION['error'] = "Không thể cập nhật mật khẩu lúc này.";
                header("Location: " . BASE_URL . "Views/auth/forgotpassword.php");
                exit();
            }
        }
    }

    // Đăng xuất xóa session
    public function logout() {
        session_destroy();
        header("Location: " . BASE_URL . "Views/auth/login.php");
        exit();
    }
}
?>