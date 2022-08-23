$(document).ready(function () {
    $(
        ".editPayments,.uoms,.materials,.submaterials, .category-cust, .area-cust, .role-acc, .warehouse-acc, .sub_type, .discount"
    ).select2({
        width: "100%",
    });
    let csrf = $('meta[name="csrf-token"]').attr("content");

    $(".product-append").select2({
        width: "100%",
        ajax: {
            type: "GET",
            url: "/products/select",
            data: {
                _token: csrf,
            },
            dataType: "json",
            delay: 250,
            processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        return [
                            {
                                text: item.nama_barang,
                                id: item.id,
                            },
                        ];
                    }),
                };
            },
        },
    });

    //  Event on change select material:start
    $("#material").change(function () {
        //clear select
        $("#sub-material").empty();
        $("#sub-type").empty();
        //set id
        let host = window.location.host;
        let material_id = $("#material").val();
        let csrf = $('meta[name="csrf-token"]').attr("content");
        if (material_id) {
            $("#sub-material").select2({
                width: "100%",
                ajax: {
                    type: "GET",
                    url: "/product_sub_materials/select/" + material_id,
                    data: { _token: csrf },
                    dataType: "json",
                    delay: 250,
                    processResults: function (data) {
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    text: item.nama_sub_material,
                                    id: item.id,
                                };
                            }),
                        };
                    },
                },
            });
        } else {
            $("#sub-material").empty();
            $("#sub-type").empty();
        }
    });
    //  Event on change select material:end

    //  Event on change select regency:start
    $("#sub-material").change(function () {
        //clear select
        $("#sub-type").empty();
        //set id
        let sub_material_id = $("#sub-material").val();
        let csrf = $('meta[name="csrf-token"]').attr("content");

        if (sub_material_id) {
            $("#sub-type").select2({
                width: "100%",
                ajax: {
                    type: "GET",
                    url: "/product_sub_types/select/" + sub_material_id,
                    data: { _token: csrf },
                    dataType: "json",
                    delay: 250,
                    processResults: function (data) {
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    text: item.type_name,
                                    id: item.id,
                                };
                            }),
                        };
                    },
                },
            });
        } else {
            $("#sub-type").empty();
        }
    });
    //  Event on change select regency:end

    // Stock
    let y = 0;
    $(".product-append-all").select2({
        width: "100%",
        ajax: {
            type: "GET",
            url: "/products/selectAll",
            data: {
                _token: csrf,
            },
            dataType: "json",
            delay: 250,
            processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        return [
                            {
                                text: item.nama_barang,
                                id: item.id,
                            },
                        ];
                    }),
                };
            },
        },
    });
    $("#addStock").on("click", function () {
        ++y;
        let form =
            '<div class="form-group row"> <div class="form-group col-5" > <label> Product </label> <select name="stockFields[' +
            y +
            '][product_id]"' +
            'class="form-control product-append-all" required> <option value=""> Choose Product </option> </select>' +
            '</div> <div class="form-group col-5">' +
            '<label> Stock </label> <input type="number" name="stockFields[' +
            y +
            '][stock]" id="discount"' +
            'class="form-control" placeholder="Enter Stocks" required>' +
            '</div>  <div class="form-group col-2">' +
            '<label for="">&nbsp;</label>' +
            '<a href="javascript:void(0)" class="form-control text-white remStock text-center" style="border:none; background-color:red">X</a></div></div>';

        $("#formdynamic").append(form);
        $(".product-append-all").select2({
            width: "100%",
            ajax: {
                type: "GET",
                url: "/products/selectAll",
                data: {
                    _token: csrf,
                },
                dataType: "json",
                delay: 250,
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return [
                                {
                                    text: item.nama_barang,
                                    id: item.id,
                                },
                            ];
                        }),
                    };
                },
            },
        });
    });

    $(document).on("click", ".remStock", function () {
        $(this).parents(".form-group").remove();
    });

    // end Stock

    //select2 product
    let i = 0;

    $("#addfields").on("click", function () {
        ++i;
        let form =
            '<div class="form-group row"> <div class="form-group col-5" > <label> Product </label> <select name="discountFields[' +
            i +
            '][product_id]"' +
            'class="form-control product-append-all" required> <option value=""> Choose Product </option> </select>' +
            '</div> <div class="form-group col-5">' +
            '<label> Discount </label> <input type="number" name="discountFields[' +
            i +
            '][discount]" id="discount"' +
            'class="form-control" placeholder="Enter Discount" required>' +
            '</div>  <div class="form-group col-2">' +
            '<label for="">&nbsp;</label>' +
            '<a href="javascript:void(0)" class="form-control text-white remfields" style="border:none; background-color:red">&#9747;</a> </div> </div>';

        $("#formdynamic").append(form);
        $(".product-append-all").select2({
            width: "100%",
            ajax: {
                type: "GET",
                url: "/products/selectAll",
                data: {
                    _token: csrf,
                },
                dataType: "json",
                delay: 250,
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return [
                                {
                                    text: item.nama_barang,
                                    id: item.id,
                                },
                            ];
                        }),
                    };
                },
            },
        });
    });
    $(document).on("click", ".remfields", function () {
        $(this).parents(".form-group").remove();
    });

    // sales order //
    $(".productSo").select2({
        width: "100%",
        ajax: {
            type: "GET",
            url: "/products/select",
            data: {
                _token: csrf,
            },
            dataType: "json",
            delay: 250,
            processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        return [
                            {
                                text: item.nama_barang,
                                id: item.id,
                            },
                        ];
                    }),
                };
            },
        },
    });

    //Get Customer ID
    let customer_id = "";
    $(".customer-append").change(function () {
        customer_id = $(".customer-append").val();
    });

    let x = 0;
    let product_id = 0;
    //Get discount depent on product
    $(document).on("change", ".productSo", function () {
        product_id = $(this).val();

        let parent_product = $(this)
            .parent()
            .siblings()
            .find(".discount-append");

        $.ajax({
            type: "GET",
            url: "/discounts/select" + "/" + customer_id + "/" + product_id,
            dataType: "json",
            success: function (data) {
                parent_product.val(data.discount);
            },
        });
    });
    $(document).on("input", ".cekQty", function () {
        let qtyValue = $(this).val();
        let toRed = $(this).css("background-color", "red");
        let toWhite = $(this).css("background-color", "white");

        $.ajax({
            type: "GET",
            url: "/stocks/cekQty/" + product_id,
            dataType: "json",
            success: function (data) {
                if (parseInt(qtyValue) > parseInt(data.stock)) {
                    $(".cekQty").append(
                        "<small>Jumlah Barang melebihi stock</small>"
                    );
                } else {
                    $(".cekQty")
                        .closest("form-group")
                        .append(
                            "<small>Jumlah Barang tidak melebihi stock</small>"
                        );
                }
            },
        });
    });

    $("#addSo").on("click", function () {
        ++x;
        let form =
            '<div class="form-group row">' +
            '<div class="form-group col-4">' +
            "<label>Product</label>" +
            '<select name="soFields[' +
            x +
            '][product_id]" class="form-control productSo" required>' +
            '<option value=""> Choose Product </option> ' +
            "</select>" +
            "</div>" +
            '<div class="col-3 col-md-3 form-group">' +
            "<label> Qty </label> " +
            '<input class="form-control cekQty" required name="soFields[' +
            x +
            '][qty]">' +
            "</div> " +
            '<div class="col-3 col-md-4 form-group">' +
            "<label>Discount %</label>" +
            '<input class="form-control discount-append" name="soFields[' +
            x +
            '][discount]" id="" readonly>' +
            "</div>" +
            '<div class="col-2 col-md-1 form-group">' +
            '<label for=""> &nbsp; </label>' +
            '<a class="form-control text-white remSo text-center" style="border:none; background-color:red">' +
            "- </a> " +
            "</div>" +
            " </div>";
        $("#formSo").append(form);

        $(".productSo").select2({
            width: "100%",
            ajax: {
                type: "GET",
                url: "/products/select",
                data: {
                    _token: csrf,
                },
                dataType: "json",
                delay: 250,
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return [
                                {
                                    text: item.nama_barang,
                                    id: item.id,
                                },
                            ];
                        }),
                    };
                },
            },
        });
    });

    //remove Sales Order fields
    $(document).on("click", ".remSo", function () {
        $(this).parents(".form-group").remove();
    });

    $("#payment_method").change(function () {
        if ($(this).val() == 1 || $(this).val() == 2) {
            // $("#payment").removeAttr("hidden");
            // $("#payment_type").removeAttr("hidden");
            $("#top").attr("hidden", "true");
        } else {
            // $("#payment").attr("hidden", "true");
            // $("#payment_type").attr("hidden", "true");
            $("#top").removeAttr("hidden");
        }
    });

    // $('.editPayment_method').click(function(){
    //     if ($('.editPayments').val()==1) {
    //         $('#editpayment').removeAttr('hidden');
    //         $('#editpayment_type').removeAttr('hidden');
    //         $('#edittop').attr('hidden','true');
    //     }else{
    //         $('#editpayment').attr('hidden','true');
    //         $('#editpayment_type').attr('hidden','true');
    //         $('#edittop').removeAttr('hidden');
    //     }

    //     $('.changePayment').change(function(){
    //         if ($('.editPayments').val()==1) {
    //             $('#editpayment').removeAttr('hidden');
    //             $('#editpayment_type').removeAttr('hidden');
    //             $('#edittop').attr('hidden','true');
    //         }else{
    //             $('#editpayment').attr('hidden','true');
    //             $('#editpayment_type').attr('hidden','true');
    //             $('#edittop').removeAttr('hidden');
    //         }

    //             });
    //         });
});
