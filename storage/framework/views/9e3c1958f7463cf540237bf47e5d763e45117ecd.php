<?php $__env->startSection('styles'); ?>
<link rel="stylesheet" href="<?php echo e(URL::asset('plugins/select2/select2.min.css')); ?>">
<style>
    .col-top-item{
        cursor:pointer;
        border: thin solid #CCCCCC;
        
    }
    .table-top-item > tbody > tr > td{
        border-top-color: #CCCCCC;
    }
</style>
<?php $__env->appendSection(); ?>

<?php $__env->startSection('content'); ?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <a href="inventory/barang" >Barang</a> <i class="fa fa-angle-double-right" ></i> Edit
    </h1>
</section>

<!-- Main content -->
<section class="content">
    <div class="nav-tabs-custom">
          <ul class="nav nav-tabs">
            <li class="active">
                <a href="#tab_1" data-toggle="tab">Data Barang</a>
                </li>
            <li>
                <a href="#tab_2" data-toggle="tab">Harga Barang </a>
                </li>
            <li>
                <a href="#tab_3" data-toggle="tab">
                    Purchases &nbsp;
                    <?php /* <span class="label bg-green pull-right" ><?php echo e(count($purchases)); ?></span> */ ?>
                </a>
            </li>
            <li>
                <a href="#tab_4" data-toggle="tab">
                    Sales &nbsp;
                    <span class="label bg-green pull-right" >0</span> 
                </a>
            </li>
            <?php /* <li><a href="#tab_5" data-toggle="tab">Stok Moves</a></li> */ ?>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="tab_1">
              <form method="POST" action="inventory/barang/update" >
                <input type="hidden" name="id" value="<?php echo e($barang->id); ?>">
                <table class="table" >
                    <tbody>
                        <tr>
                            <td class="col-lg-2" >
                                <label>Nama Barang</label>
                            </td>
                            <td colspan="3" > 
                                <input autofocus autocomplete="off"  type="text" class="form-control" placeholder="Nama Barang" name="nama" value="<?php echo e($barang->nama); ?>">        
                            </td>
                        </tr>
                        <tr>
                            <td class="col-lg-2" >
                                <label>Kode</label>
                            </td>
                            <td>
                                <input autocomplete="off" type="text" class="form-control " placeholder="Kode" name="kode" value="<?php echo e($barang->kode); ?>" >
                            </td>
                            <td class="col-lg-2" >
                                <label>Kategori</label>
                            </td>
                            <td>
                                <select name="kategori" class="form-control">
                                  <?php foreach($kategori as $dt): ?>
                                    <option value="<?php echo e($dt->id); ?>" <?php echo e($dt->id == $barang->kategori_id ? 'selected':''); ?>  ><?php echo e($dt->nama); ?></option>
                                  <?php endforeach; ?>
                                </select>     
                            </td>
                        </tr>
                        <tr>
                            <td class="col-lg-2" >
                                <label>Berat</label>
                            </td>
                            <td>
                                <div class="row" >
                                    <div class="col-lg-6" >
                                        <input autocomplete="off" type="number" class="form-control"  placeholder="Berat" name="berat" value="<?php echo e($barang->berat); ?>" >        
                                    </div>
                                </div>                                
                            </td>
                            <td class="col-lg-2" >
                                <label>Minimum Quantity</label>
                            </td>
                            <td>
                                <div class="row" >
                                    <div class="col-lg-4" >
                                        <input autocomplete="off"  type="number" class="form-control"  placeholder="Berat" name="rol" value="<?php echo e($barang->rol); ?>" >
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4" >
                                <button type="submit" class="btn btn-primary" >Save</button>
                                <a class="btn btn-danger" href="inventory/barang" >Cancel</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
              </form>
            </div>
            <!-- /.tab-pane -->
            <div class="tab-pane" id="tab_2">
                <?php /* Harga Barang */ ?>
                <form name="form-update-harga-barang" method="POST" action="inventory/barang/update-harga" >

                    <input type="hidden" name="barang_id" value="<?php echo e($barang->id); ?>">
                    <table class="table" >
                        <tbody>
                            <tr>
                                <td class="col-lg-2"><label>HPP</label></td>
                                <td class="col-lg-2" >
                                    <div class="input-group">
                                        <span class="input-group-addon">Rp.</span>
                                        <?php /* <input type="text" name="hpp_cost" readonly class="form-control text-right" value="<?php echo e(number_format($hpp,0,'.',',')); ?>" > */ ?>
                                    </div>
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td><label>Sale Price</label></td>
                                <td class="col-lg-2" >
                                    <div class="input-group">
                                        <span class="input-group-addon">Rp.</span>
                                        <?php /* <input type="text" name="sale_price" class="form-control text-right" value="<?php echo e($harga->harga_jual); ?>" > */ ?>
                                    </div>
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="2" >
                                    <!--jika Stok barang 0 maka tidak bisa update harga barang-->
                                    <?php /* <?php if($barang->stok > 0): ?>
                                        <button class="btn btn-primary" type="submit" >Save</button>
                                    <?php endif; ?> */ ?>
                                    <a class="btn btn-danger" href="inventory/barang" >Cancel</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            </div>
            <!-- /.tab-pane -->
            <div class="tab-pane" id="tab_3">
                <table class="table table-bordered table-striped table-condensed" >
                    <thead>
                        <tr>
                            <th>PO NUM</th>
                            <th>SUPPLIER</th>
                            <th>QTY</th>
                            <th>UNIT PRICE</th>
                            <th>SUBTOTAL</th>
                            <th>TANGGAL</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php /* <?php foreach($purchases as $dt): ?>
                            <tr>
                                <td><?php echo e($dt->po_num); ?></td>
                                <td><?php echo e($dt->supplier); ?></td>
                                <td><?php echo e($dt->qty); ?></td>
                                <td><?php echo e($dt->harga); ?></td>
                                <td><?php echo e($dt->subtotal); ?></td>
                                <td><?php echo e($dt->tgl_formatted); ?></td>
                            </tr>
                        <?php endforeach; ?> */ ?>
                    </tbody>
                </table>
            </div>
            <!-- /.tab-pane -->
            <div class="tab-pane" id="tab_4">

            </div>
            <!-- /.tab-pane -->
            <?php /* <div class="tab-pane" id="tab_5">

            </div> */ ?>
            <!-- /.tab-pane -->
          </div>
          <!-- /.tab-content -->
        </div>

</section><!-- /.content -->

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script src="plugins/jqueryform/jquery.form.min.js" type="text/javascript"></script>
<script src="plugins/autonumeric/autoNumeric-min.js" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo e(URL::asset('plugins/select2/select2.full.min.js')); ?>"></script>
<script type="text/javascript">
(function ($) {
    // Tampilkan satuan barang
    $('select[name=kategori]').change(function(){
        $('#label-satuan').text($('select[name=kategori] option:selected').data('satuan'));
    });

    // format auto numeric HPP
    $('input[name=hpp],input[name=sale_price]').autoNumeric('init',{
          vMin:'0',
          vMax:'9999999999'
      });

    // SELECT2
    $('select[name=kategori]').select2(); 

})(jQuery);
</script>
<?php $__env->appendSection(); ?>
<?php echo $__env->make('layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>