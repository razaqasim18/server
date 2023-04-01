<!doctype html>
<html class="fixed">

<head>
    <!-- Basic -->
    <meta charset="UTF-8">
    <meta name="keywords" content="HTML5 Admin Template" />
    <meta name="description" content="JSOFT Admin - Responsive HTML5 Template">
    <meta name="author" content="JSOFT.net">
    <meta name='csrf-token' content="{{ csrf_token() }}">
    <!-- Mobile Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

    <!-- Web Fonts  -->
    <link href="{{ asset('http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800|Shadows+Into+Light') }}"
        rel="stylesheet" type="text/css">

    <!-- Vendor CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap/css/bootstrap.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/font-awesome/css/font-awesome.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/magnific-popup/magnific-popup.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap-datepicker/css/datepicker3.css') }}" />

    <!-- Specific Page Vendor CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/jquery-datatables-bs3/assets/css/datatables.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/pnotify/pnotify.custom.css') }}" />
    <!-- Theme CSS -->
    <link rel="stylesheet" href="{{ asset('assets/stylesheets/theme.css') }}" />

    <!-- Skin CSS -->
    <link rel="stylesheet" href="{{ asset('assets/stylesheets/skins/default.css') }}" />

    <!-- Theme Custom CSS -->
    <link rel="stylesheet" href="{{ asset('assets/stylesheets/theme-custom.css') }}">

    <!-- sweetalert -->
    <link rel="stylesheet" href="{{ asset('dist/sweetalert.css') }}">
    <script src="{{ asset('dist/sweetalert.js') }}"></script>

    <!-- Head Libs -->
    <script src="{{ asset('assets/vendor/modernizr/modernizr.js') }}"></script>
    @yield('title')
    <style>
        .loader {
            position: fixed;
            left: 0px;
            top: 0px;
            width: 100%;
            height: 100%;
            z-index: 9999;
            background: url('{{ asset('assets/images/loading.gif') }}') 50% 50% no-repeat #f9f9f9;
            opacity: 1;
        }
    </style>
</head>

<body>
    <div class="loader"></div>
    <section class="body">
        <!-- start: header -->
        <header class="header">
            <div class="logo-container">
                <a href="{{ route('admin.home') }}" class="logo">
                    <img src="{{ asset('assets/images/logo.png') }}" height="35" alt="JSOFT Admin" />
                </a>
                <div class="visible-xs toggle-sidebar-left" data-toggle-class="sidebar-left-opened" data-target="html"
                    data-fire-event="sidebar-left-opened">
                    <i class="fa fa-bars" aria-label="Toggle sidebar"></i>
                </div>
            </div>

            <!-- start: search & user box -->
            <div class="header-right">

                <span class="separator"></span>

                <div id="userbox" class="userbox">
                    <a href="#" data-toggle="dropdown">
                        <figure class="profile-picture">
                            @php
                                $profile = Auth::guard('admin')->user()->image != '' ? asset('uploads/admin') . '/' . Auth::guard('admin')->user()->image : asset('assets/images/!logged-user.jpg');
                            @endphp
                            <img src="{{ $profile }}" alt=" {{ Auth::guard('admin')->user()->name }}"
                                class="img-circle" data-lock-picture="{{ $profile }}" />
                        </figure>
                        <div class="profile-info" data-lock-name="{{ Auth::guard('admin')->user()->name }}"
                            data-lock-email="{{ Auth::guard('admin')->user()->email }}">
                            <span class="name">
                                {{ Auth::guard('admin')->user()->name }}
                            </span>
                            <span class="role">
                                @if (Auth::guard('admin')->user()->is_admin == 1)
                                    administrator
                                @else
                                    staff
                                @endif
                            </span>
                        </div>

                        <i class="fa custom-caret"></i>
                    </a>

                    <div class="dropdown-menu">
                        <ul class="list-unstyled">
                            <li class="divider"></li>
                            <li>
                                <a role="menuitem" tabindex="-1" href="{{ route('admin.profile') }}"><i
                                        class="fa fa-user"></i> My Profile</a>
                            </li>
                            <li>
                                <a role="menuitem" tabindex="-1" href="{{ route('admin.password') }}"><i
                                        class="fa fa-cog"></i> Change Password</a>
                            </li>
                            <li>
                                {{-- <a role="menuitem" tabindex="-1" href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();"><i
                                        class="fa fa-power-off"></i>
                                    Logout</a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form> --}}

                                <a href="{{ route('admin.logout') }}"role="menuitem" tabindex="-1"
                                    onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    <i class="fa fa-power-off"></i>
                                    Logout
                                </a>
                                <form id="logout-form" action="{{ route('admin.logout') }}" method="POST"
                                    class="d-none">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- end: search & user box -->
        </header>
        <!-- end: header -->

        <div class="inner-wrapper">
            <!-- start: sidebar -->
            <aside id="sidebar-left" class="sidebar-left">

                <div class="sidebar-header">
                    <div class="sidebar-title" style="color: #FFF;">
                        Navigation
                    </div>
                    <div class="sidebar-toggle hidden-xs" data-toggle-class="sidebar-left-collapsed" data-target="html"
                        data-fire-event="sidebar-left-toggle">
                        <i class="fa fa-bars" aria-label="Toggle sidebar"></i>
                    </div>
                </div>

                <div class="nano">
                    <div class="nano-content">
                        <nav id="menu" class="nav-main" role="navigation">
                            <ul class="nav nav-main">
                                <li class="nav-active">
                                    <a href="{{ route('admin.home') }}">
                                        <i class="fa fa-home" aria-hidden="true"></i>
                                        <span>Dashboard</span>
                                    </a>
                                </li>

                                @if (Auth::guard('admin')->user()->is_admin)
                                    <li class="nav-active">
                                        <a href="{{ route('admin.category') }}">
                                            <i class="fa fa-copy" aria-hidden="true"></i>
                                            <span>Category</span>
                                        </a>
                                    </li>

                                    <li class="nav-parent">
                                        <a>
                                            <i class="fa fa-copy" aria-hidden="true"></i>
                                            <span>Package</span>
                                        </a>
                                        <ul class="nav nav-children">
                                            <li>
                                                <a href="{{ route('admin.package.add') }}">
                                                    Add Package
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ route('admin.package') }}">
                                                    All Package
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                @endif


                                <li class="nav-parent">
                                    <a>
                                        <i class="fa fa-copy" aria-hidden="true"></i>
                                        <span>Knowledeg Base</span>
                                    </a>
                                    <ul class="nav nav-children">
                                        @if (Auth::guard('admin')->user()->is_admin)
                                            <li>
                                                <a href="{{ route('admin.knowledge.add') }}">
                                                    Add Knowledeg Base
                                                </a>
                                            </li>
                                        @endif
                                        <li>
                                            <a href="{{ route('admin.knowledge') }}">
                                                All Knowledeg Base
                                            </a>
                                        </li>
                                    </ul>
                                </li>

                                <li class="nav-parent">
                                    <a>
                                        <span class="pull-right label label-primary" id="salescountspan"></span>
                                        <i class="fa fa-copy" aria-hidden="true"></i>
                                        <span>Sales</span>
                                    </a>
                                    <ul class="nav nav-children">
                                        <li>
                                            <a href="{{ route('admin.all.sales') }}">
                                                All Sales
                                            </a>
                                            <a href="{{ route('admin.open.sales') }}">
                                                Open Sales
                                            </a>
                                            <a href="{{ route('admin.close.sales') }}">
                                                Closed Sales
                                            </a>
                                        </li>
                                    </ul>
                                </li>

                                <li class="nav-parent">
                                    <a>
                                        <span class="pull-right label label-primary" id="servercountspan"></span>
                                        <i class="fa fa-copy" aria-hidden="true"></i>
                                        <span>Server Order</span>
                                    </a>
                                    <ul class="nav nav-children">
                                        <li>
                                            <a href="{{ route('admin.all.server') }}">
                                                All Server
                                            </a>
                                            <a href="{{ route('admin.open.server') }}">
                                                Open Server
                                            </a>
                                            <a href="{{ route('admin.close.server') }}">
                                                Closed Server
                                            </a>
                                        </li>
                                    </ul>
                                </li>

                                <li class="nav-parent">
                                    <a>
                                        <i class="fa fa-copy" aria-hidden="true"></i>
                                        <span>Server</span>
                                    </a>
                                    <ul class="nav nav-children">
                                        <li>
                                            <a href="{{ route('admin.add.server') }}">
                                                Add Server
                                            </a>
                                            @if (!empty(getListCategory()))
                                                @foreach (getListCategory() as $row)
                                                    <a href="{{ route('admin.category.server', $row->id) }}">
                                                        {{ $row->category }}
                                                    </a>
                                                @endforeach
                                            @endif
                                            {{-- <a href="{{ route('admin.list.server') }}">
                                                All Server
                                            </a> --}}
                                            <a href="{{ route('admin.available.server') }}">
                                                Available Server
                                            </a>
                                            <a href="{{ route('admin.expired.server') }}">
                                                Expired Server
                                            </a>
                                        </li>
                                    </ul>
                                </li>

                                <li class="nav-parent">
                                    <a>
                                        <span class="pull-right label label-primary" id="supportcountspan"></span>
                                        <i class="fa fa-copy" aria-hidden="true"></i>
                                        <span>Techical Support</span>
                                    </a>
                                    <ul class="nav nav-children">
                                        <li>
                                            <a href="{{ route('admin.all.support') }}">
                                                All Techical Support
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('admin.open.support') }}">
                                                Open Techical Support
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('admin.close.support') }}">
                                                Close Techical Support
                                            </a>
                                        </li>
                                    </ul>
                                </li>

                                <li class="nav-parent">
                                    <a>
                                        <i class="fa fa-copy" aria-hidden="true"></i>
                                        <span>Customers</span>
                                    </a>
                                    <ul class="nav nav-children">
                                        <li>
                                            <a href="{{ route('admin.customers.add') }}">
                                                Add Customers
                                            </a>
                                            <a href="{{ route('admin.customers') }}">
                                                All Customers
                                            </a>
                                        </li>
                                    </ul>
                                </li>

                                @if (Auth::guard('admin')->user()->is_admin)
                                    <li class="nav-parent">
                                        <a>
                                            <i class="fa fa-copy" aria-hidden="true"></i>
                                            <span>Users</span>
                                        </a>
                                        <ul class="nav nav-children">
                                            <li>
                                                <a href="{{ route('admin.users.add') }}">
                                                    Add Users
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ route('admin.users') }}">
                                                    All Users
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                @endif

                                <li class="nav-parent">
                                    <a>
                                        <i class="fa fa-copy" aria-hidden="true"></i>
                                        <span>Reports</span>
                                    </a>
                                    <ul class="nav nav-children">
                                        <li>
                                            <a href="{{ route('admin.report.server.payment') }}">
                                                Server Payments
                                            </a>
                                        </li>
                                    </ul>
                                </li>

                            </ul>
                        </nav>

                    </div>

                </div>

            </aside>
            <!-- end: sidebar -->

            <!-- start: content -->
            <div style="min-height: 224px;">
                @yield('content')
            </div>
            <!-- end: content -->
            <section role="main" class="content-body pt-5 pb-5" style="padding-top: 0;width:100%;">
                <p class="text-muted mt-md mb-md">© Copyright 2018. All rights reserved to <a
                        href="https://nexuvoice.com">Nexuvoice</a>.</p>
            </section>
        </div>


    </section>

    <!-- Vendor -->
    <script src="{{ asset('assets/vendor/jquery/jquery.js') }}"></script>
    <script src="{{ asset('assets/vendor/jquery-browser-mobile/jquery.browser.mobile.js') }}"></script>
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/vendor/nanoscroller/nanoscroller.js') }}"></script>
    <script src="{{ asset('assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('assets/vendor/magnific-popup/magnific-popup.js') }}"></script>
    <script src="{{ asset('assets/vendor/jquery-placeholder/jquery.placeholder.js') }}"></script>

    <!-- Specific Page Vendor -->
    @yield('script')

    <!-- Theme Base, Components and Settings -->
    <script src="{{ asset('assets/javascripts/theme.js') }}"></script>
    <!-- Theme Custom -->
    <script src="{{ asset('assets/javascripts/theme.custom.js') }}"></script>
    <!-- Theme Initialization Files -->
    <script src="{{ asset('assets/javascripts/theme.init.js') }}"></script>
    <script>
        $(document).ready(function() {
            $("div.loader").hide();
            // const url = "{{ url('admin/sales/unseen/count') }}" + "/" + timeStamp;
            const salesurl = "{{ url('admin/sales/unseen/count') }}";
            $.ajax({
                url: salesurl,
                type: "GET",
                success: function(response) {
                    if (response != "0") {
                        $("span#salescountspan").text(response);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    if (jqXHR.status == 404) {
                        swal('Warning', 'Error', 'error');
                    }
                    if (jqXHR.status == 500) {
                        swal('Warning', 'Foreign key constrain', 'error');
                    }
                }
            });

            // const url = "{{ url('admin/support/unseen/count') }}" + "/" + timeStamp;
            const supporturl = "{{ url('admin/support/unseen/count') }}";
            $.ajax({
                url: supporturl,
                type: "GET",
                success: function(response) {
                    if (response != "0") {
                        $("span#supportcountspan").text(response);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    if (jqXHR.status == 404) {
                        swal('Warning', 'Error', 'error');
                    }
                    if (jqXHR.status == 500) {
                        swal('Warning', 'Foreign key constrain', 'error');
                    }
                }
            });

            const serverurl = "{{ url('admin/server/unseen/count') }}";
            $.ajax({
                url: serverurl,
                type: "GET",
                success: function(response) {
                    if (response != "0") {
                        $("span#servercountspan").text(response);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    if (jqXHR.status == 404) {
                        swal('Warning', 'Error', 'error');
                    }
                    if (jqXHR.status == 500) {
                        swal('Warning', 'Foreign key constrain', 'error');
                    }
                }
            });
        });
    </script>
</body>

</html>
