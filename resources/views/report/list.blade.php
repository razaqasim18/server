@extends('layouts.admin')

@section('title')
    <title>Server || Dashboard</title>
@endsection
@section('content')
    <section role="main" class="content-body">
        <header class="page-header">
            <h2>Server Payment</h2>

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
                <h2 class="panel-title">Server Payment</h2>
                <div class="panel-actions">
                    <h4>Total Amount: <span id="amountSpan"></span></h4>
                </div>
            </header>
            <div class="panel-body">
                <div class="row" style="margin-bottom:2%">
                    <div class="col-md-5">
                        <input type="date" class="form-control" id="start_date" name="start_date" />
                    </div>
                    <div class="col-md-5">
                        <input type="date" class="form-control" id="end_date" name="end_date" />
                    </div>
                    <div class="offset-1 col-md-1">
                        <input type="submit" class="btn btn-primary" value="search" id="search" />
                    </div>
                </div>
                <table class="table table-bordered table-striped mb-none" id="datatable-default" width="100%">
                    <thead>
                        <tr>
                            <th>Customer</th>
                            <th>Server IP</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Approve BY</th>
                            <th>Created</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- @php $i = 1; @endphp
                        @foreach ($report as $row)
                            <tr>
                                <td>
                                    {{ $i++ }}
                                </td>
                                <td>
                                    {{ $row->name }}
                                </td>
                                <td>
                                    {{ $row->server_ip }}
                                </td>
                                <td>
                                    {{ $row->amount }}
                                </td>
                                <td>
                                    {{ $row->status ? 'credited' : 'deducted' }}
                                </td>
                                <td>
                                    {{ $row->approve_by == '0' ? 'System' : 'User' }}
                                </td>
                                <td>
                                    {{ date('Y-m-d', strtotime($row->created_at)) }}
                                </td>
                            </tr>
                        @endforeach --}}
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
    <script src="{{ asset('assets/vendor/jquery-datatables/extras/TableTools/js/dataTables.tableTools.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/jquery-datatables-bs3/assets/js/datatables.js') }}"></script>
    <script src="{{ asset('assets/vendor/jquery-ui/js/jquery-ui-1.10.4.custom.js') }}"></script>
    <script src="{{ asset('assets/javascripts/forms/examples.advanced.form.js') }}" />
    </script>
    <script>
        $(document).ready(function() {
            $('input#start_date').change(function() {
                console.log($(this).val());
                $("input#start_date").attr({
                    'min': $(this).val()
                });
            });

            $(document).on('change', "input#start_date", function() {
                $("input#end_date").attr("min", $(this).val());
            });

            $('input#search').click(function() {
                $('#datatable-default').DataTable().draw(true);
                getServerPaymentTotal();
            });


            if (!$.fn.DataTable.isDataTable('#datatable-default')) {
                // Initialize the DataTable instance
                getServerDetail();
                getServerPaymentTotal();
            } else {
                // Destroy the existing DataTable instance before initializing it again
                $('#datatable-default').DataTable().destroy();
                getServerDetail();
                getServerPaymentTotal();
            }

            function getServerPaymentTotal() {
                console.log($('#start_date').val());
                let url = "{{ url('/admin/report/server/payment/count') }}";
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        "_token": '{{ csrf_token() }}',
                        "start_date": $('#start_date').val(),
                        "end_date": $('#end_date').val(),
                    },
                    beforeSend: function() {
                        $(".loader").show();
                    },
                    complete: function() {
                        $(".loader").hide();
                    },
                    success: function(response) {
                        var result = jQuery.parseJSON(response);
                        $("span#amountSpan").html(result[0].amount);
                    }
                });
            }

            function getServerDetail() {
                $('#datatable-default').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ url('/admin/report/server/payment/filter') }}",
                        type: 'GET',
                        data: function(d) {
                            d.start_date = $('#start_date').val();
                            d.end_date = $('#end_date').val();
                        },
                        beforeSend: function() {
                            $(".loader").show();
                        },
                        complete: function() {
                            $(".loader").hide();
                        },
                    },
                    columns: [{
                            data: 'name',
                            name: 'name',
                        },
                        {
                            data: 'server_ip',
                            name: 'server_ip'
                        },
                        {
                            data: 'amount',
                            name: 'amount'
                        },
                        {
                            data: 'status',
                            name: 'status'
                        },
                        {
                            data: 'approve_by',
                            name: 'approve_by'
                        },
                        {
                            data: 'created_at',
                            name: 'created_at'
                        },
                    ],
                    order: [
                        [0, 'desc']
                    ]
                });

            }
        });
    </script>
@endsection
