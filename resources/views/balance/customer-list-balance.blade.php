@extends('layouts.customer')

@section('title')
    <title>Balance || Dashboard</title>
@endsection
@section('content')
    <section role="main" class="content-body">
        <header class="page-header">
            <h2>Balance List</h2>

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
                <h2 class="panel-title">Balance List</h2>
            </header>
            <div class="panel-body">
                <table class="table table-bordered table-striped mb-none" id="datatable-default">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sales as $row)
                            <tr>
                                <td>
                                    {{ $row->ticketid }}
                                </td>
                                <td>
                                    <a href="{{ route('ticket.view', ['id' => $row->ticketid]) }}">
                                        {{ $row->tickettitle }} @if ($row->isseen)
                                            <span class="pull-right label label-primary">{{ $row->isseen }}</span>
                                        @endif
                                    </a>
                                </td>
                                <td>
                                    @if ($row->transstatus == '-1')
                                        <label class="label label-danger">Closed</label>
                                    @elseif($row->transstatus == '1')
                                        <label class="label label-success">Approved</label>
                                    @else
                                        <label class="label label-info">Pending</label>
                                    @endif
                                </td>
                                <td>
                                    {{ date('Y-m-d H:i', strtotime($row->ticketcreated_at)) }}
                                </td>
                                <td>
                                    <a title="Transaction detail"
                                        href="{{ route('balance.transaction.view', ['id' => $row->transid]) }}"><button
                                            class="btn btn-primary btn-xs"><i class="fa fa-eye"></i></button>
                                    </a>
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

        });
    </script>
@endsection
