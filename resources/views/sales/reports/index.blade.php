@extends('layouts.master')

@section('styles')
<!--Bootsrap Data Table-->
<link rel="stylesheet" href="plugins/datatables/dataTables.bootstrap.css">
{{-- <link href="plugins/datepicker/datepicker3.css" rel="stylesheet" type="text/css"/> --}}
<link href="plugins/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet" type="text/css"/>

@append

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Sales Order Reports
    </h1>
</section>

<!-- Main content -->
<section class="content">

    <!-- Default box -->
    <div class="box box-solid">
      <div class="box-header with-border" >
        <h3 class="box-title" >Report Option</h3>
      </div><!-- /.box-header -->
      <div class="box-body">
        <form method="POST" action="sales/report/show-report" >
          <table class="table table-condensed" >
          <tbody>
            {{-- BY DATE RANGE --}}
            <tr>
              <td class="col-sm-2 col-md-2 col-lg-2" >
                <label>Order Date</label>
              </td>
              <td class="col-sm-4 col-md-4 col-lg-4" >
                <input type="text" name="date_range" class="form-control">
                <input type="hidden" name="start_date" class="form-control">
                <input type="hidden" name="end_date" class="form-control">
              </td>
              <td >
              </td>
            </tr>
            {{-- BY SUPPLIER --}}
            <tr>
              <td>
                <div class="checkbox">
                  <label>
                    <input type="checkbox" name="ck_by_customer"/> Customer
                  </label>
                </div>
              </td>
              <td  >
                {!! Form::select('customer',$select_customer,null,['class'=>'form-control row-by-customer','disabled']) !!}
              </td>
            </tr>
            <tr>
              <td>
                <div class="checkbox">
                  <label>
                    <input type="checkbox" name="ck_by_salesperson"/> Salesperson
                  </label>
                </div>
              </td>
              <td  >
                {!! Form::select('salesperson',$select_salesperson,null,['class'=>'form-control row-by-salesperson','disabled']) !!}
              </td>
            </tr>
            <tr>
              <td></td>
              <td>
                <button type="submit" class="btn btn-primary " id="btn-submit" >Submit</button>
              </td>
            </tr>
          </tbody>
        </table>
        </form>
      </div><!-- /.box-body -->
    </div><!-- /.box -->

</section><!-- /.content -->


@stop

@section('scripts')
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables/dataTables.bootstrap.min.js"></script>
<script src="plugins/jqueryform/jquery.form.min.js" type="text/javascript"></script>
{{-- <script src="plugins/datepicker/bootstrap-datepicker.js" type="text/javascript"></script> --}}
<script src="plugins/bootstrap-daterangepicker/moment.js" type="text/javascript"></script>
<script src="plugins/bootstrap-daterangepicker/daterangepicker.js" type="text/javascript"></script>

<script type="text/javascript">
(function ($) {

  // // SET DATEPICKER
  // $('.input-date').datepicker({
  //   format: 'dd-mm-yyyy',
  //     todayHighlight: true,
  //     autoclose: true,
  //     endDate : 'now'
  // });
  // // END OF SET DATEPICKER

  // SET DATE PICKER
  var start_date;
  var end_date;
  $('input[name=date_range]').daterangepicker({
            "autoApply": true,
            locale: {
              format: 'DD/MM/YYYY'
            },
        }, 
        function(start, end, label) {
            // alert("A new date range was chosen: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
            start_date = start.format('DD-MM-YYYY');
            end_date = end.format('DD-MM-YYYY');

            // set ke input text
            $('input[name=start_date]').val(start.format('DD-MM-YYYY'));
            $('input[name=end_date]').val(end.format('DD-MM-YYYY'));
        });
    // END OF SET DATEPICKER

  // INPUT-DATE-RANGE SET RANGE
  // $('input[name=date_start]').change(function(){
  //     // sett start date on end_date
  //     $('input[name=date_end]').datepicker('remove');
  //     $('input[name=date_end]').datepicker({
  //       format: 'dd-mm-yyyy',
  //       todayHighlight: true,
  //       autoclose: true,
  //       startDate : $('input[name=date_start]').val(),
  //       endDate : 'now'
  //     });
  //   });
  // INPUT-DATE-RANGE SET RANGE

  // check change
  // $('input[name=ck_date_range]').change(function(){
  //   if($(this).prop('checked')){
  //     $('input.row-date-range').removeAttr('readonly');
  //   }else{
  //     $('input.row-date-range').attr('readonly','readonly');
  //   }
  //
  // });

  $('input[name=ck_by_customer]').change(function(){
    if($(this).prop('checked')){
      $('select.row-by-customer').removeAttr('disabled');
      $('select.row-by-customer').focus();
    }else{
      $('select.row-by-customer').attr('disabled','disabled');
    }
  });

  $('input[name=ck_by_salesperson]').change(function(){
    if($(this).prop('checked')){
     $('select[name=salesperson]').removeAttr('disabled');
      $('select[name=salesperson]').focus();
    }else{
      $('select[name=salesperson]').attr('disabled','disabled');
    }
  });

  // $('input[name=ck_status]').change(function(){
  //   if($(this).prop('checked')){
  //     $('select.row-by-status').removeAttr('disabled');
  //     $('select.row-by-status').focus();
  //   }else{
  //     $('select.row-by-status').attr('disabled','disabled');
  //   }
  // });

  // SUBMIT REPORT WITH OPTIONS
  // $('#btn-submit').click(function(){
  //   // var date_start = $('input[name=date_start]').val();
  //   // var date_end = $('input[name=date_end]').val();

  //   // if(date_start != "" && date_end != ""){
  //   //   var newform = $('<form>').attr('method','POST').attr('action','sales/report/show-report');
  //   //   newform.append($('<input>').attr('type','hidden').attr('name','date_start').val(date_start));
  //   //   newform.append($('<input>').attr('type','hidden').attr('name','date_end').val(date_end));
  //   //   newform.submit();
  //   // }else{
  //   //   alert('Lengkapi data yang kosong.');
  //   // }

  //   var filter_by_customer = $('input[name=ck_by_customer]').prop('checked');

  //   var newform = $('<form>').attr('method','POST').attr('action','sales/report/report-by-date');

  //   if(start_date != '' && end_date != ''){
        
  //           newform.append($('<input>').attr('type','hidden').attr('name','start_date').val(start_date));
  //           newform.append($('<input>').attr('type','hidden').attr('name','end_date').val(end_date));

  //           if(filter_by_customer){
  //             var customer_id = $('select[name=customer]').val();
  //             newform.append($('<input>').attr('type','hidden').attr('name','customer_id').val(customer_id));
  //             newform.attr('action','sales/report/report-by-customer');

  //           }
  //   }else{
  //     alert('Lengkapi data yang kosong.');
  //   }

  //   newform.submit();

  // });
  // END OF SUBMIT REPORT WITH OPTIONS

})(jQuery);
</script>
@append
