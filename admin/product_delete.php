<?php
require_once "../config/config.php";
$sql = $pdo->prepare("DELETE FROM product WHERE id=".$_GET['id']);
$sql->execute();

header('Location: index.php');
?>