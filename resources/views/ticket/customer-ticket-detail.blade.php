@extends('layouts.customer')

@section('title')
    <title>Ticket || Dashboard</title>
@endsection
@section('content')
    <section role="main" class="content-body">
        <header class="page-header">
            <h2>Ticket View</h2>

            <div class="right-wrapper pull-right">
                <ol class="breadcrumbs">
                    <li>
                        <a href="{{ route('home') }}">
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
                    <a href="{{ route('ticket.reply.view', ['id' => $ticket->id]) }}">
                        <button type="button" data-id="{{ $ticket->id }}" class="btn btn-info btn-xs">
                            <i class="fa fa-mail-reply"></i> Reply
                        </button>
                    </a>
                </div>
                <h2 class="panel-title">{{ $ticket->title }}</h2>
            </header>
            <div class="panel-body">
                <input type="hidden" id="ticketid" name="ticketid" value="{{ $ticket->id }}" />
                @foreach ($ticketdetail as $row)
                    @php
                        $userdetail = getUserdetailDependsbyType($row->from_id, $row->user_type);
                    @endphp
                    <div class="row row-eq-height row-none ticket-message">
                        <div class="col-md-3 author-info">
                            <div class="current-user text-center">
                                @php
                                    $path = $row->user_type == '1' ? 'admin' : 'user';
                                    $img = 'uploads/' . $path . '/' . $userdetail->image;
                                    $image = $userdetail->image ? asset($img) : asset('assets/images/!logged-user.jpg');
                                @endphp
                                <img src="{{ $image }}" class="img-circle user-image"
                                    style="border: 5px solid #cccccc; border-radius: 150px; height: 100px; width: 100px;">
                                <h3 class="user-name text-dark m-none">
                                    {{ $userdetail->name }}
                                </h3>
                            </div>
                        </div>
                        <div class="col-md-9 message-content">
                            <span title="">Posted on {{ $row->created_at }}</span>
                            <span class="pull-right">#{{ $row->id }}</span>
                            <p class="content">{!! $row->message !!}</p>
                        </div>
                    </div>
                    <hr>
                @endforeach
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
