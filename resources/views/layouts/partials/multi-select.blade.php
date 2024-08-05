<script>
    $(document).ready(function() {
        $('.multiSelect').select2({
            placeholder: 'Select an option',
            allowClear: true,
            maximumSelectionLength: 1,
            width: '100%',
        });
        if ($('#material').val() == '') {
            $("#type").select2({
                placeholder: 'Select Sub Material First',
                allowClear: true,
                maximumSelectionLength: 1,
                width: '100%',
            });
        }
        if ($('#type').val() == '') {

            $("#product").select2({
                placeholder: 'Select Type Material First',
                allowClear: true,
                maximumSelectionLength: 1,
                width: '100%',
            });
        }


        $("#material").change(function() {

            //clear select
            $("#type").empty();
            $('#product').empty();
            //set id
            let material_product = $(this).val();
            // console.log(material_product);
            let csrf = $('meta[name="csrf-token"]').attr("content");

            if (material_product) {
                $("#type").select2({
                    placeholder: 'Select an option',
                    allowClear: true,
                    maximumSelectionLength: 1,
                    width: '100%',
                    ajax: {
                        type: "GET",
                        url: "/product_sub_types/select/" + material_product,
                        data: function(params) {
                            return {
                                _token: csrf,
                                q: params.term, // search term
                            };
                        },
                        dataType: "json",
                        delay: 250,
                        processResults: function(data) {
                            return {
                                results: $.map(data, function(item) {
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
                $("#type").empty();
                $('#product').empty();
            }
        });
        $("#type").change(function() {
            //clear select
            $("#product").empty();
            //set id
            let type_id = $(this).val();
            // console.log(type_id);
            let csrf = $('meta[name="csrf-token"]').attr("content");

            if (type_id) {
                $("#product").select2({
                    placeholder: 'Select an option',
                    allowClear: true,
                    maximumSelectionLength: 1,
                    width: '100%',
                    ajax: {
                        type: "GET",
                        url: "/product_select/" + type_id,
                        data: function(params) {
                            return {
                                _token: csrf,
                                q: params.term, // search term
                            };
                        },
                        dataType: "json",
                        delay: 250,
                        processResults: function(data) {
                            return {
                                results: $.map(data, function(item) {
                                    return {
                                        text: item.nama_barang,
                                        id: item.id,
                                    };
                                }),
                            };
                        },
                    },
                });
            } else {
                $("#product").empty();
            }
        });
    });
</script>
