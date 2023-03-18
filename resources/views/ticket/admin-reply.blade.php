@extends('layouts.admin')

@section('title')
    <title>Ticket || Dashboard</title>
@endsection
@section('content')
    <section role="main" class="content-body">
        <header class="page-header">
            <h2>Reply</h2>
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
                    <a href="{{ route('admin.ticket.view', ['id' => $ticket->id]) }}">
                        <button class="btn btn-primary btn-xs">
                            <i class="fa fa-undo"></i>
                            Back
                        </button>
                    </a>
                </div>
                <h2 class="panel-title">Add Reply</h2>
            </header>
            <form class="form-horizontal form-bordered" method="POST"
                action="{{ route('admin.ticket.message.reply', ['id' => $ticket->id]) }}">
                <div class="panel-body">
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    <input type="hidden" id="ticketid" name="ticketid" value="{{ $ticket->id }}" />
                    @csrf
                    <div class="form-group">
                        <!-- <label class="col-md-3 control-label" for="inputDefault">Password</label> -->
                        <div class="col-md-12">
                            <textarea name="message" id="summernote"></textarea>
                            @error('message')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>
                <footer class="panel-footer">
                    <div class="row">
                        <div class="col-sm-12 text-center">
                            <button class="btn btn-primary">Submit</button>
                            <button type="reset" class="btn btn-default">Reset</button>
                        </div>
                    </div>
                </footer>
            </form>
        </section>

    </section>
@endsection
@section('script')
    <!-- include summernote css/js -->
    <!-- <link href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" rel="stylesheet"> -->
    <!-- <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script> -->
    <!-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script> -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
    <script>
        $(document).ready(function() {
            // $('.summernote').summernote();
            $('#summernote').summernote({
                // placeholder: 'Hello Bootstrap 4',
                tabsize: 2,
                height: 100
            });

            $("button#changeTicket").click(function() {
                event.preventDefault();
                var id = $(this).data("id");
                var status = $(this).data("status");
                var text = (status) ? "You want to close the ticket" : "You want to open the ticket";
                swal({
                        title: 'Are you sure?',
                        text: text,
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonClass: "btn-primary",
                        confirmButtonText: "Yes!",
                        closeOnConfirm: false
                    },
                    function() {
                        var token = $("meta[name='csrf-token']").attr("content");
                        var url = '{{ url('/admin/ticket/change/') }}' + '/' + id + "/status/" + status;
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
