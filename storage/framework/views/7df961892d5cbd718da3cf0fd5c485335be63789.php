 

<?php $__env->startSection('styles'); ?>
<!--Bootsrap Data Table-->
<link rel="stylesheet" href="plugins/datatables/dataTables.bootstrap.css">

<?php $__env->appendSection(); ?>

<?php $__env->startSection('content'); ?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Customer Invoices
        <?php /* <i class="fa fa-angle-double-right" ></i>  */ ?>
        <?php /* <a href="sales/order/edit/<?php echo e($so_master->id); ?>" ><?php echo e($so_master->so_no); ?></a>  */ ?>
        <?php /* <i class="fa fa-angle-double-right" ></i>  */ ?>
        <?php /* Customer Invoices */ ?>
    </h1>
</section>

<!-- Main content -->
<section class="content">

    <!-- Default box -->
    <div class="box box-solid">
        <div class="box-header with-border" >
            <h3 class="box-title" >Account Receivable</h3>
            <div class="pull-right" >
                <table style="background-color: #ECF0F5;width: 200px;" >
                    <tbody>
                      <tr>
                        <td class="bg-green text-center" rowspan="2" style="width: 50px;" ><i class="ft-rupiah" ></i></td>
                        <td style="padding-left: 10px;padding-right: 5px;">
                            TOTAL
                        </td>
                    </tr>
                    <tr>
                        <td class="text-right"  style="padding-right: 5px;" >
                            <label class="uang"><?php echo e($total_amount_due); ?></label>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="box-body">
            <?php /* <a class="btn btn-primary btn-sm" id="btn-add" href="sales/order/add" ><i class="fa fa-plus" ></i> Add Sales Order</a> */ ?>
            <?php /* <div class="clearfix" ></div> */ ?>
            <?php /* <br/> */ ?>

            <?php $rownum=1; ?>
            <table class="table table-bordered table-condensed table-striped table-hover" id="table-order" >
                <thead>
                    <tr>
                        <th style="width:30px;" >NO</th>
                        <th>CUSTOMER</th>
                        <th>REF#</th>
                        <th>INVOICE DATE</th>
                        <th>DUE DATE</th>
                        <th>SO REF#</th>
                        <th>TOTAL</th>
                        <th>AMOUNT DUE</th>
                        <th>STATUS</th>
                        <th style="width:30px;" ></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $rownum=1; ?>
                    <?php foreach($data as $dt): ?>
                        <tr>
                            <td>
                                <?php echo e($rownum++); ?>

                            </td>
                            <td>
                                <?php echo e($dt->nama_customer); ?>

                            </td>
                            <td>
                                <?php echo e($dt->no_inv); ?>

                            </td>
                            <td>
                                <?php echo e($dt->invoice_date_formatted); ?>

                            </td>
                            <td>
                                <?php echo e($dt->due_date_formatted); ?>

                            </td>
                            <td>
                                <?php echo e($dt->so_no); ?>

                            </td>
                            <td class="text-right uang" >
                                <?php echo e($dt->total); ?>

                            </td>
                            <td class="text-right uang" >
                                <?php echo e($dt->amount_due); ?>

                            </td>
                            <td>
                                <?php if($dt->status == 'O'): ?>
                                    Open
                                <?php else: ?>
                                    Paid
                                <?php endif; ?>
                            </td>
                            <td>
                                <a class="btn btn-success btn-xs" href="invoice/customer-invoice/show/<?php echo e($dt->id); ?>" ><i class="fa fa-edit" ></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <?php /* <a class="btn btn-danger" href="sales/order/edit/<?php echo e($so_master->id); ?>" >Close</a> */ ?>
        </div><!-- /.box-body -->
    </div><!-- /.box -->

</section><!-- /.content -->


<!-- MODAL DELETE DATA -->
<div class="modal modal-danger" id="modal-delete" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" >
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">DELETE</h4>
            </div>
        <div class="modal-body">
            <p>Anda akan menghapus data ini?</p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Cancel</button>
            <button data-orderid="" data-rowid="" type="button" class="btn btn-outline" data-dismiss="modal" id="btn-modal-delete-yes" >Yes</button>
        </div>
        </div>
    <!-- /.modal-content -->
    </div>
  <!-- /.modal-dialog -->
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables/dataTables.bootstrap.min.js"></script>
<script src="plugins/jqueryform/jquery.form.min.js" type="text/javascript"></script>
<?php echo Html::script('plugins/autonumeric/autoNumeric-min.js'); ?>


<script type="text/javascript">
(function ($) {
    //required checkbox
    var requiredCheckboxes = $('.order_jual');
    requiredCheckboxes.change(function () {
        if (requiredCheckboxes.is(':checked')) {
            requiredCheckboxes.removeAttr('required');
        } else {
            requiredCheckboxes.attr('required', 'required');
        }
    });

    // -----------------------------------------------------
    // SET AUTO NUMERIC
    // =====================================================
    $('.uang').autoNumeric('init',{
        vMin:'0.00',
        vMax:'9999999999.00'
    });

    $('.uang').each(function(){
        $(this).autoNumeric('set',$(this).autoNumeric('get'));
    });

    var TBL_KATEGORI = $('#table-order').DataTable({
        "columns": [
            {className: "text-right"},
            null,
            null,
            null,
            null,
            null,
            null,
            {className: "text-right"},
            null,
            {className: "text-center"}
        ],
         "bSortClasses": false
    });


})(jQuery);
</script>
<?php $__env->appendSection(); ?>

<?php echo $__env->make('layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>