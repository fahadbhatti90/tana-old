document.addEventListener('DOMContentLoaded', function (e) {

    $("#filter_range_picker").datetimepicker({
        format: 'MM/DD/YYYY',
        minDate: '01/01/2018',
        maxDate: new Date(),
        viewMode: 'months',
        date: new Date(),
    });

    $("#vendor_filter_vendor").select2({
        dropdownParent: $("#top3_vendor_filter_form"),
        maximumSelectionLength: 3,
        language: {
            noResults: function(e){
                return "No vendor found";
            },
            maximumSelected: function (e) {
                return "Max " + e.maximum + " vendors can be selected";
            }
        }
    });

    let value = $("#filter_range_picker").val();
    let firstDate = moment(value, "MM/DD/YYYY").day(3).format("MM/DD/YYYY");
    let lastDate = moment(value, "MM/DD/YYYY").day(9).format("MM/DD/YYYY");
    var date_filter_range_value = moment(value, "MM/DD/YYYY").day(3).format("MMM DD, YYYY")+" - "+moment(value, "MM/DD/YYYY").day(9).format("MMM DD, YYYY");
    if(moment(value, "MM/DD/YYYY").day() <= 2){
        lastDate = moment(value, "MM/DD/YYYY").day(2).format("MM/DD/YYYY");
        firstDate = moment(value, "MM/DD/YYYY").isoWeekday(-4).format("MM/DD/YYYY");
        date_filter_range_value = moment(value, "MM/DD/YYYY").isoWeekday(-4).format("MMM DD, YYYY")+" - "+moment(value, "MM/DD/YYYY").day(2).format("MMM DD, YYYY");
    }
    $("#ed_filter_date_range").val(firstDate + " - " + lastDate);
    $("#selected_date_text").html(date_filter_range_value);
    let filter_date_range = $('#ed_filter_date_range').val();
    let checkbox = document.getElementById("dollar-unit-switch").checked;
    let report_type = 0; //for dollar
    if (checkbox === false){
        report_type = 1; //for unit
    }
    generateEDReport(report_type, filter_date_range);
    generateVendorEDReport(report_type, filter_date_range);

    //on Changing Dollar/Unit Toggle
    $('#dollar-unit-switch').on('click', function (ev, picker) {
        let filter_date_range = $('#ed_filter_date_range').val();
        let checkbox = document.getElementById("dollar-unit-switch").checked;
        let report_type = 0; //for dollar
        if (checkbox === false){
            report_type = 1; //for unit
        }
        generateEDReport(report_type, filter_date_range);
    });

    //on Submitting Vendor
    $('#top3_vendor_filter_form').on('submit', function (event) {
        event.preventDefault();

        let value = $("#filter_range_picker").val();
        var firstDate = moment(value, "MM/DD/YYYY").day(3).format("MM/DD/YYYY");
        var lastDate = moment(value, "MM/DD/YYYY").day(9).format("MM/DD/YYYY");
        var date_filter_range_value = moment(value, "MM/DD/YYYY").day(3).format("MMM DD, YYYY")+" - "+moment(value, "MM/DD/YYYY").day(9).format("MMM DD, YYYY");
        if(moment(value, "MM/DD/YYYY").day() <= 2){
            lastDate = moment(value, "MM/DD/YYYY").day(2).format("MM/DD/YYYY");
            firstDate = moment(value, "MM/DD/YYYY").isoWeekday(-4).format("MM/DD/YYYY");
            date_filter_range_value = moment(value, "MM/DD/YYYY").isoWeekday(-4).format("MMM DD, YYYY")+" - "+moment(value, "MM/DD/YYYY").day(2).format("MMM DD, YYYY");
        }
        $("#ed_filter_date_range").val(firstDate + " - " + lastDate);
        $("#selected_date_text").html(date_filter_range_value);
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
            url: base_url + "/ed/confirmPO/vendor/store",
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

                    let value = $("#filter_range_picker").val();
                    let firstDate = moment(value, "MM/DD/YYYY").day(3).format("MM/DD/YYYY");
                    let lastDate = moment(value, "MM/DD/YYYY").day(9).format("MM/DD/YYYY");
                    var date_filter_range_value = moment(value, "MM/DD/YYYY").day(3).format("MMM DD, YYYY")+" - "+moment(value, "MM/DD/YYYY").day(9).format("MMM DD, YYYY");
                    if(moment(value, "MM/DD/YYYY").day() <= 2){
                        lastDate = moment(value, "MM/DD/YYYY").day(2).format("MM/DD/YYYY");
                        firstDate = moment(value, "MM/DD/YYYY").isoWeekday(-4).format("MM/DD/YYYY");
                        date_filter_range_value = moment(value, "MM/DD/YYYY").isoWeekday(-4).format("MMM DD, YYYY")+" - "+moment(value, "MM/DD/YYYY").day(2).format("MMM DD, YYYY");
                    }
                    $("#ed_filter_date_range").val(firstDate + " - " + lastDate);
                    $("#selected_date_text").html(date_filter_range_value);
                    let filter_date_range = $('#ed_filter_date_range').val();
                    let checkbox = document.getElementById("dollar-unit-switch").checked;
                    let report_type = 0; //for dollar
                    if (checkbox === false){
                        report_type = 1; //for unit
                    }
                    generateEDReport(report_type, filter_date_range);
                    generateVendorEDReport(report_type, filter_date_range);
                }
            },
        });
    });

    function generateEDReport(report_type, filter_date_range){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: base_url + "/ed/confirmPO/report",
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
                    if (response.po_report[0]) {
                        confirmedPOYTD(response.po_report[0], report_type);
                    }else{
                        setTimeout(function () {
                            WeeklyConfirmedPO.internal.config.gauge_max = 100;
                            WeeklyConfirmedPO.internal.config.gauge_min = 0;
                            WeeklyConfirmedPO.load({
                                json: {
                                    'Plan Reached': 0,
                                },
                            });
                        }, 1000);
                    }
                    if (response.po_report_all_vendor) {
                        allVendorsWeeklyConfirmedPO(response.po_report_all_vendor, report_type);
                    }
                }
            },
        });
    }
    function generateVendorEDReport(report_type, filter_date_range) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: base_url + "/ed/confirmPO/report/vendor",
            type: "POST",
            data: {
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
                    $('#vendor1-card').attr("hidden", true);
                    $('#vendor2-card').attr("hidden", true);
                    $('#vendor3-card').attr("hidden", true);
                    if (response.vendor1) {
                        vendor1POConfirmedRate(response.vendor1);
                    }
                    if (response.vendor2) {
                        vendor2POConfirmedRate(response.vendor2);
                    }
                    if (response.vendor3) {
                        vendor3POConfirmedRate(response.vendor3);
                    }
                }
            },
        });

    }

    var WeeklyConfirmedPO = c3.generate({
        bindto: d3.select('#po-confirmed'),
        data: {
            columns: [
                ['Plan Reached', 0]
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
            pattern: ['#00A5B5', '#E15829']
        },
        size: {
            height: 170
        },
        transition: {
            duration: 100
        },
        legend: {
            show: true,
            position: 'inset',
            inset: {
                anchor: 'top-right',
                x: undefined,
                y: undefined,
                step: undefined
            }
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

    function confirmedPOYTD(po_report, report_type) {
        let value1 = 0;

        let gauge_min = 0;
        let gauge_max = 100;
        let gauge_value1 = 0;
        switch (report_type) {
            case 0:
                po_report.po_ytd_value != null ? $('#confirm_po_ytd').html(po_report.po_ytd_value)  : $('#confirm_po_ytd').html('-');
                po_report.po_percent != null ? value1 = po_report.po_percent : value1 = 0;
                break;
            case 1:
                po_report.po_ytd_units != null ? $('#confirm_po_ytd').html(po_report.po_ytd_units) : $('#confirm_po_ytd').html('-');
                po_report.po_percent != null ? value1 = po_report.po_percent : value1 = 0;
                break;
            default:
                break;
        }


        gauge_value1 = parseInt(value1);

        if(gauge_value1 < 0 ){
            gauge_min = ((parseInt(gauge_value1/100))*100) - 100;
        }
        if(gauge_value1 > 100 ){
            gauge_max = ((parseInt(gauge_value1/100))*100) + 100;
        }

        setTimeout(function () {
            WeeklyConfirmedPO.internal.config.gauge_max = gauge_max;
            WeeklyConfirmedPO.internal.config.gauge_min = gauge_min;
            WeeklyConfirmedPO.load({
                json: {
                    'Plan Reached': value1,
                },
            });
        }, 1000);
    }
    function vendor1POConfirmedRate(vendor_data) {
        let record1 = [];
        let record2 = [];
        let label = [];
        let date_range = [];
        $('#vendor1_chart_heading').html("-");
        $('#vendor1-card').attr("hidden", true);
        let vendor1_PO_confirmed_rate = c3.generate({
            bindto: d3.select('#vendor1_PO_confirmed_rate'),
            data: {
                x: 'x',
                json: {
                    'x': [],
                    'Total Cases': [],
                    'Confirmation Rate': [],
                },
                types: {
                    'Total Cases': 'bar',
                    'Confirmation Rate': 'bar',
                },
                axes: {
                    'Confirmation Rate': 'y2'
                },
            },
            axis: {
                x: {
                    type: 'category',
                    tick: {
                        rotate: -80,
                        multiline: false,
                    },
                    height: 50,
                },
                y: {
                    show: true,
                    label: {
                        text: 'Total Cases',
                        position: 'outer-middle'
                    },
                    tick: {
                        format: d3.format(',')
                    }
                },
                y2: {
                    show: true,
                    label: {
                        text: 'Confirmation Rate',
                        position: 'outer-middle'
                    },
                    tick: {
                        format: function (d) { return d+"%"; }
                    }
                },
            },
            bar: {
                width: {
                    ratio: 0.8 // this makes bar width 50% of length between ticks
                }
            },
            color: {
                pattern: ['#00A5B5', '#E15829']
            },
            size: {
                height: 250
            },
            transition: {
                duration: 100
            },
            legend: {
                show: true,
                position: 'inset',
                inset: {
                    anchor: 'top-right',
                    x: undefined,
                    y: undefined,
                    step: undefined
                }
            },
            tooltip: {
                format: {
                    title: function (d) {
                        return date_range[d];
                    },
                }
            }
        });

        for (var count = 0; count < vendor_data.length; count++) {
            if(vendor_data[count].vendor_name != null){
                $('#vendor1-card').attr("hidden", false);
                $('#vendor1_chart_heading').html(vendor_data[count].vendor_name);
                record1[count] = vendor_data[count].total_cases;
                record2[count] = vendor_data[count].confirmation_rate;
                label[count] = "Week "+vendor_data[count].week;
                date_range[count] = vendor_data[count].range;
            }
        }

        setTimeout(function () {
            vendor1_PO_confirmed_rate.load({
                json: {
                    'x': label,
                    'Total Cases': record1,
                    'Confirmation Rate': record2,
                },
            });
        }, 1000);
    }
    function vendor2POConfirmedRate(vendor_data) {
        let record1 = [];
        let record2 = [];
        let label = [];
        let date_range = [];

        $('#vendor2_chart_heading').html("-");
        $('#vendor2-card').attr("hidden", true);
        let vendor2_PO_confirmed_rate = c3.generate({
            bindto: d3.select('#vendor2_PO_confirmed_rate'),
            data: {
                x: 'x',
                json: {
                    'x': [],
                    'Total Cases': [],
                    'Confirmation Rate': [],
                },
                types: {
                    'Total Cases': 'bar',
                    'Confirmation Rate': 'bar',
                },
                axes: {
                    'Confirmation Rate': 'y2'
                },
            },
            axis: {
                x: {
                    type: 'category',
                    tick: {
                        rotate: -80,
                        multiline: false,
                    },
                    height: 50,
                },
                y: {
                    show: true,
                    label: {
                        text: 'Total Cases',
                        position: 'outer-middle'
                    },
                    tick: {
                        format: d3.format(',')
                    }
                },
                y2: {
                    show: true,
                    label: {
                        text: 'Confirmation Rate',
                        position: 'outer-middle'
                    },
                    tick: {
                        format: function (d) { return d+"%"; }
                    }
                },
            },
            bar: {
                width: {
                    ratio: 0.8 // this makes bar width 50% of length between ticks
                }
            },
            color: {
                pattern: ['#00A5B5', '#E15829']
            },
            size: {
                height: 250
            },
            transition: {
                duration: 100
            },
            legend: {
                show: true,
                position: 'inset',
                inset: {
                    anchor: 'top-right',
                    x: undefined,
                    y: undefined,
                    step: undefined
                }
            },
            tooltip: {
                format: {
                    title: function (d) {
                        return date_range[d];
                    },
                }
            }
        });

        for (var count = 0; count < vendor_data.length; count++) {
            if(vendor_data[count].vendor_name != null){
                $('#vendor2-card').attr("hidden", false);
                $('#vendor2_chart_heading').html(vendor_data[count].vendor_name);
                record1[count] = vendor_data[count].total_cases;
                record2[count] = vendor_data[count].confirmation_rate;
                label[count] = "Week "+vendor_data[count].week;
                date_range[count] = vendor_data[count].range;
            }
        }

        setTimeout(function () {
            vendor2_PO_confirmed_rate.load({
                json: {
                    'x': label,
                    'Total Cases': record1,
                    'Confirmation Rate': record2,
                },
            });
        }, 1000);
    }
    function vendor3POConfirmedRate(vendor_data) {
        let record1 = [];
        let record2 = [];
        let label = [];
        let date_range = [];
        $('#vendor3_chart_heading').html("-");
        $('#vendor3-card').attr("hidden", true);
        let vendor3_PO_confirmed_rate = c3.generate({
            bindto: d3.select('#vendor3_PO_confirmed_rate'),
            data: {
                x: 'x',
                json: {
                    'x': [],
                    'Total Cases': [],
                    'Confirmation Rate': [],
                },
                types: {
                    'Total Cases': 'bar',
                    'Confirmation Rate': 'bar',
                },
                axes: {
                    'Confirmation Rate': 'y2'
                },
            },
            axis: {
                x: {
                    type: 'category',
                    tick: {
                        rotate: -80,
                        multiline: false,
                    },
                    height: 50,
                },
                y: {
                    show: true,
                    label: {
                        text: 'Total Cases',
                        position: 'outer-middle'
                    },
                    tick: {
                        format: d3.format(',')
                    }
                },
                y2: {
                    show: true,
                    label: {
                        text: 'Confirmation Rate',
                        position: 'outer-middle'
                    },
                    tick: {
                        format: function (d) { return d+"%"; }
                    }
                },
            },
            bar: {
                width: {
                    ratio: 0.8 // this makes bar width 50% of length between ticks
                }
            },
            color: {
                pattern: ['#00A5B5', '#E15829']
            },
            size: {
                height: 250
            },
            transition: {
                duration: 100
            },
            legend: {
                show: true,
                position: 'inset',
                inset: {
                    anchor: 'top-right',
                    x: undefined,
                    y: undefined,
                    step: undefined
                }
            },
            tooltip: {
                format: {
                    title: function (d) {
                        return date_range[d];
                    },
                }
            }
        });

        for (var count = 0; count < vendor_data.length; count++) {
            if(vendor_data[count].vendor_name != null){
                $('#vendor3-card').attr("hidden", false);
                $('#vendor3_chart_heading').html(vendor_data[count].vendor_name);
                record1[count] = vendor_data[count].total_cases;
                record2[count] = vendor_data[count].confirmation_rate;
                label[count] = "Week "+vendor_data[count].week;
                date_range[count] = vendor_data[count].range;
            }
        }

        setTimeout(function () {
            vendor3_PO_confirmed_rate.load({
                json: {
                    'x': label,
                    'Total Cases': record1,
                    'Confirmation Rate': record2,
                },
            });
        }, 1000);
    }
    function allVendorsWeeklyConfirmedPO(po_report_all_vendor, report_type) {
        let html = '';
        if(po_report_all_vendor.length == 0){
            html =  "<tr>\n" +
                "    <td style='padding: 10px; white-space: nowrap;' align='center' colspan='6'>No data found</td>\n" +
                "</tr>";
            $('#all_vendors_weekly_confirmed_PO').html(html);
            return;
        }

        $('#week_1').html(po_report_all_vendor[0].week_1);
        $('#week_2').html(po_report_all_vendor[0].week_2);
        $('#week_3').html(po_report_all_vendor[0].week_3);

        switch (report_type) {
            case 0:
                for (var count = 0; count < po_report_all_vendor.length; count++) {
                    html += "<tr>\n" +
                        "        <td style='padding: 10px; white-space: nowrap;'>"+po_report_all_vendor[count].vendor_name+"</td>\n" +
                        "        <td style='padding: 10px; white-space: nowrap;'>"+po_report_all_vendor[count].week_3_confirmation_rate+"</td>\n" +
                        "        <td style='padding: 10px; white-space: nowrap;'>"+po_report_all_vendor[count].week_2_confirmation_rate+"</td>\n" +
                        "        <td style='padding: 10px; white-space: nowrap;'>"+po_report_all_vendor[count].week_1_confirmation_rate+"</td>\n" +
                        "        <td style='padding: 10px; white-space: nowrap;'>"+po_report_all_vendor[count].current_confirmation_rate+"</td>\n"+
                        "</tr>";
                }
                break;
            case 1:
                for (var count = 0; count < po_report_all_vendor.length; count++) {
                    html += "<tr>\n" +
                        "        <td style='padding: 10px; white-space: nowrap;'>"+po_report_all_vendor[count].vendor_name+"</td>\n" +
                        "        <td style='padding: 10px; white-space: nowrap;'>"+po_report_all_vendor[count].week_3_cases+"</td>\n" +
                        "        <td style='padding: 10px; white-space: nowrap;'>"+po_report_all_vendor[count].week_2_cases+"</td>\n" +
                        "        <td style='padding: 10px; white-space: nowrap;'>"+po_report_all_vendor[count].week_1_cases+"</td>\n" +
                        "        <td style='padding: 10px; white-space: nowrap;'>"+po_report_all_vendor[count].current_cases+"</td>\n" +
                        "</tr>";
                }
                break;
            default:
                break;
        }
        $('#all_vendors_weekly_confirmed_PO').html(html);
    }
});
