<<<<<<< Updated upstream:Views/login.php
=======
<?php
// 1. Khởi động session để hứng và hiển thị thông báo lỗi/thành công từ Controller chuyển hướng về
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 2. Định nghĩa hằng số đường dẫn gốc hệ thống
if (!defined('BASE_URL')) {
    define("BASE_URL", "http://localhost:3000/");
}

// ĐÃ LOẠI BỎ ĐOẠN KHAI BÁO CLASS AUTHCONTROLLER SAI VỊ TRÍ TẠI ĐÂY
?>

>>>>>>> Stashed changes:Views/auth/login.php
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập | Social Network</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="CSS/login-style.css">
</head>
<body>

    <div class="login-container">
        <h2>Xin chào!</h2>
        <p class="subtitle">Vui lòng đăng nhập để kết nối với bạn bè</p>
      
        <?php if(isset($_SESSION['error'])): ?>
            <div style="color: #dc3545; padding: 8px; margin-bottom: 15px; font-size: 14px; text-align: center; background: rgba(220, 53, 69, 0.08); border-radius: 4px;">
                <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <?php if(isset($_SESSION['success'])): ?>
            <div style="color: #198754; padding: 8px; margin-bottom: 15px; font-size: 14px; text-align: center; background: rgba(25, 135, 84, 0.08); border-radius: 4px;">
                <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <form action="process-login.php" method="POST">
            <div class="form-group">
                <label for="username"><i class="fa-regular fa-user"></i> Tài khoản</label>
                <input type="text" id="username" name="username" placeholder="Tên đăng nhập hoặc Email" required>
            </div>

            <div class="form-group">
                <label for="password"><i class="fa-solid fa-lock"></i> Mật khẩu</label>
                <input type="password" id="password" name="password" placeholder="Mật khẩu của bạn" required>
            </div>

            <button type="submit" class="btn-login">ĐĂNG NHẬP NGAY</button>
        </form>

        <div class="divider">
            <span>HOẶC</span>
        </div>

        <div class="extra-links">
            <a href="forgot-password.php">Quên mật khẩu?</a>
            <a href="register.php" style="color: #d39399;">Đăng ký mới</a>
        </div>

        <br>
        <a href="index.php" class="back-home"><i class="fa-solid fa-house"></i> Về trang chủ</a>
    </div>

</body>
</html>