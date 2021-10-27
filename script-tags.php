<?php
include_once('inc/conn.php');
include_once('inc/shopify.php');

$shopify = new Shopify();
$params = $_GET;

include_once("check_token.php");
$script_url = 'https://7831-2400-adc7-3101-5c00-9c41-da19-63a6-6e4.ngrok.io/shopiapp/scripts/shopiapp.js';

   /*
============================================
    Adding Script into Store
============================================
*/
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($_POST['action_type'] == 'create_script') {

        $script_tag_data = array(
            "script_tag" => array(
                "event" => 'onload',
                "src" => $script_url
            )
        );
        $script_tags = $shopify->rest_api('/admin/api/2021-04/script_tags.json', $script_tag_data, 'POST');
        $script_tags = json_decode($script_tags['body'], true);
        echo print_r($script_tags);
    }
}

   /*
============================================
    Deleting Script
============================================
*/
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($_POST['action_type'] == 'delete_script') {
        $script_tags = $shopify->rest_api('/admin/api/2021-04/script_tags.json', array(), 'GET');
        $script_tags = json_decode($script_tags['body'], true);
        
        foreach ($script_tags['script_tags'] as  $script) {
            $delete_script = $shopify->rest_api('/admin/api/2021-10/script_tags/'.$script['id'].'.json', array(), 'DELETE');
            print_r($delete_script);
        }
    }
}

   /*
============================================
    Script Page Layout
============================================
*/
include_once('inc/header.php');
?>
<section>
    <aside>
        <p>Click On Button to create script tag</p>
    </aside>
    <article>
        <form action="" method="POST">
            <input type="hidden" name="action_type" value="create_script">
            <button type="submit">Add Script Tag</button>
        </form>

    </article>
</section>
<section>
    <aside>
        <p>Click On Button to Delete script tag</p>
    </aside>
    <article>
        <form action="" method="POST">
            <input type="hidden" name="action_type" value="delete_script">
            <button type="submit">Delete Script Tag</button>
        </form>

    </article>
</section>
<?php
include_once('inc/footer.php');
?>