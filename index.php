<?php
include_once("inc/conn.php");
include_once("inc/shopify.php");

/*
============================================
    Checking Shoop Details
============================================
*/


$shopify = new Shopify();
$params = $_GET;

include_once("check_token.php");

/*
============================================
    One Time Billing Charges
============================================
*/

include_once("billing/oneTimeBilling.php");

/*
============================================
    Recurring Billing Charges
============================================
*/
// include_once("billing/recurringBilling.php");

include_once('inc/header.php');

?>
<main>
    <section>
        <aside>
            <h1>Shopify App</h1>
        </aside>
        <article>
            <div class="card">
                <h2>Modules</h2>
                <ul>
                    <li>Rest Api</li>
                    <li>GraphQl Api</li>
                    <li>WebHooks</li>
                    <li>Script Tag</li>
                    <li>App Bridge</li>
                    <li>One Time Billing</li>
                    <li>Recurring Billing</li>
                    <li>Credit</li>
                </ul>
            </div>
        </article>
    </section>
</main>
<?php
include_once('inc/footer.php');
?>