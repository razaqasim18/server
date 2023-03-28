@extends('layouts.admin')

@section('title')
    <title>Customer || Dashboard</title>
    <!-- Specific Page Vendor CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap-fileupload/bootstrap-fileupload.min.css') }}" />
@endsection
@section('content')
    <section role="main" class="content-body">
        <header class="page-header">
            <h2>Edit Customer</h2>
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
                        <h2 class="panel-title">Edit Customer</h2>
                    </header>
                    <form class="form-horizontal form-bordered" method="POST" enctype="multipart/form-data"
                        action="{{ route('admin.customer.update', ['id' => $customer->id]) }}">
                        @csrf
                        @method('PUT')
                        <div class="panel-body">
                            @if (session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif
                            @if (session('error'))
                                <div class="alert alert-danger">{{ session('error') }}</div>
                            @endif
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="inputDefault">Name</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" id="name" name="name"
                                        value="{{ $customer->name }}" required>
                                    @if ($errors->has('name'))
                                        <span class="text-danger">{{ $errors->first('name') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 control-label" for="inputDefault">E-mail Address</label>
                                <div class="col-md-6">
                                    <input type="email" class="form-control" id="email" name="email"
                                        value="{{ $customer->email }}" required>
                                    @if ($errors->has('email'))
                                        <span class="text-danger">{{ $errors->first('email') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 control-label" for="inputDefault">Password</label>
                                <div class="col-md-6">
                                    <input type="password" class="form-control" id="password" name="password"
                                        value="{{ old('password') }}" >
                                    @if ($errors->has('password'))
                                        <span class="text-danger">{{ $errors->first('password') }}</span>
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
            </div>
        </div>
    </section>
@endsection
@section('script')
@endsection
