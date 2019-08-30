<!DOCTYPE html>
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">
<head>
<meta charset="UTF-8" />
<title><?php echo $title; ?></title>
<base href="<?php echo $base; ?>" />
<link href="catalog/view/javascript/bootstrap/css/bootstrap.css" rel="stylesheet" media="all" />
<script type="text/javascript" src="catalog/view/javascript/jquery/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="catalog/view/javascript/bootstrap/js/bootstrap.min.js"></script>
<link href="catalog/view/javascript/font-awesome/css/font-awesome.min.css" type="text/css" rel="stylesheet" />
<link type="text/css" href="catalog/view/theme/default/stylesheet/stylesheet.css" rel="stylesheet" media="all" />
</head>
<body>
<div class="container">
   <div style="page-break-after: always;">
    <?php if($customer_invoice_store_logo) { ?>
		<div class="row">
			<div class="col-sm-6">
				<?php if ($logo) { ?>
					<img src="<?php echo $logo; ?>" title="<?php echo $store_name; ?>" alt="<?php echo $store_name; ?>" class="img-responsive" style="margin-top: 14px; margin-bottom: 10px;" />
				<?php } else { ?>
					<h1><?php echo $store_name; ?></h1>
				<?php } ?>
			</div>
			<div class="col-sm-6"><h1 class="pull-right"><?php echo $text_invoice; ?> #<?php echo $order_id; ?></h1></div>
		</div>
	<?php } else { ?>
		<h1><?php echo $text_invoice; ?> #<?php echo $order_id; ?></h1>
	<?php } ?>
    <table class="table table-bordered">
      <thead>
        <tr>
          <td colspan="2"><?php echo $text_order_detail; ?></td>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td style="width: 50%;"><address>
            <strong><?php echo $store_name; ?></strong><br />
            <?php echo $store_address; ?>
            </address>
            <b><?php echo $text_telephone; ?></b> <?php echo $store_telephone; ?><br />
            <?php if ($store_fax) { ?>
            <b><?php echo $text_fax; ?></b> <?php echo $store_fax; ?><br />
            <?php } ?>
            <b><?php echo $text_email; ?></b> <?php echo $store_email; ?><br />
            <b><?php echo $text_website; ?></b> <a href="<?php echo $store_url; ?>"><?php echo $store_url; ?></a></td>
          <td style="width: 50%;"><b><?php echo $text_date_added; ?></b> <?php echo $date_added; ?><br />
            <?php if ($invoice_no) { ?>
            <b><?php echo $text_invoice_no; ?></b> <?php echo $invoice_no; ?><br />
            <?php } ?>
            <b><?php echo $text_order_id; ?></b> <?php echo $order_id; ?><br />
            <b><?php echo $text_payment_method; ?></b> <?php echo $payment_method; ?><br />
            <?php if ($shipping_method) { ?>
            <b><?php echo $text_shipping_method; ?></b> <?php echo $shipping_method; ?><br />
            <?php } ?></td>
        </tr>
      </tbody>
    </table>
    <table class="table table-bordered">
      <thead>
        <tr>
          <td style="width: 50%;"><b><?php echo $text_payment_address; ?></b></td>
          <td style="width: 50%;"><b><?php echo $text_shipping_address; ?></b></td>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><address>
            <?php echo $payment_address; ?>
            </address></td>
          <td><address>
            <?php echo $shipping_address; ?>
            </address></td>
        </tr>
      </tbody>
    </table>
    <table class="table table-bordered">
      <thead>
        <tr>
          <td><b><?php echo $column_product; ?></b></td>
          <td><b><?php echo $column_model; ?></b></td>
          <td class="text-right"><b><?php echo $column_quantity; ?></b></td>
          <td class="text-right"><b><?php echo $column_price; ?></b></td>
          <td class="text-right"><b><?php echo $column_total; ?></b></td>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($products as $product) { ?>
        <tr>
          <td><?php echo $product['name']; ?>
            <?php foreach ($product['option'] as $option) { ?>
            <br />
            &nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
            <?php } ?></td>
          <td><?php echo $product['model']; ?></td>
          <td class="text-right"><?php echo $product['quantity']; ?></td>
          <td class="text-right"><?php echo $product['price']; ?></td>
          <td class="text-right"><?php echo $product['total']; ?></td>
        </tr>
        <?php } ?>
        <?php foreach ($vouchers as $voucher) { ?>
        <tr>
          <td><?php echo $voucher['description']; ?></td>
          <td></td>
          <td class="text-right">1</td>
          <td class="text-right"><?php echo $voucher['amount']; ?></td>
          <td class="text-right"><?php echo $voucher['amount']; ?></td>
        </tr>
        <?php } ?>
        <?php foreach ($totals as $total) { ?>
        <tr>
          <td class="text-right" colspan="4"><b><?php echo $total['title']; ?></b></td>
          <td class="text-right"><?php echo $total['text']; ?></td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
    <?php if ($comment) { ?>
    <table class="table table-bordered">
      <thead>
        <tr>
          <td><b><?php echo $text_comment; ?></b></td>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><?php echo $comment; ?></td>
        </tr>
      </tbody>
    </table>
    <?php } ?>
    
    <div class="row" style="margin-bottom: 20px;">
		<div class="col-sm-12 text-center">
			<button onclick="window.print();" class="btn btn-info"><?php echo $button_print_invoice; ?></button>
		</div>
    </div>
    
  </div>
</div>
</body>
</html>
