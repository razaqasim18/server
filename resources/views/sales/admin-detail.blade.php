@extends('layouts.admin')

@section('title')
    <title>Sales || Dashboard</title>
@endsection
@section('content')
    <section role="main" class="content-body">
        <header class="page-header">
            <h2>Sales View</h2>

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
                <div class="panel-actions">
                    @if ($sales->status == '0')
                        <button type="button" id="approveTransection" class="btn btn-primary btn-xs" title="approve"
                            data-id="{{ $sales->transactionsID }}">
                            <i class="fa fa-check"></i>
                            Approve Transaction</button>
                        <button type="button" id="rejectTransection" class="btn btn-danger btn-xs" title="reject"
                            data-id="{{ $sales->transactionsID }}">
                            <i class="fa fa-times"></i>
                            Reject Transaction</button>
                    @endif
                </div>
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
                                <td style="width: 40%;">
                                    @if ($sales->transectionimage != '')
                                        <a href="{{ asset('uploads/payment/') . '/' . $sales->transectionimage }}"
                                            data-plugin-lightbox data-plugin-options='{ "type":"image" }'>
                                            <img src="{{ asset('uploads/payment/') . '/' . $sales->transectionimage }}"
                                                width="145">
                                        </a>
                                    @else
                                        <img src="{{ $sales->paymentslug == 'stripe' ? asset('stripe.png') : '' }}"
                                            width="145">
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

            const url = "{{ url('admin/ticket/seen') }}" + "/" + $("input#ticketid").val();
            $.ajax({
                url: url,
                type: "GET",
                // data: fd,
                // dataType: "json",
                cache: false,
                contentType: false,
                processData: false,
                success: function() {}
            });

            $("button#approveTransection").click(function() {
                event.preventDefault();
                var id = $(this).data("id");
                swal({
                        title: 'Are you sure?',
                        text: 'Want to approve this transection!',
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonClass: "btn-primary",
                        confirmButtonText: "Yes, approve it!",
                        closeOnConfirm: false
                    },
                    function() {
                        var token = $("meta[name='csrf-token']").attr("content");
                        var url = '{{ url('admin/approval/sales/') }}' + '/' + id;
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

            $("button#rejectTransection").click(function() {
                event.preventDefault();
                var id = $(this).data("id");
                swal({
                        title: 'Are you sure?',
                        text: 'Want to reject this transection!',
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonClass: "btn-primary",
                        confirmButtonText: "Yes, reject it!",
                        closeOnConfirm: false
                    },
                    function() {
                        var token = $("meta[name='csrf-token']").attr("content");
                        var url = '{{ url('admin/reject/sales/') }}' + '/' + id;
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
        });
    </script>
@endsection
