@extends('layouts.admin')

@section('title')
    <title>Package || Dashboard</title>
@endsection
@section('content')
    <section role="main" class="content-body">
        <header class="page-header">
            <h2>Package List</h2>

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
                    <a href="{{ route('admin.package.add') }}">
                        <button class="btn btn-primary btn-xs"><i class="fa fa-plus"></i> Add Package</button>
                    </a>
                </div>
                <h2 class="panel-title">Packages List</h2>
            </header>
            <div class="panel-body">
                <table class="table table-bordered table-striped mb-none" id="datatable-default">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Category</th>
                            <th>Package</th>
                            <th>Amount</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $i = 1; @endphp
                        @foreach ($package as $row)
                            <tr>
                                <td>
                                    {{ $i++ }}
                                </td>
                                <td>
                                    {{ $row->category->category }}
                                </td>
                                <td>
                                    {{ $row->package }}
                                </td>
                                <td>
                                    {{ $row->price }}
                                </td>
                                <td>
                                    <a href="{{ route('admin.package.edit', ['id' => $row->id]) }}">
                                        <button type="button" class="btn btn-primary btn-xs">
                                            <i class="fa fa-pencil"></i>
                                        </button>
                                    </a>
                                    <button type="button" id="deletePackage" data-id="{{ $row->id }}"
                                        class="btn btn-danger btn-xs">
                                        <i class="fa fa-trash-o"></i>
                                    </button>
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

            $("#datatable-default").on("click", "button#deletePackage", function() {
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
                            var url = '{{ url('/admin/package/delete') }}' + '/' + id;
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

        });
    </script>
@endsection
