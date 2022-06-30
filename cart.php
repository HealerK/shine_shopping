<?php require_once "header.php" ?>

<!--================Cart Area =================-->
<section class="cart_area">
    <div class="container">
        <div class="cart_inner">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Product</th>
                            <th scope="col">Price</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Total</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        require_once "./config/config.php";
                        $total = 0;
                        if (isset($_SESSION['cart'])) {   
                        foreach ($_SESSION['cart'] as $key => $qty) {                                                     
                                $id = str_replace('id', '', $key);
                                $stmt = $pdo->prepare("SELECT * FROM product WHERE id=:id");
                                $stmt->execute(array(
                                    ':id' => $id
                                ));
                                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                                $total += $result['price'] * $qty;                          
                        ?>
                            <tr>
                                <td>
                                    <div class="media">
                                        <div class="d-flex">
                                            <img src="./images/<?php echo $result['image'] ?>" alt="" style="width:200px;height:160px;">
                                        </div>
                                        <div class="media-body">
                                            <p><?php echo escape($result['name']) ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <h5>$<?php echo escape($result['price']) ?></h5>
                                </td>
                                <td>
                                    <div class="product_count">
                                        <input type="text" name="qty" value="<?php echo escape($qty) ?>" title="Quantity:" class="input-text qty" readonly>

                                    </div>
                                </td>
                                <td>
                                    <h5>$<?php echo escape($result['price'] * $qty) ?></h5>
                                </td>
                                <td>
                                    <a href="cart_item_clear.php?pid=<?php echo $result['id']?>" class="btn btn-danger">Clear</a>
                                </td>
                            </tr>
                        <?php } 
                        }?>
                        <tr>
                            <td>

                            </td>
                            <td>

                            </td>
                            <td>
                                <h5>Subtotal</h5>
                            </td>
                            <td>
                                <h5>$<?php echo $total ?></h5>
                            </td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr class="out_button_area">
                            
                            <td>

                            </td>
                            <td>

                            </td>
                            <td>

                            </td>
                            <td>

                            </td>
                            <td>
                                <div class="checkout_btn_inner d-flex align-items-center">
                                    <a class="gray_btn" href="clear_all.php">Clear All</a>
                                    <a class="primary-btn" href="index.php">Continue Shopping</a>
                                    <a class="gray_btn" href="sale_order.php">Proceed to checkout</a>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
<!--================End Cart Area =================-->

<!-- start footer Area -->
<?php include_once "footer.php" ?>