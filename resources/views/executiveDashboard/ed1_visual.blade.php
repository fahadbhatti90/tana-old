@extends('layouts.app')

@section('content')
    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-left mb-0">Executive Dashboard</h2>
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">Dashboard
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-header-right text-md-right col-md-6 col-12 d-md-block ">
            <div class="form-group breadcrum-right">
                <div class="dropdown">
                    <h3 class="content-header-title mb-0">
                        <a class="customizer-toggle" href="javascript:void(0)"
                           @if(Auth::user()->profile->profile_mode == "dark-layout")
                                style="color: #C2C6DC;"
                           @else
                                style="color: #636363;"
                           @endif
                        >
                        <i class="feather icon-calendar"></i><span id="selected_date_text">-</span>
                        </a>
                    </h3>
                </div>
            </div>
        </div>
    </div>
    <div class="content-body">
        <section id="page-account-settings">
            <h4 class="py-1 mx-1 mb-0 font-medium-2">YTD Report</h4>
            <div class="row">
                <div class="col-xl-6 col-md-12 col-sm-12">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12">
                            <div class="card" style="margin-bottom: 1rem;">
                                <div class="card-body d-flex justify-content-center align-items-center" style="padding: 0.8rem;">
                                    <h4 class="card-title" >All Vendors</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 col-md-6 col-sm-12">
                            <div class="card">
                                <div class="card-content">
                                    <div class="card-body pt-0">
                                        <div class="row">
                                            <div class="col-sm-12 col-12 d-flex flex-column flex-wrap text-center">
                                                <h2 class="font-large-1 text-bold-700 mt-2" id="sc_ytd">-</h2>
                                            </div>
                                            <div class="col-sm-12 col-12 d-flex justify-content-center">
                                                <div id="shipped_cogs_ytd">
                                                </div>
                                            </div>
                                            <div class="col-sm-12 col-12 d-flex flex-column flex-wrap text-center">
                                                <p id="sc_ytd_title" > Net Receipts YTD</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 col-md-6 col-sm-12">
                            <div class="card">
                                <div class="card-content">
                                    <div class="card-body pt-0">
                                        <div class="row">
                                            <div class="col-sm-12 col-12 d-flex flex-column flex-wrap text-center">
                                                <h2 class="font-large-1 text-bold-700 mt-2" id="nr_ytd">-</h2>
                                            </div>
                                            <div class="col-sm-12 col-12 d-flex justify-content-center">
                                                <div id="net_receipts_ytd">
                                                </div>
                                            </div>
                                            <div class="col-sm-12 col-12 d-flex flex-column flex-wrap text-center">
                                                <p id="nr_ytd_title" > Net Receipts YTD</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-md-12 col-sm-12">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12">
                            <div class="card" style="margin-bottom: 1rem;">
                                <div class="card-body d-flex justify-content-center align-items-center" style="padding: 0.8rem;">
                                    <h4 class="card-title" id="vendor_name_card">{{ $edVendor_name }}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-md-4 col-sm-4">
                            <div id="vendor_shipped_cog_ytd_card" class="card">
                                <div class=" list-group-flush customer-info" style="margin-bottom: 1.1em;">
                                    <div class="d-lg-flex justify-content-center align-items-center">
                                        <div class="col-12 d-flex justify-content-center">
                                            <div id="vendor_shipped_cogs_ytd">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row avg-sessions pt-50">
                                        <div class="col-12 text-center">
                                            <p class="text-bold-600 font-medium-2 mb-0 mt-25" id="vendor_sc_value" >-</p>
                                            <p class="mb-12" id="vendor_sc_type" >Shipped COGS</p>
                                        </div>
                                        <div class="col-12 text-center">
                                            <p class="text-bold-600 font-medium-2 mb-0 mt-25" id="vendor_ptp_sc_value" >-</p>
                                            <p class="mb-75"><spam id="vendor_ptp_sc_ytd_percentage"></spam> Tanalogic Plan</p>
                                        </div>
                                    </div>
                                    <div class="d-lg-flex justify-content-center align-items-center">
                                        <div class="col-12 d-flex justify-content-center">
                                            <div id="vendor_last6_shipped_cogs_ytd">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-md-4 col-sm-4">
                            <div class="card">
                                <div class=" list-group-flush customer-info" style="margin-bottom: 1.1em;">
                                    <div class="d-lg-flex justify-content-center align-items-center">
                                        <div class="col-12 d-flex justify-content-center">
                                            <div id="vendor_net_receipts_ytd">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row avg-sessions pt-50">
                                        <div class="col-12 text-center">
                                            <p class="text-bold-600 font-medium-2 mb-0 mt-25" id="vendor_nr_value">-</p>
                                            <p class="mb-12" id="vendor_nr_type">Net Receipts</p>
                                        </div>
                                        <div class="col-12 text-center">
                                            <p class="text-bold-600 font-medium-2 mb-0 mt-25" id="vendor_ptp_nr_value">-</p>
                                            <p class="mb-75"><spam id="vendor_ptp_nr_ytd_percentage"></spam> Tanalogic Plan</p>
                                        </div>
                                    </div>
                                    <div class="d-lg-flex justify-content-center align-items-center">
                                        <div class="col-12 d-flex justify-content-center">
                                            <div id="vendor_last6_net_receipts_ytd">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-md-4 col-sm-4">
                            <div class="card">
                                <div class=" list-group-flush customer-info" style="margin-bottom: 1.1em;">
                                    <div class="d-lg-flex justify-content-center align-items-center" style="margin-bottom: 7em">

                                    </div>
                                    <div class="row avg-sessions pt-50">
                                        <div class="col-12 text-center">
                                            <p class="text-bold-600 font-medium-2 mb-0 mt-25" id="vendor_nr_value"></p>
                                            <p class="mb-12" id="vendor_roas_type_ytd">ROAS</p>
                                        </div>
                                        <div class="col-12 text-center">
                                            <p class="text-bold-600 font-medium-2 mb-0 mt-25" id="vendor_ptp_nr_value"></p>

                                        </div>
                                    </div>
                                    <div class="d-lg-flex justify-content-center align-items-center" style="margin-bottom: 7.6em">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <h4 class="py-1 mx-1 mb-0 font-medium-2" style="border-top: 2px solid #5d5d5d4a;">MTD Report</h4>
            <div class="row">
                <div class="col-xl-6 col-md-12 col-sm-12">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12">
                            <div class="card" style="margin-bottom: 1rem;">
                                <div class="card-body d-flex justify-content-center align-items-center" style="padding: 0.8rem;">
                                    <h4 class="card-title" >All Vendors</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 col-md-6 col-sm-12">
                            <div class="card">
                                <div class="card-content">
                                    <div class="card-body pt-0">
                                        <div class="row">
                                            <div class="col-sm-12 col-12 d-flex flex-column flex-wrap text-center">
                                                <h2 class="font-large-1 text-bold-700 mt-2" id="sc_mtd">-</h2>
                                            </div>
                                            <div class="col-sm-12 col-12 d-flex justify-content-center">
                                                <div id="shipped_cogs_mtd">
                                                </div>
                                            </div>
                                            <div class="col-sm-12 col-12 d-flex flex-column flex-wrap text-center">
                                                <p id="sc_mtd_title">Shipped COGS MTD</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 col-md-6 col-sm-12">
                            <div class="card">
                                <div class="card-content">
                                    <div class="card-body pt-0">
                                        <div class="row">
                                            <div class="col-sm-12 col-12 d-flex flex-column flex-wrap text-center">
                                                <h2 class="font-large-1 text-bold-700 mt-2" id="nr_mtd">-</h2>
                                            </div>
                                            <div class="col-sm-12 col-12 d-flex justify-content-center">
                                                <div id="net_receipts_mtd">
                                                </div>
                                            </div>
                                            <div class="col-sm-12 col-12 d-flex flex-column flex-wrap text-center">
                                                <p id="nr_mtd_title">Net Receipts MTD</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-md-12 col-sm-12">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12">
                            <div class="card" style="margin-bottom: 1rem;">
                                <div class="card-body d-flex justify-content-center align-items-center" style="padding: 0.8rem;">
                                    <h4 class="card-title" id="vendor_name_card_mtd">{{ $edVendor_name }}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-md-4 col-sm-4">
                            <div id="vendor_shipped_cog_mtd_card" class="card" >
                                <div class=" list-group-flush customer-info" style="margin-bottom: 1.1em;">
                                    <div class="d-lg-flex justify-content-center align-items-center">
                                        <div class="col-12 d-flex justify-content-center">
                                            <div id="vendor_shipped_cogs_mtd">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row avg-sessions pt-50">
                                        <div class="col-12 text-center">
                                            <p class="text-bold-600 font-medium-2 mb-0 mt-25" id="vendor_sc_value_mtd" >-</p>
                                            <p class="mb-12" id="vendor_sc_type_mtd" >Shipped COGS</p>
                                        </div>
                                        <div class="col-12 text-center">
                                            <p class="text-bold-600 font-medium-2 mb-0 mt-25" id="vendor_ptp_sc_value_mtd" >-</p>
                                            <p class="mb-75"><spam id="vendor_ptp_sc_mtd_percentage"></spam> Tanalogic Plan</p>
                                        </div>
                                    </div>
                                    <div class="d-lg-flex justify-content-center align-items-center">
                                        <div class="col-12 d-flex justify-content-center">
                                            <div id="vendor_last6_shipped_cogs_mtd">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-md-4 col-sm-4">
                            <div class="card">
                                <div class=" list-group-flush customer-info" style="margin-bottom: 1.1em;">
                                    <div class="d-lg-flex justify-content-center align-items-center">
                                        <div class="col-12 d-flex justify-content-center">
                                            <div id="vendor_net_receipts_mtd">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row avg-sessions pt-50">
                                        <div class="col-12 text-center">
                                            <p class="text-bold-600 font-medium-2 mb-0 mt-25" id="vendor_nr_value_mtd">-</p>
                                            <p class="mb-12" id="vendor_nr_type_mtd">Net Receipts</p>
                                        </div>
                                        <div class="col-12 text-center">
                                            <p class="text-bold-600 font-medium-2 mb-0 mt-25" id="vendor_ptp_nr_value_mtd">-</p>
                                            <p class="mb-75"><spam id="vendor_ptp_nr_mtd_percentage"></spam> Tanalogic Plan</p>
                                        </div>
                                    </div>
                                    <div class="d-lg-flex justify-content-center align-items-center">
                                        <div class="col-12 d-flex justify-content-center">
                                            <div id="vendor_last6_net_receipts_mtd">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-md-4 col-sm-4">
                            <div class="card">
                                <div class="list-group-flush customer-info" style="margin-bottom: 1.1em;">
                                    <div class="d-lg-flex justify-content-center align-items-center" style="margin-bottom: 7em">

                                    </div>
                                    <div class="row avg-sessions pt-50">
                                        <div class="col-12 text-center">
                                            <p class="text-bold-600 font-medium-2 mb-0 mt-25"></p>
                                            <p class="mb-12" id="vendor_roas_type_ytd">ROAS</p>
                                        </div>
                                        <div class="col-12 text-center">
                                            <p class="text-bold-600 font-medium-2 mb-0 mt-25"></p>
                                        </div>
                                    </div>
                                    <div class="d-lg-flex justify-content-center align-items-center" style="margin-bottom: 7.6em">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-6 col-md-6 col-sm-12">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-header">
                                <h4 class="card-title" id="sc_table">Shipped COGS</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table mb-0">
                                        <thead>
                                        <tr>
                                            <th style="white-space: nowrap;">Vendor</th>
                                            <th style="white-space: nowrap;" id="sc_table_cm" title="Current Month Shipped COGS">Current Month</th>
                                            <th style="white-space: nowrap;" title="Direct Fulfillment Orders">Dropship</th>
                                            <th style="white-space: nowrap;" id="sc_table_py" title="Previous Year Shipped COGS">Previous Year</th>
                                            <th style="white-space: nowrap;" title="Percent To Plan">PTP</th>
                                            <th style="white-space: nowrap;" title="Year Over Year">YOY</th>
                                            <th style="white-space: nowrap; width: 20%">Last 6 Trending</th>
                                        </tr>
                                        </thead>
                                        <tbody id="all_vendor_shipped_cogs_ytd">
                                            <tr>
                                               <td style='padding: 10px; white-space: nowrap;' align='center' colspan='7'>No data found</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-6 col-md-6 col-sm-12">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-header">
                                <h4 class="card-title" id="nr_table">Net Receipts</h4>
                            </div>
                            <div class="card-body">
                                <div id="top_asin_increase" class="table-responsive">
                                    <table class="table mb-0">
                                        <thead>
                                            <tr>
                                                <th style="white-space: nowrap;">Vendor</th>
                                                <th style="white-space: nowrap;" id="nr_table_cm" title="Current Month Net Receipts">Current Month </th>
                                                <th style="white-space: nowrap;" id="nr_table_py" title="Previous Year Net Receipts">Previous Year</th>
                                                <th style="white-space: nowrap;" title="Percent To Plan">PTP</th>
                                                <th style="white-space: nowrap;" title="Year Over Year">YOY</th>
                                                <th style="white-space: nowrap; width: 20%">Last 6 Trending</th>
                                            </tr>
                                        </thead>
                                        <tbody id="all_vendor_net_receipts_ytd">
                                            <tr>
                                                <td style='padding: 10px; white-space: nowrap;' align='center' colspan='6'>No data found</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>
        <!-- account setting page end -->
    </div>
@endsection


@section('right-sidebar-content')
<div class="customizer d-md-block">
    <a class="customizer-close" href="javascript:void(0)">
        <i class="feather icon-x"></i>
    </a>
    <a class="customizer-toggle" href="javascript:void(0)">
        <i class="feather icon-chevrons-left fa-fw white"></i>
    </a>
    <div class="customizer-content p-2 ps ps--active-y">
        <h4 class="text-uppercase mb-0">Report Filter</h4>
        <small></small>
        <hr>
        <div id="collapse-sidebar">
            <div class="collapse-sidebar d-flex justify-content-between">
                <div class="collapse-option-title">
                    <h5 class="pt-25">Unit/Dollar</h5>
                </div>
                <div class="collapse-option-switch custom-switch-warning">
                    <div class='custom-control custom-switch custom-control-inline'>
                        <input type='checkbox' class='status custom-control-input' style='background-color: #FF9F43;' name='dollar-unit-switch' id='dollar-unit-switch' checked />
                        <label class='custom-control-label' for='dollar-unit-switch'>
                            <span class="switch-text-right"></span>
                            <span class="switch-text-left">$</span>
                        </label>
                    </div>
                </div>
            </div>
            <hr>
        </div>
        <form method="post" id="ed_vendor_form">
            <div class="col-12 mb-1">
                <h5>Calender @csrf</h5>
                <input type='hidden' id="filter_range" name="filter_range" value="1"/>
                <div class="input-group">
                    <input type='text' id='filter_range_picker' name='filter_range_picker' placeholder="Select Range" class="form-control"  aria-describedby="button-addon2"/>
                    <input type='hidden' id="ed_filter_date_range" name="ed_filter_date_range" class="form-control pickadate" aria-describedby="button-addon2" />
                    <div class="input-group-append" id="button-addon2">
                        <button type="submit" name="change_report" id="change_report" class="btn btn-warning"><i class="feather icon-chevrons-right"></i></button>
                    </div>
                </div>
            </div>
            <hr>
            <div class="col-12 mb-1">
                <h5>Vendor</h5>
                <select class="form-control" id="vendor_filter_vendor" name="vendor_filter_vendor" >
                    @if(sizeof($platinumVendors) > 0)
                        <optgroup label="Platinum Vendors">
                            @foreach($platinumVendors as $vendor)
                                <option value="{{ $vendor->rdm_vendor_id }}"
                                @if($edVendor_id == $vendor->rdm_vendor_id)
                                selected
                                @endif
                                >{{ $vendor->vendor_name." - ".$vendor->domain }}</option>
                            @endforeach
                        </optgroup>
                    @endif
                    @if(sizeof($goldVendors) > 0)
                        <optgroup label="Gold Vendors">
                            @foreach($goldVendors as $vendor)
                                <option value="{{ $vendor->rdm_vendor_id }}"
                                @if($edVendor_id == $vendor->rdm_vendor_id)
                                selected
                                @endif
                                >{{ $vendor->vendor_name." - ".$vendor->domain }}</option>
                            @endforeach
                        </optgroup>
                    @endif
                    @if(sizeof($silverVendors) > 0)
                        <optgroup label="Silver Vendors">
                            @foreach($silverVendors as $vendor)
                                <option value="{{ $vendor->rdm_vendor_id }}"
                                @if($edVendor_id == $vendor->rdm_vendor_id)
                                selected
                                @endif
                                >{{ $vendor->vendor_name." - ".$vendor->domain }}</option>
                            @endforeach
                        </optgroup>
                    @endif
                </select>
            </div>
        </form>
        <hr>
        </div>
@endsection

@section('PageCSS')
    <link rel="stylesheet" type="text/css" href="{{ asset('c3/css/c3.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/core/menu/menu-types/vertical-menu.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/core/colors/palette-gradient.css') }}">
    <!-- vendor css files -->
    <link rel="stylesheet" href="{{ asset('vendors/css/pickers/pickadate/pickadate.css') }}">
    @if(Auth::user()->profile->profile_mode == 'dark-layout')
        <link rel="stylesheet" type="text/css" href="{{ asset('c3/css/c3-dark.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('datatimepicker/bootstrap-datetimepicker-dark.css') }}" />
    @endif
    <link rel="stylesheet" href="{{ asset('formvalidation/tachyons.min.css') }}">
@endsection

@section('VendorCSS')
    <link rel="stylesheet" type="text/css" href="{{ asset('datatimepicker/bootstrap-datetimepicker.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/tables/datatable/datatables.min.css') }}">
@endsection

@section('PageVendorJS')
    <script type="text/javascript" src="{{ asset('daterangepicker/moment.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('datatimepicker/bootstrap-datetimepicker.min.js') }}"></script>
@endsection

@section('PageJS')
    <script>
        {{--var access = 'yes';
        @if(sizeof($vendors) == 0 )
            access = 'no';
        @endif--}}
    </script>
    <!-- Load d3.js and c3.js -->
    <script src="{{ asset('c3/d3.v5.min.js') }}" charset="utf-8"></script>
    <script src="{{ asset('c3/js/c3.min.js') }}"></script>
    <script src="{{ asset('js/validation/executiveDashboard/visual1.js') }}"></script>
@endsection

