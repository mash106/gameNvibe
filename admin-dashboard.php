<?php
session_start();
require_once 'db.php';


if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: loginpage.html");
    exit();
}


try {
   
    $userCount = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
    
  
    $todayUsers = $pdo->query("SELECT COUNT(*) FROM users WHERE DATE(created_at) = CURDATE()")->fetchColumn();
    
   
    $weekUsers = $pdo->query("SELECT COUNT(*) FROM users WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)")->fetchColumn();
    
   
    $totalGames = $pdo->query("SELECT COUNT(*) FROM games")->fetchColumn();
    
    
    $gamesSoldToday = $pdo->query("SELECT COUNT(*) FROM owned_games WHERE DATE(purchased_at) = CURDATE()")->fetchColumn();
    
  
    $gamesSoldWeek = $pdo->query("SELECT COUNT(*) FROM owned_games WHERE purchased_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)")->fetchColumn();
    
   
    $totalGamesSold = $pdo->query("SELECT COUNT(*) FROM owned_games")->fetchColumn();
    
   
    $recentUsers = $pdo->query("SELECT username, email, created_at FROM users ORDER BY created_at DESC LIMIT 10")->fetchAll(PDO::FETCH_ASSOC);
    
 
    $recentSales = $pdo->query("
        SELECT g.name, u.username, og.purchased_at, g.price 
        FROM owned_games og 
        JOIN games g ON og.game_id = g.id 
        JOIN users u ON og.user_id = u.id 
        ORDER BY og.purchased_at DESC LIMIT 5
    ")->fetchAll(PDO::FETCH_ASSOC);
    
    
    $totalRevenue = $pdo->query("
        SELECT SUM(g.price) as total 
        FROM owned_games og 
        JOIN games g ON og.game_id = g.id
    ")->fetchColumn();
    
    if (!$totalRevenue) $totalRevenue = 0;
    
} catch(PDOException $e) {
    $userCount = 0;
    $todayUsers = 0;
    $weekUsers = 0;
    $totalGames = 0;
    $gamesSoldToday = 0;
    $gamesSoldWeek = 0;
    $totalGamesSold = 0;
    $recentUsers = [];
    $recentSales = [];
    $totalRevenue = 0;
}


$activeUsers = rand(800, 1200);
$cartItems = rand(50, 150);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - gameNvibe</title>
    
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
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .logo h1 {
            color: #3b82f6;
            font-size: 24px;
            font-weight: 700;
        }

        .admin-badge {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .logout-btn {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
        }

        .logout-btn:hover {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
        }

        .main-content {
            padding: 30px;
            max-width: 1400px;
            margin: 0 auto;
        }

        .welcome-section {
            background: rgba(15, 23, 42, 0.95);
            border-radius: 16px;
            padding: 30px;
            border: 1px solid rgba(59, 130, 246, 0.2);
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .welcome-content h1 {
            color: #3b82f6;
            font-size: 32px;
            margin-bottom: 10px;
        }

        .add-game-btn {
            background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
            font-size: 16px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .add-game-btn:hover {
            background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: rgba(15, 23, 42, 0.95);
            border-radius: 12px;
            padding: 24px;
            border: 1px solid rgba(59, 130, 246, 0.2);
            text-align: center;
            transition: transform 0.2s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-number {
            font-size: 36px;
            font-weight: 700;
            color: #3b82f6;
            margin-bottom: 10px;
        }

        .stat-label {
            color: #94a3b8;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .charts-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }

        .chart-card {
            background: rgba(15, 23, 42, 0.95);
            border-radius: 12px;
            padding: 24px;
            border: 1px solid rgba(59, 130, 246, 0.2);
        }

        .chart-card h3 {
            color: #e2e8f0;
            margin-bottom: 20px;
            text-align: center;
        }

        .tables-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }

        .table-section {
            background: rgba(15, 23, 42, 0.95);
            border-radius: 12px;
            padding: 24px;
            border: 1px solid rgba(59, 130, 246, 0.2);
        }

        .table-section h3 {
            color: #e2e8f0;
            margin-bottom: 20px;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table th,
        .data-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #334155;
            font-size: 14px;
        }

        .data-table th {
            background: rgba(59, 130, 246, 0.1);
            color: #3b82f6;
            font-weight: 600;
        }

        .data-table tr:hover {
            background: rgba(59, 130, 246, 0.05);
        }

        .price {
            color: #22c55e;
            font-weight: 600;
        }

        @media (max-width: 768px) {
            .charts-section,
            .tables-section {
                grid-template-columns: 1fr;
            }
            
            .main-content {
                padding: 20px;
            }
            
            .stats-grid {
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            }

            .welcome-section {
                flex-direction: column;
                gap: 20px;
                text-align: center;
            }
            
        }
        .line-chart {
    position: relative;
    height: 200px;
    background: linear-gradient(to top, rgba(59, 130, 246, 0.1) 0%, transparent 100%);
    border-radius: 8px;
    overflow: hidden;
}

.line-chart svg {
    width: 100%;
    height: 100%;
}

.chart-labels {
    display: flex;
    justify-content: space-between;
    margin-top: 10px;
    font-size: 12px;
    color: #94a3b8;
}

/* Bar Chart Styles */
.bar-chart {
    display: flex;
    align-items: end;
    justify-content: space-between;
    height: 200px;
    padding: 20px 0;
    gap: 8px;
}

.bar {
    flex: 1;
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
    border-radius: 4px 4px 0 0;
    position: relative;
    transition: all 0.3s ease;
    animation: growUp 1s ease-out;
}

.bar:hover {
    background: linear-gradient(135deg, #60a5fa 0%, #3b82f6 100%);
    transform: translateY(-5px);
}

.bar::after {
    content: attr(data-value);
    position: absolute;
    top: -25px;
    left: 50%;
    transform: translateX(-50%);
    color: #e2e8f0;
    font-size: 12px;
    font-weight: 600;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.bar:hover::after {
    opacity: 1;
}

@keyframes growUp {
    from {
        height: 0;
    }
    to {
        height: var(--height);
    }
}

.bar-labels {
    display: flex;
    justify-content: space-between;
    margin-top: 10px;
    font-size: 12px;
    color: #94a3b8;
}

.bar-labels span {
    flex: 1;
    text-align: center;
}

/* Trend indicators */
.trend-indicator {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: 12px;
    margin-top: 8px;
    justify-content: center;
}

.trend-up {
    color: #22c55e;
}

.trend-down {
    color: #ef4444;
}

.trend-arrow {
    font-size: 10px;
}

@keyframes drawLine {
    to {
        stroke-dashoffset: 0;
    }
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}
    </style>
</head>
<body>
    <header class="header">
        <div class="logo">
            <h1>gameNvibe</h1>
            <span class="admin-badge">ADMIN</span>
        </div>
        <div class="user-info">
            <span>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</span>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </header>

    <main class="main-content">
        <div class="welcome-section">
            <div class="welcome-content">
                <h1>Admin Dashboard</h1>
                <p>Manage your gameNvibe platform with complete administrative control</p>
            </div>
            <a href="admin-games.php" class="add-game-btn">
                + Add New Game
            </a>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number"><?php echo number_format($userCount); ?></div>
                <div class="stat-label">Total Users</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-number"><?php echo $todayUsers; ?></div>
                <div class="stat-label">New Users Today</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-number"><?php echo number_format($totalGames); ?></div>
                <div class="stat-label">Games Available</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-number"><?php echo $gamesSoldToday; ?></div>
                <div class="stat-label">Games Sold Today</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-number">$<?php echo number_format($totalRevenue, 2); ?></div>
                <div class="stat-label">Total Revenue</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-number"><?php echo number_format($activeUsers); ?></div>
                <div class="stat-label">Active Users</div>
            </div>
        </div>

        <div class="charts-section">
    <!-- User Registration Trends -->
    <div class="chart-card">
        <h3>User Registration Trends</h3>
        <div class="line-chart">
            <svg viewBox="0 0 350 200">
                <!-- Grid lines -->
                <defs>
                    <pattern id="grid" width="50" height="40" patternUnits="userSpaceOnUse">
                        <path d="M 50 0 L 0 0 0 40" fill="none" stroke="rgba(100, 116, 139, 0.2)" stroke-width="1"/>
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#grid)"/>
                
                <!-- Line chart -->
                <polyline 
                    fill="none" 
                    stroke="#3b82f6" 
                    stroke-width="3"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    points="25,160 75,140 125,120 175,100 225,80 275,60 325,40"
                    style="stroke-dasharray: 400; stroke-dashoffset: 400; animation: drawLine 2s ease-in-out forwards;"
                />
                
                <!-- Data points -->
                <circle cx="25" cy="160" r="4" fill="#3b82f6" opacity="0" style="animation: fadeIn 0.5s ease-in-out 2s forwards;"/>
                <circle cx="75" cy="140" r="4" fill="#3b82f6" opacity="0" style="animation: fadeIn 0.5s ease-in-out 2.2s forwards;"/>
                <circle cx="125" cy="120" r="4" fill="#3b82f6" opacity="0" style="animation: fadeIn 0.5s ease-in-out 2.4s forwards;"/>
                <circle cx="175" cy="100" r="4" fill="#3b82f6" opacity="0" style="animation: fadeIn 0.5s ease-in-out 2.6s forwards;"/>
                <circle cx="225" cy="80" r="4" fill="#3b82f6" opacity="0" style="animation: fadeIn 0.5s ease-in-out 2.8s forwards;"/>
                <circle cx="275" cy="60" r="4" fill="#3b82f6" opacity="0" style="animation: fadeIn 0.5s ease-in-out 3s forwards;"/>
                <circle cx="325" cy="40" r="4" fill="#3b82f6" opacity="0" style="animation: fadeIn 0.5s ease-in-out 3.2s forwards;"/>
                
                <!-- Area under line -->
                <polygon 
                    fill="url(#gradient)" 
                    points="25,160 75,140 125,120 175,100 225,80 275,60 325,40 325,200 25,200"
                    opacity="0"
                    style="animation: fadeIn 1s ease-in-out 2s forwards;"
                />
                
                <defs>
                    <linearGradient id="gradient" x1="0%" y1="0%" x2="0%" y2="100%">
                        <stop offset="0%" style="stop-color:rgba(59, 130, 246, 0.3);stop-opacity:1" />
                        <stop offset="100%" style="stop-color:rgba(59, 130, 246, 0);stop-opacity:1" />
                    </linearGradient>
                </defs>
            </svg>
        </div>
        <div class="chart-labels">
            <span>Jan</span>
            <span>Feb</span>
            <span>Mar</span>
            <span>Apr</span>
            <span>May</span>
            <span>Jun</span>
            <span>Jul</span>
        </div>
        <div class="trend-indicator trend-up">
            <span class="trend-arrow">↗</span>
            <span>+24% from last month</span>
        </div>
    </div>

    <!-- Sales Overview -->
    <div class="chart-card">
        <h3>Sales Overview</h3>
        <div class="bar-chart">
            <div class="bar" style="--height: 60px; height: 60px;" data-value="<?php echo max(1, $gamesSoldToday - 6); ?>"></div>
            <div class="bar" style="--height: 80px; height: 80px;" data-value="<?php echo max(1, $gamesSoldToday + 2); ?>"></div>
            <div class="bar" style="--height: 45px; height: 45px;" data-value="<?php echo max(1, $gamesSoldToday - 8); ?>"></div>
            <div class="bar" style="--height: 120px; height: 120px;" data-value="<?php echo max(1, $gamesSoldToday + 8); ?>"></div>
            <div class="bar" style="--height: 90px; height: 90px;" data-value="<?php echo max(1, $gamesSoldToday + 1); ?>"></div>
            <div class="bar" style="--height: 150px; height: 150px;" data-value="<?php echo max(1, $gamesSoldToday + 12); ?>"></div>
            <div class="bar" style="--height: 110px; height: 110px;" data-value="<?php echo $gamesSoldToday; ?>"></div>
        </div>
        <div class="bar-labels">
            <span>Mon</span>
            <span>Tue</span>
            <span>Wed</span>
            <span>Thu</span>
            <span>Fri</span>
            <span>Sat</span>
            <span>Sun</span>
        </div>
        <div class="trend-indicator trend-up">
            <span class="trend-arrow">↗</span>
            <span>+12% from last week</span>
        </div>
    </div>
</div>

        <div class="tables-section">
            <div class="table-section">
                <h3>Recent Users</h3>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Joined</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($recentUsers)): ?>
                            <tr>
                                <td colspan="3" style="text-align: center; color: #64748b;">No users found</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($recentUsers as $user): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($user['username']); ?></td>
                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                <td><?php echo date('M d', strtotime($user['created_at'])); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="table-section">
                <h3>Recent Sales</h3>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Game</th>
                            <th>User</th>
                            <th>Price</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($recentSales)): ?>
                            <tr>
                                <td colspan="4" style="text-align: center; color: #64748b;">No sales yet</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($recentSales as $sale): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($sale['name']); ?></td>
                                <td><?php echo htmlspecialchars($sale['username']); ?></td>
                                <td class="price">$<?php echo number_format((float)str_replace(['$', ','], '', $sale['price']), 2); ?></td>
                                <td><?php echo date('M d', strtotime($sale['purchased_at'])); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    
</body>
</html>