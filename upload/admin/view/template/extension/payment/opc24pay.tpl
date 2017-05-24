<?php echo $header; ?><?php echo $column_left; ?>

<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <button type="submit" form="form-opc24pay" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-opc24pay" class="form-horizontal">
                    
                    <div class="form-group required">
                        <label class="col-sm-2 control-label" for="input-test"><?php echo $entry_status; ?></label>
                        <div class="col-sm-10">
                            <select name="opc24pay_status" id="input-status" class="form-control">
                                <?php if ($opc24pay_status) { ?>
                                <option value="1" selected="selected">Enabled</option>
                                <option value="0">Disabled</option>
                                <?php } else { ?>
                                <option value="1">Enabled</option>
                                <option value="0" selected="selected">Disabled</option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group required">
                        <label class="col-sm-2 control-label" for="input-order"><?php echo $entry_order; ?></label>
                        <div class="col-sm-10">
                            <input type="text" name="opc24pay_sort_order" value="<?php echo $opc24pay_sort_order; ?>" id="input-order" class="form-control" />
                            <?php if ($error_sort_order) { ?>
                            <div class="text-danger"><?php echo $error_sort_order; ?></div>
                            <?php } ?>
                        </div>
                    </div>
                    
                    <div class="form-group required">
                        <label class="col-sm-2 control-label" for="input-test"><?php echo $entry_test; ?></label>
                        <div class="col-sm-10">
                            <select name="opc24pay_test" id="input-test" class="form-control">
                                <?php if ($opc24pay_test) { ?>
                                <option value="1" selected="selected">YES</option>
                                <option value="0">NO</option>
                                <?php } else { ?>
                                <option value="1">YES</option>
                                <option value="0" selected="selected">NO</option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group required">
                        <label class="col-sm-2 control-label" for="input-mid"><?php echo $entry_mid; ?></label>
                        <div class="col-sm-10">
                            <input type="text" name="opc24pay_mid" value="<?php echo $opc24pay_mid; ?>" id="input-mid" class="form-control" />
                            <?php if ($error_mid) { ?>
                            <div class="text-danger"><?php echo $error_mid; ?></div>
                            <?php } ?>
                        </div>
                    </div>
                    
                    <div class="form-group required">
                        <label class="col-sm-2 control-label" for="input-eshopid"><?php echo $entry_eshopid; ?></label>
                        <div class="col-sm-10">
                            <input type="text" name="opc24pay_eshopid" value="<?php echo $opc24pay_eshopid; ?>" id="input-eshopid" class="form-control" />
                            <?php if ($error_eshopid) { ?>
                            <div class="text-danger"><?php echo $error_eshopid; ?></div>
                            <?php } ?>
                        </div>
                    </div>
                    
                    <div class="form-group required">
                        <label class="col-sm-2 control-label" for="input-key"><?php echo $entry_key; ?></label>
                        <div class="col-sm-10">
                            <input type="text" name="opc24pay_key" value="<?php echo $opc24pay_key; ?>" id="input-key" class="form-control" />
                            <?php if ($error_key) { ?>
                            <div class="text-danger"><?php echo $error_key; ?></div>
                            <?php } ?>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
</div>

<?php echo $footer; ?> 