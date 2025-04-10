<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['pokemon_name'])) {
    $userId = $_SESSION['user_id'];
    $pokemonName = strtolower(trim($_POST['pokemon_name']));

    $conn = new mysqli('localhost', 'root', '', 'Pokemon');
    if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

    // Check if it's already caught
    $stmt = $conn->prepare("SELECT 1 FROM caught_pokemon WHERE user_id = ? AND pokemon_name = ?");
    $stmt->bind_param("is", $userId, $pokemonName);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 0) {
        $stmt->close();
        $stmt = $conn->prepare("INSERT INTO caught_pokemon (user_id, pokemon_name) VALUES (?, ?)");
        $stmt->bind_param("is", $userId, $pokemonName);
        $stmt->execute();
    }

    $stmt->close();
    $conn->close();
}

header("Location: " . $_SERVER['HTTP_REFERER']);
exit();
