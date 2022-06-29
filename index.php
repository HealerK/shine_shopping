<?php
if (isset($_POST['search'])) {
	setcookie('search', $_POST['search'], time() + (8400 * 30), "/");
} else {
	if (empty($_GET['pageno'])) {
		unset($_COOKIE['search']);
		setcookie('search', '', -1, "/");
	}
}

?>

<?php
include_once "header.php";

require_once "./config/config.php";

if (empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
	header('Location: login.php');
}

if (!empty($_GET['pageno'])) {
	$pageno = $_GET['pageno'];
} else {
	$pageno = 1;
}
$pageRecord = 4;
$offSet = ($pageno - 1) * $pageRecord;


if (empty($_POST['search']) && empty($_COOKIE['search'])) {
	if (isset($_GET['category_id'])) {
		$cat_id = $_GET['category_id'];
		$stmt = $pdo->prepare("SELECT * FROM product WHERE category_id=$cat_id AND quantity > 0 ORDER BY id DESC");
		$stmt->execute();
		$rawResult = $stmt->fetchAll();
		$total_pages = ceil(count($rawResult) / $pageRecord);

		$stmt = $pdo->prepare("SELECT * FROM product WHERE category_id=$cat_id AND quantity > 0 ORDER BY id DESC LIMIT $offSet,$pageRecord");
		$stmt->execute();
		$result = $stmt->fetchAll();
	} else {
		$stmt = $pdo->prepare("SELECT * FROM product WHERE quantity > 0 ORDER BY id DESC");
		$stmt->execute();
		$rawResult = $stmt->fetchAll();
		$total_pages = ceil(count($rawResult) / $pageRecord);

		$stmt = $pdo->prepare("SELECT * FROM product WHERE quantity > 0 ORDER BY id DESC LIMIT $offSet,$pageRecord");
		$stmt->execute();
		$result = $stmt->fetchAll();
	}
} else {
	$searchKey = isset($_POST['search']) ? $_POST['search'] : $_COOKIE['search'];
	$stmt = $pdo->prepare("SELECT * FROM product WHERE name AND quantity > 0 LIKE '%$searchKey%' ORDER BY id DESC");
	$stmt->execute();
	$rawResult = $stmt->fetchAll();
	$total_pages = ceil(count($rawResult) / $pageRecord);

	$stmt = $pdo->prepare("SELECT * FROM product WHERE name AND quantity > 0 LIKE '%$searchKey%' ORDER BY id DESC LIMIT $offSet,$pageRecord");
	$stmt->execute();
	$result = $stmt->fetchAll();
}


?>
<!-- End Filter Bar -->
<div class="container">
	<div class="row">
		<div class="col-xl-3 col-lg-4 col-md-5">
			<div class="sidebar-categories">
				<div class="head">Browse Categories</div>
				<ul class="main-categories">
					<li class="main-nav-list">
						<?php
						$catStmt = $pdo->prepare("SELECT * FROM categories ORDER BY id DESC");
						$catStmt->execute();
						$catResult = $catStmt->fetchAll();
						?>
						<?php foreach ($catResult as $value) { ?>
							<a href="index.php?category_id=<?php echo $value['id']; ?>">
								<span class="lnr lnr-arrow-right"></span><?php echo escape($value['name']) ?>
							</a>
						<?php } ?>
					</li>
				</ul>
			</div>
		</div>
		<div class="col-xl-9 col-lg-8 col-md-7">
			<!-- Start Filter Bar -->
			<div class="filter-bar d-flex flex-wrap align-items-center">
				<div class="pagination">
					<a href="?pageno=1" class="">First</a>
					<a href="<?php if ($pageno <= 1) {
									echo '#';
								} else {
									echo '?pageno=' . ($pageno - 1);
								} ?>" class="prev-arrow" <?php if ($pageno <= 1) echo "disabled"; ?>>
						<i class="fa fa-long-arrow-left" aria-hidden="true"></i>
					</a>
					<a href="#" class="active"><?php echo $pageno; ?></a>
					<a href="<?php if ($pageno >= $total_pages) {
									echo '#';
								} else {
									echo '?pageno=' . ($pageno + 1);
								} ?>" class="next-arrow" <?php if ($pageno >= $total_pages) echo "disabled"; ?>><i class="fa fa-long-arrow-right" aria-hidden="true"></i></a>
					<a href="?pageno=<?php echo $total_pages ?>" class="">Last</a>

				</div>
			</div>
			<!-- Start Best Seller -->
			<section class="lattest-product-area pb-40 category-list">
				<div class="row">
					<!-- single product -->
					<?php
					if ($result) {
						foreach ($result as $key => $value) { ?>
							<div class="col-lg-4 col-md-6">
								<div class="single-product">
									<a href="product_detail.php?id=<?php echo $value['id']; ?>"><img class="img-fluid" src="./images/<?php echo escape($value['image']) ?>" alt=""></a>
									<div class="product-details">
										<h6><?php echo escape($value['name']) ?></h6>
										<div class="price">
											<h6>$<?php echo escape($value['price']) ?></h6>
										</div>
										<div class="prd-bottom">
											<form action="carttoadd.php" method="post">
												<input type="hidden" name="_token" value="<?php echo $_SESSION['_token']?>">
												<input type="hidden" name="id" value="<?php echo escape($value['id'])?>">
												<input type="hidden" name="qty" value="1">
												<div class="social-info">
													<button style="display:contents;">
														<span class="ti-bag"></span>
														<p class="hover-text" style="left:20px;">add to bag</p>
													</button>
												</div>

												<a href="product_detail.php?id=<?php echo $value['id']; ?>" class="social-info">
													<span class="lnr lnr-move"></span>
													<p class="hover-text">view more</p>
												</a>
											</form>
										</div>
									</div>
								</div>
							</div>

					<?php }
					}
					?>

				</div>
			</section>
			<!-- End Best Seller -->
			<?php include_once "footer.php" ?>