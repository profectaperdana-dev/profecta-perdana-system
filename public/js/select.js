$(document).ready(function () {
    $(
        ".uoms,.materials,.submaterials, .category-cust, .area-cust, .role-acc, .warehouse-acc, .sub_type, .discount"
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

    //select2 product
    let i = 0;

    $("#addfields").on("click", function () {
        ++i;
        let form =
            '<div class="form-group row"> <div class="form-group col-5" > <label> Product </label> <select name="discountFields[' +
            i +
            '][product_id]"' +
            'class="form-control product-append" required> <option value=""> Choose Product </option> </select>' +
            '</div> <div class="form-group col-5">' +
            '<label> Discount </label> <input type="number" name="discountFields[' +
            i +
            '][discount]" id="discount"' +
            'class="form-control" placeholder="Enter Discount" required>' +
            '</div>  <div class="form-group col-2">' +
            '<label for="">&nbsp;</label>' +
            '<a href="javascript:void(0)" class="form-control text-white remfields" style="border:none; background-color:red">&#9747;</a> </div> </div>';

        $("#formdynamic").append(form);
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
    });
    $(document).on("click", ".remfields", function () {
        $(this).parents(".form-group").remove();
    });

    // sales order
    let x = 0;
$("#addSo").on("click", function() {
    ++x;
    let form =
    '<div class="form-group row">'+
        '<div class="form-group col-4">'+
        '<label>Product</label>'+
        '<select name="soFields['+ x +'][product_id]" class="form-control product-append" required>'+
        '<option value=""> Choose Product </option> '+
        '</select>'+
        '</div>'+

        '<div class="col-3 col-md-3 form-group">'+
        '<label> Qty </label> '+
        '<input class="form-control" name="soFields['+x+'][qty]">'+
        '</div> '+
        '<div class="col-3 col-md-4 form-group">'+
        '<label>Discount %</label>'+
        '<input class="form-control" name="soFields[0][discount]" id="">'+
        '</div>'+

        '<div class="col-2 col-md-1 form-group">'+
        '<label for=""> &nbsp; </label>'+
        '<a class="form-control text-white remSo text-center" style="border:none; background-color:red">'+
        '- </a> '+
        '</div>'+


        ' </div>';
    $("#formSo").append(form);
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
            processResults: function(data) {
                return {
                    results: $.map(data, function(item) {
                        return [{
                            text: item.nama_barang,
                            id: item.id,
                        }, ];
                    }),
                };
            },
        },
    });
});
$(document).on("click", ".remSo", function() {
    $(this).parents(".form-group").remove();
});

$('.customer-append').change(function(){
    let customer_id = $('.customer-append').val();
    $('.product-append').change(function(){
        let product_id = $('.product-append').val();
      $.get('/discount/select/'+customer_id+'/'+product_id,function(data){
        $('.discount-append').val(data);
      })
    });
});




});
