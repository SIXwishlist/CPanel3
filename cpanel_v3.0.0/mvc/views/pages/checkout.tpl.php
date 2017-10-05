<?
    $lang         = $data->lang;

    $cart_items   = $data->cart_items;
    $total_items  = $data->total_items;
    $total_price  = $data->total_price;

    $cart_products = $data->cart_products;
    
?>

<h1><?= Dictionary::get_text('Checkout_lbl') ?></h1>

<div id="checkout">
    
    <form id="checkout_form" class="style1" method="post" action="" enctype="application/x-www-form-urlencoded">

        <fieldset class="clearfix">
            <legend><?= Dictionary::get_text('CheckoutForm_Legend_ProductInformation_lbl') ?></legend>
        </fieldset>

        <fieldset class="clearfix">
            <legend><?= Dictionary::get_text('CheckoutForm_Legend_CardInformation_lbl') ?></legend>
            
            <div class="label"><i class="fa fa-credit-card" aria-hidden="true"></i> <?= Dictionary::get_text('CheckoutForm_CardNumber_lbl') ?></div>
            <input type="text" name="card_number" value="" />
            <div class="note"> <?= Dictionary::get_text('CheckoutForm_CardNumber_Note_lbl') ?></div>

            <div class="clearfix"></div>
            
            <div class="label"><i class="fa fa-calendar" aria-hidden="true"></i> <?= Dictionary::get_text('CheckoutForm_ExpirationDate_lbl') ?></div>
            <input type="text" name="expiration_date" value="" />
            <div class="note"> <?= Dictionary::get_text('CheckoutForm_ExpirationDate_Note_lbl') ?></div>

            <div class="clearfix"></div>
            
            <div class="label"><i class="fa fa-credit-card-alt" aria-hidden="true"></i> <?= Dictionary::get_text('CheckoutForm_CardCode_lbl') ?></div>
            <input type="text" name="card_code" value="" />
            <div class="note"> <?= Dictionary::get_text('CheckoutForm_CardCode_Note_lbl') ?></div>

            <div class="clearfix"></div>

            <!--            
            <input id="remember" type="checkbox" name="remember" value="1" />
            <label for="remember"><?= Dictionary::get_text('LoginForm_Remeber_lbl') ?></label>
            -->
            
        </fieldset>

        <fieldset class="clearfix">
            <legend><?= Dictionary::get_text('CheckoutForm_Legend_BillingInformation_lbl') ?></legend>
        </fieldset>

        <fieldset class="clearfix">
            <legend><?= Dictionary::get_text('CheckoutForm_Legend_ShippingInformation_lbl') ?></legend>
        </fieldset>

        <div class="clearfix"></div><br />

        <div class="errors alert-error clearfix"></div>
        <div class="capatcha"></div>
        <div class="clearfix"></div>

        <input type="submit" value="<?= Dictionary::get_text('CheckoutForm_Submit_lbl') ?>" />
        <input type="reset"  value="<?= Dictionary::get_text('CheckoutForm_Reset_lbl') ?>" />

    </form>

    <div class="clearfix"></div><br /><br /><br />
    
</div>