<?php
session_start();
require_once 'db.php';

// Check if user is admin
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: loginpage.html");
    exit();
}

$message = '';
$messageType = '';

// Handle form submission
if ($_POST) {
    $name = trim($_POST['name']);
    $price = floatval($_POST['price']);
    $genre = trim($_POST['genre']);
    $rating = floatval($_POST['rating']);
    $developer = trim($_POST['developer']);
    $description = trim($_POST['description']);
    
    // Basic validation
    if (empty($name) || empty($genre) || empty($developer) || empty($description)) {
        $message = "Please fill in all required fields!";
        $messageType = "error";
    } elseif ($price < 0) {
        $message = "Price cannot be negative!";
        $messageType = "error";
    } elseif ($rating < 0 || $rating > 5) {
        $message = "Rating must be between 0 and 5!";
        $messageType = "error";
    } else {
        try {
            // Check if game already exists
            $stmt = $pdo->prepare("SELECT id FROM games WHERE name = ?");
            $stmt->execute([$name]);
            if ($stmt->fetch()) {
                $message = "A game with this name already exists!";
                $messageType = "error";
            } else {
                // Insert new game
                $stmt = $pdo->prepare("INSERT INTO games (name, price, genre, rating, developer, description) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->execute([$name, $price, $genre, $rating, $developer, $description]);
                
                $message = "Game added successfully!";
                $messageType = "success";
                
                // Clear form data
                $_POST = array();
            }
        } catch(PDOException $e) {
            $message = "Error adding game: " . $e->getMessage();
            $messageType = "error";
        }
    }
}

// Get existing games for display
try {
    $games = $pdo->query("SELECT * FROM games ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $games = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Games - gameNvibe Admin</title>
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

        .nav-links {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .nav-link {
            color: #e2e8f0;
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 6px;
            border: 1px solid rgba(59, 130, 246, 0.2);
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            background: rgba(59, 130, 246, 0.2);
            border-color: #3b82f6;
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

        .page-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .page-header h1 {
            color: #3b82f6;
            font-size: 36px;
            margin-bottom: 10px;
        }

        .content-grid {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 30px;
        }

        .form-section {
            background: rgba(15, 23, 42, 0.95);
            border-radius: 12px;
            padding: 30px;
            border: 1px solid rgba(59, 130, 246, 0.2);
            height: fit-content;
        }

        .form-section h2 {
            color: #e2e8f0;
            margin-bottom: 25px;
            font-size: 24px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #94a3b8;
            font-weight: 600;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px 16px;
            background: #1e293b;
            border: 2px solid #334155;
            border-radius: 8px;
            color: #e2e8f0;
            font-size: 16px;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #3b82f6;
        }

        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .submit-btn {
            background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
            color: white;
            border: none;
            padding: 14px 28px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            width: 100%;
            margin-top: 10px;
        }

        .submit-btn:hover {
            background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
        }

        .games-section {
            background: rgba(15, 23, 42, 0.95);
            border-radius: 12px;
            padding: 30px;
            border: 1px solid rgba(59, 130, 246, 0.2);
        }

        .games-section h2 {
            color: #e2e8f0;
            margin-bottom: 25px;
            font-size: 24px;
        }

        .games-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .games-table th,
        .games-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #334155;
        }

        .games-table th {
            background: rgba(59, 130, 246, 0.1);
            color: #3b82f6;
            font-weight: 600;
            position: sticky;
            top: 0;
        }

        .games-table tr:hover {
            background: rgba(59, 130, 246, 0.05);
        }

        .games-table-container {
            max-height: 600px;
            overflow-y: auto;
            border-radius: 8px;
            border: 1px solid #334155;
        }

        .price {
            color: #22c55e;
            font-weight: 600;
        }

        .rating {
            color: #fbbf24;
            font-weight: 600;
        }

        .genre-badge {
            background: rgba(59, 130, 246, 0.2);
            color: #3b82f6;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
        }

        .description {
            max-width: 300px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .message {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .message.success {
            background: rgba(34, 197, 94, 0.2);
            color: #22c55e;
            border: 1px solid rgba(34, 197, 94, 0.3);
        }

        .message.error {
            background: rgba(239, 68, 68, 0.2);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        .stats-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: rgba(15, 23, 42, 0.95);
            border-radius: 12px;
            padding: 20px;
            border: 1px solid rgba(59, 130, 246, 0.2);
            text-align: center;
        }

        .stat-number {
            font-size: 28px;
            font-weight: 700;
            color: #3b82f6;
            margin-bottom: 5px;
        }

        .stat-label {
            color: #94a3b8;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        @media (max-width: 768px) {
            .content-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }
            
            .main-content {
                padding: 20px;
            }
            
            .form-row {
                grid-template-columns: 1fr;
            }

            .header {
                flex-direction: column;
                gap: 15px;
            }

            .nav-links {
                flex-wrap: wrap;
                justify-content: center;
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
        <div class="nav-links">
            <a href="admin-dashboard.php" class="nav-link">Dashboard</a>
            <span>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</span>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </header>

    <main class="main-content">
        <div class="page-header">
            <h1>Games Management</h1>
            <p>Add new games and manage your game library</p>
        </div>

        <?php if ($message): ?>
            <div class="message <?php echo $messageType; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <div class="stats-row">
            <div class="stat-card">
                <div class="stat-number"><?php echo count($games); ?></div>
                <div class="stat-label">Total Games</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo count(array_filter($games, function($g) { return $g['price'] == 0; })); ?></div>
                <div class="stat-label">Free Games</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo count(array_filter($games, function($g) { return $g['price'] > 0; })); ?></div>
                <div class="stat-label">Paid Games</div>
            </div>
        </div>

        <div class="content-grid">
            <div class="form-section">
                <h2>Add New Game</h2>
                <form method="POST">
                    <div class="form-group">
                        <label for="name">Game Name *</label>
                        <input type="text" id="name" name="name" required value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="price">Price ($) *</label>
                            <input type="number" id="price" name="price" step="0.01" min="0" required value="<?php echo isset($_POST['price']) ? htmlspecialchars($_POST['price']) : ''; ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="rating">Rating (0-5) *</label>
                            <input type="number" id="rating" name="rating" step="0.1" min="0" max="5" required value="<?php echo isset($_POST['rating']) ? htmlspecialchars($_POST['rating']) : ''; ?>">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="genre">Genre *</label>
                        <select id="genre" name="genre" required>
                            <option value="">Select Genre</option>
                            <option value="action" <?php echo (isset($_POST['genre']) && $_POST['genre'] == 'action') ? 'selected' : ''; ?>>Action</option>
                            <option value="sports" <?php echo (isset($_POST['genre']) && $_POST['genre'] == 'sports') ? 'selected' : ''; ?>>Sports</option>
                            <option value="racing" <?php echo (isset($_POST['genre']) && $_POST['genre'] == 'racing') ? 'selected' : ''; ?>>Racing</option>
                            <option value="simulation" <?php echo (isset($_POST['genre']) && $_POST['genre'] == 'simulation') ? 'selected' : ''; ?>>Simulation</option>
                            <option value="indie" <?php echo (isset($_POST['genre']) && $_POST['genre'] == 'indie') ? 'selected' : ''; ?>>Indie</option>
                            <option value="rpg" <?php echo (isset($_POST['genre']) && $_POST['genre'] == 'rpg') ? 'selected' : ''; ?>>RPG</option>
                            <option value="adventure" <?php echo (isset($_POST['genre']) && $_POST['genre'] == 'adventure') ? 'selected' : ''; ?>>Adventure</option>
                            <option value="strategy" <?php echo (isset($_POST['genre']) && $_POST['genre'] == 'strategy') ? 'selected' : ''; ?>>Strategy</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="developer">Developer *</label>
                        <input type="text" id="developer" name="developer" required value="<?php echo isset($_POST['developer']) ? htmlspecialchars($_POST['developer']) : ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Description *</label>
                        <textarea id="description" name="description" required placeholder="Enter game description..."><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; ?></textarea>
                    </div>
                    
                    <button type="submit" class="submit-btn">Add Game</button>
                </form>
            </div>

            <div class="games-section">
                <h2>Existing Games</h2>
                <?php if (empty($games)): ?>
                    <p style="text-align: center; color: #64748b; margin-top: 40px;">No games added yet. Add your first game using the form!</p>
                <?php else: ?>
                    <div class="games-table-container">
                        <table class="games-table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Genre</th>
                                    <th>Developer</th>
                                    <th>Price</th>
                                    <th>Rating</th>
                                    <th>Description</th>
                                    <th>Added</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($games as $game): ?>
                                <tr>
                                    <td style="font-weight: 600; color: #e2e8f0;"><?php echo htmlspecialchars($game['name']); ?></td>
                                    <td><span class="genre-badge"><?php echo ucfirst(htmlspecialchars($game['genre'])); ?></span></td>
                                    <td style="color: #94a3b8;"><?php echo htmlspecialchars($game['developer']); ?></td>
                                    <td class="price"><?php echo $game['price'] == 0 ? 'Free' : '$' . number_format($game['price'], 2); ?></td>
                                    <td class="rating"><?php echo number_format($game['rating'], 1); ?>/5</td>
                                    <td class="description" title="<?php echo htmlspecialchars($game['description']); ?>"><?php echo htmlspecialchars($game['description']); ?></td>
                                    <td style="color: #64748b; font-size: 12px;"><?php echo date('M d, Y', strtotime($game['created_at'])); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <script>
        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const name = document.getElementById('name').value.trim();
            const price = parseFloat(document.getElementById('price').value);
            const rating = parseFloat(document.getElementById('rating').value);
            const genre = document.getElementById('genre').value;
            const developer = document.getElementById('developer').value.trim();
            const description = document.getElementById('description').value.trim();
            
            if (!name || !genre || !developer || !description) {
                alert('Please fill in all required fields!');
                e.preventDefault();
                return;
            }
            
            if (price < 0) {
                alert('Price cannot be negative!');
                e.preventDefault();
                return;
            }
            
            if (rating < 0 || rating > 5) {
                alert('Rating must be between 0 and 5!');
                e.preventDefault();
                return;
            }
        });
        
        // Auto-hide success messages
        const message = document.querySelector('.message.success');
        if (message) {
            setTimeout(() => {
                message.style.opacity = '0';
                setTimeout(() => {
                    message.remove();
                }, 300);
            }, 3000);
        }
    </script>
</body>
</html>