@extends('layouts.admin')

@section('title')
    <title>Order Server || Dashboard</title>
@endsection
@section('content')
    <section role="main" class="content-body">
        <header class="page-header">
            <h2>Order Server List</h2>

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
                <h2 class="panel-title">Order Server List</h2>
            </header>
            <div class="panel-body">
                <table class="table table-bordered table-striped mb-none" id="datatable-default">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Title</th>
                            <th>Priority</th>
                            <th>Ticket Status</th>
                            <th>Date</th>
                            <th>Is Expired</th>
                            <th>Expired AT</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $i = 1; @endphp
                        @foreach ($server as $row)
                            <tr>
                                <td>
                                    <!-- {{ $row->ticketid }} -->
                                    {{ $i++ }}
                                </td>
                                <td>
                                    <a href="{{ route('admin.customer.view', ['id' => $row->userid]) }}">
                                        {{ $row->username }}
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ route('admin.view.server', ['id' => $row->ticketid]) }}">
                                        {{ $row->tickettitle }} @if ($row->isseen)
                                            <span class="pull-right label label-primary">{{ $row->isseen }}</span>
                                        @endif
                                    </a>
                                </td>
                                <td>
                                    @if ($row->ticketpriorityid == '1')
                                        <label class="label label-primary">{{ $row->ticketpriority }}</label>
                                    @elseif($row->ticketpriorityid == '2')
                                        <label class="label label-info">{{ $row->ticketpriority }}</label>
                                    @else
                                        <label class="label label-warning">{{ $row->ticketpriority }}</label>
                                    @endif
                                </td>
                                <td>
                                    @if ($row->ticketstatus)
                                        <label class="label label-danger">Closed</label>
                                    @else
                                        <label class="label label-success">Open</label>
                                    @endif
                                </td>
                                <td>
                                    {{ date('Y-m-d H:i', strtotime($row->ticketcreated_at)) }}
                                </td>
                                <td>
                                    @if (!$row->is_expired)
                                        {!! $row->is_expired
                                            ? "<label class='label label-danger'>Expired</label>"
                                            : "<label class='label label-success'>Not Expired</label>" !!}
                                    @else
                                        Null
                                    @endif
                                </td>
                                <td>
                                    @if ($row->expired_at)
                                        {{ date('Y-m-d', strtotime($row->expired_at)) }}
                                    @else
                                        Null
                                    @endif
                                </td>

                                <td>
                                    @if ($row->alloted)
                                        <a
                                            href="{{ route('admin.detail.server', [
                                                'id' => $row->serverid,
                                                'type' => $type,
                                            ]) }}">
                                            <button class="btn btn-primary btn-xs">
                                                <i class="fa fa-eye"></i>
                                            </button>
                                        </a>
                                    @endif
                                    @if (!$row->alloted)
                                        <button title="Ticket delete" id="deleteTicket" data-id="{{ $row->ticketid }}"
                                            class="btn btn-danger btn-xs">
                                            <i class="fa fa-trash-o"></i>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
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
            $("#datatable-default").on("click", "button#deleteTicket", function() {
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
                            var url = "{{ url('/admin/delete/order/server') }}" + "/" + id;
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
