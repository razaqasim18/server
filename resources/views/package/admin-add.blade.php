@extends('layouts.admin')

@section('title')
<title>Package || Dashboard</title>
<!-- Specific Page Vendor CSS -->
<link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap-fileupload/bootstrap-fileupload.min.css') }}" />
@endsection
@section('content')
<section role="main" class="content-body">
    <header class="page-header">
        <h2>Add Package</h2>
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
                    <h2 class="panel-title">Add Package</h2>
                </header>
                <form class="form-horizontal form-bordered" method="POST" enctype="multipart/form-data"  action="{{ route('admin.package.insert')}}">
                    @csrf
                    <div class="panel-body">
                        @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif
                        
                         <div class="form-group">
                            <label class="col-md-3 control-label" for="inputDefault">Category</label>
                            <div class="col-md-6">
                                @php $oldvalue = (old('category') != '') ? old('category'): ''; @endphp
                                <select name="category" id="category" class="form-control" required>
                                    <option value="">Please Select</option>
                                    @foreach($category as $row)
                                        <option value="{{ $row->id }}" @if($oldvalue == $row->id) {{ 'selected' }} @endif >{{ $row->category }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('category'))
                                <span class="text-danger">{{ $errors->first('category') }}</span>
                                @endif
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="col-md-3 control-label" for="inputDefault">Package</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" id="package" name="package" 
                                value="{{ old('package') }}"
                                required>
                                @if ($errors->has('package'))
                                    <span class="text-danger">{{ $errors->first('package') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label" for="inputDefault">Price</label>
                            <div class="col-md-6">
                                <input type="number" class="form-control" id="price" name="price" value="{{ old('price') }}" required>
                                @if ($errors->has('price'))
                                <span class="text-danger">{{ $errors->first('price') }}</span>
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
<script src="{{ asset('assets/vendor/jquery-autosize/jquery.autosize.js') }}"></script>
<script src="{{ asset('assets/vendor/bootstrap-fileupload/bootstrap-fileupload.min.js') }}"></script>

@endsection