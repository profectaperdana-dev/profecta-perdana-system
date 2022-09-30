<script>
    $(document).ready(function() {

        let cData_product = JSON.parse(`<?php echo $data_product['chart_data']; ?>`);
        let num_product = cData_product.data;
        let text_product = cData_product.label;

        var options_product = {
            series: [{
                name: 'Sales Amount',
                data: num_product,
            }, ],
            chart: {
                type: 'bar',
                height: 350
            },
            plotOptions: {
                bar: {
                    distributed: true, // this line is mandatory
                    horizontal: false,
                    columnWidth: '10%',
                    endingShape: 'flat',
                },
            },
            //   colors: [(value) => {
            //     let color_rand = '#' + Math.floor(Math.random() * 16777215).toString(16);
            //     return color_rand;
            //   }],
            dataLabels: {
                enabled: false
            },
            stroke: {
                show: true,
                width: 2,
                colors: ['transparent']
            },
            xaxis: {
                categories: text_product,
            },
            yaxis: {
                title: {
                    text: 'Unit'
                }
            },
            fill: {
                opacity: 1
            },
            tooltip: {
                y: {
                    formatter: function(val) {
                        return val + " units"
                    }
                }
            }
        };

        setTimeout(() => {
            let chart_product = new ApexCharts(document.querySelector("#chart-dash-1-line"),
                options_product);
            chart_product.render();
        }, 2100);

        $('#filter-product').click(function() {
            let from_date_product = $('#from_date_product').val();
            let to_date_product = $('#to_date_product').val();
            let material = $('#material_id').val();
            let sub_material = $('#sub_material_id').val();
            let sub_type = $('#sub_type_id').val();
            $.ajax({
                url: "/product_chart",
                method: "GET",
                data: {
                    fd: from_date_product,
                    td: to_date_product,
                    m: material,
                    sm: sub_material,
                    st: sub_type
                },
                dataType: "json",
                success: function(value) {
                    if (from_date_product != "" && to_date_product != "") {
                        let from = new Date(from_date_product);
                        from = from.toLocaleDateString("id-ID");
                        let to = new Date(to_date_product);
                        to = to.toLocaleDateString("id-ID");
                        $('#title-chart-product').html('Sales Chart By Product (' + from +
                            ' to ' +
                            to + ')')
                    }

                    let cData_product = value;
                    let num_product = cData_product.data;
                    let text_product = cData_product.label;

                    if (num_product == null) {
                        $('#chart-dash-1-line').html(
                            '<h3 class="text-center">No Data Found</h3>');
                    } else {
                        var options_product = {
                            series: [{
                                name: 'Sales Amount',
                                data: num_product,
                            }, ],
                            chart: {
                                type: 'bar',
                                height: 350
                            },
                            plotOptions: {
                                bar: {
                                    distributed: true, // this line is mandatory
                                    horizontal: false,
                                    columnWidth: '10%',
                                    endingShape: 'flat',
                                },
                            },
                            //   colors: [(value) => {
                            //     let color_rand = '#' + Math.floor(Math.random() * 16777215).toString(16);
                            //     return color_rand;
                            //   }],
                            dataLabels: {
                                enabled: false
                            },
                            stroke: {
                                show: true,
                                width: 2,
                                colors: ['transparent']
                            },
                            xaxis: {
                                categories: text_product,
                            },
                            yaxis: {
                                title: {
                                    text: 'Unit'
                                }
                            },
                            fill: {
                                opacity: 1
                            },
                            tooltip: {
                                y: {
                                    formatter: function(val) {
                                        return val + " units"
                                    }
                                }
                            }
                        };
                        $('#chart-dash-1-line').html('');
                        let chart_product = new ApexCharts(document.querySelector(
                                "#chart-dash-1-line"),
                            options_product);
                        chart_product.render();
                    }


                }
            });
        })

    });
</script>
