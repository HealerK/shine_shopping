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
    if (empty($_POST['name']) || empty($_POST['description']) || empty($_POST['price']) || empty($_POST['quantity']) || empty($_POST['category']) || empty($_FILES['image']['name'])) {
        if (empty($_POST['name'])) {
            $nameError = "Your name is required.";
        }
        if (empty($_POST['descritption'])) {
            $descriptionError = "Your description is required.";
        }
        if (empty($_POST['price'])) {
            $priceError = "Your price is required.";
        } else if (is_numeric($_POST['price']) != 1) {
            $priceError = "Your price must be inter.";
        }
        if (empty($_POST['quantity'])) {
            $qtyError = "Your quantity is required.";
        } else if (is_numeric($_POST['price']) != 1) {
            $qtyError = "Your quantity must be inter.";
        }
        if (empty($_POST['category'])) {
            $categoryError = "Your category is required.";
        }
        if (empty($_FILES['image']['name'])) {
            $imageError = "Your image is required.";
        }
    } else {
        if (is_numeric($_POST['price']) != 1) {
            $priceError = "Your quantity must be inter.";
        }
        if (is_numeric($_POST['quantity']) != 1) {
            $qtyError = "Your quantity must be inter.";
        }
        if ($qtyError == null &&  $priceError == null) {
            $image_name = $_FILES['image']['name'];
            $image = pathinfo($image_name, PATHINFO_EXTENSION);
            if ($image != 'png' && $image != 'jpeg' && $image != 'jpg') {
            } else {
                $name = $_POST['name'];
                $description = $_POST['description'];
                $price = $_POST['price'];
                $quantity = $_POST['quantity'];
                $category = $_POST['category'];
                $image_tmp = $_FILES['image']['tmp_name'];
                move_uploaded_file($image_tmp, '../images/' . $image_name);
                $stmt = $pdo->prepare("INSERT INTO product (name,description,price,quantity,category_id,image) VALUES (:name,:description,:price,:quantity,:category_id,:image);");
                $result = $stmt->execute(
                    array(
                        ':name' => $name,
                        ':description' => $description,
                        ':price' => $price,
                        ':quantity' => $quantity,
                        ':category_id' => $category,
                        ':image' => $image_name
                    )
                );
                if ($result) {
                    echo "<script>alert('Create Product Successfully.');window.location.href='index.php';</script>";
                }
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
                    <h1 class="m-0">Create New Product</h1>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <form action="product_add.php" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="_token" value="<?php echo $_SESSION['_token']; ?>">
                        <div class="form-group">
                            <label for="" class="label-control">Name</label>
                            <p class="text-red"><?php echo empty($nameError) ? '' : '*' . $nameError; ?></p>
                            <input type="text" class="form-control" name="name">
                        </div>
                        <div class="form-group">
                            <label for="" class="label-control">Description</label>
                            <p class="text-red"><?php echo empty($descriptionError) ? '' : '*' . $descriptionError; ?></p>
                            <textarea name="description" id="" class="form-control"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="" class="label-control">Price</label>
                            <p class="text-red"><?php echo empty($priceError) ? '' : '*' . $priceError; ?></p>
                            <input type="text" class="form-control" name="price">
                        </div>
                        <div class="form-group">
                            <label for="" class="label-control">Quantity</label>
                            <p class="text-red"><?php echo empty($qtyError) ? '' : '*' . $qtyError; ?></p>
                            <input type="text" class="form-control" name="quantity">
                        </div>
                        <div class="form-group">
                            <?php
                            $stmt = $pdo->prepare("SELECT * FROM categories");
                            $stmt->execute();
                            $result = $stmt->fetchAll();
                            ?>
                            <label for="" class="label-control">Category</label>
                            <p class="text-red"><?php echo empty($categoryError) ? '' : '*' . $categoryError; ?></p>
                            <select name="category" id="" class="form-control">
                                <option value="">Select Category</option>
                                <?php foreach ($result as $value) { ?>
                                    <option value="<?php echo $value['id'] ?>"><?php echo $value['name'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="" class="label-control">Image</label>
                            <p class="text-red"><?php echo empty($imageError) ? '' : '*' . $imageError; ?></p>
                            <input type="file" class="mb-2" name="image">
                        </div>
                        <div class="form-group">
                            <input type="submit" class="btn btn-success" value="SUBMIT" name="submit">
                            <a href="index.php" class="btn btn-info" type="button">Back</a>
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