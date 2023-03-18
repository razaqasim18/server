@extends('layouts.customer')

@section('title')
<title>User || Dashboard</title>
<!-- Specific Page Vendor CSS -->
<link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap-fileupload/bootstrap-fileupload.min.css') }}" />
@endsection
@section('content')
<section role="main" class="content-body">
    <header class="page-header">
        <h2>Update Profile</h2>
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
            <h2 class="panel-title">Update Profile</h2>
        </header>
        <form class="form-horizontal form-bordered" method="POST" enctype="multipart/form-data"
            action="{{ route('profile.update')}}">
            @csrf

            <div class="panel-body">
                @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                <div class="form-group">
                    <label class="col-md-3 control-label" for="inputDefault">Email</label>
                    <div class="col-md-6">
                        <input type="email" class="form-control" id="email" name="email"
                            value="{{ auth('web')->user()->email }}" readonly>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 control-label" for="inputDefault">Name</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control" id="name" name="name"
                            value="{{ auth('web')->user()->name }}" required>
                        @if ($errors->has('name'))
                        <span class="text-danger">{{ $errors->first('name') }}</span>
                        @endif
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 control-label">Upload Image</label>
                    <div class="col-md-6">
                        <div class="fileupload fileupload-new" data-provides="fileupload">
                            <div class="input-append">
                                <div class="uneditable-input">
                                    <i class="fa fa-file fileupload-exists"></i>
                                    <span class="fileupload-preview">{{ auth('web')->user()->image }}</span>
                                    <input type="hidden" name="showimage" value="{{ auth('web')->user()->image }}" />
                                </div>
                                <span class="btn btn-default btn-file">
                                    <span class="fileupload-exists">Change</span>
                                    <span class="fileupload-new">Select file</span>
                                    <input type="file" name="image" />
                                </span>
                                <a href="#" class="btn btn-default fileupload-exists"
                                    data-dismiss="fileupload">Remove</a>
                            </div>
                        </div>
                        @if ($errors->has('image'))
                        <span class="text-danger">{{ $errors->first('image') }}</span>
                        @endif
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
<script src="{{ asset('assets/vendor/jquery-autosize/jquery.autosize.js') }}"></script>
<script src="{{ asset('assets/vendor/bootstrap-fileupload/bootstrap-fileupload.min.js') }}"></script>

@endsection