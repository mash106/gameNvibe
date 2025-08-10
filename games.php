<?php
session_start();

// Check if user is logged in (optional for viewing games)
$isLoggedIn = isset($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Games Library - gameNvibe</title>
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

        .search-filters {
            background: rgba(15, 23, 42, 0.95);
            border-radius: 12px;
            padding: 25px;
            border: 1px solid rgba(59, 130, 246, 0.2);
            margin-bottom: 30px;
        }

        .search-bar {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
        }

        .search-input {
            flex: 1;
            padding: 12px 16px;
            background: #1e293b;
            border: 2px solid #334155;
            border-radius: 8px;
            color: #e2e8f0;
            font-size: 16px;
        }

        .search-input:focus {
            outline: none;
            border-color: #3b82f6;
        }

        .search-btn {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
        }

        .search-btn:hover {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        }

        .filter-tags {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .filter-tag {
            background: rgba(30, 41, 59, 0.8);
            color: #e2e8f0;
            border: 1px solid rgba(59, 130, 246, 0.2);
            padding: 8px 16px;
            border-radius: 20px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .filter-tag:hover, .filter-tag.active {
            background: rgba(59, 130, 246, 0.2);
            border-color: #3b82f6;
        }

        .games-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
        }

        .game-card {
            background: rgba(15, 23, 42, 0.95);
            border-radius: 12px;
            padding: 20px;
            border: 1px solid rgba(59, 130, 246, 0.2);
            transition: all 0.3s ease;
        }

        .game-card:hover {
            transform: translateY(-5px);
            border-color: #3b82f6;
            box-shadow: 0 10px 25px rgba(59, 130, 246, 0.2);
        }

        .game-image {
            background: #1e293b;
            border-radius: 8px;
            height: 180px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
            color: #64748b;
            font-size: 48px;
        }

        .game-info h3 {
            color: #e2e8f0;
            font-size: 18px;
            margin-bottom: 8px;
        }

        .game-genre {
            color: #3b82f6;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .game-description {
            color: #94a3b8;
            font-size: 14px;
            line-height: 1.5;
            margin-bottom: 15px;
        }

        .game-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .game-price {
            color: #22c55e;
            font-size: 18px;
            font-weight: 700;
        }

        .game-rating {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .stars {
            color: #fbbf24;
        }

        .rating-text {
            color: #94a3b8;
            font-size: 12px;
        }

        .game-actions {
            display: flex;
            gap: 10px;
        }

        .btn-primary {
            flex: 1;
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            text-align: center;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        }

        .btn-secondary {
            background: rgba(30, 41, 59, 0.8);
            color: #e2e8f0;
            border: 1px solid rgba(59, 130, 246, 0.2);
            padding: 10px 16px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
        }

        .btn-secondary:hover {
            background: rgba(59, 130, 246, 0.2);
            border-color: #3b82f6;
        }

        .featured-game {
            grid-column: 1 / -1;
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.1) 0%, rgba(37, 99, 235, 0.1) 100%);
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 30px;
            padding: 30px;
            margin-bottom: 20px;
        }

        .featured-image {
            background: #1e293b;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #64748b;
            font-size: 64px;
            min-height: 200px;
        }

        .featured-content h2 {
            color: #3b82f6;
            font-size: 28px;
            margin-bottom: 10px;
        }

        .featured-content .game-genre {
            font-size: 14px;
            margin-bottom: 15px;
        }

        .featured-content .game-description {
            font-size: 16px;
            margin-bottom: 20px;
        }

        .featured-actions {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .featured-price {
            color: #22c55e;
            font-size: 24px;
            font-weight: 700;
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

            .search-bar {
                flex-direction: column;
            }

            .featured-game {
                grid-template-columns: 1fr;
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
            <a href="games.php" class="nav-btn active">🎮 Games</a>
            <a href="reviews.php" class="nav-btn">⭐ Reviews</a>
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
            <h1>🎮 Games Library</h1>
            <p>Discover and purchase amazing games from our extensive collection</p>
        </div>

        <div class="search-filters">
            <div class="search-bar">
                <input type="text" class="search-input" placeholder="Search for games...">
                <button class="search-btn">Search</button>
            </div>
            <div class="filter-tags">
                <span class="filter-tag active">All Games</span>
                <span class="filter-tag">Action</span>
                <span class="filter-tag">Adventure</span>
                <span class="filter-tag">RPG</span>
                <span class="filter-tag">Strategy</span>
                <span class="filter-tag">Simulation</span>
                <span class="filter-tag">Sports</span>
                <span class="filter-tag">Racing</span>
                <span class="filter-tag">Indie</span>
            </div>
        </div>

        <div class="games-grid">
            <div class="featured-game">
                <div class="featured-image">🏆</div>
                <div class="featured-content">
                    <h2>Featured Game: Epic Adventure Quest</h2>
                    <div class="game-genre">Action RPG</div>
                    <p class="game-description">Embark on an epic journey through mystical lands filled with dangerous creatures, ancient secrets, and legendary treasures. This award-winning RPG offers over 100 hours of gameplay with stunning visuals and immersive storytelling.</p>
                    <div class="featured-actions">
                        <span class="featured-price">$49.99</span>
                        <a href="#" class="btn-primary">Add to Cart</a>
                        <a href="#" class="btn-secondary">View Details</a>
                    </div>
                </div>
            </div>

            <div class="game-card">
                <div class="game-image">🎯</div>
                <div class="game-info">
                    <h3>Cyber Strike Elite</h3>
                    <div class="game-genre">First Person Shooter</div>
                    <p class="game-description">Fast-paced multiplayer shooter with advanced cyberpunk aesthetics and competitive gameplay.</p>
                    <div class="game-meta">
                        <span class="game-price">$29.99</span>
                        <div class="game-rating">
                            <span class="stars">⭐⭐⭐⭐⭐</span>
                            <span class="rating-text">(4.8)</span>
                        </div>
                    </div>
                    <div class="game-actions">
                        <?php if ($isLoggedIn): ?>
                            <a href="#" class="btn-primary">Add to Cart</a>
                        <?php else: ?>
                            <a href="loginpage.html" class="btn-primary">Login to Buy</a>
                        <?php endif; ?>
                        <a href="#" class="btn-secondary">Details</a>
                    </div>
                </div>
            </div>

            <div class="game-card">
                <div class="game-image">🏰</div>
                <div class="game-info">
                    <h3>Kingdom Builder Deluxe</h3>
                    <div class="game-genre">Strategy</div>
                    <p class="game-description">Build and manage your medieval kingdom in this deep strategy game with complex economics.</p>
                    <div class="game-meta">
                        <span class="game-price">$39.99</span>
                        <div class="game-rating">
                            <span class="stars">⭐⭐⭐⭐☆</span>
                            <span class="rating-text">(4.3)</span>
                        </div>
                    </div>
                    <div class="game-actions">
                        <?php if ($isLoggedIn): ?>
                            <a href="#" class="btn-primary">Add to Cart</a>
                        <?php else: ?>
                            <a href="loginpage.html" class="btn-primary">Login to Buy</a>
                        <?php endif; ?>
                        <a href="#" class="btn-secondary">Details</a>
                    </div>
                </div>
            </div>

            <div class="game-card">
                <div class="game-image">🚗</div>
                <div class="game-info">
                    <h3>Speed Racer Championship</h3>
                    <div class="game-genre">Racing</div>
                    <p class="game-description">High-octane racing action with realistic physics and customizable vehicles.</p>
                    <div class="game-meta">
                        <span class="game-price">$24.99</span>
                        <div class="game-rating">
                            <span class="stars">⭐⭐⭐⭐⭐</span>
                            <span class="rating-text">(4.7)</span>
                        </div>
                    </div>
                    <div class="game-actions">
                        <?php if ($isLoggedIn): ?>
                            <a href="#" class="btn-primary">Add to Cart</a>
                        <?php else: ?>
                            <a href="loginpage.html" class="btn-primary">Login to Buy</a>
                        <?php endif; ?>
                        <a href="#" class="btn-secondary">Details</a>
                    </div>
                </div>
            </div>

            <div class="game-card">
                <div class="game-image">🧩</div>
                <div class="game-info">
                    <h3>Puzzle Master Pro</h3>
                    <div class="game-genre">Puzzle</div>
                    <p class="game-description">Challenge your mind with hundreds of brain-teasing puzzles and mini-games.</p>
                    <div class="game-meta">
                        <span class="game-price">$14.99</span>
                        <div class="game-rating">
                            <span class="stars">⭐⭐⭐⭐☆</span>
                            <span class="rating-text">(4.2)</span>
                        </div>
                    </div>
                    <div class="game-actions">
                        <?php if ($isLoggedIn): ?>
                            <a href="#" class="btn-primary">Add to Cart</a>
                        <?php else: ?>
                            <a href="loginpage.html" class="btn-primary">Login to Buy</a>
                        <?php endif; ?>
                        <a href="#" class="btn-secondary">Details</a>
                    </div>
                </div>
            </div>

            <div class="game-card">
                <div class="game-image">🌟</div>
                <div class="game-info">
                    <h3>Indie Masterpiece</h3>
                    <div class="game-genre">Indie Adventure</div>
                    <p class="game-description">A beautifully crafted indie game with unique art style and emotional storytelling.</p>
                    <div class="game-meta">
                        <span class="game-price">$19.99</span>
                        <div class="game-rating">
                            <span class="stars">⭐⭐⭐⭐⭐</span>
                            <span class="rating-text">(4.9)</span>
                        </div>
                    </div>
                    <div class="game-actions">
                        <?php if ($isLoggedIn): ?>
                            <a href="#" class="btn-primary">Add to Cart</a>
                        <?php else: ?>
                            <a href="loginpage.html" class="btn-primary">Login to Buy</a>
                        <?php endif; ?>
                        <a href="#" class="btn-secondary">Details</a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Filter functionality
        document.querySelectorAll('.filter-tag').forEach(tag => {
            tag.addEventListener('click', function() {
                document.querySelectorAll('.filter-tag').forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                console.log('Filter selected:', this.textContent);
            });
        });

        // Search functionality
        document.querySelector('.search-btn').addEventListener('click', function() {
            const searchTerm = document.querySelector('.search-input').value;
            console.log('Searching for:', searchTerm);
        });

        // Cart functionality (placeholder)
        document.querySelectorAll('.btn-primary').forEach(btn => {
            if (btn.textContent === 'Add to Cart') {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    alert('Game added to cart!');
                });
            }
        });
    </script>
</body>
</html>