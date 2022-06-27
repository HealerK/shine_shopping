<?php
session_start();
require_once "../config/config.php";
require_once "../config/common.php";
$stmt = $pdo->prepare("DELETE FROM categories WHERE id=".$_GET['id']);
$stmt->execute();

header('Location: category.php');
?>