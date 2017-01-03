<?php $__env->startSection('styles'); ?>
<!--Bootsrap Data Table-->
<link rel="stylesheet" href="plugins/datatables/dataTables.bootstrap.css">

<?php $__env->appendSection(); ?>

<?php $__env->startSection('content'); ?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Barang
    </h1>
</section>

<!-- Main content -->
<section class="content">

    <!-- Default box -->
    <div class="box box-solid">
        <div class="box-header with-border" >
            <a class="btn btn-primary" id="btn-add" href="inventory/barang/add" ><i class="fa fa-plus" ></i> Add Barang</a>
            
            <div class="pull-right" >
                <table style="background-color: #ECF0F5;" >
                    <tr>
                        <td class="bg-green text-center" rowspan="2" style="width: 50px;" ><i class="fa fa-tags" ></i></td>
                        <td style="padding-left: 10px;padding-right: 5px;">
                            JUMLAH DATA
                        </td>
                    </tr>
                    <tr>
                        <td class="text-right"  style="padding-right: 5px;" >
                            <label class=""><?php echo e(count($data)); ?></label>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="box-body">
            

            <?php $rownum=1; ?>
            <table class="table table-bordered table-condensed table-striped table-hover" id="table-barang" >
                <thead>
                    <tr>
                        <th style="width:50px;" >No</th>
                        <th>Kode</th>
                        <th>Nama</th>
                        <?php /* <th class="col-sm-2" >Cost</th> */ ?>
                        <?php /* <th class="col-sm-2" >Price</th> */ ?>
                        <th class="col-sm-1" >Quanity</th>
                        <th class="col-sm-1" >Berat</th>
                        <th >Unit Price</th>
                        <th style="width:65px;" ></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($data as $dt): ?>
                    <tr data-rowid="<?php echo e($rownum); ?>" data-barangid="<?php echo e($dt->id); ?>">
                        <td><?php echo e($rownum++); ?></td>
                        <td><?php echo e($dt->kode); ?></td>
                        <td><?php echo e($dt->kategori . ' ' . $dt->nama); ?></td>
                        <?php /* <td></td> */ ?>
                        <?php /* <td  ><?php echo e(number_format($dt->harga_jual,0,'.',',')); ?></td> */ ?>
                        <td class="text-right" ><?php echo e($dt->quantity_on_hand); ?></td>
                        <td class="text-right"><?php echo e($dt->berat); ?></td>
                        <td class="text-right uang"><?php echo e($dt->unit_price); ?></td>
                        <td class="text-center" >
                            <a class="btn btn-success btn-xs btn-edit-barang" href="inventory/barang/edit/<?php echo e($dt->id); ?>" ><i class="fa fa-edit" ></i></a>
                            <?php /* <?php if($dt->can_delete == 1): ?> */ ?>
                            <a data-id="<?php echo e($dt->id); ?>" class="btn btn-danger btn-xs btn-delete-barang" ><i class="fa fa-trash" ></i></a>
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
            <button data-barangid="" data-rowid="" type="button" class="btn btn-outline" data-dismiss="modal" id="btn-modal-delete-yes" >Yes</button>
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
<script src="plugins/autonumeric/autoNumeric-min.js" type="text/javascript"></script>

<script type="text/javascript">
(function ($) {
    //required checkbox
    var requiredCheckboxes = $('.satuan_jual');
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

    var TBL_BARANG = $('#table-barang').DataTable();

    // DELETE BARANG
    $(document).on('click', '.btn-delete-barang', function(){
        // //set data rowid dan barang id
        // var rowid = $(this).parent().parent().data('rowid');
        // var barangid = $(this).parent().parent().data('barangid');
        
        // $('#btn-modal-delete-yes').data('rowid',rowid);
        // $('#btn-modal-delete-yes').data('barangid',barangid);
        // // tampilkan modal delete
        // $('#modal-delete').modal('show');

        if(confirm('Anda akan menghapus data ini?')){
            var barangid = $(this).data('id');
            var rowid = $(this).data('rowid');

            $.post('inventory/barang/delete',{
                'barang_id' : barangid
            },function(datares){
                // hapus row barang
                // var row = $('#table-barang > tbody > tr[data-rowid=' + rowid + ']');
                // row.fadeOut(250,null,function(){
                //     TBL_BARANG.row(row).remove().draw();

                //     // reorder row number
                //     var rownum=1;
                //     TBL_BARANG.rows().iterator( 'row', function ( context, index ) {
                //         this.cell(index,0).data(rownum++);
                //         // this.invalidate();
                //     } );
                    
                //     TBL_BARANG.draw();
                // });

                location.reload();
            });
        }
    });

    // // modal delete klik yes
    // $(document).on('click', '#btn-modal-delete-yes', function(){
    //     var rowid = $(this).data('rowid');
    //     var barangid = $(this).data('barangid');
    //     // delete data barang dari database
    //     // var newform = $('<form>').attr('method','POST').attr('action','inventory/barang/delete');
    //     // newform.append($('<input>').attr('type','text').attr('name','barang_id').val(barangid));
    //     // newform.submit();

    //     $.post('inventory/barang/delete',{
    //         'barang_id' : barangid
    //     },function(datares){
    //         // hapus row barang
    //         var row = $('#table-barang > tbody > tr[data-rowid=' + rowid + ']');
    //         row.fadeOut(250,null,function(){
    //             TBL_BARANG.row(row).remove().draw();

    //             // reorder row number
    //             var rownum=1;
    //             TBL_BARANG.rows().iterator( 'row', function ( context, index ) {
    //                 this.cell(index,0).data(rownum++);
    //                 // this.invalidate();
    //             } );
                
    //             TBL_BARANG.draw();
    //         });
    //     });
    // });
    // END OF DELETE BARANG

    

})(jQuery);
</script>
<?php $__env->appendSection(); ?>
<?php echo $__env->make('layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>