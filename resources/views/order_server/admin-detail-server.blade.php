@extends('layouts.admin')

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
                    @if (!$server)
                        <button class="btn btn-primary btn-xs" data-toggle="modal" data-target="#addServerModal">
                            <i class="fa fa-th"></i> Install Server
                        </button>
                    @else
                        @if ($server->is_expired)
                            <button class="btn btn-primary btn-xs" data-toggle="modal" data-target="#addServerModal">
                                <i class="fa fa-th"></i> Install Server
                            </button>
                        @endif
                    @endif
                    <a href="{{ route('admin.ticket.reply.view', ['id' => $ticket->id]) }}">
                        <button type="button" data-id="{{ $ticket->id }}" class="btn btn-info btn-xs">
                            <i class="fa fa-mail-reply"></i> Reply
                        </button>
                    </a>
                    <input type="hidden" id="ticketid" name="ticketid" value="{{ $ticket->id }}" />

                    @php
                        if ($ticket->status) {
                            $title = 'Click to Open Ticket';
                            $className = 'btn btn-primary btn-xs';
                            $label = 'Open Ticket';
                            $ticketstatus = '0';
                            $icon = 'fa fa-check-square';
                        } else {
                            $title = 'Click to Close Ticket';
                            $className = 'btn btn-danger btn-xs';
                            $label = 'Close Ticket';
                            $ticketstatus = '1';
                            $icon = 'fa fa-square';
                        }
                    @endphp
                    <button type="button" id="changeTicket" data-id="{{ $ticket->id }}" data-status="{{ $ticketstatus }}"
                        class="{{ $className }}" title="{{ $title }}"><i class="{{ $icon }}"></i>
                        {{ $label }}</button>
                </div>
                <h2 class="panel-title">{{ $ticket->title }}</h2>
            </header>
            <div class="panel-body">
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

    <div class="modal fade" id="addServerModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                            class="sr-only">Close</span></button>
                    <h4 class="modal-title" id="myModalLabel">Install Server</h4>
                </div>
                <form id="addServerForm" method="POST">
                    <div class="modal-body">

                        <div class="row">
                            <div class="form-group col-md-6">
                                <label class="control-label" for="inputDefault">Data Center</label>
                                <select class="form-control" id="datacenter" name="datacenter" required>
                                    <option value="">Please select</option>
                                    @foreach ($datacenter as $row)
                                        <option value="{{ $row->id }}">{{ $row->center }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="control-label" for="inputDefault">Server IP</label>
                                <input class="form-control" name="serverip" id="serverip" required />

                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-6">
                                <label class="control-label" for="inputDefault">Web User</label>
                                <input class="form-control" name="web_user" id="web_user" required />
                            </div>

                            <div class="form-group col-md-6">
                                <label class="control-label" for="inputDefault">Web Password</label>
                                <input class="form-control" name="web_password" id="web_password" required />
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-6">
                                <label type="text" class="control-label" for="inputDefault">UUID</label>
                                <input class="form-control" name="uuid" id="uuid" required />
                            </div>
                            <div class="form-group col-md-6">
                                <label class="control-label" for="inputDefault">Server Cost</label>
                                <input type="number" class="form-control" name="servercost" id="servercost" required />
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-6">
                                <label class="control-label" for="inputDefault">Server Setup Cost</label>
                                <input type="number" class="form-control" name="serversetupcost" id="serversetupcost"
                                    required />
                            </div>
                            <div class="form-group col-md-6">
                                <label class="control-label" for="inputDefault">Sales Plan</label>
                                <input type="number" class="form-control" name="saleprice" id="saleprice" required
                                    value="{{ $ticket->package_price }}" />
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" id="insertFormSubmit" class="btn btn-primary">Save</button>
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
                        var url = '{{ url('/admin/ticket/change/') }}' + '/' + id + "/status/" +
                            status;
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

            $('button#insertFormSubmit').click(function() {
                var $myForm = $('form#addServerForm')
                if (!$myForm[0].checkValidity()) {
                    // If the form is invalid, submit it. The form won't actually submit;
                    // this will just cause the browser to display the native HTML5 error messages.
                    // $myForm.find(':submit').click();
                    $myForm[0].reportValidity();
                    return false;
                }
                var fd = new FormData();
                fd.append('_token', $("meta[name='csrf-token']").attr("content"));
                fd.append('ticketid', $('input#ticketid').val());
                fd.append('datacenter', $('select#datacenter option:selected').val());
                fd.append('serverip', $('input#serverip').val());
                fd.append('saleprice', $('input#saleprice').val());
                fd.append('servercost', $('input#servercost').val());
                fd.append('web_user', $('input#web_user').val());
                fd.append('web_password', $('input#web_password').val());
                fd.append('uuid', $('input#uuid').val());
                fd.append('serversetupcost', $('input#serversetupcost').val());
                var url = '{{ url('/admin/install/order/server') }}';
                $.ajax({
                    url: url,
                    data: fd,
                    processData: false,
                    contentType: false,
                    type: 'POST',
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
        });
    </script>
@endsection
