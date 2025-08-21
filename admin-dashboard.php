<?php
session_start();
require_once 'db.php';

// Check if user is admin
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: loginpage.html");
    exit();
}

// Get statistics from database
try {
    // Total users
    $userCount = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
    
    // Users registered today
    $todayUsers = $pdo->query("SELECT COUNT(*) FROM users WHERE DATE(created_at) = CURDATE()")->fetchColumn();
    
    // Users registered this week
    $weekUsers = $pdo->query("SELECT COUNT(*) FROM users WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)")->fetchColumn();
    
    // Total games available
    $totalGames = $pdo->query("SELECT COUNT(*) FROM games")->fetchColumn();
    
    // Games sold (purchased) today
    $gamesSoldToday = $pdo->query("SELECT COUNT(*) FROM owned_games WHERE DATE(purchased_at) = CURDATE()")->fetchColumn();
    
    // Games sold this week
    $gamesSoldWeek = $pdo->query("SELECT COUNT(*) FROM owned_games WHERE purchased_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)")->fetchColumn();
    
    // Total games sold
    $totalGamesSold = $pdo->query("SELECT COUNT(*) FROM owned_games")->fetchColumn();
    
    // Recent users for table
    $recentUsers = $pdo->query("SELECT username, email, created_at FROM users ORDER BY created_at DESC LIMIT 10")->fetchAll(PDO::FETCH_ASSOC);
    
    // Recent game sales
    $recentSales = $pdo->query("
        SELECT g.name, u.username, og.purchased_at, g.price 
        FROM owned_games og 
        JOIN games g ON og.game_id = g.id 
        JOIN users u ON og.user_id = u.id 
        ORDER BY og.purchased_at DESC LIMIT 5
    ")->fetchAll(PDO::FETCH_ASSOC);
    
    // Calculate total revenue from sales
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

// Mock data for remaining statistics
$activeUsers = rand(800, 1200);
$cartItems = rand(50, 150);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - gameNvibe</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
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
            <div class="chart-card">
                <h3>User Registration Trends</h3>
                <canvas id="userChart" width="400" height="200"></canvas>
            </div>
            
            <div class="chart-card">
                <h3>Sales Overview</h3>
                <canvas id="salesChart" width="400" height="200"></canvas>
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
                                <td class="price">$<?php echo number_format($sale['price'], 2); ?></td>
                                <td><?php echo date('M d', strtotime($sale['purchased_at'])); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <script>
        // User Registration Chart
        const userCtx = document.getElementById('userChart').getContext('2d');
        new Chart(userCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
                datasets: [{
                    label: 'New Users',
                    data: [
                        Math.max(0, <?php echo $userCount; ?> - rand(200, 400)), 
                        Math.max(0, <?php echo $userCount; ?> - rand(150, 300)), 
                        Math.max(0, <?php echo $userCount; ?> - rand(100, 200)), 
                        Math.max(0, <?php echo $userCount; ?> - rand(80, 150)), 
                        Math.max(0, <?php echo $userCount; ?> - rand(50, 100)), 
                        Math.max(0, <?php echo $userCount; ?> - rand(20, 50)), 
                        <?php echo $userCount; ?>
                    ],
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        labels: {
                            color: '#e2e8f0'
                        }
                    }
                },
                scales: {
                    y: {
                        ticks: {
                            color: '#94a3b8'
                        },
                        grid: {
                            color: '#334155'
                        }
                    },
                    x: {
                        ticks: {
                            color: '#94a3b8'
                        },
                        grid: {
                            color: '#334155'
                        }
                    }
                }
            }
        });

        // Sales Chart
        const salesCtx = document.getElementById('salesChart').getContext('2d');
        new Chart(salesCtx, {
            type: 'bar',
            data: {
                labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                datasets: [{
                    label: 'Games Sold',
                    data: [
                        Math.floor(Math.random() * 20) + 5,
                        Math.floor(Math.random() * 15) + 8,
                        Math.floor(Math.random() * 18) + 6,
                        Math.floor(Math.random() * 22) + 7,
                        Math.floor(Math.random() * 25) + 10,
                        Math.floor(Math.random() * 30) + 12,
                        <?php echo max(1, $gamesSoldToday); ?>
                    ],
                    backgroundColor: 'rgba(59, 130, 246, 0.8)',
                    borderColor: '#3b82f6',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        labels: {
                            color: '#e2e8f0'
                        }
                    }
                },
                scales: {
                    y: {
                        ticks: {
                            color: '#94a3b8'
                        },
                        grid: {
                            color: '#334155'
                        }
                    },
                    x: {
                        ticks: {
                            color: '#94a3b8'
                        },
                        grid: {
                            color: '#334155'
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>