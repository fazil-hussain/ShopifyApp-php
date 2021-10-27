<?php

include_once('inc/conn.php');
include_once('inc/shopify.php');

$shopify = new Shopify();
$params = $_GET;

include_once("check_token.php");
/*
============================================
    GraphQl Query for First 2 Products
============================================
*/
$graphiql_query = array(
    "query" => "{
        products(first:2){
    edges{
      node{
        id
        title
        vendor
        status
        images(first: 1) {
          edges {
            node {
              originalSrc
            }
          }
        }
      }
    }
  }
    }"
);

/*
============================================
    Api Call To Fetch Products
============================================
*/
$graphiql_test = $shopify->graphql($graphiql_query);

$query_details = json_decode($graphiql_test['body'], true);
$product_eges = $query_details['data']['products'];
// echo print_r($product_eges);

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
            foreach ($product_eges as $edge)
            foreach ($edge as $key => $node) {
                foreach ($node as $key => $val2) {
                    /*------------Explode id to use in rest api------------*/
                    // $pro_id= explode('/',$val2['id']);
                    // $pro_id = end($pro_id);
                    // echo $pro_id;

                    $image = count($val2['images']['edges']) > 0 ? $val2['images']['edges'][0]['node']['originalSrc'] : "";
            ?>
                    <tr>
                        
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