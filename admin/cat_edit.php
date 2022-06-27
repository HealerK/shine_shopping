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

if (isset($_POST['submit'])) {
    if (empty($_POST['name']) || empty($_POST['description'])) {
        if (empty($_POST['name'])) {
            $nameError = "Your name is required.";
        }
        if (empty($_POST['descritption'])) {
            $descriptionError = "Your description is required.";
        }
    } else {
        // echo "<script>alert('Hello')</script>";
        $name = $_POST['name'];
        $desc = $_POST['description'];
        $id = $_POST['id'];

        $sql = $pdo->prepare("UPDATE categories SET name=:name,description=:description WHERE id=:id");
        $result = $sql->execute(array(
            ':name' => $name,
            ':description' => $desc,
            ':id' => $id
        ));

        if ($result) {
            echo "<script>alert('Edit Successfully.');window.location.href='category.php';</script>";
        }
    }
}

$stmt = $pdo->prepare("SELECT * FROM categories WHERE id=" . $_GET['id']);
$stmt->execute();
$result = $stmt->fetchAll();






?>
<?php include_once "header.php" ?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Edit Category</h1>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <form action="" method="post">
                        <input type="hidden" name="id" value="<?php echo $result[0]['id'];?>">
                        <input type="hidden" name="_token" value="<?php echo $_SESSION['_token']; ?>">
                        <div class="form-group">
                            <label for="" class="label-control">Name</label>
                            <p class="text-red"><?php echo empty($nameError) ? '' : '*' . $nameError; ?></p>
                            <input type="text" class="form-control" name="name" value="<?php echo escape($result[0]['name'])?>">
                        </div>
                        <div class="form-group">
                            <label for="" class="label-control">Description</label>
                            <p class="text-red"><?php echo empty($descriptionError) ? '' : '*' . $descriptionError; ?></p>
                            <textarea name="description" id="" class="form-control"><?php echo escape($result[0]['description'])?></textarea>
                        </div>
                        <div class="form-group">
                            <input type="submit" class="btn btn-success" value="SUBMIT" name="submit">
                            <a href="category.php" class="btn btn-info" type="button">Back</a>
                        </div>
                    </form>
                </div>
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
</div>

<?php include_once "footer.php" ?>