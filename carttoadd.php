<?php
session_start();

require_once "./config/config.php";
require_once "./config/common.php";

if(isset($_POST)){
    $id = $_POST['id'];
    $qty = $_POST['qty'];

    $stmt = $pdo->prepare("SELECT * FROM product WHERE id=:id");
    $stmt->execute(array(
      ':id' => $id
    ));
    $presult = $stmt->fetch(PDO::FETCH_ASSOC);
    if($qty > $presult['quantity']){
        echo "<script>alert('Not Enough Stock');window.location.href='product_detail.php?id={$id}'</script>";
    }else{
        if(isset($_SESSION['cart']['id'.$id])){
            $_SESSION['cart']['id'.$id] += $qty;
        }else{
            $_SESSION['cart']['id'.$id] = $qty;
        }
        header('Location: cart.php');
    }
}
