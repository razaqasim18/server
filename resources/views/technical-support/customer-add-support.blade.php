@extends('layouts.customer')

@section('title')
<title>Technical Support || Dashboard</title>
<!-- Specific Page Vendor CSS -->
<link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap-fileupload/bootstrap-fileupload.min.css') }}" />
@endsection
@section('content')
<section role="main" class="content-body">
    <header class="page-header">
        <h2>Add Technical Support</h2>

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
            <!-- <div class="panel-actions">
                <a href="#" class="fa fa-caret-down"></a>
                <a href="#" class="fa fa-times"></a>
            </div> -->

            <h2 class="panel-title">Add Technical Support</h2>
        </header>
        <form class="form-horizontal form-bordered" method="POST" enctype="multipart/form-data"
            action="{{ route('technicalsupport.submit')}}">
            @csrf

            <div class="panel-body">
                @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <div class="form-group">
                    <label class="col-md-3 control-label" for="inputDefault">Title</label>
                    <div class="col-md-6">
                        <input type="type" class="form-control" id="title" name="title" value="{{ old('title') }}"
                            required>
                        @if ($errors->has('title'))
                        <span class="text-danger">{{ $errors->first('title') }}</span>
                        @endif
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 control-label" for="inputDefault">Priority Method</label>
                    <div class="col-md-6">
                        <?php $selectedvalue =
                            old('priority') != '' ? old('priority') : ''; ?>
                        <select name="priority" class="form-control mb-md" required>
                            <option value="">Select option</option>
                            @foreach($priority as $row)
                            <option value="{{ $row->id }}" @if($selectedvalue==$row->id) {{ "selected" }}
                                @endif>{{$row->priority}}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('priority'))
                        <span class="text-danger">{{ $errors->first('priority') }}</span>
                        @endif
                    </div>
                </div>
 
                <div class="form-group">
                    <label class="col-md-3 control-label" for="inputDefault">Description</label>
                    <div class="col-md-6">
                         <textarea name="description" id="summernote">{{ old('description') }}</textarea>
                        @if ($errors->has('description'))
                        <span class="text-danger">{{ $errors->first('description') }}</span>
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