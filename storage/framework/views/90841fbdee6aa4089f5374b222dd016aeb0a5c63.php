<?php $__env->startSection('styles'); ?>
<!--Bootsrap Data Table-->
<link rel="stylesheet" href="plugins/datatables/dataTables.bootstrap.css">

<?php $__env->appendSection(); ?>

<?php $__env->startSection('content'); ?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Initial Stock
    </h1>
</section>

<!-- Main content -->
<section class="content">

    <!-- Default box -->
    <div class="box box-solid">
        <div class="box-body">
            <a class="btn btn-primary" id="btn-add" href="inventory/init-stock/add" ><i class="fa fa-plus" ></i> Add</a>
            <div class="clearfix" ></div>
            <br/>

            <?php $rownum=1; ?>
            <table class="table table-bordered table-condensed table-striped table-hover" id="table-data" >
                <thead>
                    <tr>
                        <th style="width:50px;" >No</th>
                        <th>Tanggal</th>
                        <th class="col-sm-2" >Status</th>
                        <th style="width:65px;" ></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $rownum=1; ?>
                    <?php foreach($data as $dt): ?>
                        <tr data-dataid="<?php echo e($dt->id); ?>" data-rowid="<?php echo e($rownum); ?>">
                            <td><?php echo e($rownum++); ?></td>
                            <td>
                                <?php echo e(date('d-m-Y',strtotime($dt->tanggal))); ?>

                            </td>
                            <td class="text-center" >
                                <label class="label label-success" >Posted</label>
                            </td>
                            <td class="text-center" >
                                <a class="btn btn-success btn-xs btn-edit-barang" href="inventory/init-stock/edit/<?php echo e($dt->id); ?>" ><i class="fa fa-edit" ></i></a>
                                <?php /* <?php if($dt->status != 'V'): ?> */ ?>
                                <a class="btn btn-danger btn-xs btn-delete-data" href="inventory/init-stock/delete/<?php echo e($dt->id); ?>" ><i class="fa fa-trash" ></i></a>
                                <?php /* <?php endif; ?> */ ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
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
            <button data-dataid="" data-rowid="" type="button" class="btn btn-outline" data-dismiss="modal" id="btn-modal-delete-yes" >Yes</button>
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

<script type="text/javascript">
(function ($) {
    // Set Table Data
    var TBL_DATA = $('#table-data').DataTable();

    // DELETE DATA
    $(document).on('click', '.btn-delete-data', function(){
        if(confirm('Anda akan menghapus data ini?')){

        }else{
            return false;
        }
    });

    // modal delete klik yes
    $(document).on('click', '#btn-modal-delete-yes', function(){
        var rowid = $(this).data('rowid');
        var dataid = $(this).data('dataid');

        // delete data barang dari database

        $.post('inventory/init-stock/delete',{
            'adjustment_id' : dataid
        },function(datares){
            // hapus row data
            var row = $('#table-data > tbody > tr[data-rowid=' + rowid + ']');
            row.fadeOut(250,null,function(){
                TBL_DATA.row(row).remove().draw();

                // reorder row number
                var rownum=1;
                TBL_DATA.rows().iterator( 'row', function ( context, index ) {
                    this.cell(index,0).data(rownum++);
                    // this.invalidate();
                } );
                
                TBL_DATA.draw();
            });
        });
    });
    // END OF DELETE DATA

})(jQuery);
</script>
<?php $__env->appendSection(); ?>
<?php echo $__env->make('layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>