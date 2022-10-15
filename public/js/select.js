$(document).ready(function () {
    // $("form").submit(function () {
    //     $(this).find('button[type="submit"]').prop("disabled", true);
    // });

    $(
        ".editPayments,.uoms,.materials,.submaterials, .category-cust, .area-cust, .role-acc, .warehouse-acc, .sub_type, .discount, .job-acc"
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
                    data: function (params) {
                        return {
                            _token: csrf,
                            q: params.term, // search term
                        };
                    },
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
                    data: function (params) {
                        return {
                            _token: csrf,
                            q: params.term, // search term
                        };
                    },
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
            data: function (params) {
                return {
                    _token: csrf,
                    q: params.term, // search term
                    c: customer_id,
                };
            },
            dataType: "json",
            delay: 250,
            processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        return [
                            {
                                text:
                                    "("+ item.nama_sub_material + "/" + item.type_name + ") - " + item.nama_barang,

                                  
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
            '<div class="form-group row"> <div class="form-group col-7"> <label> Product </label> <select name="stockFields[' +
            y +
            '][product_id]"' +
            'class="form-control product-append-all" required> <option value=""> Choose Product </option> </select>' +
            '</div> <div class="form-group col-3">' +
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
                data: function (params) {
                return {
                    _token: csrf,
                    q: params.term, // search term
                    // c: customer_id,
                };
              },
                dataType: "json",
                delay: 250,
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return [
                                {
                                     text:
                                   "("+ item.nama_sub_material + "/" + item.type_name + ") - " + item.nama_barang,
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

    // sales order //

    //Get Customer ID
    let customer_id = "";
    $(".customer-append").change(function () {
        customer_id = $(".customer-append").val();
    });

    $(".productSo").select2({
        width: "100%",
        ajax: {
            type: "GET",
            url: "/products/select",
            data: function (params) {
                return {
                    _token: csrf,
                    q: params.term, // search term
                    c: customer_id,
                };
            },
            dataType: "json",
            delay: 250,
            processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        return [
                            {
                                text:
                                    item.nama_barang +
                                    " (" +
                                    item.type_name +
                                    ", " +
                                    item.nama_sub_material +
                                    ")",
                                id: item.id,
                            },
                        ];
                    }),
                };
            },
        },
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

        $.ajax({
            context: this,
            type: "GET",
            url: "/stocks/cekQty/" + product_id,
            data: {
                _token: csrf,
                c: customer_id,
            },
            dataType: "json",
            success: function (data) {
                if (parseInt(qtyValue) > parseInt(data.stock)) {
                    $(this).parent().find(".qty-warning").removeAttr("hidden");
                    $(this).addClass("is-invalid");
                } else {
                    $(this)
                        .parent()
                        .find(".qty-warning")
                        .attr("hidden", "true");
                    $(this).removeClass("is-invalid");
                }
            },
        });
    });

    $("#addSo").on("click", function () {
        ++x;
        let form =
            '<div class="mx-auto py-2 form-group row bg-primary">' +
            '<div class="form-group col-12 col-lg-6">' +
            "<label>Product</label>" +
            '<select name="soFields[' +
            x +
            '][product_id]" class="form-control productSo" required>' +
            '<option value=""> Choose Product </option> ' +
            "</select>" +
            "</div>" +
            '<div class="col-4 col-lg-2 form-group">' +
            "<label> Qty </label> " +
            '<input class="form-control cekQty" required name="soFields[' +
            x +
            '][qty]">' +
            '<small class="text-danger qty-warning" hidden>The number of items exceeds the stock</small>' +
            "</div>" +
            '<div class="col-4 col-lg-2 form-group">' +
            "<label>Disc(%)</label>" +
            '<input class="form-control discount-append" name="soFields[' +
            x +
            '][discount]" id="" readonly>' +
            "</div>" +
            '<div class="col-3 col-md-2 form-group">' +
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
                data: function (params) {
                    return {
                        _token: csrf,
                        q: params.term, // search term
                        c: customer_id,
                    };
                },
                dataType: "json",
                delay: 250,
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return [
                                {
                                    text:
                                        item.nama_barang +
                                        " (" +
                                        item.type_name +
                                        ", " +
                                        item.nama_sub_material +
                                        ")",
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
