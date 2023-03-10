@extends('layouts.admin')

@section('title')
    <title>Customers || Dashboard</title>
@endsection
@section('content')
    <section role="main" class="content-body">
        <header class="page-header">
            <h2>Customers List</h2>

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
                <h2 class="panel-title">Customers List</h2>
            </header>
            <div class="panel-body">
                <table class="table table-bordered table-striped mb-none" id="datatable-default">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Balance</th>
                            <th>Total Servers</th>
                            <th>Mail</th>
                            <th>Whatsapp</th>
                            <th>Skype</th>
                            <th>Status</th>
                            <th>Deleted</th>
                            <th>Join At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $i = 1; @endphp
                        @foreach ($customers as $row)
                            <tr>
                                <td class="sorting_desc">
                                    {{ $i++ }}
                                    <!-- {{ $row->customerid }} -->
                                </td>
                                <td>
                                    {{ $row->name }}
                                </td>
                                <td>
                                    $ {{ $row->addedamount }}
                                    <br />
                                    <button type="button" id="loadAddamountModal" class="btn btn-primary btn-xs"
                                        data-customerid="{{ $row->customerid }}">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                    <button type="button" id="loadDeductamountModal" class="btn btn-danger btn-xs"
                                        data-customerid="{{ $row->customerid }}">
                                        <i class="fa fa-minus"></i>
                                    </button>
                                </td>
                                <td>
                                    0
                                </td>
                                <td>
                                    {{ $row->email }}
                                </td>
                                <td>
                                    @if ($row->whatsapp)
                                        {{ $row->whatsapp }}
                                    @else
                                        <button id="addwhatapp" data-id="{{ $row->id }}"
                                            class="btn btn-default btn-xs">Add</button>
                                    @endif
                                </td>
                                <td>
                                    @if ($row->skype)
                                        {{ $row->skype }}
                                    @else
                                        <button id="addskype" data-id="{{ $row->id }}"
                                            class="btn btn-default btn-xs">Add</button>
                                    @endif
                                </td>
                                <td>
                                    @if ($row->is_block)
                                        <label class="label label-danger">Block</label>
                                    @else
                                        <label class="label label-success">Active</label>
                                    @endif
                                </td>
                                <td>
                                    @if ($row->is_deleted)
                                        <label class="label label-danger">Deleted</label>
                                    @else
                                        <label class="label label-success">Active</label>
                                    @endif
                                </td>
                                <td>
                                    {{ date('Y-m-d', strtotime($row->created_at)) }}
                                </td>
                                <td>
                                    <a href="{{ route('admin.customer.view', ['id' => $row->customerid]) }}"><button
                                            class="btn btn-primary btn-xs"><i class="fa fa-eye"></i></button>
                                    </a>
                                    <button id="deleteUser" data-id="{{ $row->customerid }}"
                                        class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    </section>

    <!-- Whatapp modal -->
    <div class="modal fade" id="modalBootstrapWhatsapp" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                            class="sr-only">Close</span></button>
                    <h4 class="modal-title" id="myModalLabel">Add Whatsapp?</h4>
                </div>
                <form id="whatsappform">
                    <div class="modal-body">
                        <div class="form-group">
                            <input type="hidden" class="form-control" id="customerid" name="customerid">
                            <label class="control-label" for="inputDefault">Add Whatsapp</label>
                            <input type="number" class="form-control" id="whatsapp" name="whatsapp" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="savewhatsapp">Save</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- skype modal -->
    <div class="modal fade" id="modalBootstrapSkype" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                            class="sr-only">Close</span></button>
                    <h4 class="modal-title" id="myModalLabel">Add Skype?</h4>
                </div>
                <form id="skypeform">
                    <div class="modal-body">
                        <div class="form-group">
                            <input type="hidden" class="form-control" id="customerid" name="customerid">
                            <label class="control-label" for="inputDefault">Add Skype</label>
                            <input type="text" class="form-control" id="skype" name="skype" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="saveskype">Save</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- add amount --}}
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

    {{-- deduct amount --}}
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
                                <input type="hidden" class="form-control" id="deductcustomerid" name="deductcustomerid"
                                    required="">
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
            $("#datatable-default").on("click", "button#addwhatapp", function() {
                $("input#customerid").val($(this).data('id'));
                $("div#modalBootstrapWhatsapp").modal('show');
            });

            $("#datatable-default").on("click", "button#addskype", function() {
                $("input#customerid").val($(this).data('id'));
                $("div#modalBootstrapSkype").modal('show');
            });


            $("#datatable-default").on("click", "button#savewhatsapp", function() {
                var $myForm = $('form#whatsappform')
                if (!$myForm[0].checkValidity()) {
                    // If the form is invalid, submit it. The form won't actually submit;
                    // this will just cause the browser to display the native HTML5 error messages.
                    // $myForm.find(':submit').click();
                    $myForm[0].reportValidity();
                    return false;
                }
                var url = '{{ url('admin/customer/add/whatsapp') }}';
                var token = $("meta[name='csrf-token']").attr("content");
                var id = $("input#customerid").val();
                var whatsapp = $("input#whatsapp").val();
                var fd = new FormData();
                fd.append('customerid', id);
                fd.append('whatsapp', whatsapp);
                fd.append('_token', token);
                $.ajax({
                    url: url,
                    type: "POST",
                    data: fd,
                    // dataType: "json",
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        var result = jQuery.parseJSON(response);
                        var typeOfResponse = result['type'];
                        var res = result['msg'];
                        if (typeOfResponse == 0) {
                            swal('Error', res, 'error');
                        } else if (typeOfResponse == 1) {
                            $("div#modalBootstrapWhatsapp").modal('hide');
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


            $("#datatable-default").on("click", "button#saveskype", function() {
                var $myForm = $('form#skypeform')
                if (!$myForm[0].checkValidity()) {
                    // If the form is invalid, submit it. The form won't actually submit;
                    // this will just cause the browser to display the native HTML5 error messages.
                    // $myForm.find(':submit').click();
                    $myForm[0].reportValidity();
                    return false;
                }
                var url = '{{ url('admin/customer/add/skype') }}';
                var token = $("meta[name='csrf-token']").attr("content");
                var id = $("input#customerid").val();
                var skype = $("input#skype").val();
                var fd = new FormData();
                fd.append('customerid', id);
                fd.append('skype', skype);
                fd.append('_token', token);
                $.ajax({
                    url: url,
                    type: "POST",
                    data: fd,
                    // dataType: "json",
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        var result = jQuery.parseJSON(response);
                        var typeOfResponse = result['type'];
                        var res = result['msg'];
                        if (typeOfResponse == 0) {
                            swal('Error', res, 'error');
                        } else if (typeOfResponse == 1) {
                            $("div#modalBootstrapSkype").modal('hide');
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

            $("#datatable-default").on("click", "button#deleteUser", function() {
                var id = $(this).data("id");
                swal({
                        title: 'Are you sure?',
                        text: 'Once deleted, you will not be able to recover this action!',
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonClass: "btn-primary",
                        confirmButtonText: "Yes!",
                        closeOnConfirm: false
                    },
                    function(willDelete) {
                        if (willDelete) {
                            var token = $("meta[name='csrf-token']").attr("content");
                            var url = '{{ url('/admin/customer/delete') }}' + '/' + id;
                            $.ajax({
                                url: url,
                                type: 'POST',
                                data: {
                                    "id": id,
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
                        }
                    });
            });

            // amount
            $("#datatable-default").on("click", "button#loadAddamountModal", function() {
                $("input#addcustomerid").val($(this).data('customerid'));
                jQuery.noConflict();
                $('div#addAmountModal').modal('show');
            });

            $("#datatable-default").on("click", "button#loadDeductamountModal", function() {
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
