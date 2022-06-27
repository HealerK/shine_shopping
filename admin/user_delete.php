<?php
require_once "../config/config.php";
$sql = $pdo->prepare("DELETE FROM users WHERE id=".$_GET['id']);
$sql->execute();

header('Location: user_add_list.php');
?>