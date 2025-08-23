<?php
session_start();
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
            overflow: hidden;
        }

        .game-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 8px;
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
            overflow: hidden;
        }

        .featured-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 8px;
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

        .no-results {
            text-align: center;
            color: #94a3b8;
            font-size: 18px;
            padding: 40px;
            grid-column: 1 / -1;
        }

        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            background: rgba(34, 197, 94, 0.9);
            color: white;
            padding: 15px 20px;
            border-radius: 8px;
            font-weight: 600;
            z-index: 1000;
            transform: translateX(400px);
            transition: transform 0.3s ease;
        }

        .notification.show {
            transform: translateX(0);
        }

        .notification.error {
            background: rgba(239, 68, 68, 0.9);
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
                <a href="cart.php" class="nav-btn">🛒 Cart</a>
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
                <input type="text" class="search-input" placeholder="Search for games..." id="searchInput">
                <button class="search-btn" id="searchBtn">Search</button>
            </div>
            <div class="filter-tags">
                <span class="filter-tag active" data-filter="all">All Games</span>
                <span class="filter-tag" data-filter="action">Action</span>
                <span class="filter-tag" data-filter="sports">Sports</span>
                <span class="filter-tag" data-filter="racing">Racing</span>
                <span class="filter-tag" data-filter="simulation">Simulation</span>
                <span class="filter-tag" data-filter="indie">Indie</span>
            </div>
        </div>

        <div class="games-grid" id="gamesGrid">
           
            <div class="featured-game game-item" data-genre="action" data-name="valorant" data-price="Free to Play" data-rating="4.5" data-developer="Riot Games">
                <div class="featured-image">
                    <img src="valorant.jpg" alt="Valorant" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div style="display:none; width:100%; height:100%; align-items:center; justify-content:center; font-size:64px; color:#64748b;">🎮</div>
                </div>
                <div class="featured-content">
                    <h2>Valorant</h2>
                    <div class="game-genre">Action</div>
                    <p class="game-description">A 5v5 character-based tactical FPS where precise gunplay meets unique agent abilities. Master your weapon, perfect your ability, and lead your team to victory in this competitive shooter.</p>
                    <div class="featured-actions">
                        <span class="featured-price">Free to Play</span>
                        <button class="btn-primary play-now-btn">Play Now</button>
                        <button class="btn-secondary game-details-btn">View Details</button>
                    </div>
                </div>
            </div>

           
            <div class="game-card game-item" data-genre="sports" data-name="fifa 26" data-price="$69.99" data-rating="4.6" data-developer="EA Sports">
                <div class="game-image">
                    <img src="fifa 26.jpg" alt="FIFA 26" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div style="display:none; width:100%; height:100%; align-items:center; justify-content:center; font-size:48px; color:#64748b;">🎮</div>
                </div>
                <div class="game-info">
                    <h3>FIFA 26</h3>
                    <div class="game-genre">Sports</div>
                    <p class="game-description">Experience the most authentic football simulation with improved AI, realistic player movements, and enhanced career mode features.</p>
                    <div class="game-meta">
                        <span class="game-price">$69.99</span>
                        <div class="game-rating">
                            <span class="stars">⭐⭐⭐⭐⭐</span>
                            <span class="rating-text">(4.6)</span>
                        </div>
                    </div>
                    <div class="game-actions">
                        <?php if ($isLoggedIn): ?>
                            <button class="btn-primary add-to-cart-btn">Add to Cart</button>
                        <?php else: ?>
                            <a href="loginpage.html" class="btn-primary">Login to Buy</a>
                        <?php endif; ?>
                        <button class="btn-secondary game-details-btn">Details</button>
                    </div>
                </div>
            </div>

            <div class="game-card game-item" data-genre="action" data-name="gta vi" data-price="$79.99" data-rating="4.9" data-developer="Rockstar Games">
                <div class="game-image">
                    <img src="gta vi.jpg" alt="GTA VI" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div style="display:none; width:100%; height:100%; align-items:center; justify-content:center; font-size:48px; color:#64748b;">🎮</div>
                </div>
                <div class="game-info">
                    <h3>GTA VI</h3>
                    <div class="game-genre">Action</div>
                    <p class="game-description">The most ambitious open-world crime saga returns with unprecedented scale, featuring dual protagonists in the vibrant state of Leonida.</p>
                    <div class="game-meta">
                        <span class="game-price">$79.99</span>
                        <div class="game-rating">
                            <span class="stars">⭐⭐⭐⭐⭐</span>
                            <span class="rating-text">(4.9)</span>
                        </div>
                    </div>
                    <div class="game-actions">
                        <?php if ($isLoggedIn): ?>
                            <button class="btn-primary add-to-cart-btn">Add to Cart</button>
                        <?php else: ?>
                            <a href="loginpage.html" class="btn-primary">Login to Buy</a>
                        <?php endif; ?>
                        <button class="btn-secondary game-details-btn">Details</button>
                    </div>
                </div>
            </div>

            <div class="game-card game-item" data-genre="action" data-name="spider-man miles morales" data-price="$49.99" data-rating="4.8" data-developer="Insomniac Games">
                <div class="game-image">
                    <img src="spdrman.jpg" alt="Spider-Man Miles Morales" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div style="display:none; width:100%; height:100%; align-items:center; justify-content:center; font-size:48px; color:#64748b;">🎮</div>
                </div>
                <div class="game-info">
                    <h3>Spider-Man: Miles Morales</h3>
                    <div class="game-genre">Action</div>
                    <p class="game-description">Experience the rise of Miles Morales as he masters new powers to become his own Spider-Man in this spectacular superhero adventure.</p>
                    <div class="game-meta">
                        <span class="game-price">$49.99</span>
                        <div class="game-rating">
                            <span class="stars">⭐⭐⭐⭐⭐</span>
                            <span class="rating-text">(4.8)</span>
                        </div>
                    </div>
                    <div class="game-actions">
                        <?php if ($isLoggedIn): ?>
                            <button class="btn-primary add-to-cart-btn">Add to Cart</button>
                        <?php else: ?>
                            <a href="loginpage.html" class="btn-primary">Login to Buy</a>
                        <?php endif; ?>
                        <button class="btn-secondary game-details-btn">Details</button>
                    </div>
                </div>
            </div>

          
            <div class="game-card game-item" data-genre="action" data-name="black myth wukong" data-price="$59.99" data-rating="4.7" data-developer="Game Science">
                <div class="game-image">
                    <img src="blackmyth.jpg" alt="Black Myth: Wukong" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div style="display:none; width:100%; height:100%; align-items:center; justify-content:center; font-size:48px; color:#64748b;">🎮</div>
                </div>
                <div class="game-info">
                    <h3>Black Myth: Wukong</h3>
                    <div class="game-genre">Action</div>
                    <p class="game-description">An action RPG rooted in Chinese mythology, featuring spectacular visuals and intense combat as you embark on the legendary journey to the West.</p>
                    <div class="game-meta">
                        <span class="game-price">$59.99</span>
                        <div class="game-rating">
                            <span class="stars">⭐⭐⭐⭐⭐</span>
                            <span class="rating-text">(4.7)</span>
                        </div>
                    </div>
                    <div class="game-actions">
                        <?php if ($isLoggedIn): ?>
                            <button class="btn-primary add-to-cart-btn">Add to Cart</button>
                        <?php else: ?>
                            <a href="loginpage.html" class="btn-primary">Login to Buy</a>
                        <?php endif; ?>
                        <button class="btn-secondary game-details-btn">Details</button>
                    </div>
                </div>
            </div>

            <div class="game-card game-item" data-genre="action" data-name="red dead redemption 2" data-price="$39.99" data-rating="4.9" data-developer="Rockstar Games">
                <div class="game-image">
                    <img src="rdr2.jpg" alt="Red Dead Redemption 2" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div style="display:none; width:100%; height:100%; align-items:center; justify-content:center; font-size:48px; color:#64748b;">🎮</div>
                </div>
                <div class="game-info">
                    <h3>Red Dead Redemption 2</h3>
                    <div class="game-genre">Action</div>
                    <p class="game-description">An epic tale of outlaw Arthur Morgan and the Van der Linde gang in America's unforgiving heartland during the decline of the Wild West era.</p>
                    <div class="game-meta">
                        <span class="game-price">$39.99</span>
                        <div class="game-rating">
                            <span class="stars">⭐⭐⭐⭐⭐</span>
                            <span class="rating-text">(4.9)</span>
                        </div>
                    </div>
                    <div class="game-actions">
                        <?php if ($isLoggedIn): ?>
                            <button class="btn-primary add-to-cart-btn">Add to Cart</button>
                        <?php else: ?>
                            <a href="loginpage.html" class="btn-primary">Login to Buy</a>
                        <?php endif; ?>
                        <button class="btn-secondary game-details-btn">Details</button>
                    </div>
                </div>
            </div>

            <div class="game-card game-item" data-genre="racing" data-name="forza horizon 5" data-price="$59.99" data-rating="4.8" data-developer="Playground Games">
                <div class="game-image">
                    <img src="fh5.jpg" alt="Forza Horizon 5" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div style="display:none; width:100%; height:100%; align-items:center; justify-content:center; font-size:48px; color:#64748b;">🎮</div>
                </div>
                <div class="game-info">
                    <h3>Forza Horizon 5</h3>
                    <div class="game-genre">Racing</div>
                    <p class="game-description">Explore the vibrant and ever-evolving open world landscapes of Mexico with limitless, fun driving action in hundreds of the world's greatest cars.</p>
                    <div class="game-meta">
                        <span class="game-price">$59.99</span>
                        <div class="game-rating">
                            <span class="stars">⭐⭐⭐⭐⭐</span>
                            <span class="rating-text">(4.8)</span>
                        </div>
                    </div>
                    <div class="game-actions">
                        <?php if ($isLoggedIn): ?>
                            <button class="btn-primary add-to-cart-btn">Add to Cart</button>
                        <?php else: ?>
                            <a href="loginpage.html" class="btn-primary">Login to Buy</a>
                        <?php endif; ?>
                        <button class="btn-secondary game-details-btn">Details</button>
                    </div>
                </div>
            </div>

           
            <div class="game-card game-item" data-genre="simulation" data-name="minecraft" data-price="$29.99" data-rating="4.8" data-developer="Mojang Studios">
                <div class="game-image">
                    <img src="mncrft.jpg" alt="Minecraft" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div style="display:none; width:100%; height:100%; align-items:center; justify-content:center; font-size:48px; color:#64748b;">🎮</div>
                </div>
                <div class="game-info">
                    <h3>Minecraft</h3>
                    <div class="game-genre">Simulation</div>
                    <p class="game-description">Build, explore, and survive in randomly generated worlds. Create anything you can imagine with blocks in this beloved sandbox adventure.</p>
                    <div class="game-meta">
                        <span class="game-price">$29.99</span>
                        <div class="game-rating">
                            <span class="stars">⭐⭐⭐⭐⭐</span>
                            <span class="rating-text">(4.8)</span>
                        </div>
                    </div>
                    <div class="game-actions">
                        <?php if ($isLoggedIn): ?>
                            <button class="btn-primary add-to-cart-btn">Add to Cart</button>
                        <?php else: ?>
                            <a href="loginpage.html" class="btn-primary">Login to Buy</a>
                        <?php endif; ?>
                        <button class="btn-secondary game-details-btn">Details</button>
                    </div>
                </div>
            </div>

            <div class="game-card game-item" data-genre="indie" data-name="stardew valley" data-price="$14.99" data-rating="4.9" data-developer="ConcernedApe">
                <div class="game-image">
                    <img src="strdewvalley.jpg" alt="Stardew Valley" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div style="display:none; width:100%; height:100%; align-items:center; justify-content:center; font-size:48px; color:#64748b;">🎮</div>
                </div>
                <div class="game-info">
                    <h3>Stardew Valley</h3>
                    <div class="game-genre">Indie</div>
                    <p class="game-description">Escape to the countryside and build the farm of your dreams in this charming farming simulation with deep social elements and endless activities.</p>
                    <div class="game-meta">
                        <span class="game-price">$14.99</span>
                        <div class="game-rating">
                            <span class="stars">⭐⭐⭐⭐⭐</span>
                            <span class="rating-text">(4.9)</span>
                        </div>
                    </div>
                    <div class="game-actions">
                        <?php if ($isLoggedIn): ?>
                            <button class="btn-primary add-to-cart-btn">Add to Cart</button>
                        <?php else: ?>
                            <a href="loginpage.html" class="btn-primary">Login to Buy</a>
                        <?php endif; ?>
                        <button class="btn-secondary game-details-btn">Details</button>
                    </div>
                </div>
            </div>
        </div>
    </main>

   
    <div id="notification" class="notification"></div>

    <script>
        
        const gameItems = document.querySelectorAll('.game-item');
        const searchInput = document.getElementById('searchInput');
        const searchBtn = document.getElementById('searchBtn');
        const filterTags = document.querySelectorAll('.filter-tag');
        const gamesGrid = document.getElementById('gamesGrid');

       
        let allGames = Array.from(gameItems);
        let currentFilter = 'all';
        let currentSearch = '';

        
        filterTags.forEach(tag => {
            tag.addEventListener('click', function() {
               
                filterTags.forEach(t => t.classList.remove('active'));
                
                this.classList.add('active');
                
                currentFilter = this.dataset.filter;
                applyFilters();
            });
        });

    
        function performSearch() {
            currentSearch = searchInput.value.toLowerCase().trim();
            applyFilters();
        }


        function applyFilters() {
            let hasVisibleGames = false;
            
            gameItems.forEach(game => {
                let showGame = true;
                
            
                if (currentFilter !== 'all') {
                    const gameGenre = game.dataset.genre.toLowerCase();
                    if (gameGenre !== currentFilter) {
                        showGame = false;
                    }
                }
                
            
                if (currentSearch && showGame) {
                    const gameName = game.dataset.name.toLowerCase();
                    const gameGenre = game.dataset.genre.toLowerCase();
                    const gameDescription = game.querySelector('.game-description').textContent.toLowerCase();
                    const gameDeveloper = game.dataset.developer ? game.dataset.developer.toLowerCase() : '';
                    
                    if (!gameName.includes(currentSearch) && 
                        !gameGenre.includes(currentSearch) && 
                        !gameDescription.includes(currentSearch) &&
                        !gameDeveloper.includes(currentSearch)) {
                        showGame = false;
                    }
                }
                
            
                if (showGame) {
                    game.style.display = game.classList.contains('featured-game') ? 'grid' : 'block';
                    hasVisibleGames = true;
                } else {
                    game.style.display = 'none';
                }
            });
            
        
            let noResultsMsg = document.querySelector('.no-results');
            if (!hasVisibleGames) {
                if (!noResultsMsg) {
                    noResultsMsg = document.createElement('div');
                    noResultsMsg.className = 'no-results';
                    noResultsMsg.textContent = 'No games found matching your criteria.';
                    gamesGrid.appendChild(noResultsMsg);
                }
            } else if (noResultsMsg) {
                noResultsMsg.remove();
            }
        }

    
        searchBtn.addEventListener('click', performSearch);

    
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                performSearch();
            }
        });

    
        searchInput.addEventListener('input', function() {
            if (this.value === '') {
                currentSearch = '';
                applyFilters();
            }
        });

    
        document.querySelectorAll('.game-details-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                
                const gameCard = this.closest('.game-item');
                const gameName = gameCard.querySelector('h2, h3').textContent;
                const gameGenre = gameCard.dataset.genre;
                const gamePrice = gameCard.dataset.price;
                const gameRating = gameCard.dataset.rating;
                const gameDeveloper = gameCard.dataset.developer;
                const gameDescription = gameCard.querySelector('.game-description').textContent;
                
                const detailsMessage = `
🎮 GAME DETAILS 🎮

Title: ${gameName}
Genre: ${gameGenre.charAt(0).toUpperCase() + gameGenre.slice(1)}
Developer: ${gameDeveloper}
Price: ${gamePrice}
Rating: ${gameRating}/5 ⭐

Description: ${gameDescription}

Platform: PC, PlayStation, Xbox
Release Status: Available Now
System Requirements: Check store page for details`;
                
                alert(detailsMessage);
            });
        });

        document.querySelectorAll('.add-to-cart-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                
                const gameCard = this.closest('.game-item');
                const gameName = gameCard.dataset.name;
                const gameGenre = gameCard.dataset.genre;
                const gamePrice = gameCard.dataset.price;
                const gameRating = gameCard.dataset.rating;
                const gameDeveloper = gameCard.dataset.developer;
                const gameDescription = gameCard.querySelector('.game-description').textContent;
                
                fetch('add_to_cart.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams({
                        'game_name': gameName,
                        'game_genre': gameGenre,
                        'game_price': gamePrice,
                        'game_rating': gameRating,
                        'game_developer': gameDeveloper,
                        'game_description': gameDescription
                    })
                })
                .then(response => response.json())
                .then(data => {
                    showNotification(data.message, data.success ? 'success' : 'error');
                    if (data.success) {
                       
                        const originalText = this.textContent;
                        this.textContent = 'Added!';
                        this.disabled = true;
                        setTimeout(() => {
                            this.textContent = originalText;
                            this.disabled = false;
                        }, 2000);
                    }
                })
                .catch(error => {
                    showNotification('Error adding game to cart', 'error');
                });
            });
        });

   
        document.querySelectorAll('.play-now-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const gameCard = this.closest('.game-item');
                const gameName = gameCard.querySelector('h2, h3').textContent;
                
                alert(`Launching ${gameName}...\n\nRedirecting to game launcher!`);
            });
        });

       
        function showNotification(message, type = 'success') {
            const notification = document.getElementById('notification');
            notification.textContent = message;
            notification.className = `notification ${type}`;
            notification.classList.add('show');
            
            setTimeout(() => {
                notification.classList.remove('show');
            }, 3000);
        }

       
        document.addEventListener('DOMContentLoaded', function() {
            applyFilters();
        });
    </script>
</body>
</html>