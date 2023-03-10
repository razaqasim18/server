@php $layout = isset($url) ? 'layouts.admin' : 'layouts.customer'; @endphp
@extends($layout)

@section('title')
    <title>Order Server || Dashboard</title>
@endsection
@section('content')
    <section role="main" class="content-body">
        <header class="page-header">
            <h2>Order Server View</h2>

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
        <!-- transection detail -->
        <section class="panel">
            <header class="panel-heading">
                <h2 class="panel-title">Order Server Detail</h2>
                <div class="panel-actions">
                    @php
                        $type = isset($type) ? $type : '';
                        $adminurl = "admin.$type.server";
                        $url = isset($url) ? route($adminurl) : route('order.server.list');
                    @endphp
                    <a href="{{ $url }}">
                        <button class="btn btn-primary btn-xs">
                            <i class="fa fa-undo"></i>
                            Back
                        </button>
                    </a>
                </div>
            </header>
            <div class="panel-body">
                <input type="hidden" id="ticketid" name="ticketid" value="{{ $server->ticket_id }}" />
                <div class="table-responsive">
                    <table class="table table-bordered mb-none">
                        <tbody>
                            <tr>
                                <th>Package Name</th>
                                <td>{{ $server->package }}</td>
                                <th>Date</th>
                                <td>{{ date('Y-m-d H:i', strtotime($server->created_at)) }}</td>
                            </tr>
                            <tr>
                                <th>Server IP</th>
                                <td>{{ $server->server_ip }}</td>
                                <th>Data Center</th>
                                <td>{{ $server->center }}</td>
                            </tr>
                            <tr>
                                <th>Sale Price</th>
                                <td>$ {{ $server->sale_price }}</td>
                                <th>Server Cost</th>
                                <td>$ {{ $server->server_cost }}</td>
                            </tr>
                            <tr>
                                <th>Experied At</th>
                                <td>
                                    @if ($server->is_expired)
                                        {{ date('Y-m-d', strtotime($server->expired_at)) }}
                                    @else
                                        Null
                                    @endif
                                </td>
                                <th>Is Experied</th>
                                <td>
                                    @if ($server->is_expired)
                                        <label class="label label-danger">Expired</label>
                                    @else
                                        <label class="label label-success">Not Expired</label>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
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
            var timeStamp = new Date().getTime();
            // const url = "{{ url('/sales/seen/count/') }}" + "/" + $("input#ticketid").val();
            const url = "{{ url('/ticket/seen') }}" + "/" + $("input#ticketid").val();
            $.ajax({
                url: url,
                type: "GET",
                cache: false,
                contentType: false,
                processData: false,
                success: function() {}
            });
        });
    </script>
@endsection
