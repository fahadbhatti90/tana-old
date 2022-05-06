@extends('layouts.app')

@section('PageCSS')
<link rel="stylesheet" type="text/css" href="{{ asset('css/core/menu/menu-types/vertical-menu.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('css/core/colors/palette-gradient.css') }}">
@endsection

@section('content')
<div class="content-header row">
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-left mb-0">PTP</h2>
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ Route('home') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active"> ptp sales
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

</div>
<div class="content-body">

    <!--  Form Area -->
    <div class="card shadow ">
        <div class="card-header ">
            <h6 class="m-0 font-weight-bold text-system">Select files from your computer</h6>
        </div>
        <div class="col-xl-12 col-lg-12">
            <div class="card-body">
                <!-- Standar Form action="{{ route('sales.store') }}"-->
                <form name="ptp_sales_form" id="ptp_sales_form" class="form-horizontal" method="post" enctype="multipart/form-data" accept-charset="utf-8">
                    <div class="form-horizontal">
                        <div class="form-group">
                            <span id="form_result"></span>
                        </div>
                        @csrf
                        <div class="form-group">
                            <label class="control-label" for="data_files">Detail Data File</label>
                            <div class="col-sm-12">
                                <input type="file" class="custom-file-input" id="ptpfile" name="ptpfile" accept=".csv,.xlsx" required>
                                <label class="custom-file-label" for="ptpfile">Choose file</label>
                            </div>
                        </div>

                        <hr />
                        <div class="form-group">
                            <input type="submit" name="action_button" id="action_button" value="Upload in ptp" style=" padding: 12px; border-radius: 6px !important; border: none; background: #ff922a !important; color: white;" />
                        </div>
                    </div>

                </form>


                <!-- End Form -->

            </div>
        </div>

        <!-- account setting page end -->
    </div>
</div>
@endsection

@section('formValidation')
<script src=" {{ asset('js/validation/ptpsales.js') }}"></script>
@endsection