<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-account" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-account" class="form-horizontal">
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
            <div class="col-sm-10">
              <select name="catalogrules_status" id="input-status" class="form-control">
                <?php if ($catalogrules_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_price; ?></label>
            <div class="col-sm-10">
              <input type="text" name="catalogrules_price" class="form-control" value="<?php echo $catalogrules_price; ?>">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-date-start"><?php echo $entry_date_start; ?></label>
            <div class="col-sm-3">
              <div class="input-group date">
                <input type="text" name="catalogrules_date_start" value="<?php echo $catalogrules_date_start; ?>" placeholder="<?php echo $entry_date_start; ?>" data-date-format="YYYY-MM-DD" id="input-date-start" class="form-control" />
                <span class="input-group-btn">
                <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                </span></div>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-date-end"><?php echo $entry_date_end; ?></label>
            <div class="col-sm-3">
              <div class="input-group date">
                <input type="text" name="catalogrules_date_end" value="<?php echo $catalogrules_date_end; ?>" placeholder="<?php echo $entry_date_end; ?>" data-date-format="YYYY-MM-DD" id="input-date-end" class="form-control" />
                <span class="input-group-btn">
                <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                </span></div>
            </div>
          </div>
          <div class="form-group">
                <label class="col-sm-2 control-label" for="input-type"><span data-toggle="tooltip" title="<?php echo $help_type_price; ?>"><?php echo $entry_type; ?></span></label>
                <div class="col-sm-10">
                  <select name="catalogrules_type" id="input-type" class="form-control">
                    <?php if ($catalogrules_type == 'P') { ?>
                    <option value="P" selected="selected"><?php echo $text_percent; ?></option>
                    <?php } else { ?>
                    <option value="P"><?php echo $text_percent; ?></option>
                    <?php } ?>
                    <?php if ($catalogrules_type == 'F') { ?>
                    <option value="F" selected="selected"><?php echo $text_amount; ?></option>
                    <?php } else { ?>
                    <option value="F"><?php echo $text_amount; ?></option>
                    <?php } ?>
                  </select>
                </div>
          </div>
          <div class="form-group">
                <label class="col-sm-2 control-label" for="input-tax"><span data-toggle="tooltip" title="<?php echo $help_type_discount; ?>"><?php echo $entry_discount_tax; ?></span></label>
                <div class="col-sm-10">
                <select name="catalogrules_discount_tax" id="input-tax" class="form-control">
                <?php if ($catalogrules_discount_tax) { ?>
                <option value="1" selected="selected"><?php echo $tax_with_discount; ?></option>
                <option value="0"><?php echo $tax_without_discount; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $tax_with_discount; ?></option>
                <option value="0" selected="selected"><?php echo $tax_without_discount; ?></option>
                <?php } ?>
              </select>
                </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
  <script type="text/javascript"><!--
$('.date').datetimepicker({
  pickTime: false
});
//--></script>
<?php echo $footer; ?>