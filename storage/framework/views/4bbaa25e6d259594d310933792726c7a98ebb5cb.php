<?php $__env->startSection('styles'); ?>
<!--Bootsrap Data Table-->
<link rel="stylesheet" href="plugins/datatables/dataTables.bootstrap.css">
<link href="plugins/datepicker/datepicker3.css" rel="stylesheet" type="text/css"/>

<style>
  /*.table tfoot {
    background-color: #DDDDDD;
  }
*/
  .table tfoot > tr > th {
    border-color: #CACACA!important;
    border-top-width: 2px!important;
  }

  #table-detil-total tbody > tr > td {
    margin:0;
    padding:0;
  }

  .dl-horizontal dt{
    text-align: left;
    width: auto;
    margin-right: 5px;
  }
  .dl-horizontal dd{
    margin-left: 0px;
  }
</style>

<?php $__env->appendSection(); ?>

<?php $__env->startSection('content'); ?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <a href="sales/report" >Sales Orders Reports</a> <i class="fa fa-angle-double-right" ></i> Report
    </h1>
</section>

<!-- Main content -->
<section class="content">

    <!-- Default box -->
    <div class="box box-solid">
      <div class="box-header with-border">
        <h3 class="box-title" >Sales Report</h3>

      </div><!-- /.box-header -->
      <div class="box-body">
        
        <dl class="dl-horizontal">
          <dt>Order Date : </dt>
          <dd><?php echo e(str_replace('-','/',$start_date) .' - ' . str_replace('-','/',$end_date)); ?></dd>
        </dl>

        <h4 class="page-header" style="font-size:14px;color:#3C8DBC"><strong>ORDER DETAILS</strong></h4>

        <table class="table table-bordered table-condensed" >
          <thead>
            <tr>
              <th>NO</th>
              <th>REF#</th>
              <th>CUSTOMER</th>
              <th>ORDER DATE</th>
              <th>SUBTOTAL</th>
              <th>DISC</th>
              <th>TOTAL</th>
              <?php /* <th>AMOUNT DUE</th> */ ?>
            </tr>
          </thead>
          <tbody>
            <?php $rownum=1; ?>
            <?php $payment_total = 0;?>
            <?php foreach($data as $dt): ?>
              <tr>
                <td><?php echo e($rownum++); ?></td>
                <td>
                  <?php echo e($dt->so_no); ?>

                </td>
                <td>
                  <?php echo e($dt->nama_customer); ?>

                </td>
                <td>
                  <?php echo e($dt->tgl_formatted); ?>

                </td>
                <td class="uang text-right" >
                  <?php echo e($dt->subtotal); ?>

                </td>
                <td class="uang text-right" >
                  <?php echo e($dt->disc); ?>

                </td>
                <td class="uang text-right" >
                  <?php echo e($dt->total); ?>

                </td>
                
              </tr>
              <?php $payment_total += $dt->total; ?>
            <?php endforeach; ?>

          </tbody>
          <tfoot>
            <tr>
              <th colspan="4" class="text-right" >
                <label>TOTAL</label>
              </th>
              <th  class="uang text-right" >
                <?php echo e($total); ?>

              </th>
              <th  class="uang text-right" >
                <?php echo e($total_disc); ?>

              </th>
              <th  class="uang text-right" >
                <?php echo e($total_payment_amount); ?>

              </th>
            </tr>
          </tfoot>
        </table>
<?php /* 
        <br/>

        <div class="row">
                <div class="col-lg-8">
                </div>
                <div class="col-lg-4">
                    <table class="table table-condensed" id="table-detil-total">
                        <tbody>
                          <tr>
                            <td colspan="2" ></td>
                          </tr>
                          <tr>
                              <td class="text-right" style="border-top:solid darkgray 1px;">
                                  Total :
                              </td>
                              <td  class="uang text-right" style="font-size:18px;font-weight:bold;border-top:solid darkgray 1px;">
                                <?php echo e($total); ?>

                              </td>
                          </tr>
                          <tr>
                              <td class="text-right" >
                                  Disc :
                              </td>
                              <td  class="uang text-right" style="font-size:18px;font-weight:bold;">
                                <?php echo e($total_disc); ?>

                              </td>
                          </tr>
                          <tr>
                              <td class="text-right" style="border-top:solid darkgray 1px;">
                                  Payment Amount :
                              </td>
                              <td  class="uang text-right" style="font-size:18px;font-weight:bold;border-top:solid darkgray 1px;">
                                <?php echo e($total_payment_amount); ?>

                              </td>
                          </tr>
                           <tr>
                              <td class="text-right" >
                                  <i>Paid</i>
                              </td>
                              <td  class="uang text-right" style="font-size:18px;font-weight:bold;">
                                <?php echo e($total_payment_amount - $total_amount_due); ?>

                              </td>
                          </tr>
                          <tr>
                              <td class="text-right" style="border-top:solid darkgray 1px;">
                                  Amount Due :
                              </td>
                              <td  class="uang text-right" style="font-size:18px;font-weight:bold;border-top:solid darkgray 1px;">
                                <?php echo e($total_amount_due); ?>

                              </td>
                          </tr>
                        </tbody>
                    </table>
                </div>
            </div> */ ?>

      </div><!-- /.box-body -->
      <div class="box-footer" >
        <form action="sales/report/pdf-by-date" method="POST" target="_blank" >
          <input type="hidden" name="start_date" value="<?php echo e($start_date); ?>" > 
          <input type="hidden" name="end_date" value="<?php echo e($end_date); ?>" > 

          <button type="submit" class="btn btn-success"  id="btn-print-pdf" ><i class="fa fa-file-pdf-o" ></i> PDF</button>
          <a class="btn btn-danger" href="sales/report" ><i class="fa fa-close" ></i> Close</a>
        </form>
      </div>
    </div><!-- /.box -->

</section><!-- /.content -->


<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables/dataTables.bootstrap.min.js"></script>
<script src="plugins/jqueryform/jquery.form.min.js" type="text/javascript"></script>
<script src="plugins/datepicker/bootstrap-datepicker.js" type="text/javascript"></script>
<script src="plugins/autonumeric/autoNumeric-min.js" type="text/javascript"></script>

<script type="text/javascript">
(function ($) {
  // SET AUTO NUMERIC
  $('.uang').autoNumeric('init',{
      vMin:'0',
      vMax:'9999999999'
  });
  $('.uang').each(function(){
    $(this).autoNumeric('set',$(this).autoNumeric('get'));
  });
  // END OF SET AUTO NUMERIC

  $('#btn-print-pdf').click(function(){

  });

})(jQuery);
</script>
<?php $__env->appendSection(); ?>

<?php echo $__env->make('layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>