<script>
    $(document).ready(function() {
        // ajax filter salesman
        $('#filter').click(function() {
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            var date_first = new Date(from_date);
            var date_second = new Date(to_date);
            var date_star = date_first.toLocaleDateString("id-ID");
            var date_end = date_second.toLocaleDateString("id-ID");
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
                                    name: '<div class="text-center badge badge-primary">Omset</div>',
                                    data: num
                                }, ],
                                title: {
                                    text: 'Omset Salesman (IDR)',
                                    align: 'center',
                                    margin: 10,
                                    offsetX: 0,
                                    offsetY: 0,
                                    floating: false,
                                    style: {
                                        fontSize: '20px',
                                        fontWeight: 'bold',
                                        fontFamily: 'Arial',
                                        color: '#263238'
                                    },
                                },
                                chart: {
                                    type: 'bar',
                                    height: 410,
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
                                subtitle: {
                                    text: 'Data taken ' + date_star +
                                        ' to ' +
                                        date_end,
                                    align: 'center',
                                    margin: 10,
                                    offsetX: 0,
                                    offsetY: 30,
                                    floating: false,
                                    style: {
                                        fontSize: '16px',
                                        fontWeight: 'bold',
                                        fontFamily: 'Arial',
                                        color: 'Black'
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
                                legend: {
                                    position: 'right',
                                    offsetX: 0,
                                    offsetY: 50
                                },


                            };
                            $('#chart-dash-2-line').html('');

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
                name: '<div class="text-center badge badge-primary">Omset</div>',
                data: num_sales
            }, ],
            title: {
                text: 'Omset Salesman (IDR)',
                align: 'center',
                margin: 10,
                offsetX: 0,
                offsetY: 0,
                floating: false,
                style: {
                    fontSize: '20px',
                    fontWeight: 'bold',
                    fontFamily: 'Arial',
                    color: '#263238'
                },
            },
            chart: {
                type: 'bar',
                height: 410,
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
            subtitle: {
                text: 'Data taken this month',
                align: 'center',
                margin: 10,
                offsetX: 0,
                offsetY: 30,
                floating: false,
                style: {
                    fontSize: '16px',
                    fontWeight: 'bold',
                    fontFamily: 'Arial',
                    color: 'Black'
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
            legend: {
                position: 'right',
                offsetX: 0,
                offsetY: 50
            },


        };
        setTimeout(() => {
            var chart_sales = new ApexCharts(document.querySelector(
                "#chart-dash-2-line"), options_sales);
            chart_sales.render();
        }, 1000);
        // .end default load chart sales


        $('#cardShadow').hide();
        $('#filterBySales').click(function() {
            // default area chart sales
            $('#cardShadow').show();
            $('#infoFilter').hide();
            let sales = $('#sales').val();
            let from_dateSales = $('#from_dateSales').val();
            let to_dateSales = $('#to_dateSales').val();

            // if (from_dateSales != '' && to_dateSales != '') {
            //     var sub = 'Data taken ' + from_dateSales + ' to ' + to_dateSales;

            // } else {
            //     var sub = 'Data taken this month'

            // }
            $.ajax({
                url: "/data_by_sales/",
                method: "GET",
                data: {
                    fd: from_dateSales,
                    td: to_dateSales,
                    sales: sales,
                },
                success: function(value) {
                    let dataBySales = value;


                    var optionsBySales = {
                        series: [{
                            name: "Omset",
                            data: dataBySales.data
                        }],
                        chart: {
                            type: 'area',
                            height: 350,
                            id: 'sales1',
                            zoom: {
                                enabled: false
                            }
                        },
                        dataLabels: {
                            enabled: false
                        },
                        stroke: {
                            curve: 'straight'
                        },

                        title: {
                            text: 'Omset Sale By ' + dataBySales.nama,
                            align: 'center',
                            margin: 10,
                            offsetX: 0,
                            offsetY: 0,
                            floating: false,
                            style: {
                                fontSize: '20px',
                                fontWeight: 'bold',
                                fontFamily: 'Arial',
                                color: '#263238'
                            },
                        },
                        subtitle: {
                            text: 'sub',
                            align: 'center',
                            margin: 10,
                            offsetX: 0,
                            offsetY: 30,
                            floating: false,
                            style: {
                                fontSize: '16px',
                                fontWeight: 'bold',
                                fontFamily: 'Arial',
                                color: 'Black'
                            },
                        },
                        yaxis: [{
                            min: 0,
                            tickAmount: 10,
                            labels: {
                                formatter: function(value) {
                                    return value.toLocaleString(
                                        'id', {
                                            minimumFractionDigits: 0,
                                            maximumFractionDigits: 0
                                        });
                                },
                            },
                        }, ],
                        labels: dataBySales.label,
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

                        yaxis: {
                            min: 0,

                            opposite: false,
                            tickAmount: 5,
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
                        legend: {
                            horizontalAlign: 'left'
                        }
                    };
                    $('#chartBySales').html('');
                    var chartBySales = new ApexCharts(document.querySelector(
                        "#chartBySales"), optionsBySales);
                    chartBySales.render();
                }
            });
        });
    });
</script>
