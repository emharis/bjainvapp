<?php $__env->startSection('styles'); ?>
<!--Bootsrap Data Table-->
<link rel="stylesheet" href="plugins/datatables/dataTables.bootstrap.css">
<link href="plugins/datepicker/datepicker3.css" rel="stylesheet" type="text/css"/>

<style>
.form-login{
  padding: 1em;
  min-width: 280px; /* change width as per your requirement */
}
</style>

<?php $__env->appendSection(); ?>

<?php $__env->startSection('content'); ?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Purchase Order
    </h1>
</section>

<!-- Main content -->
<section class="content">

    <!-- Default box -->
    <div class="box box-solid">
      <div class="box-header with-border" >
        <a class="btn btn-primary" id="btn-add" href="purchase/order/add" ><i class="fa fa-plus" ></i> Add Purchase Order</a>

        <?php /* FILTER WIDGET */ ?>
          <?php /* <button class="pull-right btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown" ><i class="fa fa-filter" ></i> Filter</button>
          <div class="dropdown-menu form-login stop-propagation pull-right" role="menu">
              <div class="form-group" >
                <form method="POST" name="form-filter-by-status-open" action="purchase/order/filter" >
                  <input type="hidden" name="filter_by" value="open" />
                  <button type="submit" class="btn btn-flat btn-block" style="text-align:left; padding-left:6px"  >Status "Open"</button>
                </form>
                <form method="POST" name="form-filter-by-status-validated" action="purchase/order/filter" >
                  <input type="hidden" name="filter_by" value="validated" />
                  <button type="submit" class="btn no-bg btn-flat btn-block" style="text-align:left; padding-left:6px"  >Status "Validated"</button>
                </form>
              </div>
              <li class="divider" ></li>
              <form method="POST" name="form-filter" action="purchase/order/filter" >
                <div class="form-group">
                    <select name="filter_by" class="form-control">
                      <option value="supplier" >Supplier</option>
                      <option value="order_date" >Order Date</option>
                    </select>
                </div>
                <div class="form-group filter_by_supplier ">
                  <?php echo Form::select('filter_select_supplier',$select_supplier,null,['class'=>'form-control']); ?>

                </div>
                <div class="form-group filter_by_order_date hide">
                  <input type="text" name="filter_date_start" class="form-control input-date" placeholder="Order date from" />
                </div>
                <div class="form-group filter_by_order_date hide">
                  <input type="text" name="filter_date_end" class="form-control input-date" placeholder="Order date to" />
                </div>

                <button type="submit" id="btn-submit-filter" class="btn btn-success btn-block "><i class="glyphicon glyphicon-ok"></i> Submit</button>
              </form>
          </div> */ ?>
        <?php /* END FILTER WIDGET */ ?>

          <?php /* <div class="dropdown-menu pull-right " style="margin-right:10px;" >
            <div class="box box-solid" >
              <div class="box-body" >
                <form name="form-filter" >
                  <table>
                    <tbody>
                      <tr>
                        <td>
                          <a href="#" class="btn btn-block " style="text-align:left!important;" >Open</a>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <a href="#" class="btn btn-block" style="text-align:left!important;" >Validated</a>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <select class="form-control">
                            <option>Supplier</option>
                            <option>Order Date</option>
                          </select>
                        </td>
                      </tr>
                    </tbody>
                  </table>

                </form>
              </div>
            </div>
          </div> */ ?>
          <?php /* <div class="dropdown-menu pull-right " style="padding: 15px; padding-bottom: 0px;">
            <!-- Login form here -->
            <form action="#" method="POST" >
              <table class="table table-condensed" >
                <tbody>
                  <tr>
                    <td colspan="3" >
                      <a href="#" id="btn-filter-by-status-open">Open</a>
                    </td>
                  </tr>
                  <tr>
                    <td colspan="3"  >
                      <a id="btn-filter-by-status-validated" href="#" >Validated</a>
                    </td>
                  </tr>
                  <tr>
                    <td>Filter by </td>
                    <td>:</td>
                    <td>
                      <select class="form-control input-sm" >
                        <option value="supplier">Supplier</option>
                        <option value="order_date">Order Date</option>
                        <option value="order_date">Order Date</option>
                      </select>
                    </td>
                  </tr>
                </tbody>
              </table>
            </form>
          </div> */ ?>
        <?php /* </div> */ ?>
      </div>
        <div class="box-body">
            <?php $rownum=1; ?>
            <table class="table table-bordered table-condensed table-striped table-hover" id="table-order" >
                <thead>
                    <tr>
                        <th style="width:50px;" >No</th>
                        <th  >REF#</th>
                        <th  >SUPPLIER REF#</th>
                        <th  >DATE</th>
                        <th>SUPPLIER</th>
                        <th>TOTAL</th>
                        <th class="col-lg-1" >STATUS</th>
                        <th style="width:65px;" ></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($data as $dt): ?>
                    <tr data-rowid="<?php echo e($rownum); ?>" data-orderid="<?php echo e($dt->id); ?>">
                        <td><?php echo e($rownum++); ?></td>
                        <td><?php echo e($dt->po_num); ?></td>
                        <td><?php echo e($dt->no_inv); ?></td>
                        <td><?php echo e($dt->tgl_formatted); ?></td>
                        <td><?php echo e($dt->supplier); ?></td>
                        <td class="text-right uang" >
                            <?php echo e($dt->total); ?>

                        </td>
                        <td class="text-center" >
                            <?php if($dt->status == 'O'): ?>
                                <label class="label label-warning" >OPEN</label>
                            <?php elseif($dt->status == 'C'): ?>
                                <label class="label label-danger" >CANCELED</label>
                            <?php elseif($dt->status == 'V'): ?>
                                <label class="label label-success" >VALIDATED</label>
                            <?php endif; ?>
                        </td>
                        <td class="text-center" >
                          <a class="btn btn-success btn-xs btn-edit-order" href="purchase/order/edit/<?php echo e($dt->id); ?>" ><i class="fa fa-edit" ></i></a>
                          <?php if($dt->status == 'O'): ?>
                            <a class="btn btn-danger btn-xs btn-delete-order" href="purchase/order/delete/<?php echo e($dt->id); ?>" ><i class="fa fa-trash" ></i></a>
                          <?php endif; ?>
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
            <button data-orderid="" data-rowid="" type="button" class="btn btn-outline" data-dismiss="modal" id="btn-modal-delete-yes" >Yes</button>
        </div>
        </div>
    <!-- /.modal-content -->
    </div>
  <!-- /.modal-dialog -->
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<?php echo Html::script('plugins/datatables/jquery.dataTables.min.js'); ?>

<?php echo Html::script('plugins/datatables/dataTables.bootstrap.min.js'); ?>

<?php echo Html::script('plugins/jqueryform/jquery.form.min.js'); ?>

<?php echo Html::script('plugins/datepicker/bootstrap-datepicker.js'); ?>

<?php echo Html::script('plugins/autonumeric/autoNumeric-min.js'); ?>


<script type="text/javascript">
(function ($) {

  // FILTER WIDGET
  // ==============================================================
    // reset select filter & select supplier
    // $('select[name=filter_by]').val([]);
    // SET DATEPICKER
    $('.input-date').datepicker({
        format: 'dd-mm-yyyy',
        todayHighlight: true,
        autoclose: true
    });
    $(document).on('click', 'span.month, th.next, th.prev, th.switch, span.year, td.day, th.datepicker-switch', function (e) {
        e.stopPropagation();
    });
  // END OF SET DATEPICKER

  // SET AUTONUMERIC
  $('.uang').autoNumeric('init',{
        vMin:'0.00',
        vMax:'9999999999.00'
    });
  $('.uang').each(function(){
    $(this).autoNumeric('set',$(this).autoNumeric('get'));
  });
  
    // select filter by change
    $('select[name=filter_by]').change(function(){
      if($(this).val() == 'supplier'){
        // show filter by supplier
        $('.filter_by_supplier').removeClass('hide');
        $('.filter_by_supplier').show();
        // hide filter by order date
        $('.filter_by_order_date').hide();

      }else{
        //hide filter by supplier
        $('.filter_by_supplier').hide();
        // show filter by order date
        $('.filter_by_order_date').removeClass('hide');
        $('.filter_by_order_date').show();

      }

      // show submit button
      $('#btn-submit-filter').removeClass('hide');
      // $('form[nae=form-filter] button[type=submit]').fadeIn(250);
    });

    // date change
    $('input[name=filter_date_start]').change(function(){
      $('input[name=filter_date_end]').datepicker('remove');
      $('input[name=filter_date_end]').datepicker({
        format: 'dd-mm-yyyy',
        todayHighlight: true,
        autoclose: true,
        startDate : $('input[name=filter_date_start]').val()
      });
    });

    $('.dropdown-menu').click(function(e){
      event.stopPropagation();
    });
  // ==============================================================
  // END OF FILTER WIDGET


    //required checkbox
    var requiredCheckboxes = $('.order_jual');
    requiredCheckboxes.change(function () {
        if (requiredCheckboxes.is(':checked')) {
            requiredCheckboxes.removeAttr('required');
        } else {
            requiredCheckboxes.attr('required', 'required');
        }
    });

    var TBL_KATEGORI = $('#table-order').DataTable();

    // DELETE KATEGORI
    $(document).on('click', '.btn-delete-order', function(){
        //set data rowid dan order id
        var rowid = $(this).parent().parent().data('rowid');
        var orderid = $(this).parent().parent().data('orderid');

        if(confirm('Anda akan menghapus data ini?')){
          // alert('delete data');
        }else{
          return false;
        }

        // $('#btn-modal-delete-yes').data('rowid',rowid);
        // $('#btn-modal-delete-yes').data('orderid',orderid);
        // // tampilkan modal delete
        // $('#modal-delete').modal('show');
    });

    // modal delete klik yes
    $(document).on('click', '#btn-modal-delete-yes', function(){
        var rowid = $(this).data('rowid');
        var orderid = $(this).data('orderid');
        // delete data order dari database
        $.post('purchase/order/delete',{
            'id' : orderid
        },function(){
            // hapus row order
            var row = $('#table-order > tbody > tr[data-rowid=' + rowid + ']');
            row.fadeOut(250,null,function(){
                TBL_KATEGORI.row(row).remove().draw();

                // reorder row number
                var rownum=1;
                TBL_KATEGORI.rows().iterator( 'row', function ( context, index ) {
                    this.cell(index,0).data(rownum++);
                    // this.invalidate();
                } );

                TBL_KATEGORI.draw();
            });
        });

    });
    // END OF DELETE KATEGORI

    
    

})(jQuery);
</script>
<?php $__env->appendSection(); ?>

<?php echo $__env->make('layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>