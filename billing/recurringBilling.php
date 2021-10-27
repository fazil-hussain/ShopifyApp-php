<?php
$query = "SELECT * FROM recurringbilling WHERE shop_url = '" . $shopify->get_url() . "' ";
$resutl = $connection->query($query);
$billing_data = $resutl->fetch_assoc();

if (isset($_GET['charge_id']) || $resutl->num_rows > 0) {
    $charge_id = isset($_GET['charge_id']) ? $_GET['charge_id'] : $billing_data['charge_id'];
    $query = array(
        "query" => '{
            node(id: "gid://shopify/AppSubscription/' . $charge_id . '"){
                ...on AppSubscription{
                    status
                    id
                }
            }
        }'
    );
    $check_charge = $shopify->graphql($query);
    $check_charge = json_decode($check_charge['body'], true);
    // print_r($check_charge);
    // die();
    if (!empty($check_charge['data']['node'])) {
        if ($check_charge['data']['node']['status'] != 'ACTIVE') {
            echo 'you have not pay to shopify ';
            die;
        }
    } else {
        echo 'wow looks like your trying to your own charge id';
        die;
    }
    $shop_url = $shopify->get_url();
    $charge_id = $check_charge['data']['node']['id'];
    $charge_id = explode('/', $charge_id);
    $charge_id = $charge_id[array_key_last($charge_id)];
    $gid = $check_charge['data']['node']['id'];
    $status = $check_charge['data']['node']['status'];
    $query = "INSERT INTO  recurringbilling(shop_url, charge_id, gid, status) VALUE ('" . $shop_url . "', '" . $charge_id . "', '" . $gid . "', '" . $status . "') ON DUPLICATE KEY UPDATE status= '" . $status . "'";
    $connection->query($query);
} else {
    $query = array(
        "query" => 'mutation {
            appSubscriptionCreate(
                name: "WeeklyHow Application Recurring Cahrge"
                lineItems: {
                    plan: {
                        appRecurringPricingDetails: {
                            price: {
                                amount: 1.1,
                                currencyCode: USD
                            }
                        }
                    }
                }
                test:true
                returnUrl: "https://' . $shopify->get_url() . '/admin/apps/shopiapp-18"
            )
            {
                appSubscription {
                    id
                }
                confirmationUrl
                userErrors{
                    field
                    message
                }
            }
        }'
    );

    $charge = $shopify->graphql($query);
    $charge = json_decode($charge['body'], true);
    echo "<script>top.window.location = '" . $charge['data']['appSubscriptionCreate']['confirmationUrl'] . "'</script>";
    die();
}
