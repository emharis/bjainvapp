@extends('layouts.master')

@section('styles')
<style>
    .col-top-item{
        cursor:pointer;
        border: thin solid #CCCCCC;
        
    }
    .table-top-item > tbody > tr > td{
        border-top-color: #CCCCCC;
    }
</style>
@append

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <a href="purchase/supplier" >Supplier</a> <i class="fa fa-angle-double-right" ></i> New
    </h1>
</section>

<!-- Main content -->
<section class="content">
  <form method="POST" action="purchase/supplier/insert" >
    <div class="box box-primary" >
      <div class="box-body" >
        <div class="form-group">
            <label >Nama Supplier</label>
            <input autofocus autocomplete="off" required type="text" class="form-control input-lg" placeholder="Nama Supplier" name="nama" value="">
        </div>
        <div class="form-group">
            <label >Nama Kontak</label>
            <input autocomplete="off" type="text" class="form-control" placeholder="Nama Kontak" name="nama_kontak" value="">
        </div>
        <div class="form-group">
            <label >Alamat</label>
            <input autocomplete="off" type="text" class="form-control" placeholder="Alamat" name="alamat" value="">
        </div>
        <div class="form-group">
            <label >Telp</label>
            <input autocomplete="off" type="text" class="form-control" placeholder="Telp" name="telp" value="">
        </div>          
        <div class="form-group">
            <input autocomplete="off" type="text" class="form-control" placeholder="Telp 2" name="telp2" value="">
        </div> 
        <div class="form-group">
            <label >Jangka Waktu Pembayaran (hari)</label>
            <div class="row" >
              <div class="col-lg-2" >
                <input required autocomplete="off" type="number" class="form-control text-right" placeholder="1" min="1" name="tempo" value="">
              </div>
              
            </div>
            
        </div>                 
        <div class="form-group">
            <label >Note</label>
            <textarea class="form-control" name="note" placeholder="Note" rows="8"></textarea>
        </div> 
      </div>
      <div class="box-footer" >
        <button type="submit" class="btn btn-primary" >Save</button>
        <a class="btn btn-danger" href="purchase/supplier" >Cancel</a>
      </div>
    </div>
  </form>
</section><!-- /.content -->

@stop

@section('scripts')
<script src="plugins/jqueryform/jquery.form.min.js" type="text/javascript"></script>
<script type="text/javascript">
(function ($) {
    

})(jQuery);
</script>
@append