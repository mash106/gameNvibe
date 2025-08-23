<?php
session_start();
require_once 'db.php';


if (!isset($_SESSION['user_id'])) {
    header("Location: loginpage.html");
    exit();
}

$user_id = $_SESSION['user_id'];


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $email = $_POST['email'];
    $bio = $_POST['bio'];
    
    try {
        $stmt = $pdo->prepare("UPDATE users SET email = ?, bio = ? WHERE id = ?");
        $stmt->execute([$email, $bio, $user_id]);
        
   
        $_SESSION['email'] = $email;
        
        $success_message = "Profile updated successfully!";
    } catch (PDOException $e) {
        $error_message = "Error updating profile: " . $e->getMessage();
    }
}


$stmt = $pdo->prepare("SELECT username, email, bio, created_at FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);


try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS owned_games (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        game_id INT NOT NULL,
        purchased_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id),
        FOREIGN KEY (game_id) REFERENCES games(id),
        UNIQUE KEY unique_ownership (user_id, game_id)
    )");
} catch (PDOException $e) {
   
}


$stmt = $pdo->prepare("
    SELECT g.*, og.purchased_at 
    FROM owned_games og 
    JOIN games g ON og.game_id = g.id 
    WHERE og.user_id = ? 
    ORDER BY og.purchased_at DESC
");
$stmt->execute([$user_id]);
$owned_games = $stmt->fetchAll(PDO::FETCH_ASSOC);


$stmt = $pdo->prepare("SELECT COUNT(*) FROM cart WHERE user_id = ?");
$stmt->execute([$user_id]);
$cart_count = $stmt->fetchColumn();


$games_owned = count($owned_games);
$reviews_written = 0; 
$forum_posts = 0; 
$hours_played = 0; 

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - gameNvibe</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            min-height: 100vh;
            color: #e2e8f0;
        }

        .header {
            background: rgba(15, 23, 42, 0.95);
            padding: 20px;
            border-bottom: 1px solid rgba(59, 130, 246, 0.2);
            display: flex;
            justify-content: space-between;
            align-items: center;
            backdrop-filter: blur(10px);
        }

        .logo {
            color: #3b82f6;
            font-size: 24px;
            font-weight: 700;
            text-decoration: none;
        }

        .nav-menu {
            display: flex;
            gap: 20px;
            align-items: center;
        }

        .nav-btn {
            background: rgba(30, 41, 59, 0.8);
            color: #e2e8f0;
            border: 1px solid rgba(59, 130, 246, 0.2);
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .nav-btn:hover, .nav-btn.active {
            background: rgba(59, 130, 246, 0.2);
            border-color: #3b82f6;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .main-content {
            padding: 30px 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .message {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
        }

        .success-message {
            background: rgba(34, 197, 94, 0.2);
            color: #22c55e;
            border: 1px solid rgba(34, 197, 94, 0.3);
        }

        .error-message {
            background: rgba(239, 68, 68, 0.2);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        .profile-header {
            background: rgba(15, 23, 42, 0.95);
            border-radius: 16px;
            padding: 40px;
            border: 1px solid rgba(59, 130, 246, 0.2);
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            gap: 30px;
            flex-wrap: wrap;
        }

        .profile-avatar {
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 48px;
            font-weight: 700;
            flex-shrink: 0;
        }

        .profile-info {
            flex: 1;
        }

        .profile-name {
            color: #e2e8f0;
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .profile-email {
            color: #94a3b8;
            font-size: 16px;
            margin-bottom: 15px;
        }

        .profile-stats {
            display: flex;
            gap: 30px;
            flex-wrap: wrap;
        }

        .stat-item {
            text-align: center;
        }

        .stat-value {
            color: #3b82f6;
            font-size: 24px;
            font-weight: 700;
            display: block;
        }

        .stat-label {
            color: #64748b;
            font-size: 12px;
            text-transform: uppercase;
        }

        .profile-sections {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
        }

        .section-card {
            background: rgba(15, 23, 42, 0.95);
            border-radius: 12px;
            padding: 25px;
            border: 1px solid rgba(59, 130, 246, 0.2);
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .section-title {
            color: #3b82f6;
            font-size: 20px;
            font-weight: 600;
        }

        .edit-btn {
            background: rgba(30, 41, 59, 0.8);
            color: #e2e8f0;
            border: 1px solid rgba(59, 130, 246, 0.2);
            padding: 6px 12px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 12px;
            text-decoration: none;
        }

        .edit-btn:hover {
            background: rgba(59, 130, 246, 0.2);
            border-color: #3b82f6;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            color: #e2e8f0;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 8px;
        }

        .form-group input, .form-group textarea {
            width: 100%;
            padding: 12px 16px;
            background: #1e293b;
            border: 2px solid #334155;
            border-radius: 8px;
            color: #e2e8f0;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .form-group input:focus, .form-group textarea:focus {
            outline: none;
            border-color: #3b82f6;
        }

        .form-group textarea {
            min-height: 80px;
            resize: vertical;
        }

        .btn-primary {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        }

        .activity-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .activity-item {
            background: rgba(30, 41, 59, 0.5);
            border-radius: 8px;
            padding: 15px;
            border: 1px solid rgba(59, 130, 246, 0.1);
        }

        .activity-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 5px;
        }

        .activity-type {
            color: #3b82f6;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .activity-date {
            color: #64748b;
            font-size: 11px;
        }

        .activity-content {
            color: #94a3b8;
            font-size: 14px;
        }

        .library-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
        }

        .game-item {
            background: rgba(30, 41, 59, 0.5);
            border-radius: 8px;
            padding: 15px;
            border: 1px solid rgba(59, 130, 246, 0.1);
            text-align: center;
        }

        .game-icon {
            background: #1e293b;
            border-radius: 8px;
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 10px;
            font-size: 32px;
            color: #64748b;
        }

        .game-name {
            color: #e2e8f0;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 5px;
        }

        .game-date {
            color: #64748b;
            font-size: 11px;
        }

        .full-width {
            grid-column: 1 / -1;
        }

        .empty-state {
            text-align: center;
            color: #64748b;
            font-style: italic;
            padding: 40px 20px;
        }

        .tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            border-bottom: 1px solid rgba(59, 130, 246, 0.2);
        }

        .tab {
            background: transparent;
            color: #64748b;
            border: none;
            padding: 10px 20px;
            border-bottom: 2px solid transparent;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .tab.active {
            color: #3b82f6;
            border-bottom-color: #3b82f6;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                gap: 15px;
            }

            .nav-menu {
                flex-wrap: wrap;
                justify-content: center;
            }

            .profile-header {
                text-align: center;
                flex-direction: column;
            }

            .profile-sections {
                grid-template-columns: 1fr;
            }

            .profile-stats {
                justify-content: center;
            }

            .library-grid {
                grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <a href="dashboard.php" class="logo">gameNvibe</a>
        <nav class="nav-menu">
            <a href="news.php" class="nav-btn">📰 News</a>
            <a href="games.php" class="nav-btn">🎮 Games</a>
            <a href="reviews.php" class="nav-btn">⭐ Reviews</a>
            <a href="forums.php" class="nav-btn">💬 Forums</a>
            <a href="profile.php" class="nav-btn active">👤 Profile</a>
            <a href="cart.php" class="nav-btn">🛒 Cart (<?php echo $cart_count; ?>)</a>
        </nav>
        <div class="user-info">
            <span>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</span>
            <a href="logout.php" class="nav-btn">Logout</a>
        </div>
    </header>

    <main class="main-content">
        <?php if (isset($success_message)): ?>
            <div class="message success-message">
                ✅ <?php echo htmlspecialchars($success_message); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($error_message)): ?>
            <div class="message error-message">
                ❌ <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>

        <div class="profile-header">
            <div class="profile-avatar">
                <?php echo strtoupper(substr($_SESSION['username'], 0, 1)); ?>
            </div>
            <div class="profile-info">
                <div class="profile-name"><?php echo htmlspecialchars($_SESSION['username']); ?></div>
                <div class="profile-email"><?php echo htmlspecialchars($_SESSION['email']); ?></div>
                <div class="profile-stats">
                    <div class="stat-item">
                        <span class="stat-value"><?php echo $games_owned; ?></span>
                        <span class="stat-label">Games Owned</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-value"><?php echo $reviews_written; ?></span>
                        <span class="stat-label">Reviews Written</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-value"><?php echo $forum_posts; ?></span>
                        <span class="stat-label">Forum Posts</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-value"><?php echo $hours_played; ?></span>
                        <span class="stat-label">Hours Played</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="profile-sections">
            <div class="section-card">
                <div class="section-header">
                    <h3 class="section-title">🔧 Account Settings</h3>
                    <button class="edit-btn" id="toggleEdit">Edit</button>
                </div>

                <form id="profileForm" method="POST">
                    <input type="hidden" name="update_profile" value="1">
                    
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($_SESSION['username']); ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($_SESSION['email']); ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label for="bio">Bio</label>
                        <textarea id="bio" name="bio" placeholder="Tell us about yourself..." readonly><?php echo htmlspecialchars($user['bio'] ?? ''); ?></textarea>
                    </div>

                    <button type="submit" class="btn-primary" id="saveBtn" style="display: none;">Save Changes</button>
                </form>
            </div>

            <div class="section-card">
                <div class="section-header">
                    <h3 class="section-title">📊 Recent Activity</h3>
                </div>

                <div class="activity-list">
                    <div class="activity-item">
                        <div class="activity-header">
                            <span class="activity-type">Account</span>
                            <span class="activity-date"><?php echo date('M j, Y', strtotime($user['created_at'])); ?></span>
                        </div>
                        <div class="activity-content">Account created successfully</div>
                    </div>

                    <?php if ($games_owned > 0): ?>
                    <div class="activity-item">
                        <div class="activity-header">
                            <span class="activity-type">Purchase</span>
                            <span class="activity-date">Recent</span>
                        </div>
                        <div class="activity-content">Purchased <?php echo $games_owned; ?> game<?php echo $games_owned > 1 ? 's' : ''; ?></div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="section-card full-width">
                <div class="section-header">
                    <h3 class="section-title">🎮 My Game Library</h3>
                </div>

                <div class="tabs">
                    <button class="tab active" data-tab="owned">Owned Games</button>
                </div>

                <div class="tab-content active" id="owned">
                    <?php if (empty($owned_games)): ?>
                        <div class="empty-state">
                            <p>No games in your library yet.<br>
                            <a href="games.php" style="color: #3b82f6;">Browse games</a> to start building your collection!</p>
                        </div>
                    <?php else: ?>
                        <div class="library-grid">
                            <?php foreach ($owned_games as $game): ?>
                                <div class="game-item">
                                    <div class="game-icon">🎮</div>
                                    <div class="game-name"><?php echo htmlspecialchars($game['name']); ?></div>
                                    <div class="game-date">Purchased: <?php echo date('M j, Y', strtotime($game['purchased_at'])); ?></div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="section-card">
                <div class="section-header">
                    <h3 class="section-title">⭐ My Reviews</h3>
                    <a href="reviews.php" class="edit-btn">Write Review</a>
                </div>

                <div class="empty-state">
                    <p>You haven't written any reviews yet.<br>
                    Share your gaming experiences with the community!</p>
                </div>
            </div>

            <div class="section-card">
                <div class="section-header">
                    <h3 class="section-title">💬 Forum Activity</h3>
                    <a href="forums.php" class="edit-btn">Visit Forums</a>
                </div>

                <div class="empty-state">
                    <p>No forum activity yet.<br>
                    Join discussions and connect with fellow gamers!</p>
                </div>
            </div>
        </div>
    </main>

    <script>
     
        document.getElementById('toggleEdit').addEventListener('click', function() {
            const form = document.getElementById('profileForm');
            const inputs = form.querySelectorAll('input, textarea');
            const saveBtn = document.getElementById('saveBtn');
            const isEditing = this.textContent === 'Edit';

            if (isEditing) {
             
                inputs.forEach(input => {
                    if (input.name !== 'username') { 
                        input.removeAttribute('readonly');
                        input.style.backgroundColor = '#0f172a';
                    }
                });
                this.textContent = 'Cancel';
                saveBtn.style.display = 'block';
            } else {
            
                location.reload();
            }
        });

      
        document.querySelectorAll('.tab').forEach(tab => {
            tab.addEventListener('click', function() {
                
                document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
                document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
                
                
                this.classList.add('active');
                
              
                const tabId = this.getAttribute('data-tab');
                document.getElementById(tabId).classList.add('active');
            });
        });
    </script>
</body>
</html>