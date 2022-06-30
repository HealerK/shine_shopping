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
    if (
        empty($_POST['name']) || empty($_POST['description']) || empty($_POST['category'])
        || empty($_POST['quantity']) || empty($_POST['price']) || empty($_FILES['image'])
    ) {
        if (empty($_POST['name'])) {
            $nameError = 'Category name is required';
        }
        if (empty($_POST['description'])) {
            $descError = 'Description is required';
        }
        if (empty($_POST['category'])) {
            $catError = 'Category is required';
        }
        if (empty($_POST['quantity'])) {
            $qtyError = 'Quantity is required';
        } elseif (is_numeric($_POST['quantity']) != 1) {
            $qtyError = 'Quantity should be integer value';
        }
        if (empty($_POST['price'])) {
            $priceError = 'Price is required';
        } elseif (is_numeric($_POST['price']) != 1) {
            $priceError = 'Price should be integer value';
        }
        if (empty($_FILES['image'])) {
            $imageError = 'Image is required';
        }
    } else {
        if (is_numeric($_POST['price']) != 1) {
            $priceError = "Your price must be inter.";
        }
        if (is_numeric($_POST['price']) != 1) {
            $qtyError = "Your quantity must be inter.";
        }
        if ($priceError == null && $qtyError == null) {
            if ($_FILES['image']['name'] != null) {
                $image_name = $_FILES['image']['name'];
                $image = pathinfo($image_name, PATHINFO_EXTENSION);
                if ($image != 'png' && $image != 'jpeg' && $image != 'jpg') {
                    echo "<script>alert('Your image must be jpeg,jpg or png.')</script>";
                } else {
                    $name = $_POST['name'];
                    $description = $_POST['description'];
                    $price = $_POST['price'];
                    $quantity = $_POST['quantity'];
                    $category = $_POST['category'];
                    $id = $_POST['id'];
                    $image_tmp = $_FILES['image']['tmp_name'];
                    move_uploaded_file($image_tmp, '../images/' . $image_name);
                    $stmt = $pdo->prepare("UPDATE product SET name=:name,description=:description,price=:price,quantity=:quantity,category_id=:category_id,image=:image WHERE id=:id");
                    $result = $stmt->execute(
                        array(
                            ':name' => $name,
                            ':description' => $description,
                            ':category_id' => $category,
                            ':price' => $price,
                            ':quantity' => $quantity,
                            ':image' => $image_name,
                            ':id' => $id
                        )
                    );
                    if ($result) {
                        echo "<script>alert('Edit Product Successfully.');window.location.href='index.php';</script>";
                    }
                }
            } else {
                $name = $_POST['name'];
                $description = $_POST['description'];
                $price = $_POST['price'];
                $quantity = $_POST['quantity'];
                $category = $_POST['category'];
                $id = $_POST['id'];

                $stmt = $pdo->prepare("UPDATE product SET name=:name,description=:description,price=:price,quantity=:quantity,category_id=:category_id WHERE id=:id");
                $result = $stmt->execute(
                    array(
                        ':name' => $name,
                        ':description' => $description,
                        ':category_id' => $category,
                        ':price' => $price,
                        ':quantity' => $quantity,
                        ':id' => $id
                    )
                );
                if ($result) {
                    echo "<script>alert('Edit Product Successfully.');window.location.href='index.php';</script>";
                }
            }
        }
    }
}

$statement = $pdo->prepare("SELECT * FROM product WHERE id=" . $_GET['id']);
$statement->execute();
$result = $statement->fetchAll();
// echo "<pre>";
// print_r($result);

?>
<?php include_once "header.php" ?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Edit Product</h1>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <?php foreach ($result as $value) {
                    ?>
                        <form action="" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="_token" value="<?php echo $_SESSION['_token']; ?>">
                            <input type="hidden" name="id" value="<?php echo $value['id'] ?>">
                            <div class="form-group">
                                <label for="" class="label-control">Name</label>
                                <p class="text-red"><?php echo empty($nameError) ? '' : '*' . $nameError; ?></p>
                                <input type="text" class="form-control" name="name" value="<?php echo escape($value['name']) ?>">
                            </div>
                            <div class="form-group">
                                <label for="" class="label-control">Description</label>
                                <p class="text-red"><?php echo empty($descError) ? '' : '*' . $descError; ?></p>
                                <textarea name="description" id="" class="form-control"><?php echo escape($value['description']) ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="" class="label-control">Price</label>
                                <p class="text-red"><?php echo empty($priceError) ? '' : '*' . $priceError; ?></p>
                                <input type="text" class="form-control" name="price" value="<?php echo escape($value['price']) ?>">
                            </div>
                            <div class="form-group">
                                <label for="" class="label-control">Quantity</label>
                                <p class="text-red"><?php echo empty($qtyError) ? '' : '*' . $qtyError; ?></p>
                                <input type="text" class="form-control" name="quantity" value="<?php echo escape($value['quantity']) ?>">
                            </div>
                            <div class="form-group">
                                <?php
                                $stmt = $pdo->prepare("SELECT * FROM categories");
                                $stmt->execute();
                                $result = $stmt->fetchAll();
                                ?>
                                <label for="" class="label-control">Category</label>
                                <p class="text-red"><?php echo empty($catError) ? '' : '*' . $catError; ?></p>
                                <select name="category" id="" class="form-control">
                                    <option value="">Select Category</option>
                                    <?php foreach ($result as $values) { ?>
                                        <?php if ($values['id'] == $result[0]['id']) : ?>
                                            <option value="<?php echo escape($values['id']) ?>" selected><?php echo escape($values['name']) ?></option>
                                        <?php else : ?>
                                            <option value="<?php echo escape($values['id']) ?>"><?php echo escape($values['name']) ?></option>
                                        <?php endif ?>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="" class="label-control">Image</label>
                                <p class="text-red"><?php echo empty($imageError) ? '' : '*' . $imageError; ?></p>
                                <img src="../images/<?php echo escape($value['image']) ?>" alt="" width="200px"><br><br>
                                <input type="file" class="mb-2" name="image">
                            </div>
                            <div class="form-group">
                                <input type="submit" class="btn btn-success" value="SUBMIT" name="submit">
                                <a href="index.php" class="btn btn-info" type="button">Back</a>
                            </div>
                        </form>
                    <?php } ?>
                </div>
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
</div>

<?php include_once "footer.php" ?>