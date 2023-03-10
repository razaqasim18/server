@extends('layouts.customer')

@section('title')
<title>User || Dashboard</title>
@endsection

@section('content')
<section role="main" class="content-body">
    <header class="page-header">
        <h2>Dashboard</h2>

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

</section>
@endsection
@section('script')

@endsection