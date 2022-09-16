<script>
    $(document).ready(function() {

        var cData = JSON.parse(`<?php echo $data_product['chart_data']; ?>`);
        var num = cData.data;
        var text = cData.label;

        var options = {
            series: [{
                name: 'Net Profit',
                data: num
            }, ],
            chart: {
                type: 'bar',
                height: 350
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '10%',
                    endingShape: 'flat',
                },
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                show: true,
                width: 2,
                colors: ['transparent']
            },
            xaxis: {
                categories: text,
            },
            yaxis: {
                title: {
                    text: '$ (thousands)'
                }
            },
            fill: {
                opacity: 1
            },
            tooltip: {
                y: {
                    formatter: function(val) {
                        return "$ " + val + " thousands"
                    }
                }
            }
        };

        var chart = new ApexCharts(document.querySelector("#chart-dash-1-line"), options);
        chart.render();

    });
</script>
