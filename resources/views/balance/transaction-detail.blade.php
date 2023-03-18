@extends('layouts.customer')

@section('title')
    <title>Balance || Dashboard</title>
@endsection
@section('content')
    <section role="main" class="content-body">
        <header class="page-header">
            <h2>Balance View</h2>

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
                <h2 class="panel-title">Transactions Detail</h2>
            </header>
            <div class="panel-body">
                <input type="hidden" id="ticketid" name="ticketid" value="{{ $sales->ticket_id }}" />
                <div class="table-responsive">
                    <table class="table table-bordered mb-none">
                        <tbody>
                            <tr>
                                <th>Paid By</th>
                                <td>{{ $sales->name }}</td>
                                <th>Date</th>
                                <td>{{ date('Y-m-d H:i', strtotime($sales->created_at)) }}</td>
                            </tr>
                            <tr>
                                <th>Payment By</th>
                                <td>{{ $sales->paymenttitle }}</td>
                                <th>Transaction No</th>
                                <td>{{ $sales->transactionid }}</td>
                            </tr>
                            <tr>
                                <th>Amount By</th>
                                <td>{{ $sales->amount }}</td>
                                <th>Status</th>
                                <td>
                                    @if ($sales->status == '-1')
                                        <label class="label label-danger">Rejected</label>
                                    @elseif($sales->status == '0')
                                        <label class="label label-info">Pending</label>
                                    @else
                                        <label class="label label-success">Accpeted</label>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Description</th>
                                <td>{{ $sales->description }}</td>
                                <th>File</th>
                                <td>
                                    <a href="{{ asset('uploads/payment/') . '/' . $sales->transectionimage }}"
                                        data-plugin-lightbox data-plugin-options='{ "type":"image" }'>
                                        <img src="{{ asset('uploads/payment/') . '/' . $sales->transectionimage }}"
                                            width="145">
                                    </a>
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
