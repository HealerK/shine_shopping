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

$statement = $pdo->prepare("SELECT * FROM users WHERE id=" . $_GET['id']);
$statement->execute();
$posts = $statement->fetchAll();

if ($_POST) {
    if (empty($_POST['name']) || empty($_POST['email'])) {
        if (empty($_POST['name'])) {
            $nameError = "Name cannot be null.";
        }
        if (empty($_POST['email'])) {
            $emailError = "Email cannot be null.";
        }
    } elseif (!empty($_POST['password']) && strlen($_POST['password']) < 4) {
        $passwordError = 'Password should be 4 characters at least';
    } else {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $address = $_POST['address'];
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $id = $_POST['id'];
        if (empty($_POST['role'])) {
            $role = 0;
        } else {
            $role = 1;
        }

        $sql = $pdo->prepare("SELECT * FROM users WHERE email=:email AND id!=:id");
        $sql->execute(array(
            ":email" => $email,
            ":id" => $id
        ));
        $user = $sql->fetch(PDO::FETCH_ASSOC);
        if ($user) {
            echo "<script>alert('Email Duplicated!');</script>";
        } else {
            if ($password != null) {
                $sql = $pdo->prepare("UPDATE users SET name='$name',email='$email',phone='$phone',address='$address',password='$password',role='$role' WHERE id='$id'");
            } else {
                $sql = $pdo->prepare("UPDATE users SET name='$name',email='$email',phone='$phone',address='$address',role='$role' WHERE id='$id'");
            }
            $result = $sql->execute();
            if ($result) {
                echo "<script>alert('Successfully User Updated!');window.location.href='user_add_list.php';</script>";
            }
        }
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
                    <h1 class="m-0">User Edit</h1>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <?php
                    foreach ($posts as $value) {
                    ?>
                        <form action="" method="post">
                            <div class="form-group">
                                <input name="_token" type="hidden" value="<?php echo $_SESSION['_token'] ?>">
                                <input type="hidden" name="id" value="<?php echo escape($value['id']) ?>">
                                <label for="" class="label-control">Name</label>
                                <p class="text-red"><?php echo empty($nameError) ? '' : '*' . $nameError; ?></p>
                                <input type="text" class="form-control" name="name" value="<?php echo escape($value['name']) ?>">
                            </div>
                            <div class="form-group">
                                <label for="" class="label-control">Email</label>
                                <p class="text-red"><?php echo empty($emailError) ? '' : '*' . $emailError; ?></p>
                                <input name="email" id="" class="form-control" value="<?php echo escape($value['email']) ?>"></input>
                            </div>
                            <div class="form-group">
                                <label for="">Phone</label>
                                <p style="color:red"><?php echo empty($phoneError) ? '' : '*' . $phoneError; ?></p>
                                <input type="text" class="form-control" name="phone" value="<?php echo escape($value['phone']) ?>">
                            </div>
                            <div class="form-group">
                                <label for="">Address</label>
                                <p style="color:red"><?php echo empty($addressError) ? '' : '*' . $addressError; ?></p>
                                <input type="text" class="form-control" name="address" value="<?php echo escape($value['address']) ?>">
                            </div>
                            <div class="form-group">
                                <label for="" class="lael-control">Password</label><br>
                                <span class="text-blue">This user already password exist.</span>
                                <p class="text-red"><?php echo empty($passwordError) ? '' : '*' . $passwordError; ?></p>
                                <input name="password" type="password" class="form-control" value="<?php echo escape($value['password']) ?>">
                            </div>
                            <div class="form-group">
                                <label for="" class="label-control">If you do check,you will be admin.</label><br>
                                <input type="checkbox" name="role">
                            </div>
                            <div class="form-group">
                                <input type="submit" class="btn btn-success" value="SUBMIT" name="submit">
                                <a href="user_add_list.php" class="btn btn-info" type="button">Back</a>
                            </div>
                        </form>
                    <?php }

                    ?>

                </div>
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
</div>
<!-- /.content-wrapper -->
<?php include_once "footer.php" ?>