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
            <form class="form-horizontal form-bordered" method="POST" enctype="multipart/form-data"
                action="{{ route('balance.payment') }}">
                @csrf

                <div class="panel-body">
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

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

    <script type="text/javascript" src="https://js.stripe.com/v2/"></script>

    <script>
        $("select#paymentmethod").change(function() {
            paymentmethod = $("select#paymentmethod option:selected").text().toLowerCase().trim();
            if (paymentmethod == 'stripe') {
                $('#stripModel').modal('show');
            }
        });

        var $form = $(".require-validation");

        $('form.require-validation').submit(function(e) {
            var $form = $(".require-validation"),
                inputSelector = ['input[type=email]', 'input[type=password]',
                    'input[type=text]', 'input[type=file]',
                    'textarea'
                ].join(', '),
                $inputs = $form.find('.required').find(inputSelector),
                $errorMessage = $form.find('div.error'),
                valid = true;
            $errorMessage.addClass('hide');

            $('.has-error').removeClass('has-error');
            $inputs.each(function(i, el) {
                var $input = $(el);
                if ($input.val() === '') {
                    $input.parent().addClass('has-error');
                    $errorMessage.removeClass('hide');
                    e.preventDefault();
                }
            });

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

        });


        function stripeResponseHandler(status, response) {
            if (response.error) {
                $('.error')
                    .removeClass('hide')
                    .find('.alert')
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
