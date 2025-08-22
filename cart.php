<?php
session_start();
require_once 'db.php';


if (!isset($_SESSION['user_id'])) {
    header("Location: loginpage.html");
    exit();
}

$user_id = $_SESSION['user_id'];


if (isset($_POST['remove_from_cart'])) {
    $game_id = $_POST['game_id'];
    $stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = ? AND game_id = ?");
    $stmt->execute([$user_id, $game_id]);
    header("Location: cart.php");
    exit();
}


if (isset($_POST['purchase'])) {
    try {
        $pdo->beginTransaction();
        
      
        $stmt = $pdo->prepare("SELECT game_id FROM cart WHERE user_id = ?");
        $stmt->execute([$user_id]);
        $cart_items = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        if (!empty($cart_items)) {
           
            foreach ($cart_items as $game_id) {
                $stmt = $pdo->prepare("INSERT IGNORE INTO owned_games (user_id, game_id) VALUES (?, ?)");
                $stmt->execute([$user_id, $game_id]);
            }
            
           
            $stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = ?");
            $stmt->execute([$user_id]);
            
            $pdo->commit();
            $_SESSION['purchase_success'] = true;
            header("Location: cart.php");
            exit();
        }
        
    } catch (Exception $e) {
        $pdo->rollBack();
        $error_message = "Purchase failed. Please try again.";
    }
}


$stmt = $pdo->prepare("
    SELECT c.id as cart_id, g.* 
    FROM cart c 
    JOIN games g ON c.game_id = g.id 
    WHERE c.user_id = ?
    ORDER BY c.added_at DESC
");
$stmt->execute([$user_id]);
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Calculate total
$total = 0;
foreach ($cart_items as $item) {
    if ($item['price'] !== 'Free to Play') {
        $total += (float) str_replace('$', '', $item['price']);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - gameNvibe</title>
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

        .cart-container {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 30px;
        }

        .cart-items {
            background: rgba(15, 23, 42, 0.95);
            border-radius: 12px;
            padding: 25px;
            border: 1px solid rgba(59, 130, 246, 0.2);
        }

        .cart-item {
            background: rgba(30, 41, 59, 0.5);
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 15px;
            display: grid;
            grid-template-columns: 80px 1fr auto auto;
            gap: 20px;
            align-items: center;
            border: 1px solid rgba(59, 130, 246, 0.1);
        }

        .game-icon {
            width: 80px;
            height: 80px;
            background: #1e293b;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #64748b;
            font-size: 32px;
        }

        .game-details h3 {
            color: #e2e8f0;
            font-size: 18px;
            margin-bottom: 5px;
        }

        .game-genre {
            color: #3b82f6;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .game-description {
            color: #94a3b8;
            font-size: 14px;
            line-height: 1.4;
        }

        .game-price {
            color: #22c55e;
            font-size: 20px;
            font-weight: 700;
        }

        .remove-btn {
            background: rgba(239, 68, 68, 0.2);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.3);
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 12px;
            transition: all 0.3s ease;
        }

        .remove-btn:hover {
            background: rgba(239, 68, 68, 0.3);
            border-color: #ef4444;
        }

        .cart-summary {
            background: rgba(15, 23, 42, 0.95);
            border-radius: 12px;
            padding: 25px;
            border: 1px solid rgba(59, 130, 246, 0.2);
            height: fit-content;
        }

        .summary-title {
            color: #3b82f6;
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            color: #94a3b8;
        }

        .summary-row.total {
            color: #e2e8f0;
            font-size: 18px;
            font-weight: 700;
            border-top: 1px solid rgba(59, 130, 246, 0.2);
            padding-top: 15px;
            margin-top: 20px;
        }

        .checkout-btn {
            width: 100%;
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            border: none;
            padding: 15px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            margin-top: 20px;
            transition: all 0.3s ease;
        }

        .checkout-btn:hover {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        }

        .checkout-btn:disabled {
            background: rgba(59, 130, 246, 0.3);
            cursor: not-allowed;
        }

        .empty-cart {
            text-align: center;
            color: #64748b;
            padding: 60px 20px;
            grid-column: 1 / -1;
        }

        .empty-cart h3 {
            color: #94a3b8;
            font-size: 24px;
            margin-bottom: 15px;
        }

        .continue-shopping {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            margin-top: 20px;
        }

        .success-message {
            background: rgba(34, 197, 94, 0.2);
            color: #22c55e;
            border: 1px solid rgba(34, 197, 94, 0.3);
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
        }

        .error-message {
            background: rgba(239, 68, 68, 0.2);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.3);
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
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

            .cart-container {
                grid-template-columns: 1fr;
            }

            .cart-item {
                grid-template-columns: 1fr;
                text-align: center;
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
            <a href="profile.php" class="nav-btn">👤 Profile</a>
            <a href="cart.php" class="nav-btn active">🛒 Cart</a>
        </nav>
        <div class="user-info">
            <span>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</span>
            <a href="logout.php" class="nav-btn">Logout</a>
        </div>
    </header>

    <main class="main-content">
        <div class="page-header">
            <h1>🛒 Shopping Cart</h1>
            <p>Review your selected games before checkout</p>
        </div>

        <?php if (isset($_SESSION['purchase_success'])): ?>
            <div class="success-message">
                🎉 Purchase successful! Your games have been added to your library.
            </div>
            <?php unset($_SESSION['purchase_success']); ?>
        <?php endif; ?>

        <?php if (isset($error_message)): ?>
            <div class="error-message">
                ❌ <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <?php if (empty($cart_items)): ?>
            <div class="empty-cart">
                <h3>Your cart is empty</h3>
                <p>Looks like you haven't added any games to your cart yet.</p>
                <a href="games.php" class="continue-shopping">Continue Shopping</a>
            </div>
        <?php else: ?>
            <div class="cart-container">
                <div class="cart-items">
                    <h2 style="color: #3b82f6; margin-bottom: 20px;">Items in Cart (<?php echo count($cart_items); ?>)</h2>
                    
                    <?php foreach ($cart_items as $item): ?>
                        <div class="cart-item">
                            <div class="game-icon">🎮</div>
                            <div class="game-details">
                                <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                                <div class="game-genre"><?php echo htmlspecialchars($item['genre']); ?></div>
                                <p class="game-description"><?php echo htmlspecialchars(substr($item['description'], 0, 100)); ?>...</p>
                            </div>
                            <div class="game-price"><?php echo htmlspecialchars($item['price']); ?></div>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="game_id" value="<?php echo $item['id']; ?>">
                                <button type="submit" name="remove_from_cart" class="remove-btn">Remove</button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="cart-summary">
                    <h3 class="summary-title">Order Summary</h3>
                    
                    <div class="summary-row">
                        <span>Items (<?php echo count($cart_items); ?>):</span>
                        <span>$<?php echo number_format($total, 2); ?></span>
                    </div>
                    
                    <div class="summary-row">
                        <span>Tax:</span>
                        <span>$<?php echo number_format($total * 0.08, 2); ?></span>
                    </div>
                    
                    <div class="summary-row total">
                        <span>Total:</span>
                        <span>$<?php echo number_format($total * 1.08, 2); ?></span>
                    </div>

                    <form method="POST">
                        <button type="submit" name="purchase" class="checkout-btn">
                            Complete Purchase
                        </button>
                    </form>

                    <a href="games.php" class="continue-shopping" style="display: block; text-align: center; margin-top: 15px; color: #3b82f6;">
                        Continue Shopping
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </main>

    <script>
       
        document.querySelectorAll('button[name="remove_from_cart"]').forEach(btn => {
            btn.addEventListener('click', function(e) {
                if (!confirm('Are you sure you want to remove this game from your cart?')) {
                    e.preventDefault();
                }
            });
        });

    
        document.querySelector('button[name="purchase"]').addEventListener('click', function(e) {
            if (!confirm('Are you sure you want to complete this purchase?')) {
                e.preventDefault();
            }
        });
    </script>
</body>
</html>