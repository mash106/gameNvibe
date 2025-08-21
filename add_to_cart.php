<?php
session_start();
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please login first']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $game_name = $_POST['game_name'] ?? '';
    $game_genre = $_POST['game_genre'] ?? '';
    $game_price = $_POST['game_price'] ?? '';
    $game_developer = $_POST['game_developer'] ?? '';
    $game_description = $_POST['game_description'] ?? '';
    $game_rating = $_POST['game_rating'] ?? '0';

    if (empty($game_name)) {
        echo json_encode(['success' => false, 'message' => 'Game name is required']);
        exit();
    }

    try {
        // First, check if game exists in games table
        $stmt = $pdo->prepare("SELECT id FROM games WHERE name = ?");
        $stmt->execute([$game_name]);
        $game = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$game) {
            // Insert new game if it doesn't exist
            $stmt = $pdo->prepare("
                INSERT INTO games (name, price, genre, rating, developer, description, created_at) 
                VALUES (?, ?, ?, ?, ?, ?, NOW())
            ");
            $stmt->execute([$game_name, $game_price, $game_genre, $game_rating, $game_developer, $game_description]);
            $game_id = $pdo->lastInsertId();
        } else {
            $game_id = $game['id'];
        }

        // Check if already in cart
        $stmt = $pdo->prepare("SELECT id FROM cart WHERE user_id = ? AND game_id = ?");
        $stmt->execute([$user_id, $game_id]);
        
        if ($stmt->fetch()) {
            echo json_encode(['success' => false, 'message' => 'Game is already in your cart']);
            exit();
        }

        // Add to cart
        $stmt = $pdo->prepare("INSERT INTO cart (user_id, game_id, added_at) VALUES (?, ?, NOW())");
        $stmt->execute([$user_id, $game_id]);

        echo json_encode(['success' => true, 'message' => 'Game added to cart successfully']);

    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>