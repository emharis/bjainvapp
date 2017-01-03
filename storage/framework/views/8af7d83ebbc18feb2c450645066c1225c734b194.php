<?php $__env->startSection('styles'); ?>
<!--Bootsrap Data Table-->
<link rel="stylesheet" href="plugins/datatables/dataTables.bootstrap.css">

<?php $__env->appendSection(); ?>

<?php $__env->startSection('content'); ?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Petty Cash
    </h1>
</section>

<!-- Main content -->
<section class="content">

    <!-- Default box -->
    <div class="box box-solid">
        <div class="box-body">
            <a class="btn btn-primary btn-sm" id="btn-add" href="cashbook/receipt/add" ><i class="fa fa-plus" ></i> Add Receipt</a>
            <div class="clearfix" ></div>
            <br/>

            <?php $rownum=1; ?>
            <table class="table table-bordered table-condensed table-striped table-hover" id="table-order" >
                <thead>
                    <tr>
                        <th style="width:50px;" >No</th>
                        <th  >DATE</th>
                        <th  >DESCRIPTION</th>
                        <th  >TOTAL</th>
                        <th class="col-sm-1 col-md-1 col-lg-1" ></th>
                    </tr>
                </thead>
                <tbody>
                    
                </tbody>
            </table>
        </div><!-- /.box-body -->
    </div><!-- /.box -->

</section><!-- /.content -->

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables/dataTables.bootstrap.min.js"></script>
<script src="plugins/jqueryform/jquery.form.min.js" type="text/javascript"></script>
<script src="plugins/autonumeric/autoNumeric-min.js" type="text/javascript"></script>

<script type="text/javascript">
(function ($) {
    // //required checkbox
    // var requiredCheckboxes = $('.order_jual');
    // requiredCheckboxes.change(function () {
    //     if (requiredCheckboxes.is(':checked')) {
    //         requiredCheckboxes.removeAttr('required');
    //     } else {
    //         requiredCheckboxes.attr('required', 'required');
    //     }
    // });

    var TBL_KATEGORI = $('#table-order').DataTable({
        "columns": [
            {className: "text-right"},
            null,
            null,
            {className: "text-right"},
            {className: "text-center"}
        ]
    });

    // SET AUTO NUMERIC UANG
    $('.uang').autoNumeric('init',{
        vMin:'0',
        vMax:'9999999999'
    });
    // normalize
    $('.uang').each(function(i,data){
        $(this).autoNumeric('set',$(this).autoNumeric('get'));
    });
    // $('.uang').autoNumeric('set',$('.uang').autoNumeric('get'));

    // DELETE EXPENSE
    $(document).on('click', '.btn-delete-receipt', function(){
        if(confirm('Anda akan menghapus data ini?')){

        }else{
            return false;
        }
    });

    // // modal delete klik yes
    // $(document).on('click', '#btn-modal-delete-yes', function(){
    //     var rowid = $(this).data('rowid');
    //     var orderid = $(this).data('orderid');
    //     // delete data order dari database
    //     $.post('purchase/order/delete',{
    //         'id' : orderid
    //     },function(){
    //         // hapus row order
    //         var row = $('#table-order > tbody > tr[data-rowid=' + rowid + ']');
    //         row.fadeOut(250,null,function(){
    //             TBL_KATEGORI.row(row).remove().draw();

    //             // reorder row number
    //             var rownum=1;
    //             TBL_KATEGORI.rows().iterator( 'row', function ( context, index ) {
    //                 this.cell(index,0).data(rownum++);
    //                 // this.invalidate();
    //             } );
                
    //             TBL_KATEGORI.draw();
    //         });
    //     });

    // });
    // // END OF DELETE KATEGORI

})(jQuery);
</script>
<?php $__env->appendSection(); ?>
<?php echo $__env->make('layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>