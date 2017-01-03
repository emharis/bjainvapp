 @extends('layouts.master')

@section('styles')
<!--Bootsrap Data Table-->
<link rel="stylesheet" href="plugins/datatables/dataTables.bootstrap.css">

@append

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Supplier Bills
    </h1>
</section>

<!-- Main content -->
<section class="content">

    <!-- Default box -->
    <div class="box box-solid">
        <div class="box-header with-border" >
            <h3 class="box-title" >Account Payable</h3>
            <div class="pull-right" >
                <table style="background-color: #ECF0F5;width: 200px;" >
                    <tbody>
                      <tr>
                        <td class="bg-orange text-center" rowspan="2" style="width: 50px;" ><i class="ft-rupiah" ></i></td>
                        <td style="padding-left: 10px;padding-right: 5px;">
                            TOTAL
                        </td>
                    </tr>
                    <tr>
                        <td class="text-right"  style="padding-right: 5px;" >
                            <label class="uang">{{$total_payable}}</label>
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
                        <th style="width:30px;" >NO</th>
                        <th>SUPPLIER</th>
                        <th>REF#</th>
                        <th>BILL DATE</th>
                        <th>SUPPLIER REF#</th>
                        <th>DUE DATE</th>
                        <th>PO REF#</th>
                        <th>TOTAL</th>
                        <th>TO PAY</th>
                        <th>STATUS</th>
                        <th style="width:30px;" ></th>
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
                                {{-- supplier --}}
                                {{$dt->supplier}}
                            </td>
                            <td>
                                {{-- ref# --}}
                                {{$dt->bill_no}}
                            </td>
                            <td>
                                {{-- bill date --}}
                                {{$dt->bill_date_formatted}}
                            </td>
                            <td>
                                {{-- supplier ref# --}}
                                {{$dt->no_inv}}
                            </td>
                            <td>
                                {{-- due date --}}
                                {{$dt->due_date_formatted}}
                            </td>
                            <td>
                                {{-- po ref# --}}
                                {{$dt->po_num}}
                            </td>
                            <td class="text-right uang" >
                                {{$dt->total}}
                            </td>
                            <td class="text-right uang" >
                                {{$dt->amount_due}}
                            </td>
                            <td class="text-center" >
                                @if($dt->status == 'O')
                                    <label class="label label-warning" >Open</label>
                                @else
                                    <label class="label label-success" >Paid</label>
                                @endif
                            </td>
                            <td class="text-center" >
                                <a class="btn btn-success btn-xs" href="invoice/supplier-bill/show/{{$dt->id }}" ><i class="fa fa-edit" ></i></a>
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
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables/dataTables.bootstrap.min.js"></script>
<script src="plugins/jqueryform/jquery.form.min.js" type="text/javascript"></script>
{!! Html::script('plugins/autonumeric/autoNumeric-min.js') !!}

<script type="text/javascript">
(function ($) {
    //required checkbox
    var requiredCheckboxes = $('.order_jual');
    requiredCheckboxes.change(function () {
        if (requiredCheckboxes.is(':checked')) {
            requiredCheckboxes.removeAttr('required');
        } else {
            requiredCheckboxes.attr('required', 'required');
        }
    });

    // SET AUTONUMERIC
  $('.uang').autoNumeric('init',{
        vMin:'0.00',
        vMax:'9999999999.00'
    });
  $('.uang').each(function(){
    $(this).autoNumeric('set',$(this).autoNumeric('get'));
  });

    var TBL_KATEGORI = $('#table-order').DataTable({
        // "columns": [
        //     {className: "text-right"},
        //     null,
        //     null,
        //     null,
        //     null,
        //     null,
        //     null,
        //     null,
        //     {className: "text-right"},
        //     null,
        //     {className: "text-center"}
        // ],
        //  "bSortClasses": false
    });


})(jQuery);
</script>
@append
