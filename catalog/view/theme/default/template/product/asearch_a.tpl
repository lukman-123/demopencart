
<div id="content" class="<?php echo $class; ?>">
  <h2><?php echo $totales; ?> <?php echo $heading_title; ?></h2>
  <?php if ($thumb || $description) { ?>
  <div class="row">
    <?php if ($thumb) { ?>
    <div class="col-sm-2"><img src="<?php echo $thumb; ?>" alt="<?php echo $heading_title; ?>" title="<?php echo $heading_title; ?>" class="img-thumbnail" /></div>
    <?php } ?>
    <?php if ($description) { ?>
    <div class="col-sm-10"><?php echo $description; ?></div>
    <?php } ?>
  </div>
  <hr>
  <?php } ?>
  <?php if ($values_selected) { ?>
  <div class="<?php echo $class; ?>">
    <div id="filter_del_nav">
      <div id="current_search">
        <h2 class="refine_txt"><?php echo $entry_selected; ?></h2>
        <?php if (count($values_selected)>1){echo $reset_all_filter;}?>
        <?php foreach ($values_selected as $value_selected){ ?>
        <a class="filter_del_link link_filter_del smenu {dnd:'<?php echo $value_selected['href'];?>', ajaxurl:'<?php echo $value_selected['ajax_url'];?>', gapush:'no'}" href="javascript:void(0)" <?php echo $nofollow; ?>> <?php echo $value_selected['dnd']; ?>: <?php echo $value_selected['name'];?><span> <img src="image/advancedmenu/spacer.gif" alt="<?php echo $remove_filter_text; ?>" class="filter_del_nav_img" /> </span></a>
        <?php } ?>
      </div>
    </div>
  </div>
  <?php } ?>
  <?php if ($categories) { ?>
  <h3><?php echo $text_refine; ?></h3>
  <?php if (count($categories) <= 5) { ?>
  <div class="row">
    <div class="col-sm-3">
      <ul>
        <?php foreach ($categories as $category) { ?>
        <li><a href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <?php } else { ?>
  <div class="row">
    <?php foreach (array_chunk($categories, ceil(count($categories) / 4)) as $categories) { ?>
    <div class="col-sm-3">
      <ul>
        <?php foreach ($categories as $category) { ?>
        <li><a href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
    <?php } ?>
  </div>
  <?php } ?>
  <?php } ?>
  <?php if ($products) { ?>
  <p><a href="<?php echo $compare; ?>" id="compare-total"><?php echo $text_compare; ?></a></p>
  <div class="row">
    <div class="col-md-4">
      <div class="btn-group hidden-xs">
        <button type="button" id="list-view" class="btn btn-default" data-toggle="tooltip" title="<?php echo $button_list; ?>"><i class="fa fa-th-list"></i></button>
        <button type="button" id="grid-view" class="btn btn-default" data-toggle="tooltip" title="<?php echo $button_grid; ?>"><i class="fa fa-th"></i></button>
      </div>
    </div>
    <div class="col-md-2 text-right">
      <label class="control-label" for="input-sort"><?php echo $text_sort; ?></label>
    </div>
    <div class="col-md-3 text-right">
      <select id="input-sort" class="form-control" onchange="location = this.value;">
        <?php foreach ($sorts as $sorts) { ?>
        <?php if ($sorts['value'] == $sort . '-' . $order) { ?>
        <option value="<?php echo $sorts['href']; ?>" selected="selected"><?php echo $sorts['text']; ?></option>
        <?php } else { ?>
        <option value="<?php echo $sorts['href']; ?>"><?php echo $sorts['text']; ?></option>
        <?php } ?>
        <?php } ?>
      </select>
    </div>
    <div class="col-md-1 text-right">
      <label class="control-label" for="input-limit"><?php echo $text_limit; ?></label>
    </div>
    <div class="col-md-2 text-right">
      <select id="input-limit" class="form-control" onchange="location = this.value;">
        <?php foreach ($limits as $limits) { ?>
        <?php if ($limits['value'] == $limit) { ?>
        <option value="<?php echo $limits['href']; ?>" selected="selected"><?php echo $limits['text']; ?></option>
        <?php } else { ?>
        <option value="<?php echo $limits['href']; ?>"><?php echo $limits['text']; ?></option>
        <?php } ?>
        <?php } ?>
      </select>
    </div>
  </div>
  <br />
  <div class="row">
    <?php foreach ($products as $product) { ?>
    <div class="product-layout product-list col-xs-12">
      <div class="product-thumb">
        <div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-responsive" /></a></div>
        <div>
          <div class="caption">
            <h4><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></h4>
            <p><?php echo $product['description']; ?></p>
            <?php if ($product['rating']) { ?>
            <div class="rating">
              <?php for ($i = 1; $i <= 5; $i++) { ?>
              <?php if ($product['rating'] < $i) { ?>
              <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span>
              <?php } else { ?>
              <span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i><i class="fa fa-star-o fa-stack-2x"></i></span>
              <?php } ?>
              <?php } ?>
            </div>
            <?php } ?>
            <?php if ($product['price']) { ?>
            <p class="price">
              <?php if (!$product['special']) { ?>
              <?php echo $product['price']; ?>
              <?php } else { ?>
              <span class="price-new"><?php echo $product['special']; ?></span> <span class="price-old"><?php echo $product['price']; ?></span>
              <?php } ?>
              <?php if ($product['tax']) { ?>
              <span class="price-tax"><?php echo $text_tax; ?> <?php echo $product['tax']; ?></span>
              <?php } ?>
            </p>
            <?php } ?>
          </div>
          <div class="button-group">
            <button type="button" onclick="cart.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-shopping-cart"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $button_cart; ?></span></button>
            <button type="button" data-toggle="tooltip" title="<?php echo $button_wishlist; ?>" onclick="wishlist.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-heart"></i></button>
            <button type="button" data-toggle="tooltip" title="<?php echo $button_compare; ?>" onclick="compare.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-exchange"></i></button>
          </div>
        </div>
      </div>
    </div>
    <?php } ?>
  </div>
  <div class="row">
    <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
    <div class="col-sm-6 text-right"><?php echo $results; ?></div>
  </div>
  <?php } ?>
  <?php if (!$categories && !$products) { ?>
  <p><?php echo $text_empty; ?></p>
  <div class="buttons">
    <div class="pull-right"><a href="<?php echo $continue; ?>" class="btn btn-primary"><?php echo $button_continue; ?></a></div>
  </div>
  <?php } ?>
</div>
<script>

$('#list-view').click(function() {
		$('#content .product-layout > .clearfix').remove();

		$('#content .product-layout').attr('class', 'product-layout product-list col-xs-12');

		localStorage.setItem('display', 'list');
	});

	// Product Grid
	$('#grid-view').click(function() {
		$('#content .product-layout > .clearfix').remove();

		// What a shame bootstrap does not take into account dynamically loaded columns
		cols = $('#column-right, #column-left').length;

		if (cols == 2) {
			$('#content .product-layout').attr('class', 'product-layout product-grid col-lg-6 col-md-6 col-sm-12 col-xs-12');
		} else if (cols == 1) {
			$('#content .product-layout').attr('class', 'product-layout product-grid col-lg-4 col-md-4 col-sm-6 col-xs-12');
		} else {
			$('#content .product-layout').attr('class', 'product-layout product-grid col-lg-3 col-md-3 col-sm-6 col-xs-12');
		}

		 localStorage.setItem('display', 'grid');
	});

	if (localStorage.getItem('display') == 'list') {
		$('#list-view').trigger('click');
	} else {
		$('#grid-view').trigger('click');
	}

</script> 
<script type="text/javascript"><!--

$("div.pagination div.links a").click(function(event){
		event.preventDefault();
		var hreflink = $(this).attr("href");
		if (hreflink.indexOf("product/asearch")!=-1){//don't have seo
			var someVar=hreflink.replace(/http(.*?)&/i,"");
		}else{//have seo
			var someVar=hreflink.replace(/http(.*?)\?/i,"");
		}
		<?php if ($is_ajax){ ?> 
		
		 if (history.pushState) {
          History.pushState(null, someVar, hreflink); 
          }else{
         Ajaxmenup(someVar);
          }
		
		<?php }else{ ?>
			window.location.href =hreflink;
		<?php } ?>
		
		return false;
	 });//--></script> 
<?php echo $ocscroll; ?> 