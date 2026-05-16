<?php
session_start();

$profileName = "Thanh Tuyen";
$profileUsername = "@thanhtuyen";
$profileBio = "Mình thích giao diện tối giản, nhẹ nhàng và lưu lại những suy nghĩ nhỏ mỗi ngày.";
$profileAvatar = "https://i.pravatar.cc/140?img=12";

$totalPosts = 24;
$totalFollowing = 842;
$totalFollowers = "1.2K";

$posts = [
    [
        "name" => "Thanh Tuyen",
        "time" => "2 giờ",
        "avatar" => "https://i.pravatar.cc/60?img=12",
        "content" => "Có những ngày chỉ cần một góc nhỏ đủ yên để viết vài dòng là thấy nhẹ hơn.",
        "image" => "",
        "likes" => 18,
        "comments" => 5,
        "shares" => 2
    ],
    [
        "name" => "Thanh Tuyen",
        "time" => "1 ngày",
        "avatar" => "https://i.pravatar.cc/60?img=12",
        "content" => "Mình đang thử thiết kế một giao diện mạng xã hội tối giản.",
        "image" => "https://images.unsplash.com/photo-1516321318423-f06f85e504b3",
        "likes" => 26,
        "comments" => 8,
        "shares" => 3
    ]
];
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Archive - Hồ sơ</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:wght@600;700;800&display=swap" rel="stylesheet">
    
    <!-- CSS -->
    <link rel="stylesheet" href="CSS/style.css">
    <link rel="stylesheet" href="CSS/profile.css">
</head>

<body class="profile-page">

<!-- HEADER -->
<header class="archive-header">
    <div class="container-fluid px-4 px-lg-5">
        <div class="row align-items-center py-3">

            <div class="col-4 d-flex align-items-center">
                <div class="brand-logo">ARCHIVE</div>
            </div>

            <div class="col-4 d-flex justify-content-center">
                <div class="header-badge">
                    <i class="bi bi-stars"></i>
                </div>
            </div>

            <div class="col-4 d-flex justify-content-end">
                <div class="header-actions">
                    <a href="feed.php" class="header-search-btn">
                        <i class="bi bi-house-door"></i>
                    </a>

                    <a href="#" class="header-star-btn">
                        <i class="bi bi-star"></i>
                    </a>

                    <a href="profile.php" class="header-login-btn">
                        <i class="bi bi-person-circle"></i>
                        <span>Hồ sơ</span>
                    </a>
                </div>
            </div>

        </div>
    </div>
</header>

<!-- PROFILE -->
<section class="profile-section py-5">
<div class="container-fluid px-3 px-lg-4">
<div class="row g-4">

    <!-- SIDEBAR -->
    <div class="col-lg-1 d-none d-lg-block">
        <aside class="left-sidebar d-flex flex-column align-items-center gap-4">
            <div class="sidebar-logo">
                <i class="bi bi-circle-square"></i>
            </div>

            <a href="feed.php" class="sidebar-icon">
                <i class="bi bi-house-door-fill"></i>
            </a>

            <a href="#" class="sidebar-icon">
                <i class="bi bi-search"></i>
            </a>

            <a href="#" class="sidebar-icon">
                <i class="bi bi-plus-square"></i>
            </a>

            <a href="#" class="sidebar-icon">
                <i class="bi bi-heart"></i>
            </a>

            <a href="profile.php" class="sidebar-icon active">
                <i class="bi bi-person"></i>
            </a>

            <a href="#" class="sidebar-icon mt-auto">
                <i class="bi bi-pin-angle"></i>
            </a>
        </aside>
    </div>

    <!-- PROFILE CARD -->
    <div class="col-lg-3">
        <div class="bg-white p-4 profile-card text-center h-100">

            <img src="<?php echo $profileAvatar; ?>" class="profile-avatar mb-3" alt="Avatar">

            <h2 class="profile-name"><?php echo $profileName; ?></h2>
            <p class="profile-username"><?php echo $profileUsername; ?></p>

            <p class="profile-bio">
                <?php echo $profileBio; ?>
            </p>

            <div class="d-flex justify-content-center gap-3 mt-3 flex-wrap">
                <button class="btn btn-pink px-4">Chỉnh sửa trang cá nhân</button>
            </div>

            <div class="row text-center mt-4 g-3">
                <div class="col-4">
                    <div class="profile-stat-box">
                        <h5><?php echo $totalPosts; ?></h5>
                        <p>Bài viết</p>
                    </div>
                </div>

                <div class="col-4">
                    <div class="profile-stat-box">
                        <h5><?php echo $totalFollowing; ?></h5>
                        <p>Theo dõi</p>
                    </div>
                </div>

                <div class="col-4">
                    <div class="profile-stat-box">
                        <h5><?php echo $totalFollowers; ?></h5>
                        <p>Follower</p>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- POSTS -->
    <div class="col-lg-8">

        <div class="bg-light p-4 profile-intro-card mb-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h3 class="profile-section-title">Bài viết gần đây</h3>
                    <p class="text-muted mb-0">Những điều bạn đã lưu lại.</p>
                </div>

                <div class="d-flex gap-2">
                    <button class="profile-tab active">Tất cả</button>
                    <button class="profile-tab">Ảnh</button>
                    <button class="profile-tab">Yêu thích</button>
                </div>
            </div>
        </div>

        <?php foreach ($posts as $post): ?>
            <div class="bg-white post-card mb-3">
                <div class="p-3">
                    <div class="d-flex gap-3">
                        <img src="<?php echo $post['avatar']; ?>" class="avatar" alt="Avatar">

                        <div>
                            <div class="fw-semibold">
                                <?php echo $post['name']; ?> • <?php echo $post['time']; ?>
                            </div>

                            <p class="post-text">
                                <?php echo $post['content']; ?>
                            </p>

                            <?php if (!empty($post['image'])): ?>
                                <img src="<?php echo $post['image']; ?>" class="profile-post-image mt-2" alt="Post image">
                            <?php endif; ?>

                            <div class="post-actions d-flex gap-4 mt-2">
                                <button>
                                    <i class="bi bi-heart"></i> <?php echo $post['likes']; ?>
                                </button>

                                <button>
                                    <i class="bi bi-chat"></i> <?php echo $post['comments']; ?>
                                </button>

                                <button>
                                    <i class="bi bi-arrow-repeat"></i> <?php echo $post['shares']; ?>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

    </div>

</div>
</div>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>