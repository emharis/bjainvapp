<?php $__env->startSection('styles'); ?>
<link href="plugins/datepicker/datepicker3.css" rel="stylesheet" type="text/css"/>
<style>
    .autocomplete-suggestions { border: 1px solid #999; background: #FFF; overflow: auto; }
    .autocomplete-suggestion { padding: 2px 5px; white-space: nowrap; overflow: hidden; }
    .autocomplete-selected { background: #FFE291; }
    .autocomplete-suggestions strong { font-weight: normal; color: red; }
    .autocomplete-group { padding: 2px 5px; }
    .autocomplete-group strong { display: block; border-bottom: 1px solid #000; }

    .table-row-mid > tbody > tr > td {
        vertical-align:middle;
    }

    input.input-clear {
        display: block;
        padding: 0;
        margin: 0;
        border: 0;
        width: 100%;
        background-color:#EEF0F0;
        float:right;
        padding-right: 5px;
        padding-left: 5px;
    }
</style>

<?php $__env->appendSection(); ?>

<?php $__env->startSection('content'); ?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <a href="invoice/customer-invoice" >Customer Invoices</a>
        <i class="fa fa-angle-double-right" ></i>
        <?php echo e($data->no_inv); ?>

    </h1>
</section>

<!-- Main content -->
<section class="content">
    <?php /* data hidden  */ ?>
    <input type="hidden" name="customer_invoice_id" value="<?php echo e($data->id); ?>">

    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
          <li class="active"><a href="#tab_data" data-toggle="tab">Invoice</a></li>
          <li><a href="#tab_payment" data-toggle="tab">Payments</a></li>
          <?php /* <li class="pull-right header" >
              <a class="" id="btn-print-invoice" href="#" ><i class="fa fa-print" ></i></a>
          </li> */ ?>
        </ul>
        <div class="tab-content">
          <div class="tab-pane active" id="tab_data">
            <div class="box-header with-border" style="padding-top:5px;padding-bottom:5px;" >

            <?php /* <?php if($data->status == "O"): ?>
                <a class="btn btn-primary" style="margin-top:0;" id="btn-reg-payment" href="sales/order/reg-payment/<?php echo e($data->id); ?>" >Register Payment</a>
            <?php else: ?>
                <label> <small>Invoice</small> <h4 style="font-weight: bolder;margin-top:0;padding-top:0;margin-bottom:0;padding-bottom:0;" ><?php echo e($data->no_inv); ?></h4></label>
            <?php endif; ?> */ ?>
                <label> <small>Invoice</small> <h4 style="font-weight: bolder;margin-top:0;padding-top:0;margin-bottom:0;padding-bottom:0;" ><?php echo e($data->no_inv); ?></h4></label>

                <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
                <a class="btn  btn-arrow-right pull-right disabled <?php echo e($data->status == 'P' ? 'bg-blue' : 'bg-gray'); ?>" >Paid</a>

                <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>

                <a class="btn btn-arrow-right pull-right disabled <?php echo e($data->status == 'O' ? 'bg-blue' : 'bg-gray'); ?>" >Open</a>

                <?php /* <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>

                <a class="btn btn-arrow-right pull-right disabled bg-gray" >Draft PO</a> */ ?>
            </div>
            <div class="box-body">
                <table class="table" >
                    <tbody>
                        <tr>
                            <td class="col-lg-2">
                                <label>Supplier</label>
                            </td>
                            <td class="col-lg-4" >
                                <?php /* <input type="text" name="supplier" class="form-control" data-supplierid="<?php echo e($so_master->supplier_id); ?>" value="<?php echo e($so_master->supplier); ?>" required> */ ?>
                                <?php echo e($data->nama_customer); ?>

                            </td>
                            <td class="col-lg-2" ></td>
                            <td class="col-lg-2" >
                                <label>Bill Date</label>
                            </td>
                            <td class="col-lg-2" >
                                <?php /* <input type="text" name="tanggal" class="input-tanggal form-control" value="<?php echo e($so_master->tgl_formatted); ?>" required> */ ?>
                                <?php echo e($data->invoice_date_formatted); ?>

                            </td>
                        </tr>
                        <tr>
                            <td class="col-lg-2">
                                <label>SO Ref#</label>
                            </td>
                            <td class="col-lg-4" >
                                <?php /* <input type="text" name="no_inv" class="form-control" value="<?php echo e($so_master->no_inv); ?>" > */ ?>
                                <a href="#" ><?php echo e($data->so_no); ?></a>
                            </td>
                            <td class="col-lg-2" ></td>
                            <td class="col-lg-2" >
                                <label>Due Date</label>
                            </td>
                            <td class="col-lg-2" >
                                <?php /* <input type="text" name="jatuh_tempo"  class="input-tanggal form-control" value="<?php echo e($so_master->jatuh_temso_formatted); ?>" > */ ?>
                                <?php /* <?php echo e($so_master->jatuh_temso_formatted); ?> */ ?>
                                <?php echo e($data->due_date_formatted); ?>

                            </td>
                        </tr>
                    </tbody>
                </table>

                <h4 class="page-header" style="font-size:14px;color:#3C8DBC"><strong>PRODUCT DETAILS</strong></h4>

                <table id="table-product" class="table table-bordered table-condensed table-striped" >
                    <thead>
                        <tr>
                            <th style="width:25px;" >NO</th>
                            <th class="col-lg-4" >PRODUCT</th>
                            <th class="col-lg-1" >QUANTITY</th>
                            <th class="col-lg-1" >UNIT</th>
                            <th class="col-lg-1" >WEIGHT</th>
                            <th>UNIT PRICE</th>
                            <th>SUBTOTAL</th>
                            <?php /* <th style="width:50px;" ></th> */ ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $rownum=1; ?>
                        <?php foreach($barang as $dt): ?>
                            <tr>
                                <td class="text-right"><?php echo e($rownum++); ?></td>
                                <td>
                                    <?php echo e($dt->kategori . ' ' . $dt->nama_barang); ?>

                                </td>
                                <td class="text-right" >
                                    <?php echo e($dt->qty); ?>

                                </td>
                                <td class="text-right" >
                                    <?php echo e($dt->satuan); ?>

                                </td>
                                <td class="text-right" >
                                    <?php echo e($dt->berat); ?>

                                </td>
                                <td class="text-right uang" >
                                    <?php echo e($dt->unit_price); ?>

                                </td>
                                <td class="text-right uang" >
                                    <?php echo e($dt->subtotal); ?>

                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <div class="row" >
                    <div class="col-lg-8" >
                        <?php /* <textarea name="note" class="form-control" rows="4" style="margin-top:5px;" placeholder="Note" ><?php echo e($so_master->note); ?></textarea> */ ?>
                        <?php /* <br/>
                        <label>Note :</label> <i><?php echo e($so_master->note); ?></i> */ ?>
                    </div>
                    <div class="col-lg-4" >
                        <table class="table table-condensed" >
                            <tbody>
                                <tr>
                                    <td class="text-right">
                                        <label>Subtotal :</label>
                                    </td>
                                    <td id="label-total-subtotal" class="uang text-right" >
                                        <?php echo e($data->subtotal); ?>

                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-right" >
                                        <label>Disc :</label>
                                    </td>
                                    <td class="text-right uang" id="label-disc" >
                                        <?php echo e($data->disc); ?>

                                       <?php /* <input style="font-size:14px;" type="text" name="disc" class="input-sm form-control text-right input-clear" value="<?php echo e($so_master->disc); ?>" >  */ ?>

                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-right" style="border-top:solid darkgray 1px;" >
                                        Total :
                                    </td>
                                    <td id="label-total" class="uang text-right" style="font-size:18px;font-weight:bold;border-top:solid darkgray 1px;" >
                                        <?php echo e($data->total); ?>

                                    </td>
                                </tr>
                                <?php if(count($payments) > 0): ?>
                                <?php foreach($payments as $dt): ?>
                                    <tr style="background-color:#EEF0F0;" >
                                        <td class="text-right" >
                                            <?php /* <a class="btn-delete-payment" data-paymentid="<?php echo e($dt->id); ?>" href="#" ><i class="fa fa-trash-o pull-left" ></i></a> */ ?>
                                            <i>Paid on <?php echo e($dt->payment_date_formatted); ?></i>
                                        </td>
                                        <td class="text-right uang" >
                                            <i><?php echo e($dt->payment_amount); ?></i>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <?php endif; ?>
                                <tr>
                                    <td class="text-right" style="border-top:solid darkgray 1px;" >
                                        Amount Due :
                                    </td>
                                    <td id="label-amount-due" class="uang text-right" style="font-size:18px;font-weight:bold;border-top:solid darkgray 1px;" >
                                        <?php echo e($data->amount_due); ?>

                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-lg-12" >
                        <?php /* <?php if($multi_invoice): ?>
                        <a class="btn btn-danger" href="sales/order/invoice/<?php echo e($so_master->id); ?>" >Close</a>
                        <?php else: ?> */ ?>
                            <a class="btn btn-success" target="_blank" href="api/cetak-sales-order-invoice/<?php echo e($data->id); ?>" ><i class="fa fa-print"  ></i> Print</a>
                            <a class="btn btn-danger" href="invoice/customer-invoice" ><i class="fa fa-close" ></i> Close</a>
                        <?php /* <?php endif; ?> */ ?>
                    </div>
                </div>
            </div><!-- /.box-body -->
          </div><!-- /.tab-pane -->
          <div class="tab-pane" id="tab_payment">
            <?php /* TAB 2 */ ?>
            <?php /* DATA PAYMENTS */ ?>
            <?php if($data->status == 'O'): ?>
            <a class="btn btn-primary" id="btn-reg-payment" href="invoice/customer-invoice/reg-payment/<?php echo e($data->id); ?>" >Register Payment</a>
            <br/>
            <div class="clear-fix"></div>
            &nbsp;
            <?php endif; ?>


            <table class="table table-bordered table-condensed table-striped" >
                <thead>
                    <tr>
                        <th style="width:30px;" >NO</th>
                        <th class="col-lg-2" >PAYMENT DATE</th>
                        <th class="col-lg-2" >REF#</th>
                        <th>PAYMENT AMOUNT</th>
                        <th style="width:30px;" ></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $rownum=1; ?>
                    <?php $payment_paid = 0; ?>
                    <?php foreach($payments as $pay): ?>
                        <tr>
                            <td class="text-right" ><?php echo e($rownum++); ?></td>
                            <td><?php echo e($pay->payment_date_formatted); ?></td>
                            <td><?php echo e($pay->payment_number); ?></td>
                            <td class="text-right" ><?php echo e($pay->payment_amount); ?></td>
                            <td  >
                                <a class="btn btn-danger btn-xs btn-delete-payment" href="#" data-paymentid="<?php echo e($dt->id); ?>" ><i class="fa fa-trash-o" ></i></a>
                            </td>
                        </tr>
                        <?php $payment_paid += $pay->payment_amount; ?>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr style="border-top:solid #CACACA 2px;background-color:whitesmoke;" >
                        <td colspan="3" style="margin:0;padding:0;" ></td>
                        <td class="text-right" style="margin:0;padding:0;" >
                            <label class="pull-left" >TOTAL:</label>
                            <label><?php echo e($payment_paid); ?></label>
                        </td>
                        <td style="margin:0;padding:0;" ></td>
                    </tr>
                </tfoot>
            </table>

            <a class="btn btn-danger" href="invoice/customer-invoice" >Close</a>

          </div><!-- /.tab-pane -->
        </div><!-- /.tab-content -->
      </div>

    <!-- Default box -->
    <div class="box box-solid"
    </div><!-- /.box -->

</section><!-- /.content -->

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables/dataTables.bootstrap.min.js"></script>
<script src="plugins/jqueryform/jquery.form.min.js" type="text/javascript"></script>
<script src="plugins/datepicker/bootstrap-datepicker.js" type="text/javascript"></script>
<script src="plugins/autocomplete/jquery.autocomplete.min.js" type="text/javascript"></script>
<script src="plugins/autonumeric/autoNumeric-min.js" type="text/javascript"></script>

<script type="text/javascript">
(function ($) {
    $('#btn-print-invoice').click(function(){
        alert('Print Invoice');
        return false;
    });

    // delete payment
    $('.btn-delete-payment').click(function(){
        if(confirm('Anda akan menghapus data ini?')){
            var payment_id = $(this).data('paymentid');
            // var deleteForm = $('<form>').attr('method','POST').attr('action','api/delete-customer-payment');
            // deleteForm.append($('<input>').attr('type','hidden').attr('name','payment_id').val(payment_id));
            // deleteForm.submit();

            $.post('api/delete-customer-payment',{
                "payment_id" : payment_id
            },function(){
                location.reload();
            });
        }

        return false;
    });

    // FORMAT UANG
    $('.uang').autoNumeric('init',{
        vMin:'0.00',
        vMax:'9999999999.00'
    });

    $('.uang').each(function(){
        $(this).autoNumeric('set',$(this).autoNumeric('get'));
    });

})(jQuery);
</script>
<?php $__env->appendSection(); ?>

<?php echo $__env->make('layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>