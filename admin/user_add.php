<?php
session_start();
require '../config/config.php';
require '../config/common.php';

if (empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
  header('Location: login.php');
}
if ($_SESSION['role'] != 1) {
  header('Location: login.php');
}

if ($_POST) {
  if (empty($_POST['name']) || empty($_POST['email']) || empty($_POST['phone']) || empty($_POST['address']) || empty($_POST['password']) || strlen($_POST['password']) < 4) {
    if (empty($_POST['name'])) {
      $nameError = 'Name cannot be null';
    }
    if (empty($_POST['email'])) {
      $emailError = 'Email cannot be null';
    }
    if (empty($_POST['phone'])) {
      $phoneError = 'Phone cannot be null';
    }
    if (empty($_POST['address'])) {
      $addressError = 'Address cannot be null';
    }
    if (empty($_POST['password'])) {
      $passwordError = 'Password cannot be null';
    }
    if(strlen($_POST['password']) < 4){
      $passwordError = 'Password should be 4 characters at least';
    }
  }else{
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $password = password_hash($_POST['password'],PASSWORD_DEFAULT);

    if (empty($_POST['role'])) {
      $role = 0;
    }else{
      $role = 1;
    }

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email=:email");

    $stmt->bindValue(':email',$email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
      echo "<script>alert('Email duplicated')</script>";
    }else{
      $stmt = $pdo->prepare("INSERT INTO users(name, email, password, phone, address, role) VALUES (:name,:email,:password,:phone,:address,:role)");
      $result = $stmt->execute(
        array(
          ':name' => $name,
          ':email' => $email,
          ':password' => $password,
          ':phone' => $phone,
          ':address' => $address,
          ':role' => $role
        )
      );
        if($result) {
          echo "<script>alert('Add New User Successfully.');window.location.href='user_add_list.php';</script>";
        }
    }
  }
}

?>


<?php include_once "header.php" ?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Create New Category</h1>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                <form class="" action="user_add.php" method="post">
                  <input name="_token" type="hidden" value="<?php echo $_SESSION['_token']; ?>">

                  <div class="form-group">
                    <label for="">Name</label><p style="color:red"><?php echo empty($nameError) ? '' : '*'.$nameError; ?></p>
                    <input type="text" class="form-control" name="name" value="" >
                  </div>
                  <div class="form-group">
                    <label for="">Email</label><p style="color:red"><?php echo empty($emailError) ? '' : '*'.$emailError; ?></p>
                    <input type="email" class="form-control" name="email" value="">
                  </div>
                  <div class="form-group">
                    <label for="">Phone</label><p style="color:red"><?php echo empty($phoneError) ? '' : '*'.$phoneError; ?></p>
                    <input type="text" class="form-control" name="phone" value="" >
                  </div>
                  <div class="form-group">
                    <label for="">Address</label><p style="color:red"><?php echo empty($addressError) ? '' : '*'.$addressError; ?></p>
                    <input type="text" class="form-control" name="address" value="" >
                  </div>
                  <div class="form-group">
                    <label for="">Password</label><p style="color:red"><?php echo empty($passwordError) ? '' : '*'.$passwordError; ?></p>
                    <input type="password" name="password" class="form-control">
                  </div>
                  <div class="form-group">
                    <label for="vehicle3"> Admin</label><br>
                    <input type="checkbox" name="role" value="1">
                  </div>
                  <div class="form-group">
                    <input type="submit" class="btn btn-success" name="" value="SUBMIT">
                    <a href="user_list.php" class="btn btn-warning">Back</a>
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