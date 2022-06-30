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
                            <h3 class="card-title">Monthly Report Table</h3>
                        </div>
                        <?php
                        $currentDate = date("Y-m-d");
                        $fromDate = date("Y-m-d", strtotime($currentDate . '+1 day'));
                        $toDate = date("Y-m-d", strtotime($currentDate . '-1 month'));
                        $stmt = $pdo->prepare("SELECT * FROM sale_orders WHERE order_date<:from_date AND order_date>=:todate ORDER BY id DESC");
                        $stmt->execute([':from_date' => $fromDate, ':todate' => $toDate]);
                        $oResult = $stmt->fetchAll();
                        ?>
                        <div class="card-body">
                            <table class="table table-bordered" id="d-table">
                                <thead>
                                    <tr>
                                        <th style="width: 10px">ID</th>
                                        <th>Customer ID</th>
                                        <th>Total Price</th>
                                        <th>
                                            Order Date
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php

                                    if ($oResult) {
                                        $i = 1;
                                        foreach ($oResult as $value) {
                                    ?>

                                            <?php
                                            $statement = $pdo->prepare("SELECT * FROM users WHERE id=" . $value['customer_id']);
                                            $statement->execute();
                                            $user = $statement->fetchAll();
                                            ?>
                                            <tr>
                                                <td><?php echo $i ?></td>
                                                <td><?php echo escape($user[0]['name']) ?></td>
                                                <td><?php echo escape($value['total_price']) ?></td>
                                                <td><?php echo escape(date('Y-m-d', strtotime($value['order_date']))) ?></td>
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