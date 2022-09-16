<script>
    $(document).ready(function() {
        // ajax filter salesman
        $('#filter').click(function() {
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            if (from_date != '' && to_date != '') {
                $.ajax({
                    url: "/salesman_chart/",
                    method: "GET",
                    data: {
                        from_date: from_date,
                        to_date: to_date,
                    },
                    success: function(value) {
                        var cData = value;
                        var num = cData.data;

                        if (num == null) {
                            $('#chart-dash-2-line').html(
                                '<h3 class="text-center">No Data Found</h3>');
                        } else {

                            var text = cData.label;
                            var name = cData.name;
                            var options = {
                                series: [{
                                    name: '<div class="text-center badge badge-success">Sale</div>',
                                    data: num
                                }],
                                chart: {
                                    type: 'bar',
                                    height: 350,
                                    id: 'sales',
                                },
                                plotOptions: {
                                    bar: {
                                        horizontal: true,
                                        columnWidth: '10%',
                                        endingShape: 'flat',
                                        distributed: true
                                    },
                                },
                                dataLabels: {
                                    enabled: false
                                },
                                labels: text,
                                xaxis: {
                                    labels: {
                                        formatter: function(value) {
                                            return value.toLocaleString(
                                                'id', {
                                                    minimumFractionDigits: 0,
                                                    maximumFractionDigits: 0
                                                });
                                        },
                                    },
                                },

                                fill: {
                                    opacity: 1
                                },
                                theme: {

                                    palette: 'palette1',

                                },
                                tooltip: {

                                    y: {
                                        formatter: function(y) {
                                            if (typeof y !== "undefined") {
                                                return "Rp " + y.toLocaleString(
                                                    'id', {
                                                        minimumFractionDigits: 0,
                                                        maximumFractionDigits: 0
                                                    });
                                            }
                                            return y;
                                        }
                                    }
                                },
                            };

                            var chart = new ApexCharts(document.querySelector(
                                "#chart-dash-2-line"), options);
                            chart.render();
                        }
                    }
                });
            } else {
                alert('Both Date is required');
            }
        });
        // default load chart sales
        var cData_sales = JSON.parse(`<?php echo $data['chart_data']; ?>`);
        var num_sales = cData_sales.data;
        var text_sales = cData_sales.label;
        var name_sales = cData_sales.name;
        var options_sales = {
            series: [{
                name: '<div class="text-center badge badge-success">Sale</div>',
                data: num_sales
            }, ],

            chart: {
                type: 'bar',
                height: 350,
                id: 'sales2',
            },
            plotOptions: {
                bar: {
                    horizontal: true,
                    columnWidth: '10%',
                    endingShape: 'flat',
                    distributed: true
                },
            },
            dataLabels: {
                enabled: false
            },
            labels: text_sales,
            xaxis: {
                labels: {
                    formatter: function(value) {
                        return value.toLocaleString(
                            'id', {
                                minimumFractionDigits: 0,
                                maximumFractionDigits: 0
                            });
                    },
                },
            },
            fill: {
                opacity: 1
            },
            theme: {

                palette: 'palette1',

            },
            tooltip: {
                y: {
                    formatter: function(y) {
                        if (typeof y !== "undefined") {
                            return "Rp " + y.toLocaleString(
                                'id', {
                                    minimumFractionDigits: 0,
                                    maximumFractionDigits: 0
                                });
                        }
                        return y;
                    }
                }
            },
        };
        setTimeout(() => {
            var chart_sales = new ApexCharts(document.querySelector(
                "#chart-dash-2-line"), options_sales);
            chart_sales.render();
        }, 2000);
        // .end default load chart sales





    });
</script>
