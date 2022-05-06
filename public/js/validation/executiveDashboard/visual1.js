document.addEventListener('DOMContentLoaded', function (e) {

    $("#filter_range_picker").datetimepicker({
        minDate: '01/01/2018',
        maxDate: new Date(),
        viewMode: 'months',
        format: 'MMM-YYYY',
        date: new Date(),
    });

    let value = $("#filter_range_picker").val();
    let firstDate = moment(value, "MMM-YYYY").startOf('month').format("MM/DD/YYYY");
    let lastDate = moment(value, "MMM-YYYY").endOf('month').format("MM/DD/YYYY");
    $("#selected_date_text").html(moment(new Date()).startOf('month').format("MMMM YYYY"));
    $("#ed_filter_date_range").val(firstDate + " - " + lastDate);

    let filter_date_range = $('#ed_filter_date_range').val();
    let checkbox = document.getElementById("dollar-unit-switch").checked;
    let report_type = 0; //for dollar
    if (checkbox === false){
        report_type = 1; //for unit
    }

    //initial load SP call
    generateEDReport(report_type, filter_date_range);
    generateVendorEDReport(report_type, filter_date_range);

    $("#vendor_filter_vendor").select2({
        dropdownParent: $("#ed_vendor_form"),
        language: {
            noResults: function(e){
                return "No vendor found";
            },
        }
    });

    //on Changing Dollar/Unit Toggle
    $('#dollar-unit-switch').on('click', function (ev, picker) {
        let filter_date_range = $('#ed_filter_date_range').val();
        let checkbox = document.getElementById("dollar-unit-switch").checked;
        let report_type = 0; //for dollar
        if (checkbox === false){
            report_type = 1; //for unit
        }
        generateEDReport(report_type, filter_date_range);
        generateVendorEDReport(report_type, filter_date_range);
    });

    $('#vendor_filter_vendor').on('change', function () {
        let filter_vendor = $('#vendor_filter_vendor').val();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: base_url + "/ed/vendor/store",
            type: "POST",
            data: {
                vendor: filter_vendor,
            },
            cache: false,
            success: function (response) {
                if (response.error) {
                    Swal.fire({
                        title: "Error",
                        text: response.error,
                        allowOutsideClick: false,
                        type: "info",
                        confirmButtonClass: 'btn btn-primary',
                        buttonsStyling: false,
                    });
                } else {
                    $('#vendor_name_card').html(response.vendor);
                    $('#vendor_name_card_mtd').html(response.vendor);

                    let value = $("#filter_range_picker").val();
                    let firstDate = moment(value, "MMM-YYYY").startOf('month').format("MM/DD/YYYY");
                    let lastDate = moment(value, "MMM-YYYY").endOf('month').format("MM/DD/YYYY");
                    $("#ed_filter_date_range").val(firstDate + " - " + lastDate);

                    let filter_date_range = $('#ed_filter_date_range').val();
                    let checkbox = document.getElementById("dollar-unit-switch").checked;
                    let report_type = 0; //for dollar
                    if (checkbox === false){
                        report_type = 1; //for unit
                    }
                    generateVendorEDReport(report_type, filter_date_range);
                }
            },
        });
    });

    //on Submitting Vendor
    $('#ed_vendor_form').on('submit', function (event) {
        event.preventDefault();

        let value = $("#filter_range_picker").val();
        let firstDate = moment(value, "MMM-YYYY").startOf('month').format("MM/DD/YYYY");
        let lastDate = moment(value, "MMM-YYYY").endOf('month').format("MM/DD/YYYY");
        $("#ed_filter_date_range").val(firstDate + " - " + lastDate);
        $("#selected_date_text").html(moment(firstDate).startOf('month').format("MMMM YYYY"));
        let filter_date_range = $('#ed_filter_date_range').val();
        let checkbox = document.getElementById("dollar-unit-switch").checked;
        let report_type = 0; //for dollar
        if (checkbox === false){
            report_type = 1; //for unit
        }
        generateEDReport(report_type, filter_date_range);
        generateVendorEDReport(report_type, filter_date_range);
    });

    function generateEDReport(report_type, filter_date_range){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: base_url + "/ed/report",
            type: "POST",
            data: {
                type: report_type,
                date_range: filter_date_range,
            },
            cache: false,
            success: function (response) {
                if (response.error) {
                    Swal.fire({
                        title: "Error",
                        text: response.error,
                        allowOutsideClick: false,
                        type: "info",
                        confirmButtonClass: 'btn btn-primary',
                        buttonsStyling: false,
                    });
                } else {
                    if (response.SC_YTD[0]) {
                        shippedCOGSYTD(response.SC_YTD[0], report_type);
                    }
                    if (response.NR_YTD[0]) {
                        netReceiptsYTD(response.NR_YTD[0], report_type);
                    }
                    if (response.SC_MTD[0]) {
                        shippedCOGSMTD(response.SC_MTD[0], report_type);
                    }
                    if (response.NR_MTD[0]) {
                        netReceiptsMTD(response.NR_MTD[0], report_type);
                    }
                    if (response.shippedCogsTable) {
                        shippedCogsTable(response.shippedCogsTable, report_type, filter_date_range);
                    }
                    if (response.netReceivedTable) {
                        netReceivedTable(response.netReceivedTable, report_type, filter_date_range);
                    }
                }
            },
        });
    }
    function generateVendorEDReport(report_type, filter_date_range){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: base_url + "/ed/vendor/report",
            type: "POST",
            data: {
                type: report_type,
                date_range: filter_date_range,
            },
            cache: false,
            success: function (response) {
                if (response.error) {
                    Swal.fire({
                        title: "Error",
                        text: response.error,
                        allowOutsideClick: false,
                        type: "info",
                        confirmButtonClass: 'btn btn-primary',
                        buttonsStyling: false,
                    });
                } else {
                    if (response.vendorDetailSC[0]) {
                        generateVendorDetailSC(response.vendorDetailSC[0], report_type, response.vendorAlerts);
                    }else{
                        setTimeout(function () {
                            vendor_shipped_cogs_ytd.internal.config.gauge_max = 100;
                            vendor_shipped_cogs_ytd.internal.config.gauge_min = 0;
                            vendor_shipped_cogs_ytd.load({
                                columns: [
                                    ['PTP', 0],
                                    ['Shipped COGS', 0],
                                    ['Shipped Units', 0],
                                ],
                            });
                        }, 1000);
                        $("#vendor_shipped_cog_ytd_card").removeAttr("style");
                        $('#vendor_sc_type').html("Shipped COGS");
                        $('#vendor_sc_value').html("-");
                        $('#vendor_ptp_sc_value').html("-");
                        $('#vendor_ptp_sc_ytd_percentage').html("");
                    }
                    if (response.vendorDetailNR[0]) {
                        generateVendorDetailNR(response.vendorDetailNR[0], report_type, response.vendorAlerts);
                    }else{
                        setTimeout(function () {
                            vendor_net_receipts_ytd.internal.config.gauge_max = 100;
                            vendor_net_receipts_ytd.internal.config.gauge_min = 0;
                            vendor_net_receipts_ytd.load({
                                columns: [
                                    ['PTP', 0],
                                    ['Net Received', 0],
                                    ['Net Received Units', 0],
                                ],
                            });
                        }, 1000);
                        $('#vendor_nr_type').html("Net Receipts");
                        $('#vendor_nr_value').html("-");
                        $('#vendor_ptp_nr_value').html("-");
                        $('#vendor_ptp_nr_ytd_percentage').html("");
                    }
                    if (response.vendorDetailSCMTD[0]) {
                        generateVendorDetailSCMTD(response.vendorDetailSCMTD[0], report_type, response.vendorAlerts);
                    }else{
                        setTimeout(function () {
                            vendor_shipped_cogs_mtd.internal.config.gauge_max = 100;
                            vendor_shipped_cogs_mtd.internal.config.gauge_min = 0;
                            vendor_shipped_cogs_mtd.load({
                                columns: [
                                    ['PTP', 0],
                                ],
                            });
                        }, 1000);
                        $("#vendor_shipped_cog_mtd_card").removeAttr("style");
                        $('#vendor_sc_type_mtd').html("Shipped COGS");
                        $('#vendor_sc_value_mtd').html("-");
                        $('#vendor_ptp_sc_value_mtd').html("-");
                        $('#vendor_ptp_sc_mtd_percentage').html("");
                    }
                    if (response.vendorDetailNRMTD[0]) {
                        generateVendorDetailNRMTD(response.vendorDetailNRMTD[0], report_type, response.vendorAlerts);
                    }else{
                        setTimeout(function () {
                            vendor_net_receipts_mtd.internal.config.gauge_max = 100;
                            vendor_net_receipts_mtd.internal.config.gauge_min = 0;
                            vendor_net_receipts_mtd.load({
                                columns: [
                                    ['PTP', 0],
                                ],
                            });
                        }, 1000);
                        $('#vendor_nr_type_mtd').html("Net Receipts");
                        $('#vendor_nr_value_mtd').html("-");
                        $('#vendor_ptp_nr_value_mtd').html("-");
                        $('#vendor_ptp_nr_mtd_percentage').html("");
                    }
                    if (response.vendor) {
                        vendorShippedCogsTrailing('vendor_last6_shipped_cogs_ytd', response.vendor, report_type, filter_date_range);
                        vendorNetReceivedTrailing('vendor_last6_net_receipts_ytd', response.vendor, report_type, filter_date_range);
                        vendorShippedCogsTrailing('vendor_last6_shipped_cogs_mtd', response.vendor, report_type, filter_date_range);
                        vendorNetReceivedTrailing('vendor_last6_net_receipts_mtd', response.vendor, report_type, filter_date_range);
                    }
                }
            },
        });
    }

    var shipped_cogs_ytd = c3.generate({
        bindto: d3.select('#shipped_cogs_ytd'),
        data: {
            columns: [
                ['PTP', 0],
                ['Shipped COGS', 0],
                ['Shipped Units', 0],
            ],
            type: 'gauge',
        },
        gauge: {
            label: {
                show: true // to turn off the min/max labels.
            },
            min: 0, // 0 is default, //can handle negative min e.g. vacuum / voltage / current flow / rate of change
            max: 100, // 100 is default
            width: 50 // for adjusting arc thickness
        },
        color: {
            pattern: ['#00A5B5', '#E15829', '#E15829']
        },
        size: {
            height: 150
        },
        transition: {
            duration: 100
        },
        legend: {
            show: false,
        },
        tooltip: {
            show: true,
            format: {
                value: function (value, ratio, id) {
                    return value+"%";
                }
            }
        },
    });
    var net_receipts_ytd = c3.generate({
        bindto: d3.select('#net_receipts_ytd'),
        data: {
            columns: [
                ['PTP', 0],
                ['Net Received', 0],
                ['Net Received Units', 0],
            ],
            type: 'gauge',
        },
        gauge: {
            min: 0, // 0 is default, //can handle negative min e.g. vacuum / voltage / current flow / rate of change
            max: 100, // 100 is default
            width: 50 // for adjusting arc thickness
        },
        color: {
            pattern: ['#00A5B5', '#E15829', '#E15829']
        },
        size: {
            height: 150
        },
        transition: {
            duration: 100
        },
        legend: {
            show: false,
        },
        tooltip: {
            show: true,
            format: {
                value: function (value, ratio, id) {
                    return value+"%";
                }
            }
        },
    });

    var shipped_cogs_mtd = c3.generate({
        bindto: d3.select('#shipped_cogs_mtd'),
        data: {
            columns: [
                ['PTP', 0],
                ['Shipped COGS', 0],
                ['Shipped Units', 0],
            ],
            type: 'gauge',
        },
        gauge: {
            label: {
                show: true // to turn off the min/max labels.
            },
            min: 0, // 0 is default, //can handle negative min e.g. vacuum / voltage / current flow / rate of change
            max: 100, // 100 is default
            width: 50 // for adjusting arc thickness
        },
        color: {
            pattern: ['#00A5B5', '#E15829', '#E15829']
        },
        size: {
            height: 150
        },
        transition: {
            duration: 100
        },
        legend: {
            show: false,
        },
        tooltip: {
            show: true,
            format: {
                value: function (value, ratio, id) {
                    return value+"%";
                }
            }
        },
    });
    var net_receipts_mtd = c3.generate({
        bindto: d3.select('#net_receipts_mtd'),
        data: {
            columns: [
                ['PTP', 0],
                ['Net Received', 0],
                ['Net Received Units', 0],
            ],
            type: 'gauge',
        },
        gauge: {
            min: 0, // 0 is default, //can handle negative min e.g. vacuum / voltage / current flow / rate of change
            max: 100, // 100 is default
            width: 50 // for adjusting arc thickness
        },
        color: {
            pattern: ['#00A5B5', '#E15829', '#E15829']
        },
        size: {
            height: 150
        },
        transition: {
            duration: 100
        },
        legend: {
            show: false,
        },
        tooltip: {
            show: true,
            format: {
                value: function (value, ratio, id) {
                    return value+"%";
                }
            }
        },
    });

    var vendor_shipped_cogs_ytd  = c3.generate({
        bindto: d3.select('#vendor_shipped_cogs_ytd'),
        data: {
            columns: [
                ['PTP', 0],
                ['Shipped COGS', 0],
            ],
            type: 'gauge',
        },
        gauge: {
            min: 0, // 0 is default, //can handle negative min e.g. vacuum / voltage / current flow / rate of change
            max: 100, // 100 is default
            width: 40 // for adjusting arc thickness
        },
        color: {
            pattern: ['#00A5B5', '#E15829', '#E15829']
        },
        size: {
            height: 90
        },
        transition: {
            duration: 100
        },
        legend: {
            show: false,
        },
        tooltip: {
            format: {
                value: function (value, ratio, id) {
                    return value+"%";
                }
            }
        },
    });
    var vendor_net_receipts_ytd = c3.generate({
        bindto: d3.select('#vendor_net_receipts_ytd'),
        data: {
            columns: [
                ['PTP', 0],
                ['Net Received', 0],
            ],
            type: 'gauge',
        },
        gauge: {
            min: 0, // 0 is default, //can handle negative min e.g. vacuum / voltage / current flow / rate of change
            max: 100, // 100 is default
            width: 40 // for adjusting arc thickness
        },
        color: {
            pattern: ['#00A5B5', '#E15829', '#E15829']
        },
        size: {
            height: 90
        },
        transition: {
            duration: 100
        },
        legend: {
            show: false,
        },
        tooltip: {
            format: {
                value: function (value, ratio, id) {
                    return value+"%";
                },
            }
        },
    });

    var vendor_shipped_cogs_mtd  = c3.generate({
        bindto: d3.select('#vendor_shipped_cogs_mtd'),
        data: {
            columns: [
                ['PTP', 0],
            ],
            type: 'gauge',
        },
        gauge: {
            min: 0, // 0 is default, //can handle negative min e.g. vacuum / voltage / current flow / rate of change
            max: 100, // 100 is default
            width: 40 // for adjusting arc thickness
        },
        color: {
            pattern: ['#00A5B5', '#E15829', '#E15829']
        },
        size: {
            height: 90
        },
        transition: {
            duration: 100
        },
        legend: {
            show: false,
        },
        tooltip: {
            format: {
                value: function (value, ratio, id) {
                    return value+"%";
                }
            }
        },
    });
    var vendor_net_receipts_mtd = c3.generate({
        bindto: d3.select('#vendor_net_receipts_mtd'),
        data: {
            columns: [
                ['PTP', 0],
            ],
            type: 'gauge',
        },
        gauge: {
            min: 0, // 0 is default, //can handle negative min e.g. vacuum / voltage / current flow / rate of change
            max: 100, // 100 is default
            width: 40 // for adjusting arc thickness
        },
        color: {
            pattern: ['#00A5B5', '#E15829', '#E15829']
        },
        size: {
            height: 90
        },
        transition: {
            duration: 100
        },
        legend: {
            show: false,
        },
        tooltip: {
            format: {
                value: function (value, ratio, id) {
                    return value+"%";
                },
            }
        },
    });

    function shippedCOGSYTD(SC_YTD, report_type) {
        let value = 0;
        let value1 = 0;

        var gauge_min = 0;
        var gauge_max = 100;
        var gauge_value1 = 0;
        var gauge_value2 = 0;
        switch (report_type) {
            case 0:
                SC_YTD.shipped_cogs_percent != null ? value = SC_YTD.shipped_cogs_percent : value = 0;
                SC_YTD.ptp_shipped_cogs_percent != null ? value1 = SC_YTD.ptp_shipped_cogs_percent : value1 = 0;
                SC_YTD.shipped_cogs_ytd != null ? $('#sc_ytd').html(SC_YTD.shipped_cogs_ytd) : $('#sc_ytd').html("-");
                $('#sc_ytd_title').html("Shipped COGS YTD");
                gauge_value1 = parseInt(value);
                gauge_value2 =  parseInt(value1);
                gauge_min = 0;
                gauge_max = 100;

                if(gauge_value1 < 0 || gauge_value2 < 0){
                    gauge_min = ((parseInt(gauge_value2/100))*100) - 100;
                    if(gauge_value1 < gauge_value2){
                        gauge_min = ((parseInt(gauge_value1/100))*100) - 100;
                    }
                }
                if(gauge_value1 > 100 || gauge_value2 > 100){
                    gauge_max = ((parseInt(gauge_value2/100))*100) + 100;
                    if(gauge_value1 > gauge_value2){
                        gauge_max = ((parseInt(gauge_value1/100))*100) + 100;
                    }
                }

                setTimeout(function () {
                    shipped_cogs_ytd.internal.config.gauge_max = gauge_max;
                    shipped_cogs_ytd.internal.config.gauge_min = gauge_min;
                    shipped_cogs_ytd.load({
                        columns: [
                            ['Shipped COGS', value],
                            ['PTP', value1],
                        ]
                    });
                    shipped_cogs_ytd.unload({
                        ids: ['Shipped Units']
                    });
                }, 1000);
                break;
            case 1:
                SC_YTD.shipped_units_percent != null ? value = SC_YTD.shipped_units_percent : value = 0;
                SC_YTD.ptp_shipped_units_percent != null ? value1 = SC_YTD.ptp_shipped_units_percent : value1 = 0;
                SC_YTD.shipped_units_ytd != null ? $('#sc_ytd').html(SC_YTD.shipped_units_ytd) : $('#sc_ytd').html("-");
                $('#sc_ytd_title').html("Shipped Units YTD");
                gauge_value1 = parseInt(value);
                gauge_value2 =  parseInt(value1);
                gauge_min = 0;
                gauge_max = 100;

                if(gauge_value1 < 0 || gauge_value2 < 0){
                    gauge_min = ((parseInt(gauge_value2/100))*100) - 100;
                    if(gauge_value1 < gauge_value2){
                        gauge_min = ((parseInt(gauge_value1/100))*100) - 100;
                    }
                }
                if(gauge_value1 > 100 || gauge_value2 > 100){
                    gauge_max = ((parseInt(gauge_value2/100))*100) + 100;
                    if(gauge_value1 > gauge_value2){
                        gauge_max = ((parseInt(gauge_value1/100))*100) + 100;
                    }
                }

                setTimeout(function () {
                    shipped_cogs_ytd.internal.config.gauge_max = gauge_max;
                    shipped_cogs_ytd.internal.config.gauge_min = gauge_min;
                    shipped_cogs_ytd.load({
                        columns: [
                            ['Shipped Units', value],
                            ['PTP', value1],
                        ]
                    });
                    shipped_cogs_ytd.unload({
                        ids: ['Shipped COGS']
                    });
                }, 1000);
                break;
            default:
                break;
        }
    }
    function netReceiptsYTD(NR_YTD, report_type) {
        let value = 0;
        let value1 = 0;

        var gauge_min = 0;
        var gauge_max = 100;
        var gauge_value1 = 0;
        var gauge_value2 = 0;
        switch (report_type) {
            case 0:
                NR_YTD.net_received_percent != null ? value = NR_YTD.net_received_percent : value = 0;
                NR_YTD.ptp_net_received_percent != null ? value1 = NR_YTD.ptp_net_received_percent : value1 = 0;
                NR_YTD.net_received_ytd != null ? $('#nr_ytd').html(NR_YTD.net_received_ytd) : $('#sc_ytd').html("-");
                $('#nr_ytd_title').html("Net Received YTD");
                gauge_value1 = parseInt(value);
                gauge_value2 =  parseInt(value1);
                gauge_min = 0;
                gauge_max = 100;

                if(gauge_value1 < 0 || gauge_value2 < 0){
                    gauge_min = ((parseInt(gauge_value2/100))*100) - 100;
                    if(gauge_value1 < gauge_value2){
                        gauge_min = ((parseInt(gauge_value1/100))*100) - 100;
                    }
                }
                if(gauge_value1 > 100 || gauge_value2 > 100){
                    gauge_max = ((parseInt(gauge_value2/100))*100) + 100;
                    if(gauge_value1 > gauge_value2){
                        gauge_max = ((parseInt(gauge_value1/100))*100) + 100;
                    }
                }

                setTimeout(function () {
                    net_receipts_ytd.internal.config.gauge_max = gauge_max;
                    net_receipts_ytd.internal.config.gauge_min = gauge_min;
                    net_receipts_ytd.load({
                        columns: [
                            ['Net Received', value],
                            ['PTP', value1],
                        ]
                    });
                    net_receipts_ytd.unload({
                        ids: ['Net Received Units']
                    });
                }, 1000);
                break;
            case 1:
                NR_YTD.net_received_units_percent != null ? value = NR_YTD.net_received_units_percent : value = 0;
                NR_YTD.ptp_net_received_units_percent != null ? value1 = NR_YTD.ptp_net_received_units_percent : value1 = 0;
                NR_YTD.net_received_units_ytd != null ? $('#nr_ytd').html(NR_YTD.net_received_units_ytd) : $('#nr_ytd').html("-");
                $('#nr_ytd_title').html("Net Received Units YTD");
                gauge_value1 = parseInt(value);
                gauge_value2 =  parseInt(value1);
                gauge_min = 0;
                gauge_max = 100;

                if(gauge_value1 < 0 || gauge_value2 < 0){
                    gauge_min = ((parseInt(gauge_value2/100))*100) - 100;
                    if(gauge_value1 < gauge_value2){
                        gauge_min = ((parseInt(gauge_value1/100))*100) - 100;
                    }
                }
                if(gauge_value1 > 100 || gauge_value2 > 100){
                    gauge_max = ((parseInt(gauge_value2/100))*100) + 100;
                    if(gauge_value1 > gauge_value2){
                        gauge_max = ((parseInt(gauge_value1/100))*100) + 100;
                    }
                }

                setTimeout(function () {
                    net_receipts_ytd.internal.config.gauge_max = gauge_max;
                    net_receipts_ytd.internal.config.gauge_min = gauge_min;
                    net_receipts_ytd.load({
                        columns: [
                            ['Net Received Units', value],
                            ['PTP', value1],
                        ]
                    });
                    net_receipts_ytd.unload({
                        ids: ['Net Received']
                    });
                }, 1000);
                break;
            default:
                break;
        }
    }

    function shippedCOGSMTD(SC_MTD, report_type) {
        let value = 0;
        let value1 = 0;

        var gauge_min = 0;
        var gauge_max = 100;
        var gauge_value1 = 0;
        var gauge_value2 = 0;
        switch (report_type) {
            case 0:
                SC_MTD.shipped_cogs_percent != null ? value = SC_MTD.shipped_cogs_percent : value = 0;
                SC_MTD.ptp_shipped_cogs_percent != null ? value1 = SC_MTD.ptp_shipped_cogs_percent : value1 = 0;
                SC_MTD.shipped_cogs_mtd != null ? $('#sc_mtd').html(SC_MTD.shipped_cogs_mtd) : $('#sc_mtd').html("-");
                $('#sc_mtd_title').html("Shipped COGS MTD");
                gauge_value1 = parseInt(value);
                gauge_value2 =  parseInt(value1);
                gauge_min = 0;
                gauge_max = 100;

                if(gauge_value1 < 0 || gauge_value2 < 0){
                    gauge_min = ((parseInt(gauge_value2/100))*100) - 100;
                    if(gauge_value1 < gauge_value2){
                        gauge_min = ((parseInt(gauge_value1/100))*100) - 100;
                    }
                }
                if(gauge_value1 > 100 || gauge_value2 > 100){
                    gauge_max = ((parseInt(gauge_value2/100))*100) + 100;
                    if(gauge_value1 > gauge_value2){
                        gauge_max = ((parseInt(gauge_value1/100))*100) + 100;
                    }
                }

                setTimeout(function () {
                    shipped_cogs_mtd.internal.config.gauge_max = gauge_max;
                    shipped_cogs_mtd.internal.config.gauge_min = gauge_min;
                    shipped_cogs_mtd.load({
                        columns: [
                            ['Shipped COGS', value],
                            ['PTP', value1],
                        ]
                    });
                    shipped_cogs_mtd.unload({
                        ids: ['Shipped Units']
                    });
                }, 1000);
                break;
            case 1:
                SC_MTD.shipped_units_percent != null ? value = SC_MTD.shipped_units_percent : value = 0;
                SC_MTD.ptp_shipped_units_percent != null ? value1 = SC_MTD.ptp_shipped_units_percent : value1 = 0;
                SC_MTD.shipped_units_mtd != null ? $('#sc_mtd').html(SC_MTD.shipped_units_mtd) : $('#sc_mtd').html("-");
                $('#sc_mtd_title').html("Shipped Units MTD");
                gauge_value1 = parseInt(value);
                gauge_value2 =  parseInt(value1);
                gauge_min = 0;
                gauge_max = 100;

                if(gauge_value1 < 0 || gauge_value2 < 0){
                    gauge_min = ((parseInt(gauge_value2/100))*100) - 100;
                    if(gauge_value1 < gauge_value2){
                        gauge_min = ((parseInt(gauge_value1/100))*100) - 100;
                    }
                }
                if(gauge_value1 > 100 || gauge_value2 > 100){
                    gauge_max = ((parseInt(gauge_value2/100))*100) + 100;
                    if(gauge_value1 > gauge_value2){
                        gauge_max = ((parseInt(gauge_value1/100))*100) + 100;
                    }
                }

                setTimeout(function () {
                    shipped_cogs_mtd.internal.config.gauge_max = gauge_max;
                    shipped_cogs_mtd.internal.config.gauge_min = gauge_min;
                    shipped_cogs_mtd.load({
                        columns: [
                            ['Shipped Units', value],
                            ['PTP', value1],
                        ]
                    });
                    shipped_cogs_mtd.unload({
                        ids: ['Shipped COGS']
                    });
                }, 1000);
                break;
            default:
                break;
        }
    }
    function netReceiptsMTD(NR_MTD, report_type) {
        let value = 0;
        let value1 = 0;

        var gauge_min = 0;
        var gauge_max = 100;
        var gauge_value1 = 0;
        var gauge_value2 = 0;
        switch (report_type) {
            case 0:
                NR_MTD.net_received_percent != null ? value = NR_MTD.net_received_percent : value = 0;
                NR_MTD.ptp_net_received_percent != null ? value1 = NR_MTD.ptp_net_received_percent : value1 = 0;
                NR_MTD.net_received_mtd != null ? $('#nr_mtd').html(NR_MTD.net_received_mtd) : $('#nr_mtd').html("-");
                $('#nr_mtd_title').html("Net Received MTD");
                gauge_value1 = parseInt(value);
                gauge_value2 =  parseInt(value1);
                gauge_min = 0;
                gauge_max = 100;

                if(gauge_value1 < 0 || gauge_value2 < 0){
                    gauge_min = ((parseInt(gauge_value2/100))*100) - 100;
                    if(gauge_value1 < gauge_value2){
                        gauge_min = ((parseInt(gauge_value1/100))*100) - 100;
                    }
                }
                if(gauge_value1 > 100 || gauge_value2 > 100){
                    gauge_max = ((parseInt(gauge_value2/100))*100) + 100;
                    if(gauge_value1 > gauge_value2){
                        gauge_max = ((parseInt(gauge_value1/100))*100) + 100;
                    }
                }

                setTimeout(function () {
                    net_receipts_mtd.internal.config.gauge_max = gauge_max;
                    net_receipts_mtd.internal.config.gauge_min = gauge_min;
                    net_receipts_mtd.load({
                        columns: [
                            ['Net Received', value],
                            ['PTP', value1],
                        ]
                    });
                    net_receipts_mtd.unload({
                        ids: ['Net Received Units']
                    });
                }, 1000);
                break;
            case 1:
                NR_MTD.net_received_units_percent != null ? value = NR_MTD.net_received_units_percent : value = 0;
                NR_MTD.ptp_net_received_units_percent != null ? value1 = NR_MTD.ptp_net_received_units_percent : value1 = 0;
                NR_MTD.net_received_units_mtd != null ? $('#nr_mtd').html(NR_MTD.net_received_units_mtd) : $('#nr_mtd').html("-");
                $('#nr_mtd_title').html("Net Received Units MTD");
                gauge_value1 = parseInt(value);
                gauge_value2 =  parseInt(value1);
                gauge_min = 0;
                gauge_max = 100;

                if(gauge_value1 < 0 || gauge_value2 < 0){
                    gauge_min = ((parseInt(gauge_value2/100))*100) - 100;
                    if(gauge_value1 < gauge_value2){
                        gauge_min = ((parseInt(gauge_value1/100))*100) - 100;
                    }
                }
                if(gauge_value1 > 100 || gauge_value2 > 100){
                    gauge_max = ((parseInt(gauge_value2/100))*100) + 100;
                    if(gauge_value1 > gauge_value2){
                        gauge_max = ((parseInt(gauge_value1/100))*100) + 100;
                    }
                }

                setTimeout(function () {
                    net_receipts_mtd.internal.config.gauge_max = gauge_max;
                    net_receipts_mtd.internal.config.gauge_min = gauge_min;
                    net_receipts_mtd.load({
                        columns: [
                            ['Net Received Units', value],
                            ['PTP', value1],
                        ]
                    });
                    net_receipts_mtd.unload({
                        ids: ['Net Received']
                    });
                }, 1000);
                break;
            default:
                break;
        }
    }

    function generateVendorDetailSC(data, report_type, vendorAlerts) {
        var sc_type = '-';
        var sc_value = '-';
        var ptp_sc_value = '-';
        var ptp_graph_value1 = 0;
        var ptp_graph_value2 = 0;

        var gauge_min = 0;
        var gauge_max = 100;
        var gauge_value1 = 0;
        var gauge_value2 = 0;
        $("#vendor_shipped_cog_ytd_card").removeAttr("style");
        switch (report_type) {
            case 0:
                for (var i = 0; i < vendorAlerts.length; i++) {
                    let shipped_cogs = parseInt((data.shipped_cogs_ytd).replace( new RegExp("\\s|,|\\$","gm"),""))
                    let reported_value = parseInt((vendorAlerts[i].reported_value).replace( new RegExp("\\s|,|\\$","gm"),""))
                    if(shipped_cogs == reported_value && vendorAlerts[i].reported_attribute == 'shipped_cogs'){
                        $("#vendor_shipped_cog_ytd_card").attr({
                            "style" : "box-shadow: 1px 4px 25px 0px rgb(255 0 0);"
                        });
                        break;
                    }
                }
                sc_type = "Shipped COGS";
                if(data.shipped_cogs_ytd != null) {
                    sc_value = data.shipped_cogs_ytd;
                }
                if(data.ptp_shipped_cogs_ytd != null) {
                    ptp_sc_value = data.ptp_shipped_cogs_ytd;
                }
                if(data.shipped_cogs_percent != null) {
                    ptp_graph_value1 = data.shipped_cogs_percent;
                }
                if(data.ptp_shipped_cogs_percent != null) {
                    ptp_graph_value2 = data.ptp_shipped_cogs_percent;
                }
                gauge_value1 = parseInt(ptp_graph_value1);
                gauge_value2 =  parseInt(ptp_graph_value2);
                gauge_min = 0;
                gauge_max = 100;

                if(gauge_value1 < 0 || gauge_value2 < 0){
                    gauge_min = ((parseInt(gauge_value2/100))*100) - 100;
                    if(gauge_value1 < gauge_value2){
                        gauge_min = ((parseInt(gauge_value1/100))*100) - 100;
                    }
                }
                if(gauge_value1 > 100 || gauge_value2 > 100){
                    gauge_max = ((parseInt(gauge_value2/100))*100) + 100;
                    if(gauge_value1 > gauge_value2){
                        gauge_max = ((parseInt(gauge_value1/100))*100) + 100;
                    }
                }

                setTimeout(function () {
                    vendor_shipped_cogs_ytd.internal.config.gauge_max = gauge_max;
                    vendor_shipped_cogs_ytd.internal.config.gauge_min = gauge_min;
                    vendor_shipped_cogs_ytd.load({
                        columns: [
                            ['Shipped COGS', ptp_graph_value1],
                            ['PTP', ptp_graph_value2],
                        ],
                    });
                    vendor_shipped_cogs_ytd.unload({
                        ids: ['Shipped Units']
                    });
                }, 1000);
                break;
            case 1:
                for (var i = 0; i < vendorAlerts.length; i++) {
                    let shipped_cogs = parseInt((data.shipped_units_ytd).replace( new RegExp("\\s|,|\\$","gm"),""))
                    let reported_value = parseInt((vendorAlerts[i].reported_value).replace( new RegExp("\\s|,|\\$","gm"),""))
                    if(shipped_cogs == reported_value && vendorAlerts[i].reported_attribute == 'shipped_unit'){
                        $("#vendor_shipped_cog_ytd_card").attr({
                            "style" : "box-shadow: 1px 4px 25px 0px rgb(255 0 0);"
                        });
                        break;
                    }
                }
                sc_type = "Shipped Units";
                if(data.shipped_units_ytd != null) {
                    sc_value = data.shipped_units_ytd
                }
                if(data.ptp_shipped_units_ytd != null) {
                    ptp_sc_value = data.ptp_shipped_units_ytd
                }
                if(data.shipped_units_percent != null) {
                    ptp_graph_value1 = data.shipped_units_percent
                }
                if(data.ptp_shipped_units_percent != null) {
                    ptp_graph_value2 = data.ptp_shipped_units_percent
                }
                gauge_value1 = parseInt(ptp_graph_value1);
                gauge_value2 =  parseInt(ptp_graph_value2);
                gauge_min = 0;
                gauge_max = 100;

                if(gauge_value1 < 0 || gauge_value2 < 0){
                    gauge_min = ((parseInt(gauge_value2/100))*100) - 100;
                    if(gauge_value1 < gauge_value2){
                        gauge_min = ((parseInt(gauge_value1/100))*100) - 100;
                    }
                }
                if(gauge_value1 > 100 || gauge_value2 > 100){
                    gauge_max = ((parseInt(gauge_value2/100))*100) + 100;
                    if(gauge_value1 > gauge_value2){
                        gauge_max = ((parseInt(gauge_value1/100))*100) + 100;
                    }
                }

                setTimeout(function () {
                    vendor_shipped_cogs_ytd.internal.config.gauge_max = gauge_max;
                    vendor_shipped_cogs_ytd.internal.config.gauge_min = gauge_min;
                    vendor_shipped_cogs_ytd.load({
                        columns: [
                            ['Shipped Units', ptp_graph_value1],
                            ['PTP', ptp_graph_value2],
                        ],
                    });
                    vendor_shipped_cogs_ytd.unload({
                        ids: ['Shipped COGS']
                    });
                }, 1000);
                break;
            default:
                break;
        }
        $('#vendor_sc_type').html(sc_type);
        $('#vendor_sc_value').html(sc_value);
        $('#vendor_ptp_sc_value').html(ptp_sc_value);
        $('#vendor_ptp_sc_ytd_percentage').html(ptp_graph_value2+"% ");
    }
    function generateVendorDetailNR(data, report_type, vendorAlerts) {
        var nr_type = '-';
        var nr_value = '-';
        var ptp_nr_value = '-';
        var ptp_graph_value1 = 0;
        var ptp_graph_value2 = 0;
        nr_ytd_title_vendor = "-";

        var gauge_min = 0;
        var gauge_max = 100;
        var gauge_value1 = 0;
        var gauge_value2 = 0;

        switch (report_type) {
            case 0:
                nr_type = "Net Received";
                if(data.net_received_ytd != null) {
                    nr_value = data.net_received_ytd
                }
                if(data.ptp_net_received_ytd != null) {
                    ptp_nr_value = data.ptp_net_received_ytd
                }
                if(data.net_received_percent != null) {
                    ptp_graph_value1 = data.net_received_percent
                }
                if(data.ptp_net_received_percent != null) {
                    ptp_graph_value2 = data.ptp_net_received_percent
                }
                gauge_value1 = parseInt(ptp_graph_value1);
                gauge_value2 =  parseInt(ptp_graph_value2);
                gauge_min = 0;
                gauge_max = 100;

                if(gauge_value1 < 0 || gauge_value2 < 0){
                    gauge_min = ((parseInt(gauge_value2/100))*100) - 100;
                    if(gauge_value1 < gauge_value2){
                        gauge_min = ((parseInt(gauge_value1/100))*100) - 100;
                    }
                }
                if(gauge_value1 > 100 || gauge_value2 > 100){
                    gauge_max = ((parseInt(gauge_value2/100))*100) + 100;
                    if(gauge_value1 > gauge_value2){
                        gauge_max = ((parseInt(gauge_value1/100))*100) + 100;
                    }
                }

                setTimeout(function () {
                    vendor_net_receipts_ytd.internal.config.gauge_max = gauge_max;
                    vendor_net_receipts_ytd.internal.config.gauge_min = gauge_min;
                    vendor_net_receipts_ytd.load({
                        columns: [
                            ['Net Received', ptp_graph_value1],
                            ['PTP', ptp_graph_value2],
                        ],
                    });
                    vendor_net_receipts_ytd.unload({
                        ids: ['Net Received Units']
                    });
                }, 1000);
                break;
            case 1:
                nr_type = "Net Received Units";
                if(data.net_received_units_ytd != null) {
                    nr_value = data.net_received_units_ytd
                }
                if(data.ptp_net_received_units_ytd != null) {
                    ptp_nr_value = data.ptp_net_received_units_ytd
                }
                if(data.net_received_units_percent != null) {
                    ptp_graph_value1 = data.net_received_units_percent
                }
                if(data.ptp_net_received_units_percent != null) {
                    ptp_graph_value2 = data.ptp_net_received_units_percent
                }
                gauge_value1 = parseInt(ptp_graph_value1);
                gauge_value2 =  parseInt(ptp_graph_value2);
                gauge_min = 0;
                gauge_max = 100;

                if(gauge_value1 < 0 || gauge_value2 < 0){
                    gauge_min = ((parseInt(gauge_value2/100))*100) - 100;
                    if(gauge_value1 < gauge_value2){
                        gauge_min = ((parseInt(gauge_value1/100))*100) - 100;
                    }
                }
                if(gauge_value1 > 100 || gauge_value2 > 100){
                    gauge_max = ((parseInt(gauge_value2/100))*100) + 100;
                    if(gauge_value1 > gauge_value2){
                        gauge_max = ((parseInt(gauge_value1/100))*100) + 100;
                    }
                }

                setTimeout(function () {
                    vendor_net_receipts_ytd.internal.config.gauge_max = gauge_max;
                    vendor_net_receipts_ytd.internal.config.gauge_min = gauge_min;
                    vendor_net_receipts_ytd.load({
                        columns: [
                            ['Net Received Units', ptp_graph_value1],
                            ['PTP', ptp_graph_value2],
                        ],
                    });
                    vendor_net_receipts_ytd.unload({
                        ids: ['Net Received']
                    });
                }, 1000);
                break;
            default:
                break;
        }
        $('#vendor_nr_type').html(nr_type);
        $('#vendor_nr_value').html(nr_value);
        $('#vendor_ptp_nr_value').html(ptp_nr_value);
        $('#vendor_ptp_nr_ytd_percentage').html(ptp_graph_value2+"% ");
    }

    function generateVendorDetailSCMTD(data, report_type, vendorAlerts) {
        var sc_type = '-';
        var sc_value = '-';
        var ptp_sc_value = '-';
        var ptp_graph_value = 0;

        var gauge_min = 0;
        var gauge_max = 100;
        var gauge_value1 = 0;

        $("#vendor_shipped_cog_mtd_card").removeAttr("style");

        switch (report_type) {
            case 0:
                for (var i = 0; i < vendorAlerts.length; i++) {
                    let shipped_cogs = parseInt((data.shipped_cogs_mtd).replace( new RegExp("\\s|,|\\$","gm"),""))
                    let reported_value = parseInt((vendorAlerts[i].reported_value).replace( new RegExp("\\s|,|\\$","gm"),""))
                    if(shipped_cogs == reported_value && vendorAlerts[i].reported_attribute == 'shipped_cogs'){
                        $("#vendor_shipped_cog_mtd_card").attr({
                            "style" : "box-shadow: 1px 4px 25px 0px rgb(255 0 0);"
                        });
                        break
                    }
                }
                sc_type = "Shipped COGS";
                if(data.shipped_cogs_mtd != null) {
                    sc_value = data.shipped_cogs_mtd;
                }
                if(data.shipped_cogs_percent != null) {
                    ptp_graph_value = data.shipped_cogs_percent;
                }
                if(data.ptp_shipped_cogs_mtd != null ) {
                    ptp_sc_value = data.ptp_shipped_cogs_mtd
                }
                gauge_value1 = parseInt(ptp_graph_value);
                gauge_min = 0;
                gauge_max = 100;

                if(gauge_value1 < 0 ){
                    gauge_min = ((parseInt(gauge_value1/100))*100) - 100;
                }
                if(gauge_value1 > 100 ){
                    gauge_max = ((parseInt(gauge_value1/100))*100) + 100;
                }

                setTimeout(function () {
                    vendor_shipped_cogs_mtd.internal.config.gauge_max = gauge_max;
                    vendor_shipped_cogs_mtd.internal.config.gauge_min = gauge_min;
                    vendor_shipped_cogs_mtd.load({
                        columns: [
                            ['PTP', ptp_graph_value],
                        ],
                    });
                }, 1000);
                break;
            case 1:
                for (var i = 0; i < vendorAlerts.length; i++) {
                    let shipped_cogs = parseInt((data.shipped_units_mtd).replace( new RegExp("\\s|,|\\$","gm"),""))
                    let reported_value = parseInt((vendorAlerts[i].reported_value).replace( new RegExp("\\s|,|\\$","gm"),""))
                    if(shipped_cogs == reported_value && vendorAlerts[i].reported_attribute == 'shipped_unit'){
                        $("#vendor_shipped_cog_mtd_card").attr({
                            "style" : "box-shadow: 1px 4px 25px 0px rgb(255 0 0);"
                        });
                        break
                    }
                }
                sc_type = "Shipped Units";
                if(data.shipped_units_mtd != null) {
                    sc_value = data.shipped_units_mtd;
                }
                if(data.shipped_units_percent != null) {
                    ptp_graph_value = data.shipped_units_percent;
                }
                if(data.ptp_shipped_units_mtd != null ) {
                    ptp_sc_value = data.ptp_shipped_units_mtd
                }
                gauge_value1 = parseInt(ptp_graph_value);
                gauge_min = 0;
                gauge_max = 100;

                if(gauge_value1 < 0 ){
                    gauge_min = ((parseInt(gauge_value1/100))*100) - 100;
                }
                if(gauge_value1 > 100 ){
                    gauge_max = ((parseInt(gauge_value1/100))*100) + 100;
                }

                setTimeout(function () {
                    vendor_shipped_cogs_mtd.internal.config.gauge_max = gauge_max;
                    vendor_shipped_cogs_mtd.internal.config.gauge_min = gauge_min;
                    vendor_shipped_cogs_mtd.load({
                        columns: [
                            ['PTP', ptp_graph_value],
                        ],
                    });
                }, 1000);
                break;
            default:
                break;
        }
        $('#vendor_sc_type_mtd').html(sc_type);
        $('#vendor_sc_value_mtd').html(sc_value);
        $('#vendor_ptp_sc_value_mtd').html(ptp_sc_value);
        $('#vendor_ptp_sc_mtd_percentage').html(ptp_graph_value+"% ");
    }
    function generateVendorDetailNRMTD(data, report_type, vendorAlerts) {
        var nr_type = '-';
        var nr_value = '-';
        var ptp_nr_value = '-';
        var ptp_graph_value = 0;

        var gauge_min = 0;
        var gauge_max = 100;
        var gauge_value1 = 0;

        switch (report_type) {
            case 0:
                nr_type = "Net Received";
                if(data.net_received_mtd != null) {
                    nr_value = data.net_received_mtd
                }
                if(data.net_received_percent != null) {
                    ptp_graph_value = data.net_received_percent
                }
                if(data.ptp_net_received_mtd != null ) {
                    ptp_nr_value = data.ptp_net_received_mtd
                }
                gauge_value1 = parseInt(ptp_graph_value);
                gauge_min = 0;
                gauge_max = 100;

                if(gauge_value1 < 0 ){
                    gauge_min = ((parseInt(gauge_value1/100))*100) - 100;
                }
                if(gauge_value1 > 100 ){
                    gauge_max = ((parseInt(gauge_value1/100))*100) + 100;
                }

                setTimeout(function () {
                    vendor_net_receipts_mtd.internal.config.gauge_max = gauge_max;
                    vendor_net_receipts_mtd.internal.config.gauge_min = gauge_min;
                    vendor_net_receipts_mtd.load({
                        columns: [
                            ['PTP', ptp_graph_value],
                        ],
                    });
                }, 1000);
                break;
            case 1:
                nr_type = "Net Received Units";
                if(data.net_received_units_mtd != null) {
                    nr_value = data.net_received_units_mtd
                }
                if(data.net_received_units_percent != null) {
                    ptp_graph_value = data.net_received_units_percent
                }
                if(data.ptp_net_received_units_mtd != null ) {
                    ptp_nr_value = data.ptp_net_received_units_mtd
                }
                gauge_value1 = parseInt(ptp_graph_value);
                gauge_min = 0;
                gauge_max = 100;

                if(gauge_value1 < 0 ){
                    gauge_min = ((parseInt(gauge_value1/100))*100) - 100;
                }
                if(gauge_value1 > 100 ){
                    gauge_max = ((parseInt(gauge_value1/100))*100) + 100;
                }

                setTimeout(function () {
                    vendor_net_receipts_mtd.internal.config.gauge_max = gauge_max;
                    vendor_net_receipts_mtd.internal.config.gauge_min = gauge_min;
                    vendor_net_receipts_mtd.load({
                        columns: [
                            ['PTP', ptp_graph_value],
                        ],
                    });
                }, 1000);
                break;
            default:
                break;
        }
        $('#vendor_nr_type_mtd').html(nr_type);
        $('#vendor_nr_value_mtd').html(nr_value);
        $('#vendor_ptp_nr_value_mtd').html(ptp_nr_value);
        $('#vendor_ptp_nr_mtd_percentage').html(ptp_graph_value+"% ");
    }

    function vendorShippedCogsTrailing(DOM_id, vendor_id, report_type) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        let value = $("#filter_range_picker").val();
        let firstDate = moment(value, "MMM-YYYY").startOf('month').format("MM/DD/YYYY");
        let lastDate = moment(value, "MMM-YYYY").endOf('month').format("MM/DD/YYYY");
        var filter_date_range = firstDate + " - " + lastDate;
        $.ajax({
            url: base_url + "/ed/vendor/trailing/sc",
            type: "POST",
            data: {
                type: report_type,
                vendor: vendor_id,
                date_range: filter_date_range,
            },
            cache: false,
            success: function (response) {
                if(response.shippedCogsTrailing){
                    let responsedata = response.shippedCogsTrailing;
                    let month = [];
                    let value = [];
                    switch (report_type) {
                        case 0:
                            var chart = c3.generate({
                                bindto: d3.select('#'+DOM_id),
                                data: {
                                    x: 'x',
                                    json: {
                                        'x': [],
                                        'Shipped COGS': [],
                                    },
                                    colors: {
                                        'Shipped COGS': '#00A5B5',
                                    },
                                    types: {
                                        'Shipped COGS': 'bar',
                                    },
                                },
                                axis: {
                                    x: {
                                        type: 'category',
                                        show: false
                                    },
                                    y: {
                                        show: false,
                                        tick: {
                                            format: d3.format('$,')
                                        }
                                    },
                                },
                                size: {
                                    height: 40,
                                },
                                bar: {
                                    width: {
                                        ratio: 0.8 // this makes bar width 50% of length between ticks
                                    },
                                },
                                transition: {
                                    duration: 100
                                },
                                legend: {
                                    show: false,
                                },
                            });
                            for (var count = 0; count < responsedata.length; count++) {
                                month[count] = responsedata[count].month_name;
                                value[count] = parseInt(responsedata[count].shipped_cogs);
                            }
                            setTimeout(function () {
                                chart.load({
                                    json: {
                                        'x': month,
                                        'Shipped COGS': value,
                                    },
                                });
                            }, 1000);
                            break;
                        case 1:
                            var chart = c3.generate({
                                bindto: d3.select('#'+DOM_id),
                                data: {
                                    x: 'x',
                                    json: {
                                        'x': [],
                                        'Shipped Unit': [],
                                    },
                                    colors: {
                                        'Shipped Unit': '#00A5B5',
                                    },
                                    types: {
                                        'Shipped Unit': 'bar',
                                    },
                                },
                                axis: {
                                    x: {
                                        type: 'category',
                                        show: false
                                    },
                                    y: {
                                        show: false,
                                        tick: {
                                            format: d3.format(',')
                                        }
                                    },
                                },
                                size: {
                                    height: 40,
                                },
                                bar: {
                                    width: {
                                        ratio: 0.8 // this makes bar width 50% of length between ticks
                                    },
                                },
                                transition: {
                                    duration: 100
                                },
                                legend: {
                                    show: false,
                                },
                            });
                            for (var count = 0; count < responsedata.length; count++) {
                                month[count] = responsedata[count].month_name;
                                value[count] = parseInt(responsedata[count].shipped_units);
                            }
                            setTimeout(function () {
                                chart.load({
                                    json: {
                                        'x': month,
                                        'Shipped Unit': value,
                                    },
                                });
                            }, 1000);
                            break;
                        default:
                            break;
                    }
                }
            },
        });
    }
    function vendorNetReceivedTrailing(DOM_id, vendor_id, report_type) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        let value = $("#filter_range_picker").val();
        let firstDate = moment(value, "MMM-YYYY").startOf('month').format("MM/DD/YYYY");
        let lastDate = moment(value, "MMM-YYYY").endOf('month').format("MM/DD/YYYY");
        var filter_date_range = firstDate + " - " + lastDate;
        $.ajax({
            url: base_url + "/ed/vendor/trailing/nr",
            type: "POST",
            data: {
                type: report_type,
                vendor: vendor_id,
                date_range: filter_date_range,
            },
            cache: false,
            success: function (response) {
                if(response.netReceivedTrailing){
                    let responsedata = response.netReceivedTrailing;
                    let month = [];
                    let value = [];
                    switch (report_type) {
                        case 0:
                            var chart = c3.generate({
                                bindto: d3.select('#'+DOM_id),
                                data: {
                                    x: 'x',
                                    json: {
                                        'x': [],
                                        'Net Received': [],
                                    },
                                    colors: {
                                        'Net Received': '#00A5B5',
                                    },
                                    types: {
                                        'Net Received': 'bar',
                                    },
                                },
                                axis: {
                                    x: {
                                        type: 'category',
                                        show: false
                                    },
                                    y: {
                                        show: false,
                                        tick: {
                                            format: d3.format('$,')
                                        }
                                    },
                                },
                                size: {
                                    height: 40,
                                },
                                bar: {
                                    width: {
                                        ratio: 0.8 // this makes bar width 50% of length between ticks
                                    },
                                },
                                transition: {
                                    duration: 100
                                },
                                legend: {
                                    show: false,
                                },
                            });
                            for (var count = 0; count < responsedata.length; count++) {
                                month[count] = responsedata[count].month_name;
                                value[count] = parseInt(responsedata[count].net_received);
                            }
                            setTimeout(function () {
                                chart.load({
                                    json: {
                                        'x': month,
                                        'Net Received': value,
                                    },
                                });
                            }, 1000);
                            break;
                        case 1:
                            var chart = c3.generate({
                                bindto: d3.select('#'+DOM_id),
                                data: {
                                    x: 'x',
                                    json: {
                                        'x': [],
                                        'Net Received Units': [],
                                    },
                                    colors: {
                                        'Net Received Units': '#00A5B5',
                                    },
                                    types: {
                                        'Net Received Units': 'bar',
                                    },
                                },
                                axis: {
                                    x: {
                                        type: 'category',
                                        show: false
                                    },
                                    y: {
                                        show: false,
                                        tick: {
                                            format: d3.format(',')
                                        }
                                    },
                                },
                                size: {
                                    height: 40,
                                },
                                bar: {
                                    width: {
                                        ratio: 0.8 // this makes bar width 50% of length between ticks
                                    },
                                },
                                transition: {
                                    duration: 100
                                },
                                legend: {
                                    show: false,
                                },
                            });
                            for (var count = 0; count < responsedata.length; count++) {
                                month[count] = responsedata[count].month_name;
                                value[count] = parseInt(responsedata[count].net_received_units);
                            }
                            setTimeout(function () {
                                chart.load({
                                    json: {
                                        'x': month,
                                        'Net Received Units': value,
                                    },
                                });
                            }, 1000);
                            break;
                        default:
                            break;
                    }
                }
            },
        });
    }

    function shippedCogsTable(shippedCogsTable, report_type, filter_date_range) {
        switch (report_type) {
            case 0:
                $("#sc_table_cm").attr({
                    "title" : "Current Month Shipped COGS"
                });
                $("#sc_table_py").attr({
                    "title" : "Previous Year Shipped COGS"
                });
                $('#sc_table').html('Shipped COGS');
                break;
            case 1:
                $("#sc_table_cm").attr({
                    "title" : "Current Month Shipped Unit"
                });
                $("#sc_table_py").attr({
                    "title" : "Previous Year Shipped Unit"
                });
                $('#sc_table').html('Shipped Unit');
                break;
        }
        let html = '';
        if(shippedCogsTable.length == 0){
            html =  "<tr>\n" +
                "    <td style='padding: 10px; white-space: nowrap;' align='center' colspan='7'>No data found</td>\n" +
                "</tr>";
            $('#all_vendor_shipped_cogs_ytd').html(html);
            return;
        }
        let Last6_Trending = [];
        let Last6_Trending_vendor = [];
        switch (report_type) {
            case 0:
                for (var count = 0; count < shippedCogsTable.length; count++) {
                    let tr_style = '';
                    if (shippedCogsTable[count].alert == "yes"){
                        tr_style = "table-danger";
                    }
                    html += "<tr class='"+tr_style+"'>\n" +
                        "        <td style='padding: 10px; white-space: nowrap;'>"+shippedCogsTable[count].fk_vendor_name+"</td>\n" +
                        "        <td style='padding: 10px; white-space: nowrap;'>"+shippedCogsTable[count].shipped_cogs+"</td>\n" +
                        "        <td style='padding: 10px; white-space: nowrap;'>"+shippedCogsTable[count].dropship+"</td>\n" +
                        "        <td style='padding: 10px; white-space: nowrap;'>"+shippedCogsTable[count].previous_shipped_cogs+"</td>\n" +
                        "        <td style='padding: 10px; white-space: nowrap;'>"+shippedCogsTable[count].ptp+"</td>\n" +
                        "        <td style='padding: 10px; white-space: nowrap;'>"+shippedCogsTable[count].yoy+"</td>\n" +
                        "        <td style='padding: 5px;'><span id='last"+count+"-trend-sc-ytd'></span></td>\n" +
                        "</tr>";
                    Last6_Trending[count] = "last"+count+"-trend-sc-ytd";
                    Last6_Trending_vendor[count] = shippedCogsTable[count].fk_vendor_id;
                }
                break;
            case 1:

                for (var count = 0; count < shippedCogsTable.length; count++) {
                    let tr_style = '';
                    if (shippedCogsTable[count].alert == "yes"){
                        tr_style = "table-danger";
                    }
                    html += "<tr class='"+tr_style+"'>\n" +
                        "        <td style='padding: 10px; white-space: nowrap;'>"+shippedCogsTable[count].fk_vendor_name+"</td>\n" +
                        "        <td style='padding: 10px; white-space: nowrap;'>"+shippedCogsTable[count].shipped_units+"</td>\n" +
                        "        <td style='padding: 10px; white-space: nowrap;'>"+shippedCogsTable[count].dropship+"</td>\n" +
                        "        <td style='padding: 10px; white-space: nowrap;'>"+shippedCogsTable[count].previous_shipped_units+"</td>\n" +
                        "        <td style='padding: 10px; white-space: nowrap;'>"+shippedCogsTable[count].ptp+"</td>\n" +
                        "        <td style='padding: 10px; white-space: nowrap;'>"+shippedCogsTable[count].yoy+"</td>\n" +
                        "        <td style='padding: 5px;'><span id='last"+count+"-trend-sc-ytd'></span></td>\n" +
                        "</tr>";
                    Last6_Trending[count] = "last"+count+"-trend-sc-ytd";
                    Last6_Trending_vendor[count] = shippedCogsTable[count].fk_vendor_id;
                }
                break;
            default:
                break;
        }
        $('#all_vendor_shipped_cogs_ytd').html(html);
        for (var count = 0; count < Last6_Trending.length; count++) {
            vendorShippedCogsTrailing(Last6_Trending[count], Last6_Trending_vendor[count], report_type, filter_date_range);
        }
    }
    function netReceivedTable(netReceivedTable, report_type, filter_date_range) {
        let html = '';
        switch (report_type) {
            case 0:
                $("#nr_table_cm").attr({
                    "title" : "Current Month Net Receipts"
                });
                $("#nr_table_py").attr({
                    "title" : "Previous Year Net Receipts"
                });
                $('#nr_table').html('Net Receipts');
                break;
            case 1:
                $("#nr_table_cm").attr({
                    "title" : "Current Month Net Receipts Unit"
                });
                $("#nr_table_py").attr({
                    "title" : "Previous Year Net Receipts Unit"
                });
                $('#nr_table').html('Net Receipts Unit');
                break;
        }
        if(netReceivedTable.length == 0){
            html =  "<tr>\n" +
                "    <td style='padding: 10px; white-space: nowrap;' align='center' colspan='6'>No data found</td>\n" +
                "</tr>";
            $('#all_vendor_net_receipts_ytd').html(html);
            return;
        }
        let Last6_Trending = [];
        let Last6_Trending_vendor = [];
        switch (report_type) {
            case 0:
                for (var count = 0; count < netReceivedTable.length; count++) {
                    html += "<tr>\n" +
                        "        <td style='padding: 10px; white-space: nowrap;'>"+netReceivedTable[count].fk_vendor_name+"</td>\n" +
                        "        <td style='padding: 10px; white-space: nowrap;'>"+netReceivedTable[count].net_received+"</td>\n" +
                        "        <td style='padding: 10px; white-space: nowrap;'>"+netReceivedTable[count].previous_net_received+"</td>\n" +
                        "        <td style='padding: 10px; white-space: nowrap;'>"+netReceivedTable[count].ptp+"</td>\n" +
                        "        <td style='padding: 10px; white-space: nowrap;'>"+netReceivedTable[count].yoy+"</td>\n" +
                        "        <td style='padding: 5px;'><span id='last"+count+"-trend-nr-ytd'></span></td>\n" +
                        "</tr>";
                    Last6_Trending[count] = "last"+count+"-trend-nr-ytd";
                    Last6_Trending_vendor[count] = netReceivedTable[count].fk_vendor_id;
                }
                break;
            case 1:
                for (var count = 0; count < netReceivedTable.length; count++) {
                    html += "<tr>\n" +
                        "        <td style='padding: 10px; white-space: nowrap;'>"+netReceivedTable[count].fk_vendor_name+"</td>\n" +
                        "        <td style='padding: 10px; white-space: nowrap;'>"+netReceivedTable[count].net_received_units+"</td>\n" +
                        "        <td style='padding: 10px; white-space: nowrap;'>"+netReceivedTable[count].previous_net_received_units+"</td>\n" +
                        "        <td style='padding: 10px; white-space: nowrap;'>"+netReceivedTable[count].ptp+"</td>\n" +
                        "        <td style='padding: 10px; white-space: nowrap;'>"+netReceivedTable[count].yoy+"</td>\n" +
                        "        <td style='padding: 5px;'><span id='last"+count+"-trend-nr-ytd'></span></td>\n" +
                        "</tr>";
                    Last6_Trending[count] = "last"+count+"-trend-nr-ytd";
                    Last6_Trending_vendor[count] = netReceivedTable[count].fk_vendor_id;
                }
                break;
            default:
                break;
        }
        $('#all_vendor_net_receipts_ytd').html(html);
        for (var count = 0; count < Last6_Trending.length; count++) {
            vendorNetReceivedTrailing(Last6_Trending[count], Last6_Trending_vendor[count], report_type, filter_date_range);
        }
    }
});
