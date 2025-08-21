<?php
session_start();

// Check if user is logged in (optional for news viewing)
$isLoggedIn = isset($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gaming News - gameNvibe</title>
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

        .news-filters {
            display: flex;
            gap: 15px;
            margin-bottom: 30px;
            flex-wrap: wrap;
            justify-content: center;
        }

        .filter-btn {
            background: rgba(30, 41, 59, 0.8);
            color: #e2e8f0;
            border: 1px solid rgba(59, 130, 246, 0.2);
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .filter-btn:hover, .filter-btn.active {
            background: rgba(59, 130, 246, 0.2);
            border-color: #3b82f6;
        }

        .news-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 25px;
        }

        .news-card {
            background: rgba(15, 23, 42, 0.95);
            border-radius: 12px;
            padding: 25px;
            border: 1px solid rgba(59, 130, 246, 0.2);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .news-card:hover {
            transform: translateY(-5px);
            border-color: #3b82f6;
            box-shadow: 0 10px 25px rgba(59, 130, 246, 0.2);
        }

        .news-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .news-category {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
        }

        .news-date {
            color: #64748b;
            font-size: 12px;
        }

        .news-card h3 {
            color: #e2e8f0;
            font-size: 20px;
            margin-bottom: 12px;
            line-height: 1.4;
        }

        .news-excerpt {
            color: #94a3b8;
            line-height: 1.6;
            margin-bottom: 15px;
        }

        .read-more {
            color: #3b82f6;
            text-decoration: none;
            font-weight: 500;
            font-size: 14px;
        }

        .read-more:hover {
            text-decoration: underline;
        }

        .featured-news {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.1) 0%, rgba(37, 99, 235, 0.1) 100%);
            grid-column: 1 / -1;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            padding: 30px;
            margin-bottom: 20px;
        }

        .featured-content h2 {
            color: #3b82f6;
            font-size: 28px;
            margin-bottom: 15px;
        }

        .featured-content p {
            color: #94a3b8;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .featured-image {
            background: #1e293b;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #64748b;
            font-size: 48px;
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

            .featured-news {
                grid-template-columns: 1fr;
            }

            .news-filters {
                justify-content: center;
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
            <a href="news.php" class="nav-btn active">📰 News</a>
            <a href="games.php" class="nav-btn">🎮 Games</a>
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
            <h1>📰 Gaming News</h1>
            <p>Stay updated with the latest gaming industry news and announcements</p>
        </div>

        <div class="news-filters">
            <button class="filter-btn active">All</button>
            <button class="filter-btn">PC Gaming</button>
            <button class="filter-btn">Console</button>
            <button class="filter-btn">Mobile</button>
            <button class="filter-btn">Industry</button>
            <button class="filter-btn">Reviews</button>
        </div>

        <div class="news-grid">
            <div class="featured-news">
                <div class="featured-content">
                    <h2>🔥 Breaking: Major Gaming Conference Announced</h2>
                    <p>The gaming industry is buzzing with excitement as the annual Gaming Expo 2025 has been officially announced. This year's event promises groundbreaking reveals, exclusive previews, and major announcements from top gaming companies worldwide.</p>
                    <a href="#" class="read-more">Read Full Story →</a>
                </div>
                <div class="featured-image">
                    <img src="gamingcnfrn.jpg">
                </div>
            </div>

            <div class="news-card">
                <div class="news-meta">
                    <span class="news-category">PC Gaming</span>
                    <span class="news-date">2 hours ago</span>
                </div>
                <h3>New Graphics Cards Deliver Unprecedented Gaming Performance</h3>
                <p class="news-excerpt">Latest GPU releases are pushing the boundaries of 4K gaming, offering ray tracing capabilities that bring photorealistic graphics to mainstream gaming.</p>
                <a href="#" class="read-more">Read More →</a>
            </div>

            <div class="news-card">
                <div class="news-meta">
                    <span class="news-category">Console</span>
                    <span class="news-date">4 hours ago</span>
                </div>
                <h3>Console Wars Heat Up with New Exclusive Titles</h3>
                <p class="news-excerpt">Major console manufacturers announce exclusive partnerships with renowned game developers, promising unique gaming experiences for their platforms.</p>
                <a href="#" class="read-more">Read More →</a>
            </div>

            <div class="news-card">
                <div class="news-meta">
                    <span class="news-category">Industry</span>
                    <span class="news-date">6 hours ago</span>
                </div>
                <h3>Gaming Industry Revenue Reaches Record Highs</h3>
                <p class="news-excerpt">New market research reveals that the gaming industry continues its explosive growth, surpassing traditional entertainment sectors in revenue generation.</p>
                <a href="#" class="read-more">Read More →</a>
            </div>

            <div class="news-card">
                <div class="news-meta">
                    <span class="news-category">Mobile</span>
                    <span class="news-date">8 hours ago</span>
                </div>
                <h3>Mobile Gaming Revolution: Cloud Gaming Goes Mainstream</h3>
                <p class="news-excerpt">Cloud gaming services are making console-quality games accessible on mobile devices, revolutionizing how we think about portable gaming.</p>
                <a href="#" class="read-more">Read More →</a>
            </div>

            <div class="news-card">
                <div class="news-meta">
                    <span class="news-category">Reviews</span>
                    <span class="news-date">12 hours ago</span>
                </div>
                <h3>Indie Game of the Month: Hidden Gems Discovered</h3>
                <p class="news-excerpt">Our review team highlights outstanding indie games that deserve recognition, showcasing creativity and innovation in the gaming space.</p>
                <a href="#" class="read-more">Read More →</a>
            </div>

            <div class="news-card">
                <div class="news-meta">
                    <span class="news-category">PC Gaming</span>
                    <span class="news-date">1 day ago</span>
                </div>
                <h3>Virtual Reality Gaming Enters New Era</h3>
                <p class="news-excerpt">Advanced VR headsets with improved resolution and haptic feedback are creating more immersive gaming experiences than ever before.</p>
                <a href="#" class="read-more">Read More →</a>
            </div>
        </div>
    </main>

    <script>
// Full news articles data
const newsArticles = {
    'breaking-gaming-expo': {
        title: '🔥 Breaking: Major Gaming Conference Announced',
        category: 'Industry',
        date: '1 hour ago',
        content: `
            <p>The gaming industry is buzzing with excitement as the annual Gaming Expo 2025 has been officially announced for September 15-18, 2025, at the Los Angeles Convention Center.</p>
            
            <p>This year's event promises to be the biggest yet, with over 500 exhibitors and an expected attendance of 200,000+ gaming enthusiasts from around the world. Major gaming companies including Sony Interactive Entertainment, Microsoft Xbox, Nintendo, and leading PC gaming manufacturers have confirmed their participation.</p>
            
            <p><strong>Key Highlights Expected:</strong></p>
            <ul>
                <li>Exclusive world premieres of highly anticipated games</li>
                <li>Next-generation console announcements</li>
                <li>VR and AR gaming technology showcases</li>
                <li>Esports tournaments with $2 million in prizes</li>
                <li>Developer panels and industry insights</li>
            </ul>
            
            <p>Early bird tickets are now available starting at $75 for general admission, with VIP packages offering exclusive access to developer meet-and-greets and behind-the-scenes experiences.</p>
            
            <p>"This year's Gaming Expo will showcase the future of interactive entertainment," said conference director Sarah Martinez. "We're excited to bring together the global gaming community for this unprecedented celebration of gaming culture."</p>
        `
    },
    'graphics-cards': {
        title: 'New Graphics Cards Deliver Unprecedented Gaming Performance',
        category: 'PC Gaming',
        date: '2 hours ago',
        content: `
            <p>The latest generation of graphics cards has officially launched, setting new benchmarks for 4K gaming performance and ray tracing capabilities that were previously unimaginable.</p>
            
            <p>Leading GPU manufacturer NVIDIA's new RTX 5080 series delivers up to 40% better performance than its predecessor, while AMD's competing RX 8800 XT offers exceptional value for high-end gaming at 1440p and 4K resolutions.</p>
            
            <p><strong>Key Performance Improvements:</strong></p>
            <ul>
                <li>Native 4K gaming at 120+ FPS in most titles</li>
                <li>Advanced ray tracing with minimal performance impact</li>
                <li>AI-powered upscaling technology (DLSS 4.0 / FSR 4.0)</li>
                <li>Enhanced power efficiency - 20% better performance per watt</li>
                <li>Support for next-gen gaming features like hardware-accelerated mesh shaders</li>
            </ul>
            
            <p>Early benchmarks show these cards can handle the most demanding games like Cyberpunk 2077 with full ray tracing enabled at 4K resolution while maintaining smooth 60+ FPS gameplay.</p>
            
            <p>Industry analysts predict these GPUs will drive the adoption of 4K gaming monitors and push developers to create more visually stunning games that take advantage of the increased horsepower.</p>
            
            <p>Pricing starts at $699 for entry-level models, with flagship cards reaching $1,599 for the most powerful configurations.</p>
        `
    },
    'console-wars': {
        title: 'Console Wars Heat Up with New Exclusive Titles',
        category: 'Console',
        date: '4 hours ago',
        content: `
            <p>The competition between major console manufacturers has intensified dramatically with the announcement of several high-profile exclusive partnerships that promise to deliver unique gaming experiences.</p>
            
            <p>Sony Interactive Entertainment has secured exclusive rights to three major franchises, while Microsoft Xbox has countered with Game Pass ultimate additions and backwards compatibility enhancements. Nintendo continues to dominate the portable gaming space with innovative titles designed specifically for the Switch platform.</p>
            
            <p><strong>Major Exclusive Announcements:</strong></p>
            <ul>
                <li><strong>PlayStation 5:</strong> Exclusive partnership with FromSoftware for a new dark fantasy RPG</li>
                <li><strong>Xbox Series X/S:</strong> Bethesda's next major RPG confirmed as Xbox/PC exclusive</li>
                <li><strong>Nintendo Switch:</strong> New Mario and Zelda titles announced for 2025</li>
                <li><strong>Steam Deck:</strong> Enhanced compatibility with Anti-Cheat systems</li>
            </ul>
            
            <p>These exclusive deals are reshaping how gamers choose their preferred gaming platform. Market research indicates that exclusive titles are now the primary factor influencing console purchase decisions, even more than hardware specifications.</p>
            
            <p>"Console exclusives drive platform differentiation and create compelling reasons for gamers to choose one ecosystem over another," explains gaming industry analyst Dr. Michael Chen. "We're seeing the most competitive landscape since the early 2000s."</p>
            
            <p>The battle extends beyond just exclusive games, with each platform offering unique services, social features, and backwards compatibility options to attract and retain users.</p>
        `
    },
    'industry-revenue': {
        title: 'Gaming Industry Revenue Reaches Record Highs',
        category: 'Industry',
        date: '6 hours ago',
        content: `
            <p>The global gaming industry has shattered all previous revenue records, generating an unprecedented $218.8 billion in 2024, representing a 15.3% increase from the previous year and surpassing the combined revenue of movies and music industries.</p>
            
            <p>This explosive growth is driven by several key factors including the widespread adoption of mobile gaming, the success of live service games, and the emergence of new monetization models like battle passes and cosmetic microtransactions.</p>
            
            <p><strong>Revenue Breakdown by Segment:</strong></p>
            <ul>
                <li><strong>Mobile Gaming:</strong> $116.4 billion (53.2% of total)</li>
                <li><strong>PC Gaming:</strong> $45.8 billion (20.9% of total)</li>
                <li><strong>Console Gaming:</strong> $56.6 billion (25.9% of total)</li>
            </ul>
            
            <p>The mobile gaming sector continues to lead growth, particularly in emerging markets where smartphone adoption has made gaming accessible to millions of new players. Popular titles like "Honor of Kings" and "PUBG Mobile" have generated billions in revenue through in-app purchases.</p>
            
            <p>Live service games have proven to be particularly lucrative, with titles like Fortnite, League of Legends, and Call of Duty: Warzone maintaining massive player bases and consistent revenue streams through seasonal content and battle passes.</p>
            
            <p>"The gaming industry has fundamentally transformed from a product-based business to a service-based ecosystem," notes industry economist Dr. Lisa Park. "This shift has created unprecedented revenue opportunities and changed how we think about game development and player engagement."</p>
            
            <p>Looking ahead, industry projections suggest the gaming market will continue its rapid expansion, potentially reaching $300 billion by 2027 as new technologies like cloud gaming and VR become mainstream.</p>
        `
    },
    'mobile-cloud': {
        title: 'Mobile Gaming Revolution: Cloud Gaming Goes Mainstream',
        category: 'Mobile',
        date: '8 hours ago',
        content: `
            <p>Cloud gaming technology has reached a tipping point, with major services now offering console-quality gaming experiences on mobile devices, fundamentally changing how we approach portable gaming.</p>
            
            <p>Services like Xbox Cloud Gaming, NVIDIA GeForce Now, and Google Stadia have significantly improved their mobile offerings, providing access to AAA titles that were previously impossible to play on smartphones and tablets.</p>
            
            <p><strong>Key Cloud Gaming Developments:</strong></p>
            <ul>
                <li>5G network rollout enabling low-latency streaming</li>
                <li>Advanced compression algorithms reducing bandwidth requirements</li>
                <li>Mobile-optimized control schemes for touchscreen gaming</li>
                <li>Cross-platform save synchronization across all devices</li>
                <li>Integration with popular mobile accessories and controllers</li>
            </ul>
            
            <p>The technology has become so refined that competitive esports players are now using cloud gaming for practice and even tournament play. Input latency has been reduced to under 20 milliseconds on 5G networks, making the experience virtually indistinguishable from local gaming.</p>
            
            <p>Major mobile device manufacturers are taking notice, with Samsung, Apple, and other companies optimizing their devices specifically for cloud gaming performance. New smartphones feature enhanced cooling systems, low-latency displays, and improved wireless connectivity.</p>
            
            <p>"Cloud gaming represents the democratization of high-end gaming," explains technology journalist Alex Rodriguez. "Players no longer need expensive hardware to enjoy the latest games - just a good internet connection and a compatible device."</p>
            
            <p>Market analysts predict that cloud gaming will account for 25% of all gaming revenue by 2027, as the technology becomes more accessible and affordable for mainstream consumers.</p>
        `
    },
    'indie-gems': {
        title: 'Indie Game of the Month: Hidden Gems Discovered',
        category: 'Reviews',
        date: '12 hours ago',
        content: `
            <p>Our editorial team has spent countless hours exploring the indie gaming landscape to uncover exceptional titles that deserve recognition beyond their modest marketing budgets.</p>
            
            <p>This month's selection showcases the incredible creativity and innovation that independent developers bring to the gaming industry, proving that groundbreaking experiences don't always require massive budgets or large development teams.</p>
            
            <p><strong>Featured Indie Games:</strong></p>
            <ul>
                <li><strong>"Ethereal Echoes" (PC/Switch):</strong> A hauntingly beautiful puzzle-platformer with hand-drawn animations and an emotional narrative about memory and loss. Rating: 9/10</li>
                <li><strong>"Neon Nights" (PC/PS5/Xbox):</strong> A cyberpunk detective thriller combining investigation mechanics with fast-paced combat. Rating: 8.5/10</li>
                <li><strong>"Garden of Whispers" (PC/Mobile):</strong> A relaxing farming simulation with magical elements and stunning watercolor visuals. Rating: 8/10</li>
                <li><strong>"Quantum Leap" (PC/VR):</strong> An innovative puzzle game that uses quantum mechanics as core gameplay mechanics. Rating: 9.5/10</li>
            </ul>
            
            <p>What makes these titles special is their willingness to experiment with unconventional gameplay mechanics and storytelling approaches that larger studios often avoid due to market risk concerns.</p>
            
            <p>"Indie games are the R&D department of the gaming industry," says indie gaming curator Maria Santos. "They push boundaries and explore new possibilities that eventually influence mainstream game development."</p>
            
            <p>These games are available on various platforms with prices ranging from $9.99 to $24.99, offering exceptional value for the unique experiences they provide. Many also support their developers through additional content updates and community engagement.</p>
            
            <p>We encourage gamers to explore these hidden gems and support independent developers who are shaping the future of interactive entertainment.</p>
        `
    },
    'vr-gaming': {
        title: 'Virtual Reality Gaming Enters New Era',
        category: 'PC Gaming',
        date: '1 day ago',
        content: `
            <p>The virtual reality gaming landscape has been revolutionized with the launch of next-generation VR headsets featuring unprecedented visual fidelity, advanced haptic feedback systems, and wireless capabilities that eliminate the barriers that previously limited VR adoption.</p>
            
            <p>Leading VR manufacturers have addressed the key pain points that kept mainstream gamers away from virtual reality: setup complexity, motion sickness, and limited game libraries have all been significantly improved.</p>
            
            <p><strong>Next-Gen VR Features:</strong></p>
            <ul>
                <li><strong>4K per eye displays</strong> with 120Hz refresh rates for crystal-clear visuals</li>
                <li><strong>Inside-out tracking</strong> eliminating the need for external sensors</li>
                <li><strong>Advanced haptic suits</strong> providing full-body tactile feedback</li>
                <li><strong>Wireless connectivity</strong> with sub-20ms latency</li>
                <li><strong>Eye tracking technology</strong> enabling foveated rendering and natural interaction</li>
                <li><strong>Hand tracking</strong> allowing controller-free gameplay</li>
            </ul>
            
            <p>The game library has expanded dramatically, with over 500 VR-native titles now available across all major platforms. AAA studios are finally investing in VR development, with several major franchises announcing VR adaptations or spin-offs.</p>
            
            <p>Popular VR titles like "Half-Life: Alyx," "Beat Saber," and "Resident Evil 4 VR" have demonstrated the platform's potential, while new releases like "Horizon Call of the Mountain" and "Asgard's Wrath 2" are pushing the boundaries of what's possible in virtual reality.</p>
            
            <p>"We're at the iPhone moment for VR," explains VR industry veteran Dr. Sarah Kim. "The technology has matured to the point where it's accessible, affordable, and offers compelling experiences that can't be replicated on traditional gaming platforms."</p>
            
            <p>Market research indicates that VR gaming is projected to grow by 45% annually over the next three years, with enterprise applications and social VR experiences driving additional adoption beyond traditional gaming.</p>
        `
    }
};

// Filter functionality
document.querySelectorAll('.filter-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        // Remove active class from all buttons
        document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
        // Add active class to clicked button
        this.classList.add('active');
        
        const selectedFilter = this.textContent.trim();
        filterNewsCards(selectedFilter);
    });
});

function filterNewsCards(filter) {
    const newsCards = document.querySelectorAll('.news-card');
    const featuredNews = document.querySelector('.featured-news');
    
    newsCards.forEach(card => {
        const category = card.querySelector('.news-category');
        
        if (filter === 'All') {
            card.style.display = 'block';
        } else {
            if (category && category.textContent.trim() === filter) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        }
    });
    
    // Always show featured news for 'All' and 'Industry' filters
    if (filter === 'All' || filter === 'Industry') {
        featuredNews.style.display = 'grid';
    } else {
        featuredNews.style.display = 'none';
    }
}

// Create modal for full articles
function createModal() {
    const modal = document.createElement('div');
    modal.id = 'newsModal';
    modal.innerHTML = `
        <div class="modal-overlay">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 id="modalTitle"></h2>
                    <button class="modal-close">&times;</button>
                </div>
                <div class="modal-meta">
                    <span id="modalCategory"></span>
                    <span id="modalDate"></span>
                </div>
                <div class="modal-body" id="modalBody"></div>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
    
    // Add modal styles
    const modalStyles = document.createElement('style');
    modalStyles.textContent = `
        #newsModal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1000;
        }
        
        .modal-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(5px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .modal-content {
            background: #0f172a;
            border: 1px solid rgba(59, 130, 246, 0.2);
            border-radius: 12px;
            max-width: 800px;
            max-height: 90vh;
            width: 100%;
            overflow-y: auto;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5);
        }
        
        .modal-header {
            padding: 25px 25px 15px;
            border-bottom: 1px solid rgba(59, 130, 246, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 20px;
        }
        
        .modal-header h2 {
            color: #e2e8f0;
            font-size: 24px;
            margin: 0;
            line-height: 1.3;
        }
        
        .modal-close {
            background: none;
            border: none;
            color: #64748b;
            font-size: 28px;
            cursor: pointer;
            padding: 0;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: all 0.3s ease;
            flex-shrink: 0;
        }
        
        .modal-close:hover {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
        }
        
        .modal-meta {
            padding: 15px 25px;
            display: flex;
            gap: 15px;
            align-items: center;
            border-bottom: 1px solid rgba(59, 130, 246, 0.1);
        }
        
        #modalCategory {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
        }
        
        #modalDate {
            color: #64748b;
            font-size: 14px;
        }
        
        .modal-body {
            padding: 25px;
            color: #94a3b8;
            line-height: 1.7;
            font-size: 16px;
        }
        
        .modal-body p {
            margin-bottom: 20px;
        }
        
        .modal-body ul {
            margin: 20px 0;
            padding-left: 20px;
        }
        
        .modal-body li {
            margin-bottom: 8px;
        }
        
        .modal-body strong {
            color: #e2e8f0;
        }
        
        @media (max-width: 768px) {
            .modal-content {
                margin: 10px;
                max-height: 95vh;
            }
            
            .modal-header h2 {
                font-size: 20px;
            }
            
            .modal-body {
                padding: 20px;
                font-size: 15px;
            }
        }
    `;
    
    document.head.appendChild(modalStyles);
    
    // Close modal functionality
    modal.querySelector('.modal-close').addEventListener('click', closeModal);
    modal.querySelector('.modal-overlay').addEventListener('click', function(e) {
        if (e.target === this) closeModal();
    });
    
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeModal();
    });
}

function openModal(articleKey, title, category, date) {
    const modal = document.getElementById('newsModal');
    const article = newsArticles[articleKey];
    
    if (article) {
        document.getElementById('modalTitle').textContent = article.title;
        document.getElementById('modalCategory').textContent = article.category;
        document.getElementById('modalDate').textContent = article.date;
        document.getElementById('modalBody').innerHTML = article.content;
    } else {
        // Fallback for articles without full content
        document.getElementById('modalTitle').textContent = title;
        document.getElementById('modalCategory').textContent = category;
        document.getElementById('modalDate').textContent = date;
        document.getElementById('modalBody').innerHTML = '<p>Full article content coming soon...</p>';
    }
    
    modal.style.display = 'block';
    document.body.style.overflow = 'hidden'; // Prevent background scrolling
}

function closeModal() {
    const modal = document.getElementById('newsModal');
    modal.style.display = 'none';
    document.body.style.overflow = 'auto'; // Restore background scrolling
}

// Initialize modal when page loads
document.addEventListener('DOMContentLoaded', function() {
    createModal();
    
    // Add click handlers for read more buttons
    const readMoreButtons = document.querySelectorAll('.read-more');
    readMoreButtons.forEach((button, index) => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const newsCard = this.closest('.news-card, .featured-news');
            const title = newsCard.querySelector('h2, h3').textContent;
            const category = newsCard.querySelector('.news-category').textContent;
            const date = newsCard.querySelector('.news-date').textContent;
            
            // Map articles to their keys based on index or title
            const articleKeys = [
                'breaking-gaming-expo', // Featured article
                'graphics-cards',       // First card
                'console-wars',         // Second card  
                'industry-revenue',     // Third card
                'mobile-cloud',         // Fourth card
                'indie-gems',          // Fifth card
                'vr-gaming'            // Sixth card
            ];
            
            const articleKey = articleKeys[index] || null;
            openModal(articleKey, title, category, date);
        });
    });
    
    // Add click handlers for news cards (optional - opens modal when clicking anywhere on card)
    document.querySelectorAll('.news-card').forEach((card, index) => {
        card.addEventListener('click', function(e) {
            // Don't trigger if clicking on read more button
            if (e.target.classList.contains('read-more')) return;
            
            const title = card.querySelector('h3').textContent;
            const category = card.querySelector('.news-category').textContent;
            const date = card.querySelector('.news-date').textContent;
            
            const articleKeys = [
                'graphics-cards',
                'console-wars', 
                'industry-revenue',
                'mobile-cloud',
                'indie-gems',
                'vr-gaming'
            ];
            
            const articleKey = articleKeys[index] || null;
            openModal(articleKey, title, category, date);
        });
    });
});
    </script>
</body>
</html>