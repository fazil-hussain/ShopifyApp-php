<?php

include_once('inc/conn.php');
include_once('inc/shopify.php');

$shopify = new Shopify();
$params = $_GET;

include_once("check_token.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    /*
============================================
    Delete Product
============================================
*/
    if (isset($_POST['del_pro']) && $_POST['action_type'] == 'delete') {
        $delete = $shopify->rest_api('/admin/api/2021-10/products/' . $_POST['del_pro'] . '.json', array(), 'DELETE');
        $delete = json_decode($delete['body'], true);
    }

    /*
============================================
    Update Product
============================================
*/
    if (isset($_POST['update_pro']) && $_POST['action_type'] == 'update') {

        $update_details = array(
            "product" => array(
                'id' => $_POST['update_pro'],
                'title' => $_POST['update_name'],

            )
        );
        $update = $shopify->rest_api('/admin/api/2021-10/products/' . $_POST['update_pro'] . '.json', $update_details, 'PUT');
        $update = json_decode($update['body'], true);
    }

    /*
============================================
    Create Product
============================================
*/
    if (isset($_POST['product_title']) && isset($_POST['product_body_html']) && $_POST['action_type'] == 'create_product') {
        $product_data = array(
            "product" => array(
                'title' => $_POST['product_title'],
                'body_html' => $_POST['product_body_html']
            )
        );
        $create_product = $shopify->rest_api('/admin/api/2021-10/products.json', $product_data, 'POST');
        $create_product = json_decode($create_product['body'], true);
    }
}
/*
============================================
    Get All Prodcues
============================================
*/
$products = $shopify->rest_api('/admin/api/2021-10/products.json', array(), 'GET');
$products = json_decode($products['body'], true);
include_once('inc/header.php')
?>
<section>
    <aside>
        <h2>Create New Product</h2>
        <p>fill out the following form to create new product</p>
    </aside>
    <article>
        <div class="card">
            <form action="" method="post">
                <input type="hidden" name="action_type" value="create_product">
                <div class="row">
                    <label for="producttile">Title</label>
                    <input type="text" name="product_title" id="producttile">
                </div>
                <div class="row">
                    <label for="productdescription">Description</label>
                    <textarea name="product_body_html" id="productdescription"></textarea>
                </div>

                <div class="row">
                    <button type="submit">Add</button>
                </div>
            </form>
        </div>
    </article>
</section>
<section>
    <table>
        <thead>
            <tr>
                <th colspan="2">Product</th>
                <th>Vendor</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($products as $key => $value) {
                foreach ($value as $key => $val2) {
                    $image = count($val2['images']) > 0 ? $val2['images'][0]['src'] : "";
            ?>
                    <tr>
                        <!-- <td><a href="#"><img width="35" height="35" alt="" src="<?php echo $val2['image']['src'] ?>"></a></td> -->
                        <td><a href="#"><img width="35" height="35" alt="" src="<?php echo  $image ?>"></a></td>
                        <td>
                            <form action="" class="row side-elements" method="POST">
                                <input type="hidden" value="<?php echo $val2['id'] ?>" name="update_pro">
                                <input type="hidden" name="action_type" value="update">
                                <input type="text" name="update_name" value="<?php echo $val2['title'] ?>" id="">
                                <button type="submit" class="secondary icon-checkmark"></button>
                            </form>
                        </td>
                        <td><?php echo $val2['vendor'] ?></td>
                        <td><?php echo $val2['status'] ?></td>
                        <td>
                            <form action="" method="POST">
                                <input type="hidden" name="del_pro" value="<?php echo $val2['id'] ?>">
                                <input type="hidden" name="action_type" value="delete">
                                <button type="submit" class="secondary icon-trash"></button>
                            </form>
                        </td>

                    </tr>
            <?php
                }
            }
            ?>
        </tbody>
    </table>
</section>
<?php
include_once('inc/footer.php');
?>