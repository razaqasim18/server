@extends('layouts.customer')

@section('title')
    <title>Balance || Dashboard</title>
    <!-- Specific Page Vendor CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap-fileupload/bootstrap-fileupload.min.css') }}" />
@endsection
@section('content')
    <section role="main" class="content-body">
        <header class="page-header">
            <h2>Add Balance</h2>

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
                <h2 class="panel-title">Add Balance</h2>
            </header>
            <form id="balanceForm" class="form-horizontal form-bordered" method="POST" enctype="multipart/form-data"
                action="{{ route('balance.submit') }}">
                <div class="panel-body">
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif @csrf
                    <div id="carderror" class="alert alert-danger hide">Please correct the errors and try again.</div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="inputDefault">Payment Method</label>
                        <div class="col-md-6">
                            <?php $selectedvalue = old('paymentmethod') != '' ? old('paymentmethod') : ''; ?>
                            <select id="paymentmethod" name="paymentmethod" class="form-control mb-md" required>
                                <option value="">Select option</option>
                                @foreach ($paymentmethod as $row)
                                    <option value="{{ $row->id }}"
                                        @if ($selectedvalue == $row->id) {{ 'selected' }} @endif>{{ $row->title }}
                                    </option>
                                @endforeach
                            </select>
                            @if ($errors->has('paymentmethod'))
                                <span class="text-danger">{{ $errors->first('paymentmethod') }}</span>
                            @endif
                        </div>
                    </div>

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

                    <div id="paymentInput"></div>

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
    <script src="{{ asset('assets/vendor/jquery-maskedinput/jquery.maskedinput.js') }}"></script>
    <script type="text/javascript" src="https://js.stripe.com/v2/"></script>

    <script>
        $("select#paymentmethod").change(function() {
            paymentmethod = $("select#paymentmethod option:selected").text().toLowerCase().trim();
            var output;
            if (paymentmethod == 'other') {
                output = otherPayment();
            }
            if (paymentmethod == 'stripe') {
                output = stripePayment();
            }
            // if (paymentmethod == 'paypal') {
            //     output = paypalPayment();
            // }
            $("#paymentInput").html(output);
        }).change();

        function otherPayment() {
            $('#balanceForm').prop('action', "{{ route('balance.submit') }}");
            $('#balanceForm').removeAttr('data-cc-on-file');
            $('#balanceForm').removeAttr('data-stripe-publishable-key');

            var output = '';

            output += '<div class="form-group">';
            output += '<label class="col-md-3 control-label" for="inputDefault">Transaction id</label>';
            output += '<div class="col-md-6">';
            output +=
                '<input type="text" class="form-control" id="transaction_id" name="transaction_id" value="{{ old('transaction_id') }}" required>';
            @if ($errors->has('transaction_id'))
                output += '<span class="text-danger">{{ $errors->first('transaction_id') }}</span>';
            @endif
            output += '</div>';
            output += '</div>';

            output += '<div class="form-group">';
            output += ' <label class="col-md-3 control-label" for="inputDefault">Description</label>';
            output += '<div class="col-md-6">';
            output +=
                '<textarea class="form-control" id="description" name="description" style="width:100%;height:100px" required>{{ old('description') }}</textarea>';
            @if ($errors->has('description'))
                output += ' <span class="text-danger">{{ $errors->first('description') }}</span>';
            @endif
            output += '</div>';
            output += '</div>';

            output += '<div class="form-group">';
            output += '<label class="col-md-3 control-label">Upload Image</label>';
            output += '<div class="col-md-6">';
            output += '<div class="fileupload fileupload-new" data-provides="fileupload">';
            output += '<div class="input-append">';
            output += '<div class="uneditable-input">';
            output += '<i class="fa fa-file fileupload-exists"></i>';
            output += '<span class="fileupload-preview"></span>';
            output += '</div>';
            output += '<span class="btn btn-default btn-file">';
            output += '<span class="fileupload-exists">Change</span>';
            output += '<span class="fileupload-new">Select file</span>';
            output += '<input type="file" name="image" />';
            output += '</span>';
            output += '<a href="#" class="btn btn-default fileupload-exists" data-dismiss="fileupload">Remove</a>';
            output += '</div>';
            output += '</div>';
            @if ($errors->has('image'))
                output += '<span class="text-danger">{{ $errors->first('image') }}</span>';
            @endif
            output += '</div>';
            output += '</div>';
            return output;

        }

        function stripePayment() {
            $('#balanceForm').prop('action', "{{ route('balance.submit') }}");
            $('#balanceForm').attr('data-cc-on-file', "false");
            $('#balanceForm').attr('data-stripe-publishable-key', "{{ getPaymentPublicKeyByslug('stripe') }}");
            var output = '';
            output +=
                '<div class="form-row m-1" style="display: flex;justify-content: space-around; margin: 0% 5% 0% 5%;">';
            output += '<div class="form-group col-md-4">';
            output += '<label class="control-label" for="inputDefault">Card Holder Name</label>';
            output += '<input class="form-control" type="text" name="nameoncard" required>';
            output += '</div>';
            output += '<div class="form-group col-md-4">';
            output += '<label class="control-label" for="inputDefault">Email</label>';
            output += '<input class="form-control" type="email" name="email"  required>';
            output += '</div>';
            output += '<div class="form-group col-md-4">';
            output += '<label class="control-label" for="inputDefault">Country</label>';
            output += '<input class="form-control" type="text" name="country" required>';
            output += '</div>';
            output += '</div>';

            output +=
                '<div class="form-row m-1" style="display: flex;justify-content: space-around; margin: 0% 5% 0% 5%;">';
            output += '<div class="form-group col-md-3 m-1">';
            output += '<label class="control-label" for="inputDefault">Card Number</label>';
            output += '<input autocomplete="off" class="form-control card-number" maxlength="20" type="text" required>';
            output += '</div>';
            output += '<div class="form-group col-md-3 m-1">';
            output += '<label class="control-label">CVC</label>';
            output +=
                '<input autocomplete="off" class="form-control card-cvc" data-plugin-masked-input data-input-mask="999"  placeholder="ex. 311" maxlength="4" type="text" required>';
            output += '</div>';
            output += '<div class="form-group col-md-3 m-1">';
            output += '<label class="control-label">Expiration Month</label>';
            output +=
                '<input class="form-control card-expiry-month" placeholder="MM" data-plugin-masked-input data-input-mask="99" maxlength="2" type="text" required>';
            output += '</div>';
            output += '<div class="form-group col-md-3 m-1">';
            output += '<label class="control-label">Expiration Year</label>';
            output +=
                '<input class="form-control card-expiry-year" placeholder="YYYY" maxlength="4" data-plugin-masked-input data-input-mask="9999" type="text" required>';
            output += '</div>';
            output += '</div>';

            output +=
                '<div class="form-row m-1" style="display: flex;justify-content: space-around; margin: 0% 5% 0% 5%;">';
            output += '<div class="form-group col-md-12 m-1">';
            output += '<label class="control-label" for="inputDefault">Address</label>';
            output += '<textarea class="form-control" name="address" row="4" required/></textarea>';
            output += '<br></div>';
            output += '</div>';

            output +=
                '<div class="form-row m-1" style="display: flex;justify-content: space-around; margin: 0% 5% 0% 5%;">';
            output += '<div class="form-group col-md-4 m-1">';
            output += '<label class="control-label" for="inputDefault">Company</label>';
            output += '<input autocomplete="off" class="form-control" name="company" type="text" />';
            output += '</div>';
            output += '<div class="form-group col-md-4 m-1">';
            output += '<label class="control-label" for="inputDefault">Website</label>';
            output += '<input class="form-control" type="text" name="website" type="url" />';
            output += '</div>';
            output += '<div class="form-group col-md-4 m-1">';
            output += '<label class="control-label" for="inputDefault">Phone</label>';
            output += '<input class="form-control" type="text" name="phone" type="tel" />';
            output += '</div>';
            output += '</div>';



            output += '<div class="form-group">';
            output += '<div class="col-md-12 error form-group hide">';
            output += '<div class="alert-danger alert">Please correct the errors and try again.</div>';
            output += '</div>';
            output += '</div>';
            return output;

        }


        var $form = $("#balanceForm");
        $('form#balanceForm').submit(function(e) {
            // var $form = $(".require-validation"),
            //     inputSelector = ['input[type=email]', 'input[type=password]',
            //         'input[type=text]', 'input[type=file]',
            //         'textarea'
            //     ].join(', '),
            //     $inputs = $form.find('.required').find(inputSelector),
            //     $errorMessage = $form.find('div.error'),
            //     valid = true;
            // $errorMessage.addClass('hide');

            // $('.has-error').removeClass('has-error');
            // $inputs.each(function(i, el) {
            //     var $input = $(el);
            //     if ($input.val() === '') {
            //         $input.parent().addClass('has-error');
            //         $errorMessage.removeClass('hide');
            //         e.preventDefault();
            //     }
            // });
            paymentmethod = $("select#paymentmethod option:selected").text().toLowerCase().trim();
            if (paymentmethod == 'stripe') {
                if (!$form.data('cc-on-file')) {
                    e.preventDefault();
                    Stripe.setPublishableKey($form.data('stripe-publishable-key'));
                    Stripe.createToken({
                        number: $('.card-number').val(),
                        cvc: $('.card-cvc').val(),
                        exp_month: $('.card-expiry-month').val(),
                        exp_year: $('.card-expiry-year').val()
                    }, stripeResponseHandler);
                }
            }

        });

        function stripeResponseHandler(status, response) {
            if (response.error) {
                $('#carderror').removeClass('hide')
                    // .find('.alert')
                    .text(response.error.message);
            } else {
                /* token contains id, last4, and card type */
                var token = response['id'];

                $form.find('input[type=text]').empty();
                $form.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");
                $form.get(0).submit();
            }
        }
    </script>
@endsection
