@extends('layouts.admin')

@section('title')
    <title>Customer || Dashboard</title>
@endsection
@section('content')
    <section role="main" class="content-body">
        <header class="page-header">
            <h2>Customer View</h2>

            <div class="right-wrapper pull-right">
                <ol class="breadcrumbs">
                    <li>
                        <a href="{{ route('admin.home') }}">
                            <i class="fa fa-home"></i>
                        </a>
                    </li>
                    <li><span>Dashboard</span></li>
                </ol>

                <a class="sidebar-right-toggle"><i class="fa fa-chevron-left"></i></a>
            </div>
        </header>

        <section class="panel">
            <header class="panel-heading">
                <div class="panel-actions">
                    @if ($customers->is_block)
                        <button type="button" id="statusCustomer" data-status="0"
                            class="btn btn-primary btn-md fa fa-unlock" title="unblock"
                            data-customerid="{{ $customers->customerid }}"></button>
                    @else
                        <button type="button" id="statusCustomer" data-status="1"
                            class="btn btn-danger btn-md fa fa-unlock-alt" title="block"
                            data-customerid="{{ $customers->customerid }}"></button>
                    @endif
                </div>
                <h2 class="panel-title">Customer Personal Detail</h2>
            </header>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-bordered mb-none">
                        <tbody>
                            <tr>
                                <th>Name</th>
                                <td>{{ $customers->name }}</td>
                                <th>Email</th>
                                <td>{{ $customers->email }}</td>
                            </tr>
                            <tr>
                                <th>Whatsapp</th>
                                <td>{{ $customers->whatsapp }}</td>
                                <th>Skype</th>
                                <td>{{ $customers->skype }}</td>
                            </tr>
                            <tr>
                                <th>Is active</th>
                                <td>
                                    @if ($customers->is_block)
                                        <label class="label label-danger">De-actived</label>
                                    @else
                                        <label class="label label-success">Active</label>
                                    @endif
                                </td>
                                <th>Joined</th>
                                <td>{{ date('Y-m-d H:i', strtotime($customers->created_at)) }}</td>
                            </tr>
                            <tr>
                                <th>Is Deleted</th>
                                <td>
                                    @if ($customers->is_deleted)
                                        <label class="label label-danger">Deleted</label>
                                    @else
                                        <label class="label label-success">Active</label>
                                    @endif
                                </td>
                                <th>Deleted at</th>
                                <td>
                                    @if ($customers->is_deleted_at)
                                        {{ $customers->is_deleted_at == '' || $customers->is_deleted_at == '0000-00-00' ? '' : date('Y-m-d H:i', strtotime($customers->is_deleted_at)) }}
                                    @endif
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <section class="panel">
            <header class="panel-heading">
                <div class="panel-actions">
                    <button type="button" id="loadAddamountModal" class="btn btn-primary btn-md fa fa-plus" title="block"
                        data-customerid="{{ $customers->customerid }}">
                        Add Amount
                    </button>
                    <button type="button" id="loadDeductamountModal" class="btn btn-danger btn-md fa fa-minus"
                        title="block" data-customerid="{{ $customers->customerid }}">
                        Deduct Amount
                    </button>
                </div>
                <h2 class="panel-title">Customer Account Detail</h2>
            </header>
            <div class="panel-body">
                <div class="row  text-center">
                    <div class="col-3 col-xs-3">
                        <h4>Total Amount</h4>
                        <h4>$ {{ $customers->totalamount ? $customers->totalamount : 0 }}</h4>
                    </div>
                    <div class="col-3 col-xs-3">
                        <h4>Added Amount</h4>
                        <h4>$ {{ $customers->addedamount ? $customers->addedamount : 0 }}</h4>
                    </div>
                    <div class="col-3 col-xs-3">
                        <h4>Pending Amount</h4>
                        <h4>$ {{ $customers->pendingamount ? $customers->pendingamount : 0 }}</h4>
                    </div>
                    <div class="col-3 col-xs-3">
                        <h4>Deducted Amount</h4>
                        <h4>$ {{ $customers->deductedamount ? $customers->deductedamount : 0 }}</h4>
                    </div>
                </div>
            </div>
        </section>

        <div class="modal fade" id="addAmountModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span
                                aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <h4 class="modal-title" id="myModalLabel">Add Category</h4>
                    </div>
                    <form id="addAmountForm" method="POST">
                        <div class="modal-body">
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="inputDefault">Amount</label>
                                <div class="col-md-12">
                                    <input type="hidden" class="form-control" id="addcustomerid" name="addcustomerid"
                                        required="">
                                    <input type="number" class="form-control" id="addamount" name="addamount"
                                        required="">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="addAmountFormSubmit" class="btn btn-primary">Save</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="deductAmountModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span
                                aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <h4 class="modal-title" id="myModalLabel">Add Category</h4>
                    </div>
                    <form id="deductAmountForm" method="POST">
                        <div class="modal-body">
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="inputDefault">Amount</label>
                                <div class="col-md-12">
                                    <input type="hidden" class="form-control" id="deductcustomerid"
                                        name="deductcustomerid" required="">
                                    <input type="number" class="form-control" id="deductamount" name="deductamount"
                                        required="">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="deductAmountFormSubmit" class="btn btn-primary">Save</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('script')
    <script src="{{ asset('assets/javascripts/tables/examples.datatables.default.js') }}"></script>
    <script src="{{ asset('assets/javascripts/tables/examples.datatables.row.with.details.js') }}"></script>
    <script src="{{ asset('assets/javascripts/tables/examples.datatables.tabletools.js') }}"></script>

    <!-- Specific Page Vendor -->
    <script src="{{ asset('assets/vendor/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/vendor/jquery-datatables/media/js/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets/vendor/jquery-datatables/extras/TableTools/js/dataTables.tableTools.min.js') }}">
    </script>
    <script src="{{ asset('assets/vendor/jquery-datatables-bs3/assets/js/datatables.js') }}"></script>

    <script>
        $(document).ready(function() {

            $("button#statusCustomer").click(function() {
                event.preventDefault();
                var id = $(this).data("customerid");
                var status = $(this).data("status");
                var text = (status) ? "You want to block the user" : "You want to un-block the user";
                swal({
                        title: 'Are you sure?',
                        text: text,
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonClass: "btn-primary",
                        confirmButtonText: "Yes!",
                        closeOnConfirm: false
                    },
                    function() {
                        var token = $("meta[name='csrf-token']").attr("content");
                        var url = '{{ url('admin/customer/status') }}' + '/' + id + '/' + status;
                        $.ajax({
                            url: url,
                            type: 'GET',
                            // data: {
                            //     "id": id,
                            //     "_token": token,
                            // },
                            beforeSend: function() {
                                $(".loader").show();
                            },
                            complete: function() {
                                $(".loader").hide();
                            },
                            success: function(response) {
                                var result = jQuery.parseJSON(response);
                                var typeOfResponse = result['type'];
                                var res = result['msg'];
                                if (typeOfResponse == 0) {
                                    swal('Error', res, 'error');
                                } else if (typeOfResponse == 1) {
                                    swal({
                                            title: 'Success',
                                            text: res,
                                            icon: 'success',
                                            type: 'success',
                                            showCancelButton: false, // There won't be any cancel button
                                            showConfirmButton: true // There won't be any confirm button
                                        },
                                        function() {
                                            location.reload();
                                        });
                                }
                            }
                        });
                    });
            });

            $("button#loadAddamountModal").click(function() {
                $("input#addcustomerid").val($(this).data('customerid'));
                $('div#addAmountModal').modal('show');
            });

            $("button#loadDeductamountModal").click(function() {
                $("input#deductcustomerid").val($(this).data('customerid'));
                $('div#deductAmountModal').modal('show');
            });

            $("#addAmountFormSubmit").click(function() {
                var $myForm = $('form#addAmountForm')
                if (!$myForm[0].checkValidity()) {
                    // If the form is invalid, submit it. The form won't actually submit;
                    // this will just cause the browser to display the native HTML5 error messages.
                    // $myForm.find(':submit').click();
                    $myForm[0].reportValidity();
                    return false;
                }
                var customerid = $("input#addcustomerid").val();
                var amount = $("input#addamount").val();
                var token = $("meta[name='csrf-token']").attr("content");
                var url = '{{ url('/admin/customers/add/amount') }}';
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        "customerid": customerid,
                        "amount": amount,
                        "_token": token,
                    },
                    beforeSend: function() {
                        $(".loader").show();
                    },
                    complete: function() {
                        $(".loader").hide();
                    },
                    success: function(response) {
                        var result = jQuery.parseJSON(response);
                        var typeOfResponse = result['type'];
                        var res = result['msg'];
                        if (typeOfResponse == 0) {
                            swal('Error', res, 'error');
                        } else if (typeOfResponse == 1) {
                            swal({
                                title: 'Success',
                                text: res,
                                icon: 'success',
                                type: 'success',
                                showCancelButton: false, // There won't be any cancel button
                                showConfirmButton: true // There won't be any confirm button
                            }, function(oK) {
                                if (oK) {
                                    location.reload();
                                }
                            });
                        }
                    }
                });
            });

            $("#deductAmountFormSubmit").click(function() {
                var $myForm = $('form#deductAmountForm')
                if (!$myForm[0].checkValidity()) {
                    // If the form is invalid, submit it. The form won't actually submit;
                    // this will just cause the browser to display the native HTML5 error messages.
                    // $myForm.find(':submit').click();
                    $myForm[0].reportValidity();
                    return false;
                }
                var customerid = $("input#deductcustomerid").val();
                var amount = $("input#deductamount").val();
                var token = $("meta[name='csrf-token']").attr("content");
                var url = '{{ url('/admin/customers/deduct/amount') }}';
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        "customerid": customerid,
                        "amount": amount,
                        "_token": token,
                    },
                    beforeSend: function() {
                        $(".loader").show();
                    },
                    complete: function() {
                        $(".loader").hide();
                    },
                    success: function(response) {
                        var result = jQuery.parseJSON(response);
                        var typeOfResponse = result['type'];
                        var res = result['msg'];
                        if (typeOfResponse == 0) {
                            swal('Error', res, 'error');
                        } else if (typeOfResponse == 1) {
                            swal({
                                title: 'Success',
                                text: res,
                                icon: 'success',
                                type: 'success',
                                showCancelButton: false, // There won't be any cancel button
                                showConfirmButton: true // There won't be any confirm button
                            }, function(oK) {
                                if (oK) {
                                    location.reload();
                                }
                            });
                        }
                    }
                });
            });
        });
    </script>
@endsection
