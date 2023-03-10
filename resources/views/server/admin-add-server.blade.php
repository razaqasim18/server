@extends('layouts.admin')

@section('title')
    <title>Server || Dashboard</title>
@endsection
@section('content')
    <section role="main" class="content-body">
        <header class="page-header">
            <h2>Server List</h2>

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
                <h2 class="panel-title">Add Server</h2>
            </header>
            <form id="addServerForm" method="POST" action={{ route('admin.insert.server') }}>
                @csrf
                <div class="panel-body">

                    <div class="row">
                        <div class="form-group col-md-4">
                            <label class="control-label" for="inputDefault">Customer</label>
                            <select class="form-control" id="userid" name="userid" required>
                                <option value="">Please select</option>
                                @foreach ($user as $row)
                                    <option value="{{ $row->id }}" @if (old('userid') == $row->id) selected @endif>
                                        {{ $row->name }}</option>
                                @endforeach
                            </select>
                            @error('userid')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group col-md-4">
                            <label class="control-label" for="inputDefault">Server Plane</label>
                            <select class="form-control" id="category" name="category" required>
                                <option value="">Please select</option>
                                @foreach ($category as $row)
                                    <option value="{{ $row->id }}" @if (old('category') == $row->id) selected @endif>
                                        {{ $row->category }}</option>
                                @endforeach
                            </select>
                            @error('category')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group col-md-4">
                            <input type='hidden' id='packageidhidden' name='packageidhidden'
                                value="{{ old('packageid') }}" />
                            <label class="control-label" for="inputDefault">Package</label>
                            <select class="form-control" id="packageid" name="packageid" required>
                                <option value="">Select server plane option</option>
                            </select>
                            @error('packageid')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class="control-label" for="inputDefault">Data Center</label>
                            <select class="form-control" id="datacenter" name="datacenter" required>
                                <option value="">Please select</option>
                                @foreach ($datacenter as $row)
                                    <option value="{{ $row->id }}" @if (old('datacenter') == $row->id) selected @endif>
                                        {{ $row->center }}</option>
                                @endforeach
                            </select>
                            @error('datacenter')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label class="control-label" for="inputDefault">Server IP</label>
                            <input class="form-control" name="serverip" id="serverip" required
                                value="{{ old('serverip') }}" />
                            @error('serverip')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class="control-label" for="inputDefault">Web User</label>
                            <input class="form-control" name="web_user" id="web_user" required
                                value="{{ old('web_user') }}" />
                            @error('web_user')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group col-md-6">
                            <label class="control-label" for="inputDefault">Web Password</label>
                            <input class="form-control" name="web_password" id="web_password" required
                                value="{{ old('web_password') }}" />
                            @error('web_password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-6">
                            <label type="text" class="control-label" for="inputDefault">UUID</label>
                            <input class="form-control" name="uuid" id="uuid" required
                                value="{{ old('uuid') }}" />
                            @error('uuid')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label class="control-label" for="inputDefault">Server Cost</label>
                            <input type="number" class="form-control" name="servercost" id="servercost" required
                                value="{{ old('servercost') }}" />
                            @error('servercost')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class="control-label" for="inputDefault">Server Setup Cost</label>
                            <input type="number" class="form-control" name="serversetupcost" id="serversetupcost"
                                required value="{{ old('serversetupcost') }}" />
                            @error('serversetupcost')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label class="control-label" for="inputDefault">Sales Plan</label>
                            <input type="number" class="form-control" name="saleprice" id="saleprice" required
                                value="{{ old('saleprice') }}" />
                            @error('saleprice')
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
    </section>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            $("select#category").change(function() {
                var selectedCategory = $("select#category option:selected").val();
                var url = '{{ url('/admin/server/get/sale') }}' + "/" + selectedCategory;
                var saleplanehidden = $("input#packageidhidden").val();
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
                                    .price + '|' + category[i].package + '" selected >' +
                                    category[i].package +
                                    ' &nbsp ( price $' + category[i].price + ' )';
                                var plantext = $("select#saleplan option:selected").val();
                                $('input#price').val(oldsaleprice);
                            } else {
                                output += '<option value="' + category[i].id + '|' + category[i]
                                    .price + '|' + category[i].package + '">' + category[i]
                                    .package + ' &nbsp ( price $' +
                                    category[i].price + ' )';
                            }
                            output += '</option>';
                        }
                        $('select#packageid').html(output);
                    }
                });
            }).change();

            $("select#packageid").change(function() {
                var plantext = $("select#packageid option:selected").val();
                $('input#saleprice').val(plantext.split('|')[1]);
            });
        });
    </script>
@endsection
