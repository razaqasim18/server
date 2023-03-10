@extends('layouts.admin')

@section('title')
<title>Customers || Dashboard</title>
@endsection
@section('content')
<section role="main" class="content-body">
    <header class="page-header">
        <h2>Customers List</h2>

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
            <div class="panel-actions">
                <a href="{{ route('admin.users.add') }}"><button
                        class="btn btn-primary btn-xs"><i class="fa fa-plus"></i> Add User</button>
                </a>
                       
            </div>
            <h2 class="panel-title">Customers List</h2>
        </header>
        <div class="panel-body">
            <table  class="table table-bordered table-striped mb-none" id="datatable-default">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Is Admin</th>
                        <th>Is Block</th>
                        <th>Is Deleted</th>
                        <th>Created At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @php $i = 1; @endphp
                    @foreach($user as $row)
                    <tr>
                        <td >
                            {{ $i++ }}
                            <!-- {{ $row->id }} -->
                        </td>
                        <td>
                            {{ $row->name }}
                        </td>
                        <td>
                            {{ $row->email }}
                        </td>
                        <td>
                            @if($row->is_admin)
                            <label class="label label-info">Admin</label>
                            @else
                            <label class="label label-primary">Staff</label>
                            @endif
                        </td>
                        <td>
                            @if($row->is_block)
                            <label class="label label-danger">Block</label>
                            @else
                            <label class="label label-success">Active</label>
                            @endif
                        </td>
                        <td>
                            @if($row->is_deleted)
                            <label class="label label-danger">Deleted</label>
                            @else
                            <label class="label label-success">Active</label>
                            @endif
                        </td>
                        <td>
                            {{ date("Y-m-d",strtotime($row->created_at)); }}
                        </td>
                        <td>
                            <a href="{{ route('admin.users.edit', ['id' => $row->id]) }}">
                                <button type="button" class="btn btn-primary btn-xs">
                                    <i class="fa fa-eye"></i>
                                </button>
                            </a>
                            <button type="button" id="deleteUser" data-id="{{ $row->id }}" class="btn btn-danger btn-xs">
                                <i class="fa fa-trash-o"></i>
                            </button>
                            @if($row->is_block)
                            <button type="button" id="statusCustomer" data-status="0" class="btn btn-primary btn-xs"
                                title="unblock" data-id="{{ $row->id }}"><i class="fa fa-unlock"></i></button>
                            @else
                            <button type="button" id="statusCustomer" data-status="1" class="btn btn-danger btn-xs"
                                title="block" data-id="{{ $row->id }}"><i class="fa fa-unlock-alt"></i></button>
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
<script src="{{ asset('assets/vendor/jquery-datatables/extras/TableTools/js/dataTables.tableTools.min.js') }}"></script>
<script src="{{ asset('assets/vendor/jquery-datatables-bs3/assets/js/datatables.js') }}"></script>

<script>
$(document).ready(function() {
   
    $("#datatable-default").on("click", "button#deleteUser", function() {
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
                    var url = '{{ url("/admin/users/delete") }}' + '/' + id;
                    $.ajax({
                        url: url,
                        type: 'POST',
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

   $ ("#datatable-default").on("click", "button#statusCustomer", function(){
        event.preventDefault();
        var id = $(this).data("id");
        var status = $(this).data("status");
        var text = (status) ? "You want to block the user" : "You want to un-block the user";
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
                var url = '{{ url("admin/user/status") }}' + '/' + id + '/' + status;
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