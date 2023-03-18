@extends('layouts.customer')

@section('title')
    <title>Order Server || Dashboard</title>
    <!-- Specific Page Vendor CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap-fileupload/bootstrap-fileupload.min.css') }}" />
@endsection
@section('content')
    <section role="main" class="content-body">
        <header class="page-header">
            <h2>Add Order Server</h2>

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
                <h2 class="panel-title">Add Order Server</h2>
            </header>
            <form class="form-horizontal form-bordered" method="POST" enctype="multipart/form-data"
                action="{{ route('order.server.submit') }}">
                @csrf

                <div class="panel-body">
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <div class="form-group">
                        <label class="col-md-3 control-label" for="inputDefault">Server Plane</label>
                        <div class="col-md-6">
                            <?php $selectedvalue = old('category') != '' ? old('category') : ''; ?>
                            <select name="category" id="category" class="form-control mb-md" required>
                                <option value="">Select option</option>
                                @foreach ($category as $row)
                                    <option value="{{ $row->id }}"
                                        @if ($selectedvalue == $row->id) {{ 'selected' }} @endif>{{ $row->category }}
                                    </option>
                                @endforeach
                            </select>
                            @if ($errors->has('category'))
                                <span class="text-danger">{{ $errors->first('category') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label" for="inputDefault">Sale Plane</label>
                        <div class="col-md-6">
                            <?php $selectedvalue = old('saleplan') != '' ? old('saleplan') : ''; ?>

                            {{-- @if ($selectedvalue) --}}
                            <input type='hidden' id='saleplanehidden' name='saleplanehidden'
                                value='{{ $selectedvalue }}' />
                            {{-- @endif --}}
                            <select name="saleplan" id="saleplan" class="form-control mb-md" required>
                                <option value="">Select server plane option</option>
                            </select>
                            @if ($errors->has('saleplan'))
                                <span class="text-danger">{{ $errors->first('saleplan') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label" for="inputDefault">Price</label>
                        <div class="col-md-6">
                            <input type="number" id="price" name="price" class="form-control" required readonly />
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

    </section>
@endsection
@section('script')
    <script>
        $(document).ready(function() {

            $("select#category").change(function() {
                var selectedCategory = $("select#category option:selected").val();
                var url = '{{ url('/order/server/get/sale') }}' + "/" + selectedCategory;
                var saleplanehidden = $("input#saleplanehidden").val();
                if (saleplanehidden != '') {
                    var oldsaleplane = saleplanehidden.split('|')[0];
                    var oldsaleprice = saleplanehidden.split('|')[1];
                }
                $.ajax({
                    type: "GET",
                    url: url,
                    data: {
                        "category": selectedCategory
                    },
                    dataType: "JSON",
                    beforeSend: function() {
                        $(".loader").show();
                    },
                    complete: function() {
                        $(".loader").hide();
                    },
                    success: function(response) {
                        category = response;
                        var output = '';
                        output += '<option value="">Select option</option>';
                        for (var i = 0; i < category.length; i++) {
                            if (oldsaleplane != '' && oldsaleplane == category[i].id) {
                                output += '<option value="' + category[i].id + '|' + category[i]
                                    .price + '" selected >' + category[i].package +
                                    ' &nbsp ( price $' + category[i].price + ' )';
                                var plantext = $("select#saleplan option:selected").val();
                                $('input#price').val(oldsaleprice);
                            } else {
                                output += '<option value="' + category[i].id + '|' + category[i]
                                    .price + '">' + category[i].package + ' &nbsp ( price $' +
                                    category[i].price + ' )';
                            }
                            output += '</option>';
                        }
                        $('select#saleplan').html(output);
                    }
                });
            }).change();

            $("select#saleplan").change(function() {
                var plantext = $("select#saleplan option:selected").val();
                $('input#price').val(plantext.split('|')[1]);
            });

        });
    </script>
@endsection
