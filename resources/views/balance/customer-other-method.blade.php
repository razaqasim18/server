@extends('layouts.customer')

@section('title')
    <title>Balance || Dashboard</title>
    <!-- Specific Page Vendor CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap-fileupload/bootstrap-fileupload.min.css') }}" />
@endsection
@section('content')
    <section role="main" class="content-body">
        <header class="page-header">
            <h2>Other Payment Method</h2>

            <div class="right-wrapper pull-right">
                <ol class="breadcrumbs">
                    <li>
                        <a href="{{ route('admin.home') }}">
                            <i class="fa fa-home"></i>
                        </a>
                    </li>
                    <li><span>Dashboard</span></li>
                </ol>

            </div>
        </header>

        <section class="panel">
            <header class="panel-heading">
                <h2 class="panel-title">Other Payment Method</h2>
            </header>
            <form class="form-horizontal form-bordered" method="POST" enctype="multipart/form-data"
                action="{{ route('balance.submit') }}">
                @csrf

                <div class="panel-body">
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    <input type="hidden" class="form-control" id="paymentmethod" name="paymentmethod"
                        value="{{ $paymentmethod != '' ? $paymentmethod : old('transaction_id') }}" required>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="inputDefault">Amount</label>
                        <div class="col-md-6">
                            <input type="number" class="form-control" id="amount" name="amount"
                                value="{{ old('amount') }}" required>
                            @if ($errors->has('amount'))
                                <span class="text-danger">{{ $errors->first('amount') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label" for="inputDefault">Transaction id</label>
                        <div class="col-md-6">
                            <input type="text" class="form-control" id="transaction_id" name="transaction_id"
                                value="{{ old('transaction_id') }}" required>
                            @if ($errors->has('transaction_id'))
                                <span class="text-danger">{{ $errors->first('transaction_id') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label" for="inputDefault">Description</label>
                        <div class="col-md-6">
                            <textarea class="form-control" id="description" name="description" style="width:100%;height:100px" required>{{ old('description') }}</textarea>
                            @if ($errors->has('description'))
                                <span class="text-danger">{{ $errors->first('description') }}</span>
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
                                        <span class="fileupload-preview"></span>
                                    </div>
                                    <span class="btn btn-default btn-file">
                                        <span class="fileupload-exists">Change</span>
                                        <span class="fileupload-new">Select file</span>
                                        <input type="file" name="image" required />
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
                            <button class="btn btn-primary">Proceed</button>
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
