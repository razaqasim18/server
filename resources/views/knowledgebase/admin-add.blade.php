@extends('layouts.admin')

@section('title')
    <title>Knowledeg Base || Dashboard</title>
    <!-- Specific Page Vendor CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap-fileupload/bootstrap-fileupload.min.css') }}" />
@endsection
@section('content')
    <section role="main" class="content-body">
        <header class="page-header">
            <h2>Add Knowledeg Base</h2>
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
                        <h2 class="panel-title">Add Knowledeg Base</h2>
                    </header>
                    <form class="form-horizontal form-bordered" method="POST" enctype="multipart/form-data"
                        action="{{ route('admin.knowledge.insert') }}">
                        @csrf
                        <div class="panel-body">
                            @if (session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif
                            @if (session('error'))
                                <div class="alert alert-danger">{{ session('error') }}</div>
                            @endif
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="inputDefault">Title</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" id="title" name="title"
                                        value="{{ old('title') }}" required>
                                    @if ($errors->has('title'))
                                        <span class="text-danger">{{ $errors->first('title') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 control-label" for="inputDefault">Description</label>
                                <div class="col-md-6">
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
            </div>
        </div>
    </section>
@endsection
@section('script')
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
@endsection
