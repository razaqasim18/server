@php
    $type = $type == 'admin' ? 'layouts.admin' : 'layouts.customer';
@endphp
@extends($type)

@section('title')
    <title>Server || Dashboard</title>
@endsection
@section('content')
    <section role="main" class="content-body">
        <header class="page-header">
            <h2>Server List</h2>

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
                <h2 class="panel-title">Server List</h2>
            </header>
            <div class="panel-body">
                <table class="table table-bordered table-striped mb-none" id="datatable-default">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Server IP</th>
                            <th>Is Expired</th>
                            <th>Expired AT</th>
                            <th>Created AT</th>
                            <th>Sales Plan</th>
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
                                    {{ $row->server_ip }}
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
                                    {{ date('Y-m-d H:i', strtotime($row->created_at)) }}
                                </td>
                                <td>
                                    {{ $row->package }}
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

    {{-- <script>
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
    </script> --}}
@endsection
