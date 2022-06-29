<?php
session_start();
require_once "../config/config.php";
require_once "../config/common.php";

if (empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
    header('Location: login.php');
}
if ($_SESSION['role'] != 1) {
    header('Location: login.php');
}
if (!empty($_GET['pageno'])) {
    $pageno = $_GET['pageno'];
} else {
    $pageno = 1;
}
$pageRecord = 2;
$offSet = ($pageno - 1) * $pageRecord;
$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM sale_order_detail WHERE sale_order_id='$id'");
$stmt->execute();
$rawResult = $stmt->fetchAll();
$total_pages = ceil(count($rawResult) / $pageRecord);
$stmt = $pdo->prepare("SELECT * FROM sale_order_detail WHERE sale_order_id='$id'");
$stmt->execute();
$result = $stmt->fetchAll();


?>

<?php include_once "header.php" ?>


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Order Detail</h1>
                </div>
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Order Detail Table</h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th style="width: 10px">ID</th>
                                        <th>Product</th>
                                        <th>Quantity</th>
                                        <th>
                                            Order Date
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php

                                    if ($result) {
                                        $i = 1;
                                        foreach ($result as $value) {
                                    ?>
                                            <?php
                                            $statement = $pdo->prepare("SELECT * FROM product WHERE id=" . $value['product_id']);
                                            $statement->execute();
                                            $presult = $statement->fetchAll();
                                            ?>
                                            <tr>
                                                <td><?php echo $i ?></td>
                                                <td><?php echo escape($presult[0]['name']) ?></td>
                                                <td><?php echo escape($value['quantity']) ?></td>
                                                <td><?php echo escape(date('Y-m-d', strtotime($value['order_date']))) ?></td>
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