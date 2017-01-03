<?php $__env->startSection('styles'); ?>
<link href="plugins/datepicker/datepicker3.css" rel="stylesheet" type="text/css"/>
<!--Bootsrap Data Table-->
<link rel="stylesheet" href="plugins/datatables/dataTables.bootstrap.css">
<style>
    .col-top-item{
        cursor:pointer;
        border: thin solid #CCCCCC;
        
    }
    .table-top-item > tbody > tr > td{
        border-top-color: #CCCCCC;
    }

    /*input on table row with no bezel*/
    .real-qty-input-on-row, .real-cost-input-on-row{
      display: block;
      padding: 0;
      margin: 0;
      border: 0;
      width: 100%!important;
      background-color:#EEF0F0;
      float:right;
      padding-right: 5px;
      padding-left: 5px;
    }

    /*minimalkan row table condensed*/
    .table.table-condensed > tbody > tr > td{
      /*padding-top:0;
      padding-bottom:0;*/
    }
</style>
<?php $__env->appendSection(); ?>

<?php $__env->startSection('content'); ?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <a href="inventory/init-stock" >Initial Stock</a> 
        <i class="fa fa-angle-double-right" ></i> 
        New
    </h1>
</section>

<!-- Main content -->
<section class="content">

  <form name="form-start-inventory" method="POST" action="inventory/init-stock/insert" >
    <input type="hidden" name="inventory_adjustment_id" value="`" >
    <div class="box box-solid" >
      <div class="box-header with-border" style="padding-top:5px;padding-bottom:5px;" >
        <?php /* PAGE TITLE */ ?>
        <label> <h4 style="font-weight: bolder;margin-top:0;padding-top:0;margin-bottom:0;padding-bottom:0;" >NEW</h4></label>
        
        <?php /* STATUS TITLE */ ?>
        <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
        <a class="btn bg-gray btn-arrow-right pull-right disabled" >Posted</a>

        <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
        <a class="btn bg-blue btn-arrow-right pull-right disabled" >Draft</a>
                

      </div>
      <div class="box-body" >
        <table class="table no-border" >
          <tbody>
            <tr>
              <td class="col-sm-2" >
                <label>Tanggal</label>
              </td>
              <td class="col-sm-3" >
                <input type="text" name="tanggal" class="form-control" required>
              </td>
              <td></td>
            </tr>
          </tbody>
        </table>

        <h4 class="page-header" style="font-size:14px;color:#3C8DBC" ><strong>INVENTORY DETAILS</strong></h4>
        
        <table class="table table-bordered table-condensed table-condensed" id="table-barang"  >
          <thead>
            <tr>
              <th style="width:10px;" >No</th>
              <th style="width:10px;" ></th>
              <th>Kode </th>
              <th>Nama</th>
              <th class="col-lg-1" >Quantity</th>
              <th class="col-lg-2" >Unit Cost</th>
            </tr>
          </thead>
          <tbody>
            <?php $rownum = 1; ?>
            <?php foreach($barang as $dt): ?>
              <tr >
                <td class="text-right" ><?php echo e($rownum++); ?></td>
                <td class="text-center" >
                  <input type="checkbox" class="cb-on-row" value="<?php echo e($dt->id); ?>" name="barang[]">
                </td>
                <td><?php echo e($dt->kode); ?></td>
                <td><?php echo e($dt->kategori . ' ' . $dt->nama); ?></td>
                <td>
                  <input type="text" name="qty" class="hide form-control real-qty-input-on-row text-right input-sm" value="" min="0" data-id="<?php echo e($dt->id); ?>" >
                </td>
                <td>
                  <input type="text" name="harga_beli" class="hide form-control real-cost-input-on-row text-right input-sm" value="" min="0" data-id="<?php echo e($dt->id); ?>" >
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>

      <div class="box-footer" >
        <button type="submit" class="btn btn-primary" id="btn-save" >Save</button>
        <a class="btn btn-danger" href="inventory/init-stock" >Cancel</a>
      </div>
    </div>
  </form>

</section><!-- /.content -->

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script src="plugins/jqueryform/jquery.form.min.js" type="text/javascript"></script>
<script src="plugins/datepicker/bootstrap-datepicker.js" type="text/javascript"></script>
<script src="plugins/autonumeric/autoNumeric-min.js" type="text/javascript"></script>
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables/dataTables.bootstrap.min.js"></script>
<script type="text/javascript">
(function ($) {


  // set datetimepicker
  $('input[name=tanggal]').datepicker({
      format: 'dd-mm-yyyy',
      todayHighlight: true,
      autoclose: true
  }).on('changeDate',function(env){
      
  });;

  // -----------------------------------------------------
  // SET AUTO NUMERIC
  // =====================================================
  $('input.real-qty-input-on-row, input.real-cost-input-on-row').autoNumeric('init',{
      vMin:'0',
      vMax:'9999999999'
  });

  // -----------------------------------------------------
  // SETTING DATATABLE
  // =====================================================
  /* Create an array with the values of all the checkboxes in a column */
  $.fn.dataTable.ext.order['dom-checkbox'] = function  ( settings, col )
  {
      return this.api().column( col, {order:'index'} ).nodes().map( function ( td, i ) {
          return $('input', td).prop('checked') ? '1' : '0';
      } );
  }
  var tableBarang = $('#table-barang').DataTable();

  // -----------------------------------------------------
  // CHECKBOX ON ROW CLICKED
  // =====================================================
  $(document).on('change','input.cb-on-row', function(){
    if($(this).prop('checked')){
      // tampilkan input on row
      $(this).parent().next().next().next().children('input').removeClass('hide').focus();
      $(this).parent().next().next().next().next().children('input').removeClass('hide');
    }else{
      // sembunyikan dan clear data
      $(this).parent().next().next().next().children('input').val('').addClass('hide');
      $(this).parent().next().next().next().next().children('input').val('').addClass('hide');
    }
  });

  $('form[name=form-start-inventory]').submit(function(e){
      e.preventDefault();
      var formAddInit = $('<form>').attr('method','POST').attr('action','inventory/init-stock/insert');
      var data_barang = JSON.parse('{"barang" : [] }');
      // $('form[name=form-start-inventory]').unbind('submit');
      tableBarang.$('input[type=checkbox]').each(function(){
        var ck_barang = $(this);
        if(ck_barang.prop('checked')){
          var inp_qty = ck_barang.parent().next().next().next().children('input').val();
          var inp_cost = ck_barang.parent().next().next().next().next().children('input').autoNumeric('get');
          
        }else{
          var inp_qty =0;
          var inp_cost=0;
        }
        
        // var elm = $(this);
        
        data_barang.barang.push({
                    id:ck_barang.val(),
                    qty:inp_qty,
                    unit_cost:inp_cost
                });
      });

      if(tableBarang.$('input[type=checkbox]:checked').length > 0){
        formAddInit.append($('<input>').attr('type','hidden').attr('name','tanggal').val($('input[name=tanggal]').val()));
        formAddInit.append($('<input>').attr('type','hidden').attr('name','barang').val(JSON.stringify(data_barang)));
        formAddInit.submit();
      }else{
        alert('Lengkapi data yang kosong.');
      }

    return false;
  });
                        
})(jQuery);
</script>
<?php $__env->appendSection(); ?>
<?php echo $__env->make('layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>