@extends('layouts.master')

@section('styles')
<!--Bootsrap Data Table-->
<link rel="stylesheet" href="plugins/datatables/dataTables.bootstrap.css">
<link href="plugins/datepicker/datepicker3.css" rel="stylesheet" type="text/css"/>

<style>
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

@append

@section('content')
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
        <h3 class="box-title">Sales Order Report</h3>

      </div><!-- /.box-header -->
      <div class="box-body">
        <table class="table table-condensed" >
          <tbody>
            <tr>
              <td class="col-sm-2 col-md-2 col-lg-2" >
                <label>Order date</label>
              </td>
              <td>:</td>
              <td>
                {{str_replace('-','/',$start_date)  .' - ' .str_replace('-','/',$end_date) }}
              </td>
              <td></td>
              <td class="col-sm-2 col-md-2 col-lg-2" >
                <label>Customer</label>
              </td>
              <td>:</td>
              <td>
                {{$data_customer->nama}}
              </td>
            </tr>
            <tr>
              <td class="col-sm-2 col-md-2 col-lg-2" >
                <label>Salesperson</label>
              </td>
              <td>:</td>
              <td>
                {{$data_salesperson->nama}}
              </td>
              <td></td>
              <td class="col-sm-2 col-md-2 col-lg-2" >
                
              </td>
              <td></td>
              <td>
                
              </td>
            </tr>

          </tbody>
        </table>

        <h4 class="page-header" style="font-size:14px;color:#3C8DBC"><strong>ORDER DETAILS</strong></h4>

        <table class="table table-bordered table-condensed" >
          <thead>
            <tr>
              <th>NO</th>
              <th>REF#</th>
              <th>ORDER DATE</th>
              <th>SUBTOTAL</th>
              <th>DISC</th>
              <th>TOTAL</th>
            </tr>
          </thead>
          <tbody>
            <?php $rownum=1; ?>
            <?php $payment_total = 0;?>
            @foreach($data as $dt)
              <tr>
                <td>{{$rownum++}}</td>
                <td>
                  {{$dt->so_no}}
                </td>
                <td>
                  {{$dt->tgl_formatted}}
                </td>
                <td class="uang text-right" >
                  {{$dt->subtotal}}
                </td>
                <td class="uang text-right" >
                  {{$dt->disc}}
                </td>
                <td class="uang text-right" >
                  {{$dt->total}}
                </td>
                
              </tr>
              <?php $payment_total += $dt->total; ?>
            @endforeach

          </tbody>
          <tfoot>
            <tr>
              <th colspan="3" class="text-right" >
                <label>TOTAL</label>
              </th>
              <th  class="uang text-right" >
                {{$subtotal}}
              </th>
              <th  class="uang text-right" >
                {{$total_disc}}
              </th>
              <th  class="uang text-right" >
                {{$total}}
              </th>
            </tr>
          </tfoot>
        </table>


      </div><!-- /.box-body -->
      <div class="box-footer" >
        <form action="sales/report/pdf-by-all" method="POST" target="_blank" >
          <input type="hidden" name="start_date" value="{{$start_date}}" > 
          <input type="hidden" name="end_date" value="{{$end_date}}" > 
          <input type="hidden" name="customer_id" value="{{$data_customer->id}}" > 
          <input type="hidden" name="salesperson_id" value="{{$data_salesperson->id}}" > 

          <button type="submit" class="btn btn-success"  id="btn-print-pdf" ><i class="fa fa-file-pdf-o" ></i> PDF</button>
          <a class="btn btn-danger" href="sales/report" ><i class="fa fa-close" ></i> Close </a>
        </form>
      </div>
    </div><!-- /.box -->

</section><!-- /.content -->


@stop

@section('scripts')
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

})(jQuery);
</script>
@append
