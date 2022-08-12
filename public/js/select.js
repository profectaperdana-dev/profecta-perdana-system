$(document).ready(function () {
    $(
        ".uoms,.materials,.submaterials, .category-cust, .area-cust, .role-acc, .warehouse-acc, .sub_type"
    ).select2({
        width: "100%",
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
                    url:  "/product_sub_materials/select/" + material_id,
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
});
