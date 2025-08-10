<?php
session_start();

// Check if user is logged in (optional for viewing reviews)
$isLoggedIn = isset($_SESSION['user_id']);
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

        .featured-review {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.1) 0%, rgba(37, 99, 235, 0.1) 100%);
            border: 1px solid rgba(59, 130, 246, 0.3);
        }

        .featured-badge {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
            margin-bottom: 10px;
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

        <div class="review-actions">
            <div class="filter-options">
                <button class="filter-btn active">All Reviews</button>
                <button class="filter-btn">Recent</button>
                <button class="filter-btn">Highest Rated</button>
                <button class="filter-btn">Action</button>
                <button class="filter-btn">RPG</button>
                <button class="filter-btn">Strategy</button>
            </div>
            <?php if ($isLoggedIn): ?>
                <a href="#" class="write-review-btn">✏️ Write Review</a>
            <?php else: ?>
                <a href="loginpage.html" class="write-review-btn">Login to Review</a>
            <?php endif; ?>
        </div>

        <div class="reviews-container">
            <div class="review-card featured-review">
                <div class="featured-badge">⭐ Featured Review</div>
                <div class="review-header">
                    <div class="game-info">
                        <div class="game-title">Epic Adventure Quest</div>
                        <div class="game-genre">Action RPG</div>
                    </div>
                    <div class="review-rating">
                        <div class="stars">⭐⭐⭐⭐⭐</div>
                        <div class="rating-score">4.8/5</div>
                    </div>
                </div>
                <div class="reviewer-info">
                    <div class="reviewer-avatar">SB</div>
                    <div class="reviewer-details">
                        <div class="reviewer-name">StrategyBuilder</div>
                        <div class="review-date">2 weeks ago</div>
                    </div>
                </div>
                <div class="review-title">Deep Strategy with Endless Replayability</div>
                <div class="review-content">
                    This is exactly what I was looking for in a strategy game. The economic systems are complex but intuitive, and every decision has meaningful consequences. The tutorial does a great job of introducing new players to the mechanics. I've been playing for months and still discovering new strategies.
                </div>
                <div class="review-actions-bar">
                    <div class="review-votes">
                        <button class="vote-btn">👍 67</button>
                        <button class="vote-btn">👎 5</button>
                    </div>
                    <div class="review-meta">Verified Purchase</div>
                </div>
            </div>

            <div class="review-card">
                <div class="review-header">
                    <div class="game-info">
                        <div class="game-title">Speed Racer Championship</div>
                        <div class="game-genre">Racing</div>
                    </div>
                    <div class="review-rating">
                        <div class="stars">⭐⭐⭐☆☆</div>
                        <div class="rating-score">3.5/5</div>
                    </div>
                </div>
                <div class="reviewer-info">
                    <div class="reviewer-avatar">RC</div>
                    <div class="reviewer-details">
                        <div class="reviewer-name">RacingChamp</div>
                        <div class="review-date">3 weeks ago</div>
                    </div>
                </div>
                <div class="review-title">Good Racing but Lacks Innovation</div>
                <div class="review-content">
                    The racing mechanics are solid and the car customization is decent. However, it feels like I've played this game before. The track selection is limited and the AI could be more challenging. It's enjoyable but not groundbreaking.
                </div>
                <div class="review-actions-bar">
                    <div class="review-votes">
                        <button class="vote-btn">👍 42</button>
                        <button class="vote-btn">👎 18</button>
                    </div>
                    <div class="review-meta">Verified Purchase</div>
                </div>
            </div>

            <div class="review-card">
                <div class="review-header">
                    <div class="game-info">
                        <div class="game-title">Indie Masterpiece</div>
                        <div class="game-genre">Indie Adventure</div>
                    </div>
                    <div class="review-rating">
                        <div class="stars">⭐⭐⭐⭐⭐</div>
                        <div class="rating-score">4.9/5</div>
                    </div>
                </div>
                <div class="reviewer-info">
                    <div class="reviewer-avatar">IL</div>
                    <div class="reviewer-details">
                        <div class="reviewer-name">IndieLover</div>
                        <div class="review-date">1 month ago</div>
                    </div>
                </div>
                <div class="review-title">Emotional Journey with Beautiful Art</div>
                <div class="review-content">
                    This indie gem touched my heart in ways I didn't expect. The art style is absolutely gorgeous, and the narrative deals with deep themes while remaining accessible. The puzzle mechanics perfectly complement the storytelling. A true work of art that deserves more recognition.
                </div>
                <div class="review-actions-bar">
                    <div class="review-votes">
                        <button class="vote-btn">👍 156</button>
                        <button class="vote-btn">👎 2</button>
                    </div>
                    <div class="review-meta">Verified Purchase</div>
                </div>
            </div>

            <div class="review-card">
                <div class="review-header">
                    <div class="game-info">
                        <div class="game-title">Puzzle Master Pro</div>
                        <div class="game-genre">Puzzle</div>
                    </div>
                    <div class="review-rating">
                        <div class="stars">⭐⭐⭐⭐☆</div>
                        <div class="rating-score">4.1/5</div>
                    </div>
                </div>
                <div class="reviewer-info">
                    <div class="reviewer-avatar">PM</div>
                    <div class="reviewer-details">
                        <div class="reviewer-name">PuzzleMaster</div>
                        <div class="review-date">1 month ago</div>
                    </div>
                </div>
                <div class="review-title">Great Brain Training with Variety</div>
                <div class="review-content">
                    Perfect for daily brain training! The variety of puzzle types keeps things interesting, and the difficulty progression is well-balanced. Some puzzles can be quite challenging, which I appreciate. The hint system is helpful without being too easy.
                </div>
                <div class="review-actions-bar">
                    <div class="review-votes">
                        <button class="vote-btn">👍 73</button>
                        <button class="vote-btn">👎 8</button>
                    </div>
                    <div class="review-meta">Verified Purchase</div>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Filter functionality
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                console.log('Filter selected:', this.textContent);
            });
        });

        // Vote functionality
        document.querySelectorAll('.vote-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                <?php if ($isLoggedIn): ?>
                    // Toggle vote styling
                    this.style.color = this.style.color === 'rgb(59, 130, 246)' ? '#64748b' : '#3b82f6';
                    console.log('Vote clicked:', this.textContent);
                <?php else: ?>
                    alert('Please login to vote on reviews');
                    window.location.href = 'loginpage.html';
                <?php endif; ?>
            });
        });

        // Write review functionality
        document.querySelector('.write-review-btn').addEventListener('click', function(e) {
            <?php if ($isLoggedIn): ?>
                e.preventDefault();
                alert('Write Review feature coming soon!');
            <?php endif; ?>
        });
    </script>
</body>
</html>score">5.0/5</div>
                    </div>
                </div>
                <div class="reviewer-info">
                    <div class="reviewer-avatar">GM</div>
                    <div class="reviewer-details">
                        <div class="reviewer-name">GameMaster_Pro</div>
                        <div class="review-date">3 days ago</div>
                    </div>
                </div>
                <div class="review-title">A Masterpiece of Modern Gaming</div>
                <div class="review-content">
                    This game exceeded all my expectations. The storyline is captivating, the graphics are stunning, and the gameplay mechanics are incredibly well-designed. I've spent over 80 hours exploring the world and I'm still discovering new secrets. The character development system is deep and rewarding, making every choice feel meaningful. Definitely a must-play for any RPG fan.
                </div>
                <div class="review-actions-bar">
                    <div class="review-votes">
                        <button class="vote-btn">👍 124</button>
                        <button class="vote-btn">👎 3</button>
                    </div>
                    <div class="review-meta">Verified Purchase</div>
                </div>
            </div>

            <div class="review-card">
                <div class="review-header">
                    <div class="game-info">
                        <div class="game-title">Cyber Strike Elite</div>
                        <div class="game-genre">First Person Shooter</div>
                    </div>
                    <div class="review-rating">
                        <div class="stars">⭐⭐⭐⭐☆</div>
                        <div class="rating-score">4.2/5</div>
                    </div>
                </div>
                <div class="reviewer-info">
                    <div class="reviewer-avatar">FP</div>
                    <div class="reviewer-details">
                        <div class="reviewer-name">FPS_Legend</div>
                        <div class="review-date">1 week ago</div>
                    </div>
                </div>
                <div class="review-title">Solid Shooter with Great Multiplayer</div>
                <div class="review-content">
                    The multiplayer component is fantastic with smooth matchmaking and balanced gameplay. The cyberpunk aesthetic is well-executed and the weapon customization options are extensive. However, the single-player campaign feels a bit short. Overall, it's a solid addition to the FPS genre.
                </div>
                <div class="review-actions-bar">
                    <div class="review-votes">
                        <button class="vote-btn">👍 89</button>
                        <button class="vote-btn">👎 12</button>
                    </div>
                    <div class="review-meta">Verified Purchase</div>
                </div>
            </div>

            <div class="review-card">
                <div class="review-header">
                    <div class="game-info">
                        <div class="game-title">Kingdom Builder Deluxe</div>
                        <div class="game-genre">Strategy</div>
                    </div>
                    <div class="review-rating">
                        <div class="stars">⭐⭐⭐⭐⭐</div>
                        <div class="rating-