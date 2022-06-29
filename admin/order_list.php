<?php
session_start();
ob_start();
require_once "../config/config.php";
require_once "../config/common.php";

if (empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
    header('Location: login.php');
}
if ($_SESSION['role'] != 1) {
    header('Location: login.php');
}

if(isset($_POST['search'])) {
    setcookie('search',$_POST['search'],time()+(8400*30),"/");
  }else{
    if(empty($_GET['pageno'])){
      unset($_COOKIE['search']);
      setcookie('search','NULL',-1,"/");
    }
  }
?>

<?php include_once "header.php" ?>


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Orders</h1>
                </div>
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <?php



    ?>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Order List Table</h3>
                        </div>
                        <?php
                        if (!empty($_GET['pageno'])) {
                            $pageno = $_GET['pageno'];
                        } else {
                            $pageno = 1;
                        }
                        $pageRecord = 2;
                        $offSet = ($pageno - 1) * $pageRecord;
                        $stmt = $pdo->prepare("SELECT * FROM sale_orders ORDER BY id DESC");
                        $stmt->execute();
                        $rawResult = $stmt->fetchAll();
                        $total_pages = ceil(count($rawResult) / $pageRecord);

                        $stmt = $pdo->prepare("SELECT * FROM sale_orders ORDER BY id DESC LIMIT $offSet,$pageRecord");
                        $stmt->execute();
                        $result = $stmt->fetchAll();
                        ?>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th style="width: 10px">ID</th>
                                        <th>Name</th>
                                        <th>Total Price</th>
                                        <th>
                                            Order Date
                                        </th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php

                                    if ($result) {
                                        $i = 1;
                                        foreach ($result as $value) {
                                    ?>

                                            <?php 
                                            $statement = $pdo->prepare("SELECT * FROM users WHERE id=" . $value['customer_id']);
                                            $statement->execute();
                                            $user = $statement->fetchAll(); 
                                            // echo "<pre>";
                                            // print_r($user);
                                            // exit();
                                            ?>
                                            <tr>
                                                <td><?php echo $i ?></td>
                                                <td><?php echo escape($user[0]['name']) ?></td>
                                                <td><?php echo escape($value['total_price']) ?></td>
                                                <td><?php echo escape(date('Y-m-d',strtotime($value['order_date']))) ?></td>
                                                <td>
                                                       <a href="order_detail.php?id=<?php echo $value['id']?>" type="button" class="btn btn-success">View</a>
                                                </td>
                                            </tr>
                                    <?php
                                            $i++;
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                            <nav aria-label="Page navigation example" class="mt-3" style="float:right">
                                <ul class="pagination">
                                    <li class="page-item">
                                        <a class="page-link" href="?pageno=1">First</a>
                                    </li>
                                    <li class="page-item <?php if ($pageno <= 1) echo "disabled"; ?>">
                                        <a class="page-link" href="<?php if ($pageno <= 1) {
                                                                        echo '#';
                                                                    } else {
                                                                        echo '?pageno=' . ($pageno - 1);
                                                                    } ?>">Previous</a>
                                    </li>
                                    <li class="page-item">
                                        <a class="page-link" href=""><?php echo $pageno; ?></a>
                                    </li>
                                    <li class="page-item <?php if ($pageno >= $total_pages) echo "disabled"; ?>">
                                        <a class="page-link" href="<?php if ($pageno >= $total_pages) {
                                                                        echo '#';
                                                                    } else {
                                                                        echo '?pageno=' . ($pageno + 1);
                                                                    } ?>">Next</a>
                                    </li>
                                    <li class="page-item">
                                        <a class="page-link" href="?pageno=<?php echo $total_pages ?>">Last</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<?php include_once "footer.php" ?>