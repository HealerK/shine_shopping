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

?>

<?php include_once "header.php" ?>


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Monthly Report</h1>
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
                            <h3 class="card-title">Monthly Report Table</h3><br>
                            <p>Item which are sold above 5</p>
                        </div>
                        <?php
                        $currentDate = date("Y-m-d");
                        $stmt = $pdo->prepare("SELECT * FROM sale_order_detail GROUP BY product_id HAVING SUM(quantity) > 5 ORDER BY id DESC");
                        $stmt->execute();
                        $oResult = $stmt->fetchAll();
                        ?>
                        <div class="card-body">
                            <table class="table table-bordered" id="d-table">
                                <thead>
                                    <tr>
                                        <th style="width: 10px">ID</th>
                                        <th>Product</th>
                                        <th>Quantity</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php

                                    if ($oResult) {
                                        $i = 1;
                                        foreach ($oResult as $value) {
                                    ?>

                                            <?php
                                            $statement = $pdo->prepare("SELECT * FROM product WHERE id=" . $value['product_id']);
                                            $statement->execute();
                                            $user = $statement->fetchAll();
                                            ?>
                                            <tr>
                                                <td><?php echo $i ?></td>
                                                <td><?php echo escape($user[0]['name']) ?></td>
                                                <td><?php echo escape($value['quantity'])?></td>
                                            </tr>
                                    <?php
                                            $i++;
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
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