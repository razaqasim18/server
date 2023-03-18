@extends('layouts.admin')

@section('title')
<title>Category || Dashboard</title>
@endsection
@section('content')
<section role="main" class="content-body">
    <header class="page-header">
        <h2>Category List</h2>

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
                <button class="btn btn-primary btn-xs" data-toggle="modal" data-target="#addCategoryModel"><i class="fa fa-plus"></i> Add Category</button>
            </div>
            <h2 class="panel-title">Category List</h2>
        </header>
        <div class="panel-body">
            <table class="table table-bordered table-striped mb-none" id="datatable-default">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Category</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @php $i = 1; @endphp
                    @foreach($category as $row)
                    <tr>
                        <td>
                            <!-- {{ $row->ticketid }} -->
                            {{ $i++ }}
                        </td>
                        <td>
                                {{ $row->category }}
                        </td>
                        <td>
                            <button title="category edit" id="editcategory" data-id="{{ $row->id }}" data-category="{{ $row->category }}" class="btn btn-primary btn-xs">
                                <i class="fa fa-pencil"></i>
                            </button>
                            <button title="category delete" id="deletecategory" data-id="{{ $row->id }}" class="btn btn-danger btn-xs">
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

<div class="modal fade" id="addCategoryModel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel">Add Category</h4>
            </div>
            <form id="addcategoryForm" method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="inputDefault">Category</label>
                        <div class="col-md-12">
                            <input type="text" class="form-control" id="category" name="category" required="">
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

<div class="modal fade" id="editCategoryModel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel">Add Category</h4>
            </div>
            <form id="editcategoryForm" method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="inputDefault">Category</label>
                        <div class="col-md-12">
                            <input type="hidden" class="form-control" id="editid" name="editid" required="">
                            <input type="text" class="form-control" id="editcategory" name="editcategory" required="">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="editFormSubmit" class="btn btn-primary">Save</button>
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
<script src="{{ asset('assets/vendor/jquery-datatables/extras/TableTools/js/dataTables.tableTools.min.js') }}"></script>
<script src="{{ asset('assets/vendor/jquery-datatables-bs3/assets/js/datatables.js') }}"></script>

<script>
$(document).ready(function() {
    $("#datatable-default").on("click", "button#deletecategory", function() {
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
                    var url = '{{ url("/admin/category/delete") }}' + '/' + id;
                    $.ajax({
                        url: url,
                        type: 'DELETE',
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

    $("#insertFormSubmit").click(function(){
        var $myForm = $('form#addcategoryForm')
        if (!$myForm[0].checkValidity()) {
            // If the form is invalid, submit it. The form won't actually submit;
            // this will just cause the browser to display the native HTML5 error messages.
            // $myForm.find(':submit').click();
            $myForm[0].reportValidity();
            return false;
        }
        var category = $("input#category").val();
        var token = $("meta[name='csrf-token']").attr("content");
        var url = '{{ url("/admin/category/submit") }}';
        $.ajax({
            url: url,
            type: 'POST',
            data: {
                "category": category,
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
    });

    $("#datatable-default").on("click", "button#editcategory", function() {
        $("input#editid").val($(this).data('id'));
        $("input#editcategory").val($(this).data('category'));
        $("div#editCategoryModel").modal('show');
    });

    $("#editFormSubmit").click(function(){
        var $myForm = $('form#editcategoryForm')
        if (!$myForm[0].checkValidity()) {
            // If the form is invalid, submit it. The form won't actually submit;
            // this will just cause the browser to display the native HTML5 error messages.
            // $myForm.find(':submit').click();
            $myForm[0].reportValidity();
            return false;
        }
        var editid = $("input#editid").val();
        var category = $("input#editcategory").val();
        var token = $("meta[name='csrf-token']").attr("content");
        var url = '{{ url("/admin/category/update") }}';
        $.ajax({
            url: url,
            type: 'POST',
            data: {
                "id" : editid,
                "category": category,
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
    });
});
</script>
@endsection