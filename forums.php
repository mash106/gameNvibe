<?php
session_start();


$isLoggedIn = isset($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Community Forums - gameNvibe</title>
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

        .forum-categories {
            display: grid;
            gap: 20px;
            margin-bottom: 40px;
        }

        .category-section {
            background: rgba(15, 23, 42, 0.95);
            border-radius: 12px;
            padding: 25px;
            border: 1px solid rgba(59, 130, 246, 0.2);
        }

        .category-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .category-title {
            color: #3b82f6;
            font-size: 24px;
            font-weight: 600;
        }

        .new-topic-btn {
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

        .new-topic-btn:hover {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        }

        .forum-list {
            display: grid;
            gap: 15px;
        }

        .forum-item {
            background: rgba(30, 41, 59, 0.5);
            border-radius: 8px;
            padding: 20px;
            border: 1px solid rgba(59, 130, 246, 0.1);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .forum-item:hover {
            border-color: #3b82f6;
            background: rgba(30, 41, 59, 0.7);
        }

        .forum-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 10px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .forum-info {
            flex: 1;
        }

        .forum-title {
            color: #e2e8f0;
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .forum-description {
            color: #94a3b8;
            font-size: 14px;
            line-height: 1.4;
        }

        .forum-stats {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 5px;
        }

        .stat-item {
            color: #64748b;
            font-size: 12px;
        }

        .stat-value {
            color: #3b82f6;
            font-weight: 600;
        }

        .recent-topics {
            margin-top: 30px;
        }

        .topics-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .topics-title {
            color: #3b82f6;
            font-size: 24px;
            font-weight: 600;
        }

        .topic-item {
            background: rgba(15, 23, 42, 0.95);
            border-radius: 8px;
            padding: 20px;
            border: 1px solid rgba(59, 130, 246, 0.2);
            margin-bottom: 15px;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .topic-item:hover {
            border-color: #3b82f6;
            transform: translateY(-2px);
        }

        .topic-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 10px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .topic-info {
            flex: 1;
        }

        .topic-title {
            color: #e2e8f0;
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .topic-meta {
            display: flex;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
        }

        .topic-author {
            color: #3b82f6;
            font-size: 14px;
        }

        .topic-date {
            color: #64748b;
            font-size: 12px;
        }

        .topic-category {
            background: rgba(59, 130, 246, 0.2);
            color: #3b82f6;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
        }

        .topic-stats {
            display: flex;
            gap: 20px;
            align-items: center;
        }

        .topic-stat {
            text-align: center;
        }

        .topic-stat-value {
            color: #3b82f6;
            font-weight: 600;
            font-size: 16px;
        }

        .topic-stat-label {
            color: #64748b;
            font-size: 11px;
        }

        .login-prompt {
            background: rgba(59, 130, 246, 0.1);
            border: 1px solid rgba(59, 130, 246, 0.3);
            border-radius: 12px;
            padding: 30px;
            text-align: center;
            margin-bottom: 30px;
        }

        .login-prompt h3 {
            color: #3b82f6;
            margin-bottom: 15px;
        }

        .login-prompt p {
            color: #94a3b8;
            margin-bottom: 20px;
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

            .category-header {
                flex-direction: column;
                align-items: stretch;
                gap: 15px;
            }

            .forum-header, .topic-header {
                flex-direction: column;
                align-items: stretch;
            }

            .forum-stats, .topic-stats {
                align-items: flex-start;
            }

            .topic-stats {
                flex-direction: row;
                gap: 15px;
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
            <a href="reviews.php" class="nav-btn">⭐ Reviews</a>
            <a href="forums.php" class="nav-btn active">💬 Forums</a>
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
            <h1>💬 Community Forums</h1>
            <p>Join the conversation with fellow gamers</p>
        </div>

        <?php if (!$isLoggedIn): ?>
            <div class="login-prompt">
                <h3>Join the Discussion!</h3>
                <p>Login or create an account to participate in our gaming community forums.</p>
                <a href="loginpage.html" class="new-topic-btn">Login to Participate</a>
            </div>
        <?php endif; ?>

        <div class="forum-categories">
            <div class="category-section">
                <div class="category-header">
                    <h2 class="category-title">🎮 General Gaming</h2>
                    <?php if ($isLoggedIn): ?>
                        <a href="#" class="new-topic-btn">+ New Topic</a>
                    <?php endif; ?>
                </div>
                <div class="forum-list">
                    <div class="forum-item">
                        <div class="forum-header">
                            <div class="forum-info">
                                <div class="forum-title">Game Recommendations</div>
                                <div class="forum-description">Share and discover amazing games with the community</div>
                            </div>
                            <div class="forum-stats">
                                <div class="stat-item">Topics: <span class="stat-value">247</span></div>
                                <div class="stat-item">Posts: <span class="stat-value">1,892</span></div>
                            </div>
                        </div>
                    </div>

                    <div class="forum-item">
                        <div class="forum-header">
                            <div class="forum-info">
                                <div class="forum-title">Gaming News & Updates</div>
                                <div class="forum-description">Discuss the latest gaming news and industry updates</div>
                            </div>
                            <div class="forum-stats">
                                <div class="stat-item">Topics: <span class="stat-value">156</span></div>
                                <div class="stat-item">Posts: <span class="stat-value">943</span></div>
                            </div>
                        </div>
                    </div>

                    <div class="forum-item">
                        <div class="forum-header">
                            <div class="forum-info">
                                <div class="forum-title">Gaming Help & Support</div>
                                <div class="forum-description">Get help with technical issues and gameplay questions</div>
                            </div>
                            <div class="forum-stats">
                                <div class="stat-item">Topics: <span class="stat-value">89</span></div>
                                <div class="stat-item">Posts: <span class="stat-value">456</span></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="category-section">
                <div class="category-header">
                    <h2 class="category-title">🎯 Platform Specific</h2>
                    <?php if ($isLoggedIn): ?>
                        <a href="#" class="new-topic-btn">+ New Topic</a>
                    <?php endif; ?>
                </div>
                <div class="forum-list">
                    <div class="forum-item">
                        <div class="forum-header">
                            <div class="forum-info">
                                <div class="forum-title">PC Gaming</div>
                                <div class="forum-description">Discuss PC games, hardware, and optimization tips</div>
                            </div>
                            <div class="forum-stats">
                                <div class="stat-item">Topics: <span class="stat-value">324</span></div>
                                <div class="stat-item">Posts: <span class="stat-value">2,156</span></div>
                            </div>
                        </div>
                    </div>

                    <div class="forum-item">
                        <div class="forum-header">
                            <div class="forum-info">
                                <div class="forum-title">Console Gaming</div>
                                <div class="forum-description">PlayStation, Xbox, Nintendo, and other console discussions</div>
                            </div>
                            <div class="forum-stats">
                                <div class="stat-item">Topics: <span class="stat-value">198</span></div>
                                <div class="stat-item">Posts: <span class="stat-value">1,467</span></div>
                            </div>
                        </div>
                    </div>

                    <div class="forum-item">
                        <div class="forum-header">
                            <div class="forum-info">
                                <div class="forum-title">Mobile Gaming</div>
                                <div class="forum-description">iOS, Android, and portable gaming discussions</div>
                            </div>
                            <div class="forum-stats">
                                <div class="stat-item">Topics: <span class="stat-value">145</span></div>
                                <div class="stat-item">Posts: <span class="stat-value">892</span></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="recent-topics">
            <div class="topics-header">
                <h2 class="topics-title">🔥 Recent Discussions</h2>
            </div>

            <div class="topic-item">
                <div class="topic-header">
                    <div class="topic-info">
                        <div class="topic-title">Best RPGs of 2025 - What are your favorites?</div>
                        <div class="topic-meta">
                            <span class="topic-author">GameMaster_Pro</span>
                            <span class="topic-date">2 hours ago</span>
                            <span class="topic-category">Game Recommendations</span>
                        </div>
                    </div>
                    <div class="topic-stats">
                        <div class="topic-stat">
                            <div class="topic-stat-value">24</div>
                            <div class="topic-stat-label">Replies</div>
                        </div>
                        <div class="topic-stat">
                            <div class="topic-stat-value">156</div>
                            <div class="topic-stat-label">Views</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="topic-item">
                <div class="topic-header">
                    <div class="topic-info">
                        <div class="topic-title">Graphics card recommendations for 4K gaming?</div>
                        <div class="topic-meta">
                            <span class="topic-author">TechGuru_88</span>
                            <span class="topic-date">5 hours ago</span>
                            <span class="topic-category">PC Gaming</span>
                        </div>
                    </div>
                    <div class="topic-stats">
                        <div class="topic-stat">
                            <div class="topic-stat-value">18</div>
                            <div class="topic-stat-label">Replies</div>
                        </div>
                        <div class="topic-stat">
                            <div class="topic-stat-value">203</div>
                            <div class="topic-stat-label">Views</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="topic-item">
                <div class="topic-header">
                    <div class="topic-info">
                        <div class="topic-title">New console exclusive announced - Thoughts?</div>
                        <div class="topic-meta">
                            <span class="topic-author">ConsoleKing</span>
                            <span class="topic-date">8 hours ago</span>
                            <span class="topic-category">Gaming News</span>
                        </div>
                    </div>
                    <div class="topic-stats">
                        <div class="topic-stat">
                            <div class="topic-stat-value">31</div>
                            <div class="topic-stat-label">Replies</div>
                        </div>
                        <div class="topic-stat">
                            <div class="topic-stat-value">287</div>
                            <div class="topic-stat-label">Views</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="topic-item">
                <div class="topic-header">
                    <div class="topic-info">
                        <div class="topic-title">Looking for co-op games to play with friends</div>
                        <div class="topic-meta">
                            <span class="topic-author">FriendlyGamer</span>
                            <span class="topic-date">1 day ago</span>
                            <span class="topic-category">Game Recommendations</span>
                        </div>
                    </div>
                    <div class="topic-stats">
                        <div class="topic-stat">
                            <div class="topic-stat-value">15</div>
                            <div class="topic-stat-label">Replies</div>
                        </div>
                        <div class="topic-stat">
                            <div class="topic-stat-value">94</div>
                            <div class="topic-stat-label">Views</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
       // Forum item click handlers
document.querySelectorAll('.forum-item').forEach(item => {
    item.addEventListener('click', function() {
        <?php if ($isLoggedIn): ?>
            const forumTitle = this.querySelector('.forum-title').textContent;
            const forumDesc = this.querySelector('.forum-description').textContent;
            
            // Create modal to show forum topics
            const modal = document.createElement('div');
            modal.style.cssText = `
                position: fixed; top: 0; left: 0; width: 100%; height: 100%;
                background: rgba(0,0,0,0.8); display: flex; align-items: center;
                justify-content: center; z-index: 1000; overflow-y: auto;
            `;
            
            modal.innerHTML = `
                <div style="background: #0f172a; padding: 30px; border-radius: 12px; 
                           width: 90%; max-width: 700px; border: 1px solid #3b82f6; max-height: 80vh; overflow-y: auto;">
                    <h3 style="color: #3b82f6; margin-bottom: 10px;">${forumTitle}</h3>
                    <p style="color: #94a3b8; margin-bottom: 20px;">${forumDesc}</p>
                    
                    <div style="border-top: 1px solid #334155; padding-top: 20px;">
                        <h4 style="color: #e2e8f0; margin-bottom: 15px;">Recent Topics:</h4>
                        ${getForumTopics(forumTitle)}
                    </div>
                    
                    <button id="closeForum" style="margin-top: 20px; padding: 10px 20px; background: #374151; 
                            color: #e2e8f0; border: none; border-radius: 6px; cursor: pointer; float: right;">Close</button>
                </div>
            `;
            
            document.body.appendChild(modal);
            document.getElementById('closeForum').onclick = () => document.body.removeChild(modal);
            modal.addEventListener('click', (e) => {
                if (e.target === modal) document.body.removeChild(modal);
            });
        <?php else: ?>
            alert('Please login to access the forums');
            window.location.href = 'loginpage.html';
        <?php endif; ?>
    });
});

// Topic item click handlers
document.querySelectorAll('.topic-item').forEach(item => {
    item.addEventListener('click', function() {
        <?php if ($isLoggedIn): ?>
            const topicTitle = this.querySelector('.topic-title').textContent;
            const topicAuthor = this.querySelector('.topic-author').textContent;
            const topicDate = this.querySelector('.topic-date').textContent;
            
            showTopicDiscussion(topicTitle, topicAuthor, topicDate);
        <?php else: ?>
            alert('Please login to view topics');
            window.location.href = 'loginpage.html';
        <?php endif; ?>
    });
});

// Helper function to generate forum topics
function getForumTopics(forumType) {
    const topics = {
        'Game Recommendations': [
            { title: 'Hidden indie gems you must play', author: 'IndieHunter', replies: 12 },
            { title: 'Best co-op games for couples', author: 'GamerCouple', replies: 8 },
            { title: 'Racing games with realistic physics', author: 'SpeedDemon', replies: 15 }
        ],
        'Gaming News & Updates': [
            { title: 'E3 2025 predictions and wishlist', author: 'NewsHawk', replies: 23 },
            { title: 'New console generation rumors', author: 'TechInsider', replies: 31 },
            { title: 'Gaming industry layoffs discussion', author: 'IndustryWatcher', replies: 18 }
        ],
        'Gaming Help & Support': [
            { title: 'PC crashing during intensive games', author: 'TechTrouble', replies: 6 },
            { title: 'Best settings for budget GPU?', author: 'BudgetGamer', replies: 9 },
            { title: 'Controller not working properly', author: 'ControllerIssue', replies: 4 }
        ],
        'PC Gaming': [
            { title: 'RTX 4090 vs RTX 4080 comparison', author: 'GraphicsGuru', replies: 28 },
            { title: 'Best mechanical keyboard for gaming?', author: 'KeyboardWarrior', replies: 19 },
            { title: 'Overclocking guide for beginners', author: 'OCExpert', replies: 14 }
        ],
        'Console Gaming': [
            { title: 'PS5 vs Xbox Series X in 2025', author: 'ConsoleDebater', replies: 45 },
            { title: 'Nintendo Direct predictions', author: 'NintendoFan', replies: 22 },
            { title: 'Best exclusive games this year', author: 'ExclusiveHunter', replies: 17 }
        ],
        'Mobile Gaming': [
            { title: 'iOS vs Android gaming performance', author: 'MobileGuru', replies: 11 },
            { title: 'Best mobile games without ads', author: 'AdFreeGamer', replies: 16 },
            { title: 'Gaming phone recommendations', author: 'PhoneGamer', replies: 13 }
        ]
    };
    
    const forumTopics = topics[forumType] || topics['Game Recommendations'];
    
    return forumTopics.map(topic => `
        <div style="background: #1e293b; padding: 15px; border-radius: 8px; margin-bottom: 10px; cursor: pointer; border: 1px solid #334155;"
             onclick="showTopicDiscussion('${topic.title}', '${topic.author}', '2 hours ago')">
            <div style="color: #e2e8f0; font-weight: 600; margin-bottom: 5px;">${topic.title}</div>
            <div style="color: #64748b; font-size: 12px;">by ${topic.author} • ${topic.replies} replies</div>
        </div>
    `).join('');
}

// Function to show topic discussion
function showTopicDiscussion(title, author, date) {
    const discussions = [
        { user: author, time: date, message: getOriginalPost(title) },
        { user: 'GameExplorer', time: '1 hour ago', message: 'Great topic! I totally agree with your points. Have you tried the games I mentioned in my review?' },
        { user: 'RetroGamer90', time: '45 min ago', message: 'This reminds me of classic games from the 90s. The mechanics you described are very similar to what we had back then.' },
        { user: 'ProGamer2025', time: '30 min ago', message: '@GameExplorer Which games are you referring to? I\'d love to check them out!' },
        { user: 'CasualPlayer', time: '15 min ago', message: 'As someone new to gaming, this is really helpful. Thanks for the detailed explanation!' }
    ];
    
    const modal = document.createElement('div');
    modal.style.cssText = `
        position: fixed; top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0,0,0,0.8); display: flex; align-items: center;
        justify-content: center; z-index: 1000; overflow-y: auto;
    `;
    
    modal.innerHTML = `
        <div style="background: #0f172a; padding: 30px; border-radius: 12px; 
                   width: 90%; max-width: 800px; border: 1px solid #3b82f6; max-height: 80vh; overflow-y: auto;">
            <h3 style="color: #3b82f6; margin-bottom: 20px;">${title}</h3>
            
            <div style="max-height: 400px; overflow-y: auto; border: 1px solid #334155; border-radius: 8px; padding: 15px; background: #1e293b;">
                ${discussions.map(msg => `
                    <div style="margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px solid #334155;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                            <span style="color: #3b82f6; font-weight: 600;">${msg.user}</span>
                            <span style="color: #64748b; font-size: 12px;">${msg.time}</span>
                        </div>
                        <div style="color: #e2e8f0; line-height: 1.5;">${msg.message}</div>
                    </div>
                `).join('')}
            </div>
            
            <div style="margin-top: 20px; display: flex; gap: 10px;">
                <input type="text" placeholder="Type your reply..." 
                       style="flex: 1; padding: 10px; background: #1e293b; border: 1px solid #334155; 
                              border-radius: 6px; color: #e2e8f0;">
                <button style="padding: 10px 20px; background: linear-gradient(135deg, #3b82f6, #2563eb); 
                               color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 600;">Reply</button>
            </div>
            
            <button id="closeTopic" style="margin-top: 15px; padding: 10px 20px; background: #374151; 
                    color: #e2e8f0; border: none; border-radius: 6px; cursor: pointer; float: right;">Close</button>
        </div>
    `;
    
    document.body.appendChild(modal);
    document.getElementById('closeTopic').onclick = () => document.body.removeChild(modal);
    modal.addEventListener('click', (e) => {
        if (e.target === modal) document.body.removeChild(modal);
    });
}


function getOriginalPost(title) {
    const posts = {
        'Best RPGs of 2025 - What are your favorites?': 'Hey everyone! I\'ve been diving deep into RPGs this year and wanted to share some amazing discoveries. The storytelling and character development in recent releases have been absolutely incredible. What are your top picks for 2025?',
        'Graphics card recommendations for 4K gaming?': 'I\'m planning to upgrade my setup for 4K gaming. My current GPU is struggling with newer titles at max settings. What would you recommend for a smooth 4K experience at 60+ FPS? Budget is around $800-1200.',
        'New console exclusive announced - Thoughts?': 'Just saw the announcement trailer and I\'m hyped! The graphics look incredible and the gameplay mechanics seem innovative. However, I\'m concerned about the exclusivity aspect. What are your thoughts on this trend?',
        'Looking for co-op games to play with friends': 'My friend group is looking for new co-op games to play during our weekend sessions. We enjoy both competitive and collaborative gameplay. Any suggestions for 4-6 players? We\'ve already played most of the popular titles.'
    };
    
    return posts[title] || 'This is an interesting topic that deserves discussion. What are your thoughts on this subject? I\'d love to hear different perspectives from the community!';


document.querySelectorAll('.new-topic-btn').forEach(btn => {
    if (btn.textContent.includes('New Topic')) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            
           
            const categorySection = this.closest('.category-section');
            const categoryTitle = categorySection.querySelector('.category-title').textContent;
            
            
            const modal = document.createElement('div');
            modal.style.cssText = `
                position: fixed; top: 0; left: 0; width: 100%; height: 100%;
                background: rgba(0,0,0,0.8); display: flex; align-items: center;
                justify-content: center; z-index: 1000;
            `;
            
            modal.innerHTML = `
                <div style="background: #0f172a; padding: 30px; border-radius: 12px; 
                           width: 90%; max-width: 500px; border: 1px solid #3b82f6;">
                    <h3 style="color: #3b82f6; margin-bottom: 20px;">Create New Topic in ${categoryTitle}</h3>
                    
                    <div style="margin-bottom: 15px;">
                        <label style="color: #e2e8f0; display: block; margin-bottom: 5px;">Topic Title:</label>
                        <input type="text" id="topicTitle" placeholder="Enter an engaging title..." 
                               style="width: 100%; padding: 10px; background: #1e293b; border: 1px solid #334155; 
                                      border-radius: 6px; color: #e2e8f0; font-size: 14px;">
                    </div>
                    
                    <div style="margin-bottom: 20px;">
                        <label style="color: #e2e8f0; display: block; margin-bottom: 5px;">Your Message:</label>
                        <textarea id="topicContent" placeholder="Share your thoughts, ask questions, or start a discussion..." 
                                style="width: 100%; height: 120px; padding: 10px; background: #1e293b; 
                                       border: 1px solid #334155; border-radius: 6px; color: #e2e8f0; 
                                       font-size: 14px; resize: vertical; font-family: inherit;"></textarea>
                    </div>
                    
                    <div style="display: flex; gap: 10px; justify-content: flex-end;">
                        <button id="cancelTopic" style="padding: 10px 20px; background: #374151; color: #e2e8f0; 
                                border: none; border-radius: 6px; cursor: pointer;">Cancel</button>
                        <button id="createTopic" style="padding: 10px 20px; background: linear-gradient(135deg, #3b82f6, #2563eb); 
                                color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 600;">Create Topic</button>
                    </div>
                </div>
            `;
            
            document.body.appendChild(modal);
            document.getElementById('topicTitle').focus();
            
            
            document.getElementById('cancelTopic').onclick = () => {
                document.body.removeChild(modal);
            };
            
          
            document.getElementById('createTopic').onclick = () => {
                const title = document.getElementById('topicTitle').value.trim();
                const content = document.getElementById('topicContent').value.trim();
                
                if (!title) {
                    alert('Please enter a topic title!');
                    return;
                }
                
                if (!content) {
                    alert('Please enter your message!');
                    return;
                }
                
                // Create new topic element
                const newTopic = document.createElement('div');
                newTopic.className = 'topic-item';
                newTopic.innerHTML = `
                    <div class="topic-header">
                        <div class="topic-info">
                            <div class="topic-title">${title}</div>
                            <div class="topic-meta">
                                <span class="topic-author"><?php echo isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest_User'; ?></span>
                                <span class="topic-date">Just now</span>
                                <span class="topic-category">${categoryTitle.replace('🎮 ', '').replace('🎯 ', '')}</span>
                            </div>
                        </div>
                        <div class="topic-stats">
                            <div class="topic-stat">
                                <div class="topic-stat-value">0</div>
                                <div class="topic-stat-label">Replies</div>
                            </div>
                            <div class="topic-stat">
                                <div class="topic-stat-value">1</div>
                                <div class="topic-stat-label">Views</div>
                            </div>
                        </div>
                    </div>
                `;
                
                // Add click handler to new topic
                newTopic.addEventListener('click', function() {
                    alert(`Topic: "${title}"\n\nContent: "${content}"\n\nClick OK to view full discussion!`);
                });
                
                // Add to recent topics section
                const recentTopics = document.querySelector('.recent-topics');
                const firstTopic = recentTopics.querySelector('.topic-item');
                if (firstTopic) {
                    recentTopics.insertBefore(newTopic, firstTopic);
                } else {
                    recentTopics.appendChild(newTopic);
                }
                
                // Success feedback
                document.body.removeChild(modal);
                
                // Smooth scroll to the new topic
                newTopic.scrollIntoView({ behavior: 'smooth', block: 'center' });
                
                // Highlight the new topic briefly
                newTopic.style.background = 'rgba(59, 130, 246, 0.2)';
                setTimeout(() => {
                    newTopic.style.background = '';
                }, 2000);
                
                // Show success message
                const successMsg = document.createElement('div');
                successMsg.style.cssText = `
                    position: fixed; top: 20px; right: 20px; background: linear-gradient(135deg, #10b981, #059669);
                    color: white; padding: 15px 20px; border-radius: 8px; z-index: 1001;
                    font-weight: 600; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
                `;
                successMsg.textContent = '🎉 Topic created successfully!';
                document.body.appendChild(successMsg);
                
                setTimeout(() => {
                    document.body.removeChild(successMsg);
                }, 3000);
            };
            
            // Close modal when clicking outside
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    document.body.removeChild(modal);
                }
            });
        });
    }
});
    </script>
</body>
</html>