 @extends('layouts.master')

@section('styles')
<!--Bootsrap Data Table-->
<link rel="stylesheet" href="plugins/datatables/dataTables.bootstrap.css">
<link href="plugins/datepicker/datepicker3.css" rel="stylesheet" type="text/css"/>
<style>
.form-login{
  padding: 1em;
  min-width: 280px; /* change width as per your requirement */
}
</style>
@append

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Sales Order
    </h1>
</section>

<!-- Main content -->
<section class="content">

    <!-- Default box -->
    <div class="box box-solid">
        <div class="box-header with-border" >   
            <a class="btn btn-primary" id="btn-add" href="sales/order/add" ><i class="fa fa-plus" ></i> Add Sales Order</a>
            {{-- FILTER WIDGET --}}
            {{-- <button class="pull-right btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown" ><i class="fa fa-filter" ></i> Filter</button> --}}
            {{-- <div class="dropdown-menu form-login stop-propagation pull-right" role="menu">
              <div class="form-group" >
                <form method="POST" name="form-filter-by-status-open" action="sales/order/filter" >
                  <input type="hidden" name="filter_by" value="open" />
                  <button type="submit" class="btn btn-flat btn-block" style="text-align:left; padding-left:6px"  >Status "Open"</button>
                </form>
                <form method="POST" name="form-filter-by-status-validated" action="sales/order/filter" >
                  <input type="hidden" name="filter_by" value="validated" />
                  <button type="submit" class="btn no-bg btn-flat btn-block" style="text-align:left; padding-left:6px"  >Status "Validated"</button>
                </form>
              </div>
              <li class="divider" ></li>
              
            </div> --}}
            {{-- END FILTER WIDGET --}}

            <div class="pull-right" >
                <table style="background-color: #ECF0F5;width: 200px;" >
                    <tbody>
                      <tr>
                        <td class="bg-green text-center" rowspan="2" style="width: 50px;" ><i class="ft-rupiah" ></i></td>
                        <td style="padding-left: 10px;padding-right: 5px;">
                            TOTAL
                        </td>
                    </tr>
                    <tr>
                        <td class="text-right"  style="padding-right: 5px;" >
                            <label class="uang">{{$total}}</label>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="box-body">
            <?php $rownum=1; ?>
            <table class="table table-bordered table-condensed table-striped table-hover" id="table-order" >
                <thead>
                    <tr>
                        <th style="width:50px;" >NO</th>
                        <th class="col-lg-1" >REF#</th>
                        {{-- <th class="col-lg-1" >INV NO.</th> --}}
                        <th class="col-lg-1" >DATE</th>
                        <th>CUSTOMER</th>
                        <th>SALESPERSON</th>
                        <th>TOTAL</th>
                        <th class="col-lg-1" >STATUS</th>
                        <th style="width:65px;" ></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $rownum=1; ?>
                    @foreach($data as $dt)
                        <tr>
                            <td>
                                {{$rownum++}}
                            </td>
                            <td>
                                {{$dt->so_no}}
                            </td>
                            {{-- <td>
                                {{$dt->no_inv}}
                            </td> --}}
                            <td>
                                {{$dt->tgl_formatted}}
                            </td>
                            <td>
                                {{$dt->nama_customer}}
                            </td>
                            <td>
                                {{$dt->salesman}}
                            </td>
                            <td class="uang text-right" >
                                {{$dt->total}}
                            </td>
                            <td class="text-center" >
                                @if($dt->status == 'O')
                                    <label class="label label-warning" >OPEN</label>
                                @elseif($dt->status == 'C')
                                    <label class="label label-danger" >CANCELED</label>
                                @else
                                    <label class="label label-success" >VALIDATED</label>
                                @endif
                            </td>
                            <td>
                                <a class="btn btn-success btn-xs" href="sales/order/edit/{{$dt->id}}" ><i class="fa fa-edit" ></i></a>
                                @if($dt->status == 'O')
                                <a class="btn btn-danger btn-xs btn-delete-so" href="sales/order/delete/{{$dt->id}}" ><i class="fa fa-trash" ></i></a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
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

@stop

@section('scripts')
{!! Html::script('plugins/datatables/jquery.dataTables.min.js') !!}
{!! Html::script('plugins/datatables/dataTables.bootstrap.min.js') !!}
{!! Html::script('plugins/jqueryform/jquery.form.min.js') !!}
{!! Html::script('plugins/datepicker/bootstrap-datepicker.js') !!}
{!! Html::script('plugins/autonumeric/autoNumeric-min.js') !!}

<script type="text/javascript">
(function ($) {
    
    // FILTER WIDGET
    // ==============================================================
    
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
  
    // select filter by change
    $('select[name=filter_by]').change(function(){
      if($(this).val() == 'customer'){
        // show filter by customer
        $('.filter_by_customer').removeClass('hide');
        $('.filter_by_customer').show();
        // hide filter by order date
        $('.filter_by_order_date').hide();

      }else{
        //hide filter by customer
        $('.filter_by_customer').hide();
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

    var TBL_KATEGORI = $('#table-order').DataTable({
        "columns": [
            {className: "text-right"},
            null,
            // null,
            null,
            null,
            null,
            {className: "text-right"},
            null,
            {className: "text-center"}
        ],
         "bSortClasses": false
    });

    // DELETE DATA SALES ORDER
    $('.btn-delete-so').click(function(){
        if(confirm('Anda akan menghapus data ini?')){

        }else{
            return false;
        }
    });
    // END OF DELETE DATA SALES ORDER


})(jQuery);
</script>
@append
