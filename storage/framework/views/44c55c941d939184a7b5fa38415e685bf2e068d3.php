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
        <a href="sales/order" >Sales Order</a> <i class="fa fa-angle-double-right" ></i> <?php echo e($so_master->so_no); ?>

    </h1>
</section>

<!-- Main content -->
<section class="content">

    <!-- Default box -->
    <div class="box box-solid">
        <div class="box-header with-border" style="padding-top:5px;padding-bottom:5px;" >
          <label><h3 style="margin:0;padding:0;font-weight:bold;" ><?php echo e($so_master->so_no); ?></h3></label>

            <?php /* button VALIDATE */ ?>
            <?php /* <a class="btn btn-primary" style="margin-top:0;" id="btn-validate-so" href="sales/order/validate/<?php echo e($so_master->id); ?>" >Validate</a> */ ?>
            <?php /* sales order title */ ?>
            <?php /* <label> <small>Sales Order</small> <h4 style="font-weight: bolder;margin-top:0;padding-top:0;margin-bottom:0;padding-bottom:0;" ><?php echo e($so_master->so_no); ?></h4></label> */ ?>

            <?php /* Button Cancel Order  */ ?>
            
            <?php /* Button Print */ ?>
            <?php /* <div class="btn-group">
              <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Print <span class="caret"></span>
              </button>
              <ul class="dropdown-menu">
                <li><a href="#">Direct Print</a></li>
                <li><a href="#">PDF</a></li>
              </ul>
            </div> */ ?>

            <?php if($so_master->status == 'V'): ?>
            <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
            <a class="btn  btn-arrow-right pull-right disabled bg-blue"" >VALIDATED</a>

            <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
            <a class="btn btn-arrow-right pull-right disabled bg-gray" >OPEN</a>

            <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
            <a class="btn btn-arrow-right pull-right disabled bg-gray" >DRAFT</a>
            <?php else: ?>
                <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
                <a class="btn btn-arrow-right pull-right disabled bg-red" >CANCELED</a>
            <?php endif; ?>
        </div>
        <div class="box-body">

            <?php /* Data Hidden */ ?>
            <input type="hidden" name="so_master_id" value="<?php echo e($so_master->id); ?>">

            <div class="row" >
                <div class="col-lg-10" >
                    <table class="table no-border" >
                <tbody>
                    <tr>
                        <td class="col-lg-2">
                            <label>Customer</label>
                        </td>
                        <td class="col-lg-3" >
                            <?php /* <input type="text" name="customer" autofocus class="form-control text-uppercase" data-customerid="<?php echo e($so_master->customer_id); ?>" value="<?php echo e($so_master->customer); ?>" required> */ ?>
                            <?php echo e($so_master->nama_customer); ?>

                        </td>
                        <td class="col-lg-2" ></td>
                        <td class="col-lg-2" >
                            <label>Order Date</label>
                        </td>
                        <td class="col-lg-3" >
                            <?php /* <input type="text" name="tanggal" class="input-tanggal form-control" value="<?php echo e($so_master->tgl_formatted); ?>" required> */ ?>
                            <?php echo e($so_master->tgl_formatted); ?>

                        </td>

                    </tr>
                    <tr>
                        <td class="">
                            <label>Salesperson</label>
                        </td>
                        <td class="" >
                            <?php /* <input type="text" name="salesperson" class="form-control text-uppercase" data-salespersonid="<?php echo e($so_master->salesman_id); ?>" value="<?php echo e($so_master->nama_salesman_full); ?>" required > */ ?>
                            <?php echo e($so_master->salesman); ?>

                        </td>
                        <td class="" ></td>
                        <td class=" hide" >
                            <label>Inv. No.</label>
                        </td>
                        <td class="hide" >
                            <?php /* <input type="text" name="no_inv"  class="input-tanggal form-control" value="<?php echo e($so_master->no_inv); ?>"  readonly> */ ?>
                            <?php echo e($so_master->no_inv); ?>

                        </td>

                    </tr>
                </tbody>
            </table>
                </div>
                <div class="col-lg-2" >
                    <?php /* widget invoices */ ?>
                    <?php if($so_master->status == 'V'): ?>
                    <a class="btn btn-app pull-right" href="sales/order/invoice/<?php echo e($so_master->id); ?>" >
                            <span class="badge bg-green"><?php echo e($invoice_num); ?></span>
                            <i class="fa fa-newspaper-o"></i> Invoices
                          </a>
                    <?php endif; ?>
                </div>
            </div>

            <h4 class="page-header" style="font-size:14px;color:#3C8DBC"><strong>PRODUCT DETAILS</strong></h4>

            <table id="table-product" class="table table-bordered table-condensed" >
                <thead>
                    <tr>
                        <th style="width:25px;" >NO</th>
                        <th  >PRODUCT</th>
                        <th class="col-lg-1" >QUANTITY</th>
                        <th class="col-lg-1" >SATUAN</th>
                        <?php /* <th class="col-lg-1" >BERAT</th> */ ?>
                        <?php /* <th class="col-lg-2" >UNIT PRICE</th> */ ?>
                        <th class="col-lg-2" >UNIT PRICE</th>
                        <th class="col-lg-2" >SUBTOTAL</th>
                        <?php /* <th style="width:50px;" ></th> */ ?>
                    </tr>
                </thead>
                <tbody>
                    <?php $rownum=1; ?>
                    <?php foreach($so_barang as $dt): ?>
                        <tr class="row-product"  >
                            <td class="text-right" ><?php echo e($rownum++); ?></td>
                            <td>
                                <?php /* <input autocomplete="off" type="text"  data-barangid="<?php echo e($dt->barang_id); ?>" data-kode="<?php echo e($dt->kode); ?>"  value="<?php echo e($dt->nama_full); ?>" class="text-uppercase form-control input-product input-sm input-clear" > */ ?>
                                <?php echo e($dt->kategori.' '. $dt->nama_barang); ?>

                            </td>
                            <td class="text-right" >
                                <?php /* <input type="number" autocomplete="off" min="1" class="form-control text-right input-quantity input-sm input-clear" value="<?php echo e($dt->qty); ?>" > */ ?>
                                <?php echo e($dt->qty); ?>

                            </td>
                            <td>
                                <?php echo e($dt->satuan); ?>

                            </td>
                            <?php /* <td>
                                <?php echo e($dt->berat); ?>

                            </td> */ ?>
                            <?php /* <td class="text-right" > */ ?>
                                <?php /* <input autocomplete="off" type="text" class="text-right form-control input-unit-price input-sm input-clear" readonly="" value="<?php echo e($dt->harga_satuan); ?>" > */ ?>
                                <?php /* <?php echo e(number_format($dt->harga_satuan,0,'.',',')); ?> */ ?>
                            <?php /* </td> */ ?>
                            <td class="text-right" >
                                <?php /* <input autocomplete="off" type="text" class="text-right form-control input-salesperson-unit-price input-sm input-clear" value="<?php echo e($dt->harga_salesman); ?>" > */ ?>
                                <?php echo e(number_format($dt->unit_price,0,'.',',')); ?>

                            </td>
                            <td class="text-right" >
                                <?php /* <input autocomplete="off" type="text" readonly  class="text-right form-control input-subtotal input-sm input-clear" value="<?php echo e($dt->subtotal); ?>" > */ ?>
                                <?php echo e(number_format($dt->subtotal,0,'.',',')); ?>

                            </td>
                            <?php /* <td class="text-center" >
                                <a href="#" class="btn-delete-row-product" ><i class="fa fa-trash" ></i></a>
                            </td> */ ?>
                        </tr>
                    <?php endforeach; ?>
                    <?php /* <tr id="row-btn-add-item">
                        <td></td>
                        <td colspan="7" >
                            <a id="btn-add-item" href="#">Add an item</a>
                        </td>
                    </tr> */ ?>


                </tbody>
            </table>

            <div class="row" >
                <div class="col-lg-8" >
                    <?php /* <textarea name="note" class="form-control" rows="3" style="margin-top:5px;" placeholder="Note" ><?php echo e($so_master->note); ?></textarea> */ ?>
                    <?php /* <i>* <span>Q.O.H : Quantity on Hand</span></i>
                    <i>&nbsp;|&nbsp;</i>
                    <i><span>S.U.P : Salesperson Unit Price</span></i> */ ?>
                    <span>Note : <i><?php echo e($so_master->note); ?></i></span>
                </div>
                <div class="col-lg-4" >
                    <table class="table table-condensed" >
                        <tbody>
                            <tr>
                                <td class="text-right">
                                    <label>Subtotal :</label>
                                </td>
                                <td class="label-total-subtotal text-right" >
                                    <?php echo e(number_format($so_master->subtotal,0,'.',',')); ?>

                                </td>
                            </tr>
                            <tr>
                                <td class="text-right" >
                                    <label>Disc :</label>
                                </td>
                                <td class="text-right" >
                                   <?php /* <input style="font-size:14px;" type="text" name="disc" class="input-sm form-control text-right input-clear" value="<?php echo e($so_master->disc); ?>" >  */ ?>
                                   <?php echo e(number_format($so_master->disc,0,'.',',')); ?>

                                </td>
                            </tr>
                            <tr>
                                <td class="text-right" style="border-top:solid darkgray 1px;" >
                                    Total :
                                </td>
                                <td class="label-total text-right" style="font-size:18px;font-weight:bold;border-top:solid darkgray 1px;" >
                                    <?php echo e(number_format($so_master->total,0,'.',',')); ?>

                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-lg-12" >
                    <?php /* <button type="submit" class="btn btn-primary" id="btn-save" >Save</button> */ ?>
                    <?php if($so_master->status == 'V'): ?>
                    <button class="btn btn-warning" id="btn-cancel-order" data-orderid="<?php echo e($so_master->id); ?>" ><i class="fa fa-reply" ></i> Cancel Order</button>
                    &nbsp;
                    <?php endif; ?>
                    <a class="btn btn-danger" href="sales/order" ><i class="fa fa-close" ></i> Close</a>
                </div>
            </div>



            <?php /* <a id="btn-test" href="#" >TEST</a> */ ?>


        </div><!-- /.box-body -->
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

<script type="text/javascript" >
  (function($){
    $('#btn-cancel-order').click(function(){
      if(confirm('Anda akan membatalkan transaksi ini? \nData yang telah dibatalakan tidak dapat dikembalikan.')){
            var orderid = $(this).data('orderid');
            var newform = $('<form>').attr('method','POST').attr('action','sales/order/cancel-order');
            newform.append($('<input>').attr('type','hidden').attr('name','sales_order_id').val(orderid));
            newform.submit();
      }
    });
  })(jQuery);
</script>
<?php $__env->appendSection(); ?>

<?php echo $__env->make('layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>