@extends('layouts.admin')

@section('title')
    <title>Knowledeg Base || Dashboard</title>
    <!-- Specific Page Vendor CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap-fileupload/bootstrap-fileupload.min.css') }}" />
@endsection
@section('content')
    <section role="main" class="content-body">
        <header class="page-header">
            <h2>View Knowledeg Base</h2>
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
        <div class="row">
            <div class="col-md-12">
                <section class="panel">
                    <header class="panel-heading">
                        <h2 class="panel-title">View Knowledeg Base</h2>
                    </header>

                    <div class="panel-body">

                        <div class="form-group">
                            <label class="col-md-3 control-label" for="inputDefault">Title</label>
                            <div class="col-md-12">
                                <p style="border: 1px solid;padding: 2%;">
                                    {{ $knowledge->title }}
                                </p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label" for="inputDefault">Description</label>
                            <div class="col-md-12">
                                <div style="border: 1px solid;padding: 2%;">
                                    {!! $knowledge->description !!}
                                </div>
                            </div>
                        </div>


                    </div>
                    <footer class="panel-footer">

                    </footer>
                </section>
            </div>
        </div>
    </section>
@endsection
{{-- @section('script')
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
        });
    </script>
@endsection --}}
