<?php
session_start();
require_once 'db.php';


$isLoggedIn = isset($_SESSION['user_id']);


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_review']) && $isLoggedIn) {
    $user_id = $_SESSION['user_id'];
    $game_id = $_POST['game_id'];
    $rating = $_POST['rating'];
    $review_title = $_POST['review_title'];
    $review_content = $_POST['review_content'];
    
    try {
    
        $stmt = $pdo->prepare("SELECT id FROM reviews WHERE user_id = ? AND game_id = ?");
        $stmt->execute([$user_id, $game_id]);
        
        if ($stmt->fetch()) {
            $error_message = "You have already reviewed this game!";
        } else {
           
            $stmt = $pdo->prepare("
                INSERT INTO reviews (user_id, game_id, rating, title, content, created_at) 
                VALUES (?, ?, ?, ?, ?, NOW())
            ");
            $stmt->execute([$user_id, $game_id, $rating, $review_title, $review_content]);
            $success_message = "Review submitted successfully!";
        }
    } catch (PDOException $e) {
        $error_message = "Error submitting review: " . $e->getMessage();
    }
}


try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS reviews (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        game_id INT NOT NULL,
        rating INT NOT NULL CHECK (rating >= 1 AND rating <= 5),
        title VARCHAR(255) NOT NULL,
        content TEXT NOT NULL,
        helpful_votes INT DEFAULT 0,
        unhelpful_votes INT DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id),
        FOREIGN KEY (game_id) REFERENCES games(id),
        UNIQUE KEY unique_user_game_review (user_id, game_id)
    )");
} catch (PDOException $e) {
    
}


$filter = $_GET['filter'] ?? 'all';


$where_clause = "";
$params = [];

if ($filter !== 'all' && $filter !== 'recent' && $filter !== 'highest_rated') {
    $where_clause = "WHERE g.genre LIKE ?";
    $params[] = "%$filter%";
}


$order_clause = "ORDER BY r.created_at DESC";
if ($filter === 'highest_rated') {
    $order_clause = "ORDER BY r.rating DESC, r.created_at DESC";
}

$stmt = $pdo->prepare("
    SELECT r.*, g.name as game_name, g.genre, g.price, u.username
    FROM reviews r 
    JOIN games g ON r.game_id = g.id 
    JOIN users u ON r.user_id = u.id 
    $where_clause
    $order_clause
");
$stmt->execute($params);
$reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);


$stmt = $pdo->prepare("SELECT id, name, genre FROM games ORDER BY name");
$stmt->execute();
$games = $stmt->fetchAll(PDO::FETCH_ASSOC);


$cart_count = 0;
if ($isLoggedIn) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM cart WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $cart_count = $stmt->fetchColumn();
}


function getStarDisplay($rating) {
    $stars = '';
    for ($i = 1; $i <= 5; $i++) {
        if ($i <= $rating) {
            $stars .= '⭐';
        } else {
            $stars .= '☆';
        }
    }
    return $stars;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Game Reviews - gameNvibe</title>
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

        .page-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .page-header h1 {
            color: #3b82f6;
            font-size: 36px;
            margin-bottom: 10px;
        }

        .page-header p {
            color: #94a3b8;
            font-size: 18px;
        }

        .review-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .filter-options {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .filter-btn {
            background: rgba(30, 41, 59, 0.8);
            color: #e2e8f0;
            border: 1px solid rgba(59, 130, 246, 0.2);
            padding: 8px 16px;
            border-radius: 20px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .filter-btn:hover, .filter-btn.active {
            background: rgba(59, 130, 246, 0.2);
            border-color: #3b82f6;
        }

        .write-review-btn {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
        }

        .write-review-btn:hover {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        }

        .review-form {
            background: rgba(15, 23, 42, 0.95);
            border-radius: 12px;
            padding: 25px;
            border: 1px solid rgba(59, 130, 246, 0.2);
            margin-bottom: 30px;
            display: none;
        }

        .review-form.active {
            display: block;
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

        .form-group select,
        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 12px 16px;
            background: #1e293b;
            border: 2px solid #334155;
            border-radius: 8px;
            color: #e2e8f0;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .form-group select:focus,
        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #3b82f6;
        }

        .rating-input {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .star-rating {
            display: flex;
            gap: 5px;
        }

        .star {
            font-size: 24px;
            color: #64748b;
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .star:hover,
        .star.active {
            color: #fbbf24;
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

        .btn-secondary {
            background: rgba(30, 41, 59, 0.8);
            color: #e2e8f0;
            border: 1px solid rgba(59, 130, 246, 0.2);
            padding: 12px 24px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            margin-left: 10px;
        }

        .reviews-container {
            display: grid;
            gap: 25px;
        }

        .review-card {
            background: rgba(15, 23, 42, 0.95);
            border-radius: 12px;
            padding: 25px;
            border: 1px solid rgba(59, 130, 246, 0.2);
            transition: all 0.3s ease;
        }

        .review-card:hover {
            border-color: #3b82f6;
            box-shadow: 0 5px 15px rgba(59, 130, 246, 0.1);
        }

        .review-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 15px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .game-info {
            flex: 1;
        }

        .game-title {
            color: #e2e8f0;
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .game-genre {
            color: #3b82f6;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .review-rating {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
        }

        .stars {
            color: #fbbf24;
            font-size: 18px;
            margin-bottom: 5px;
        }

        .rating-score {
            color: #94a3b8;
            font-size: 14px;
        }

        .reviewer-info {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
        }

        .reviewer-avatar {
            width: 40px;
            height: 40px;
            background: #3b82f6;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
        }

        .reviewer-details {
            flex: 1;
        }

        .reviewer-name {
            color: #e2e8f0;
            font-weight: 500;
            margin-bottom: 2px;
        }

        .review-date {
            color: #64748b;
            font-size: 12px;
        }

        .review-content {
            color: #94a3b8;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .review-title {
            color: #e2e8f0;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .review-actions-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 15px;
            border-top: 1px solid rgba(59, 130, 246, 0.1);
        }

        .review-votes {
            display: flex;
            gap: 15px;
        }

        .vote-btn {
            background: transparent;
            border: none;
            color: #64748b;
            cursor: pointer;
            font-size: 14px;
            transition: color 0.3s ease;
        }

        .vote-btn:hover {
            color: #3b82f6;
        }

        .review-meta {
            color: #64748b;
            font-size: 12px;
        }

        .no-reviews {
            text-align: center;
            color: #64748b;
            padding: 60px 20px;
        }

        .no-reviews h3 {
            color: #94a3b8;
            font-size: 24px;
            margin-bottom: 15px;
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

            .review-actions {
                flex-direction: column;
                align-items: stretch;
            }

            .filter-options {
                justify-content: center;
            }

            .review-header {
                flex-direction: column;
                align-items: stretch;
            }

            .review-rating {
                align-items: flex-start;
            }

            .page-header h1 {
                font-size: 28px;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <a href="<?php echo $isLoggedIn ? 'dashboard.php' : 'loginpage.html'; ?>" class="logo">gameNvibe</a>
        <nav class="nav-menu">
            <a href="news.php" class="nav-btn">📰 News</a>
            <a href="games.php" class="nav-btn">🎮 Games</a>
            <a href="reviews.php" class="nav-btn active">⭐ Reviews</a>
            <a href="forums.php" class="nav-btn">💬 Forums</a>
            <?php if ($isLoggedIn): ?>
                <a href="profile.php" class="nav-btn">👤 Profile</a>
                <a href="cart.php" class="nav-btn">🛒 Cart (<?php echo $cart_count; ?>)</a>
            <?php endif; ?>
        </nav>
        <div class="user-info">
            <?php if ($isLoggedIn): ?>
                <span>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</span>
                <a href="logout.php" class="nav-btn">Logout</a>
            <?php else: ?>
                <a href="loginpage.html" class="nav-btn">Login</a>
                <a href="signuppage.html" class="nav-btn">Sign Up</a>
            <?php endif; ?>
        </div>
    </header>

    <main class="main-content">
        <div class="page-header">
            <h1>⭐ Game Reviews</h1>
            <p>Read honest reviews from the gaming community</p>
        </div>

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

        <div class="review-actions">
            <div class="filter-options">
                <a href="?filter=all" class="filter-btn <?php echo $filter === 'all' ? 'active' : ''; ?>">All Reviews</a>
                <a href="?filter=recent" class="filter-btn <?php echo $filter === 'recent' ? 'active' : ''; ?>">Recent</a>
                <a href="?filter=highest_rated" class="filter-btn <?php echo $filter === 'highest_rated' ? 'active' : ''; ?>">Highest Rated</a>
                <a href="?filter=Action" class="filter-btn <?php echo $filter === 'Action' ? 'active' : ''; ?>">Action</a>
                <a href="?filter=RPG" class="filter-btn <?php echo $filter === 'RPG' ? 'active' : ''; ?>">RPG</a>
                <a href="?filter=Strategy" class="filter-btn <?php echo $filter === 'Strategy' ? 'active' : ''; ?>">Strategy</a>
                <a href="?filter=Racing" class="filter-btn <?php echo $filter === 'Racing' ? 'active' : ''; ?>">Racing</a>
                <a href="?filter=Puzzle" class="filter-btn <?php echo $filter === 'Puzzle' ? 'active' : ''; ?>">Puzzle</a>
            </div>
            <?php if ($isLoggedIn): ?>
                <button class="write-review-btn" id="toggleReviewForm">✏️ Write Review</button>
            <?php else: ?>
                <a href="loginpage.html" class="write-review-btn">Login to Review</a>
            <?php endif; ?>
        </div>

        <?php if ($isLoggedIn): ?>
        <div class="review-form" id="reviewForm">
            <h3 style="color: #3b82f6; margin-bottom: 20px;">Write a Review</h3>
            <form method="POST">
                <div class="form-group">
                    <label for="game_id">Select Game</label>
                    <select id="game_id" name="game_id" required>
                        <option value="">Choose a game...</option>
                        <?php foreach ($games as $game): ?>
                            <option value="<?php echo $game['id']; ?>"><?php echo htmlspecialchars($game['name']); ?> (<?php echo htmlspecialchars($game['genre']); ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Rating</label>
                    <div class="rating-input">
                        <div class="star-rating">
                            <span class="star" data-rating="1">⭐</span>
                            <span class="star" data-rating="2">⭐</span>
                            <span class="star" data-rating="3">⭐</span>
                            <span class="star" data-rating="4">⭐</span>
                            <span class="star" data-rating="5">⭐</span>
                        </div>
                        <input type="hidden" id="rating" name="rating" value="" required>
                        <span id="rating-text">Select a rating</span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="review_title">Review Title</label>
                    <input type="text" id="review_title" name="review_title" placeholder="Brief title for your review" required>
                </div>

                <div class="form-group">
                    <label for="review_content">Your Review</label>
                    <textarea id="review_content" name="review_content" rows="6" placeholder="Share your thoughts about this game..." required></textarea>
                </div>

                <button type="submit" name="submit_review" class="btn-primary">Submit Review</button>
                <button type="button" class="btn-secondary" id="cancelReview">Cancel</button>
            </form>
        </div>
        <?php endif; ?>

        <div class="reviews-container">
            <?php if (empty($reviews)): ?>
                <div class="no-reviews">
                    <h3>No reviews found</h3>
                    <p>Be the first to review a game!</p>
                </div>
            <?php else: ?>
                <?php foreach ($reviews as $review): ?>
                    <div class="review-card">
                        <div class="review-header">
                            <div class="game-info">
                                <div class="game-title"><?php echo htmlspecialchars($review['game_name']); ?></div>
                                <div class="game-genre"><?php echo htmlspecialchars($review['genre']); ?></div>
                            </div>
                            <div class="review-rating">
                                <div class="stars"><?php echo getStarDisplay($review['rating']); ?></div>
                                <div class="rating-score"><?php echo $review['rating']; ?>.0/5</div>
                            </div>
                        </div>
                        <div class="reviewer-info">
                            <div class="reviewer-avatar"><?php echo strtoupper(substr($review['username'], 0, 1)); ?></div>
                            <div class="reviewer-details">
                                <div class="reviewer-name"><?php echo htmlspecialchars($review['username']); ?></div>
                                <div class="review-date"><?php echo date('M j, Y', strtotime($review['created_at'])); ?></div>
                            </div>
                        </div>
                        <div class="review-title"><?php echo htmlspecialchars($review['title']); ?></div>
                        <div class="review-content"><?php echo htmlspecialchars($review['content']); ?></div>
                        <div class="review-actions-bar">
                            <div class="review-votes">
                                <button class="vote-btn" data-type="helpful">👍 <?php echo $review['helpful_votes']; ?></button>
                                <button class="vote-btn" data-type="unhelpful">👎 <?php echo $review['unhelpful_votes']; ?></button>
                            </div>
                            <div class="review-meta">Verified Purchase</div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>

    <script>
       
        <?php if ($isLoggedIn): ?>
        document.getElementById('toggleReviewForm').addEventListener('click', function() {
            const form = document.getElementById('reviewForm');
            form.classList.toggle('active');
            this.textContent = form.classList.contains('active') ? 'Cancel' : '✏️ Write Review';
        });

        document.getElementById('cancelReview').addEventListener('click', function() {
            const form = document.getElementById('reviewForm');
            const toggleBtn = document.getElementById('toggleReviewForm');
            form.classList.remove('active');
            toggleBtn.textContent = '✏️ Write Review';
        });

        
        const stars = document.querySelectorAll('.star');
        const ratingInput = document.getElementById('rating');
        const ratingText = document.getElementById('rating-text');

        stars.forEach(star => {
            star.addEventListener('click', function() {
                const rating = this.dataset.rating;
                ratingInput.value = rating;
                ratingText.textContent = `${rating} star${rating > 1 ? 's' : ''}`;
                
                
                stars.forEach((s, index) => {
                    if (index < rating) {
                        s.classList.add('active');
                    } else {
                        s.classList.remove('active');
                    }
                });
            });
        });
        <?php endif; ?>

        
        document.querySelectorAll('.vote-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                <?php if ($isLoggedIn): ?>
                   
                    this.style.color = this.style.color === 'rgb(59, 130, 246)' ? '#64748b' : '#3b82f6';
                    console.log('Vote clicked:', this.dataset.type);
                <?php else: ?>
                    alert('Please login to vote on reviews');
                    window.location.href = 'loginpage.html';
                <?php endif; ?>
            });
        });
    </script>
</body>
</html