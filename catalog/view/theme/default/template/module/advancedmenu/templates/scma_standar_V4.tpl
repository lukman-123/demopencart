<?php if ($menu) { ?>
<div id="advancedm">
  <?php $i=1; ?>
  <div class="box">
    <div class="filter_box">
      <?php if (!empty($values_selected)) {?>
      <?php foreach ($values_selected as $value_sel) {?>
      <?php  $i==1 ? $liclass="first upper" : $liclass="upper";?>
      <dl id="filter_p<?php echo $i; ?>" class="filters opened selected" >
        <dt class="<?php echo $liclass; ?>"><span><em>&nbsp;</em><?php echo $value_sel['dnd']; ?></span><?php echo html_entity_decode($value_sel['tip_code'], ENT_QUOTES, 'UTF-8'); ?></dt>
        <dd class="page_preload"><?php echo $value_sel['html']; ?></dd>
      </dl>
      <?php $i++; } ?>
      <?php } ?>
      <?php if (!empty($values_no_selected)) { 
      ksort($values_no_selected); ?>
      <?php foreach ($values_no_selected as $value_no_select) { ?>
      <?php foreach ($value_no_select as $value_no_sel) { ?>
      <?php  $i==1 ? $liclass="first upper" : $liclass="upper";?>
      <dl id="filter_p<?php echo $i; ?>" class="filters <?php echo $value_no_sel['initval']; ?>">
        <dt class="<?php echo $liclass; ?>"><span><em>&nbsp;</em><?php echo $value_no_sel['name']; ?></span><?php echo html_entity_decode($value_no_sel['tip_code'], ENT_QUOTES, 'UTF-8'); ?></dt>
       <dd class="page_preload"><?php echo $value_no_sel['html']; ?></dd>
      </dl>
      <?php $i++; } ?>
      <?php } ?>
      <?php } ?>
      <dl class="filters">
        <dt class="last"><span>&nbsp;</span></dt>
      </dl>
    </div>
  </div>
</div>
<!!!!!! INSERT JAVASCRIPT VQMOD !!!!!!!!!!!>
<?php  } ?>