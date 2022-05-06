document.addEventListener('DOMContentLoaded', function (e) {

    var validationForm = FormValidation.formValidation(
        document.getElementById('vendors_form'),
        {
            fields: {
                vendor_name: {
                    validators: {
                        notEmpty: {
                            message: 'The vendor name is required'
                        },
                        stringLength: {
                            min: 4,
                            max: 64,
                            message: 'The vendor name must be more than 3 and less than 65 characters long'
                        },
                        regexp: {
                            regexp: /^([a-zA-Z0-9!@#$%^&*().,<>{}[\]<>?_=+\-|;:\'\"\/]+\s?)*$/,
                            message: 'Extra space are not allowed in the vendor name'
                        },
                    }
                },
                domain: {
                    validators: {
                        notEmpty: {
                            message: 'The vendor domain is required'
                        },
                        stringLength: {
                            min: 1,
                            max: 4,
                            message: 'The vendor domain must be less than 5 characters long'
                        },
                        regexp: {
                            regexp: /^[A-Z]+$/,
                            message: 'The vendor domain can only consist of uppercase alphabet'
                        },
                    }
                },
                tier: {
                    validators: {
                        notEmpty: {
                            message: 'The vendor tier is required'
                        },
                        stringLength: {
                            min: 4,
                            max: 29,
                            message: 'The vendor tier must be more than 3 and less than 30 characters long'
                        },
                        regexp: {
                            regexp: /^([a-zA-Z0-9!@#$%^&*().,<>{}[\]<>?_=+\-|;:\'\"\/]+\s?)*$/,
                            message: 'Extra space are not allowed in the vendor tier'
                        },

                    }
                },
            },
            plugins: {
                trigger: new FormValidation.plugins.Trigger(),
                bootstrap: new FormValidation.plugins.Bootstrap(),
                submitButton: new FormValidation.plugins.SubmitButton(),
                icon: new FormValidation.plugins.Icon({
                    valid: 'fa fa-check',
                    invalid: 'fa fa-times',
                    validating: 'fa fa-refresh',
                }),
            },
        }
    ).on('core.form.valid', function () {

        var action_url = '';
        var action_method = '';

        $('#form_result').html("");
        $("#action_button").attr("disabled", true);

        if ($('#form_action').val() == 'Edit') {
            action_url = base_url + "/user-vendors/" + $('#hidden_id').val();
            action_method = "PUT";
        }

        //ajax call
        $.ajax({
            url: action_url,
            type: action_method,
            data: $('#vendors_form').serialize(),
            dataType: "json",
            cache: false,
            success: function (data) {
                $("#action_button").attr("disabled", false);
                if (data.errors) {
                    var html = '';
                    $('#form_result').html('');
                    html += '<div class="alert alert-danger">';
                    for (var count = 0; count < data.errors.length; count++) {
                        html += '<p>' + data.errors[count] + '</p>';
                    }
                    html += '</div>';
                    $('#form_result').html(html);
                }
                if (data.success) {

                    $('#vendors_form')[0].reset();
                    //Refresh datatable
                    $('#vendors_table').DataTable().ajax.reload(null, false);
                    $('#vendorModal').modal('hide');
                    Swal.fire({
                        title: "Done",
                        text: data.success,
                        allowOutsideClick: false,
                        type: "success",
                        confirmButtonClass: 'btn btn-primary',
                        buttonsStyling: false,
                    });
                }
            },
        });
    });

    var validationVendorForm = FormValidation.formValidation(
        document.getElementById('assign_vendor_form'),
        {
            fields: {
                // vendor_info: {
                //     validators: {
                //         notEmpty: {
                //             message: 'Please select Vendor'
                //         },
                //         choice: {
                //             min: 1,
                //             max: 1,
                //             message: 'Only 1 Vendor is selected at a time'
                //         },
                //     }
                // },
            },
            plugins: {
                trigger: new FormValidation.plugins.Trigger(),
                bootstrap: new FormValidation.plugins.Bootstrap(),
                submitButton: new FormValidation.plugins.SubmitButton(),
                icon: new FormValidation.plugins.Icon({
                    valid: 'fa fa-check',
                    invalid: 'fa fa-times',
                    validating: 'fa fa-refresh',
                }),
            },
        }
    ).on('core.form.valid', function () {
        $("#assign_vendor_button").attr("disabled", true);
        //ajax call
        $.ajax({
            url: base_url + "/brand/assignVendor/" + $('#add_brand_id').val(),
            type: "PUT",
            data: $('#assign_vendor_form').serialize(),
            dataType: "json",
            cache: false,
            success: function (data) {
                $("#assign_vendor_button").attr("disabled", false);
                if (data.success) {
                    $('#assign_vendor_form')[0].reset();
                    $('#vendors_table').DataTable().ajax.reload(null, false);
                    $('#addVendorModal').modal('hide');
                    Swal.fire({
                        title: "Done",
                        text: data.success,
                        allowOutsideClick: false,
                        type: "success",
                        confirmButtonClass: 'btn btn-primary',
                        buttonsStyling: false,
                    });
                }
            },
        });
    });

    //Set Model for adding new user
    $(document).on('click', '.removeVendor', function () {
        var id = $(this).attr('id');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var data = 'brand_info=' + brand_id;
        $.ajax({
            url: base_url + "/brand/unassignVendor/" + id + "",
            type: "PUT",
            data: data,
            dataType: "json",
            success: function (data) {
                //set user data in model and show model
                if (data.success) {
                    $('#vendors_table').DataTable().ajax.reload(null, false);
                    Swal.fire({
                        title: "Done",
                        text: data.success,
                        allowOutsideClick: false,
                        type: "success",
                        confirmButtonClass: 'btn btn-primary',
                        buttonsStyling: false,
                    });
                }

            }
        })
    });

    //View all brand in datatabale
    $('#vendors_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: base_url + "/brand/vendors/" + brand_id,
        },
        language: {
            "emptyTable": "No data found"
        },
        columns: [
            { "data": "DT_RowIndex", "name": "DT_RowIndex", orderable: false, searchable: false },
            { data: 'vendor_name', name: 'vendor_name' },
            { data: 'domain', name: 'domain' },
            { data: 'tier', name: 'tier' },
            { data: 'is_active', name: 'is_active', orderable: false },
            { data: 'action', name: 'action', orderable: false },
        ],
        order: [[1, 'asc']],
    }).columns.adjust().draw();


    //Set Model for adding new user
    $('#create_record').click(function () {
        $('#vendor_info').html("");
        var id = brand_id;
        validationVendorForm.resetForm(true);
        $.ajax({
            url: base_url + "/brand/unassignedVendors/" + id + "",
            dataType: "json",
            success: function (data) {
                //set user data in model and show model
                $('#add_brand_id').val(id);
                if (data.result && data.result.length > 0) {
                    var html = '<option value="" disabled>-- select Vendors --</option>';
                    for (var count = 0; count < data.result.length; count++) {
                        html += '<option value=' + data.result[count].vendor_id + '>' + data.result[count].vendor_name + " - " + data.result[count].domain + '</option>';
                    }
                    $('#vendor_info').html(html);
                    $('#addVendorModal').modal({ backdrop: 'static', keyboard: false });
                } else {
                    Swal.fire({
                        title: "Vendor not found",
                        text: "Please add new vendors",
                        allowOutsideClick: false,
                        type: "info",
                        confirmButtonClass: 'btn btn-primary',
                        buttonsStyling: false,
                    });
                }
            }
        })
    });


    //get user data for updating
    $(document).on('click', '.edit', function () {
        var id = $(this).attr('id');
        $('#form_result').html('');
        $.ajax({
            url: base_url + "/user-vendors/" + id + "/edit",
            dataType: "json",
            success: function (data) {
                //set user data in model and show model
                validationForm.resetForm(true);
                $('#vendor_name').val(data.result.vendor_name);
                $('#domain').val(data.result.domain);
                $('#tier').val(data.result.tier);
                $('#is_active').val(data.result.is_active);
                $('#hidden_id').val(id);
                $('#form_result').html('');
                $('#vendor_modal_title').text('Edit Vendors Information');
                $('#action_button').val('Edit Vendor');
                $('#form_action').val('Edit');
                $('#vendorModal').modal({ backdrop: 'static', keyboard: false });
            }
        })
    });
    //change status in on checking or unchecking Checkbox

    $(document).on('click', '.status', function () {

        //get user and Status ID
        var id = $(this).attr('id');
        var status = $(this).val();
        //set CSRF Token

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var data = 'is_active=' + status;
        $('#form_result').html('');

        $.ajax({
            url: base_url + "/user-vendors/status/" + id,
            data: data,
            type: "PUT",
            dataType: "json",
            success: function (data) {
                //Reload datatable
                $('#vendors_table').DataTable().ajax.reload(null, false);
            }
        })
    });

    $(document).on('click', '.deleteVendor', function () {
        //get user and Status ID
        var id = $(this).attr('id');
        var status = $(this).val();

        Swal.fire({
            title: 'Are you sure?',
            text: "You want to delete this vendor!",
            type: 'warning',
            showCancelButton: true,
            allowOutsideClick: false,
            confirmButtonText: 'OK',
            confirmButtonClass: 'btn btn-primary',
            cancelButtonClass: 'btn btn-danger ml-1',
            buttonsStyling: false,
        }).then(function (result) {
            if (result.value) {
                //set CSRF Token
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                var data = 'is_active=' + status;
                $.ajax({
                    url: base_url + "/user-vendors/status/" + id,
                    data: data,
                    type: "PUT",
                    dataType: "json",
                    success: function (data) {
                        //Reload datatable
                        $('#vendors_table').DataTable().ajax.reload(null, false);
                        Swal.fire({
                            type: "success",
                            title: 'Deleted!',
                            allowOutsideClick: false,
                            text: "Vendor is deleted",
                            confirmButtonClass: 'btn btn-success',
                        })
                    }
                });
            }
        });
    });
    $('#assign_vendor_button').click(function () {
        var a = document.getElementById('vendor_info').value;
        if (a == "" || a == null) {
            Swal.fire({
                title: 'Cancelled',
                allowOutsideClick: false,
                text: 'Sorry! No Vendor selected',
                type: 'error',
                confirmButtonClass: 'btn btn-danger',
            }).then(function () {
                location.reload();
            });

        } else {
            return true;
        }

    });


});
$(document).ready(function () {
    $("#vendor_info").select2({
        dropdownParent: $("#assign_vendor_form")
    });
});
