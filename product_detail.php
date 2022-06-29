<?php include_once "header.php";
require_once "./config/config.php";
?>
<!--================Single Product Area =================-->
<div class="product_image_area" style="padding-top: 0px !important;">
  <div class="container">
    <?php
    $id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM product WHERE id=:id");
    $stmt->execute(array(
      ':id' => $id
    ));
    $result = $stmt->fetchAll();

    ?>
    <div class="row s_product_inner">
      <?php foreach ($result as $value) { ?>
        <div class="col-lg-6">
          <img class="img-fluid" src="./images/<?php echo escape($value['image']) ?>" style="height:100%;width:100%;">
        </div>
        <div class="col-lg-5 offset-lg-1">
          <div class="s_product_text">
            <h3><?php echo escape($value['name']) ?></h3>
            <h2>$<?php echo escape($value['price']) ?></h2>
            <ul class="list">
              <?php
              $catStmt = $pdo->prepare("SELECT * FROM categories ORDER BY id DESC");
              $catStmt->execute();
              $catResult = $catStmt->fetchAll();
              ?>
              <?php foreach ($catResult as $values) { ?>
                <li><span>Category</span> : <?php echo escape($values['name']) ?></li>
              <?php } ?>
              <li><span>Availibility</span> : In Stock</li>
            </ul>
            <p><?php echo $value['description'] ?></p>
            <form action="carttoadd.php" method="post">
              <input type="hidden" name="_token" value="<?php echo $_SESSION['_token']?>">
              <input type="hidden" name="id" value="<?php echo $value['id']?>">
            <div class="product_count">
              <label for="qty">Quantity:</label>
              <input type="text" name="qty" id="sst" maxlength="12" value="1" title="Quantity:" class="input-text qty">
              <button onclick="var result = document.getElementById('sst'); var sst = result.value; if( !isNaN( sst )) result.value++;return false;" class="increase items-count" type="button"><i class="lnr lnr-chevron-up"></i></button>
              <button onclick="var result = document.getElementById('sst'); var sst = result.value; if( !isNaN( sst ) &amp;&amp; sst > 0 ) result.value--;return false;" class="reduced items-count" type="button"><i class="lnr lnr-chevron-down"></i></button>
            </div>
            <div class="card_area d-flex align-items-center">
              <button class="primary-btn" href="" name="submit">Add to Cart</button>
              <a class="primary-btn" href="index.php">Back</a>
            </div>
            </form>
          </div>
        </div>
    </div>
  <?php } ?>
  </div>
</div><br>
<!--================End Single Product Area =================-->

<!--================End Product Description Area =================-->
<?php include_once "footer.php"; ?>