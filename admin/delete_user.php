<<?php
require '../config/config.php';
// require '../config/common.php';

$stmt = $pdo->prepare("DELETE FROM users WHERE id=".$_GET['id']);
$stmt->execute();

header('Location: user.php');
?>
