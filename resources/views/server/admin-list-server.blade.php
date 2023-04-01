@extends('layouts.admin')

@section('title')
    <title>Server || Dashboard</title>
@endsection
@section('content')
    <section role="main" class="content-body">
        <header class="page-header">
            <h2>{{ isset($title) ? $title : 'Server List' }}</h2>

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
                <h2 class="panel-title">{{ isset($title) ? $title : 'Server List' }}</h2>
                <div class="panel-actions">
                    <a href="{{ route('admin.insert.server') }}">
                        <button type="button" class="btn btn-primary btn-xs">
                            <i class="fa fa-plus"></i> Add Server
                        </button>
                    </a>
                </div>
            </header>
            <div class="panel-body">
                <table class="table table-bordered table-striped mb-none" id="datatable-default">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Customer</th>
                            <th>Data Center</th>
                            <th>Server IP</th>
                            <th>Server Cost</th>
                            <th>Server Step Cost</th>
                            <th>Sale Plan</th>
                            <th>Is Expired</th>
                            <th>Expired AT</th>
                            <th>Created AT</th>
                            <th>Add Expiry</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $i = 1; @endphp
                        @foreach ($server as $row)
                            <tr>
                                <td>
                                    {{ $i++ }}
                                </td>
                                <td>
                                    {{ $row->name }}
                                </td>
                                <td>
                                    {{ $row->center }}
                                </td>
                                <td>
                                    {{ $row->server_ip }}
                                </td>
                                <td>
                                    {{ $row->server_cost }}
                                </td>
                                <td>
                                    {{ $row->setup_cost }}
                                </td>
                                <td>
                                    {{ $row->sale_price }}
                                </td>
                                <td>
                                    @if (!$row->is_expired)
                                        <span
                                            class="label label-info">{{ dateDiff(date('Y-m-d'), $row->expired_at) }}</span>
                                    @endif
                                    {!! $row->is_expired
                                        ? "<label class='label label-danger'>Expired</label>"
                                        : "<label class='label label-success'>Not Expired</label>" !!}

                                </td>
                                <td>
                                    @if ($row->expired_at)
                                        {{ date('Y-m-d', strtotime($row->expired_at)) }}
                                    @else
                                        Null
                                    @endif
                                </td>
                                <td>
                                    {{ date('Y-m-d H:i', strtotime($row->created_at)) }}
                                </td>
                                <td style="display: flex;    border: none;">
                                    <button id="addexpiry" style="margin: 1px" data-id="{{ $row->serverid }}"
                                        class="btn btn-default btn-xs">Add</button><br />

                                    <button id="renewalServer" style="margin: 1px" data-id="{{ $row->serverid }}"
                                        data-userid="{{ $row->user_id }}" data-packageid="{{ $row->package_id }}"
                                        class="btn btn-info btn-xs">Renew</button>
                                </td>
                                <td>
                                    <button type="button" id="deleteServer" data-id="{{ $row->serverid }}"
                                        class="btn btn-danger btn-xs">
                                        <i class="fa fa-trash-o"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    </section>

    <div class="modal fade" id="modalBootstrapAddExpiry" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                            class="sr-only">Close</span></button>
                    <h4 class="modal-title" id="myModalLabel">Add Expiry</h4>
                </div>
                <form id="expiryform">
                    <div class="modal-body">
                        <div class="form-group">
                            <input type="hidden" class="form-control" id="serverid" name="serverid">
                            <label class="control-label" for="inputDefault">Add New Expiry</label>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </span>
                                {{-- <input type="text" id="expirydate" name="newdate" data-plugin-datepicker
                                    class="form-control" required> --}}
                                <input type="date" id="expirydate" name="newdate" class="form-control"
                                    min="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="saveexpiry">Save</button>
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
    <script src="{{ asset('assets/vendor/jquery-ui/js/jquery-ui-1.10.4.custom.js') }}"></script>
    <script src="{{ asset('assets/javascripts/forms/examples.advanced.form.js') }}" />
    </script>
    <script>
        $(document).ready(function() {

            var dateToday = new Date();
            $(function() {
                $("#expirydate").datepicker({
                    numberOfMonths: 3,
                    showButtonPanel: true,
                    minDate: dateToday
                });
            });

            $("#datatable-default").on("click", "button#addexpiry", function() {
                $("input#serverid").val($(this).data("id"));
                $("div#modalBootstrapAddExpiry").modal('show');
            });

            $("#datatable-default").on("click", "button#renewalServer", function() {
                var id = $(this).data("id");
                var userid = $(this).data("userid");
                var packageid = $(this).data("packageid");
                swal({
                        title: 'Are you sure?',
                        text: 'You want to add 30 days!',
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonClass: "btn-primary",
                        confirmButtonText: "Yes!",
                        closeOnConfirm: false
                    },
                    function(willDelete) {
                        if (willDelete) {
                            var token = $("meta[name='csrf-token']").attr("content");
                            var url = '{{ url('/admin/servers/add/renewal') }}' + '/' + id + '/' +
                                userid + '/' + packageid;
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

            $("#saveexpiry").click(function() {
                var $myForm = $('form#expiryform')
                if (!$myForm[0].checkValidity()) {
                    // If the form is invalid, submit it. The form won't actually submit;
                    // this will just cause the browser to display the native HTML5 error messages.
                    // $myForm.find(':submit').click();
                    $myForm[0].reportValidity();
                    return false;
                }
                var serverid = $("input#serverid").val();
                var expirydate = $("input#expirydate").val();
                var token = $("meta[name='csrf-token']").attr("content");
                var url = '{{ url('/admin/servers/add/expiry') }}';
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        "serverid": serverid,
                        "expirydate": expirydate,
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

            $("#datatable-default").on("click", "button#deleteServer", function() {
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
                            var url = '{{ url('/admin/server/delete') }}' + '/' + id;
                            $.ajax({
                                url: url,
                                type: 'DELETE',
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

        });
    </script>
@endsection
