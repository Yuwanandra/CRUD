<?php
include 'config.php';

$id = $_GET['id'];
$stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: users.php"); // Redirect ke daftar user
exit;
?>
