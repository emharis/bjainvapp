

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

    .tebal {
      font-weight: bold;
    }

    .dl-horizontal dt{
      text-align: left;
      width: 100px;
    }
    .dl-horizontal dd{
      /*border: thin solid red;*/
      margin-left: 10px;
    }
</style>
<?php $__env->appendSection(); ?>

<?php $__env->startSection('content'); ?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Stock Valuation
    </h1>
</section>

<!-- Main content -->
<section class="content">

  <form name="form-start-inventory" method="POST" action="inventory/init-stock/insert" >
    <input type="hidden" name="inventory_adjustment_id" value="`" >
    <div class="box box-solid" >
      <div class="box-header with-border" style="padding-top:5px;padding-bottom:5px;" >
        <?php /* PAGE TITLE */ ?>
        <?php /* <label> <h4 style="font-weight: bolder;margin-top:0;padding-top:0;margin-bottom:0;padding-bottom:0;" >Inventory Details</h4></label> */ ?>

        <h1 class="box-title" >Inventory Detail</h1>

        <div class="pull-right" >
                <table style="background-color: #ECF0F5;width: 200px;" >
                    <tbody>
                      <tr>
                        <td class="bg-green text-center" rowspan="2" style="width: 50px;" ><i class="ft-rupiah" ></i></td>
                        <td style="padding-left: 10px;padding-right: 5px;">
                            TOTAL VALUE
                        </td>
                    </tr>
                    <tr>
                        <td class="text-right"  style="padding-right: 5px;" >
                            <label class="uang"><?php echo e($total_value); ?></label>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
      </div>
      <div class="box-body" >
        <?php /* <h4 class="page-header" style="font-size:14px;color:#3C8DBC" ><strong>INVENTORY DETAILS</strong></h4> */ ?>
        
        <table class="table table-bordered table-condensed table-condensed" id="table-barang"  >
          <thead>
            <tr>
              <th style="width:10px;" >No</th>
              <th>Kode </th>
              <th>Nama</th>
              <th class="" >QOH</th>
              <th class="" >Latest Cost</th>
              <th class="" >COGS</th>
              <th class="" >Value</th>
              <th class="hide" >Value 2</th>
            </tr>
          </thead>
          <tbody>
            <?php $rownum = 1; ?>
            <?php foreach($data as $dt): ?>
              <tr >
                <td><?php echo e($rownum++); ?></td>
                <td><?php echo e($dt->kode); ?></td>
                <td><?php echo e($dt->kategori . ' ' . $dt->nama); ?></td>
                <td class="text-right" >
                  <?php echo e($dt->quantity_on_hand); ?>

                </td>
                <td class="text-right uang" >
                  0
                </td>
                <td class="text-right uang" >
                  <?php echo e($dt->cogs); ?>

                </td>
                <td class="text-right uang" >
                  <?php echo e($dt->value_by_cogs); ?>

                </td>
                <td class="text-right uang hide" >
                  <?php echo e($dt->value_by_summary); ?>

                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>

      <div class="box-footer" >
        <dl class="dl-horizontal">
          <dt>*Keterangan : </dt>
            <dd></dd>
          <dt>QOH</dt>
            <dd>: Quantity on hand/Jumlah barang saat ini.</dd>
          <dt>COGS</dt>
            <dd>: Cost of good sold/Harga pokok penjualan (HPP).</dd>
          <dt>Latest Cost</dt>
            <dd>: Harga pembelian terakhir.</dd>
        </dl>
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

  $('#table-barang').dataTable({
    "columnDefs": [
      { className: "tebal", "targets": [ 5,6 ] }
    ]
  });

  // // -----------------------------------------------------
  // // SETTING DATATABLE
  // // =====================================================
  // /* Create an array with the values of all the checkboxes in a column */
  // $.fn.dataTable.ext.order['dom-checkbox'] = function  ( settings, col )
  // {
  //     return this.api().column( col, {order:'index'} ).nodes().map( function ( td, i ) {
  //         return $('input', td).prop('checked') ? '1' : '0';
  //     } );
  // }
  // var tableBarang = $('#table-barang').DataTable();

  // // -----------------------------------------------------
  // // CHECKBOX ON ROW CLICKED
  // // =====================================================
  // $(document).on('change','input.cb-on-row', function(){
  //   if($(this).prop('checked')){
  //     // tampilkan input on row
  //     $(this).parent().next().next().next().children('input').removeClass('hide').focus();
  //     $(this).parent().next().next().next().next().children('input').removeClass('hide');
  //   }else{
  //     // sembunyikan dan clear data
  //     $(this).parent().next().next().next().children('input').val('').addClass('hide');
  //     $(this).parent().next().next().next().next().children('input').val('').addClass('hide');
  //   }
  // });

  // $('form[name=form-start-inventory]').submit(function(){
  

  //     var formAddInit = $('<form>').attr('method','POST').attr('action','inventory/init-stock/insert');
  //     var data_barang = JSON.parse('{"barang" : [] }');
  //     // $('form[name=form-start-inventory]').unbind('submit');
  //     tableBarang.$('input[type=checkbox]').each(function(){
  //       var ck_barang = $(this);
  //       if(ck_barang.prop('checked')){
  //         var inp_qty = ck_barang.parent().next().next().next().children('input').val();
  //         var inp_cost = ck_barang.parent().next().next().next().next().children('input').autoNumeric('get');
          
  //       }else{
  //         var inp_qty =0;
  //         var inp_cost=0;
  //       }
        
  //       // var elm = $(this);
        
  //       data_barang.barang.push({
  //                   id:ck_barang.val(),
  //                   qty:inp_qty,
  //                   unit_cost:inp_cost
  //               });
  //     });

  //     formAddInit.append($('<input>').attr('type','hidden').attr('name','barang').val(JSON.stringify(data_barang)));
  //     formAddInit.submit();

  //   //   alert('before serialize');                  
  //   // }

  //   return false;
  // });
                        
})(jQuery);
</script>
<?php $__env->appendSection(); ?>
<?php echo $__env->make('layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>