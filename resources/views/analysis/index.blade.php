@extends('layouts.master')
@section('content')
  @push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets') }}/css/date-picker.css">
  @endpush
  <div class="container-fluid">
    <div class="page-header">
      <div class="row">
        <div class="col-sm-6">
          <h3 class="font-weight-bold">{{ $title }}</h3>

        </div>

      </div>
    </div>
  </div>

  <div class="container-fluid">
    <div class="col-xl-12 xl-100 box-col-12">
      <div class="row">

        {{-- CHART SALESMAN --}}
        <div class="col-xl-12 box-col-12 des-xl-100">
          <div class="row">
            <div class="col-xl-6 box-col-12">
              <div class="card">
                <div class="card-header">
                  <div class="header-top d-sm-flex justify-content-between align-items-center">
                    <h5>sales chart by salesman</h5><br>
                  </div>
                  <div class="form-group row col-12">
                    <div class="col-3">
                      <label class="col-form-label">Start Date</label>
                      <div class="input-group">
                        <input class="form-control digits" type="date" data-language="en" placeholder="Start"
                          name="from_date" id="from_date">
                      </div>
                    </div>
                    <div class="col-3">
                      <label class="col-form-label">End Date</label>
                      <div class="input-group">
                        <input class="form-control digits" type="date" data-language="en" placeholder="Start"
                          name="to_date" id="to_date">
                      </div>
                    </div>
                    <div class="col-2">
                      <label class="col-form-label">&nbsp;</label>
                      <div class="input-group">
                        <button class="btn btn-primary" name="filter" id="filter"><i
                            class="fa fa-search"></i></button>
                      </div>
                    </div>
                    <div class="col-2">
                      <label class="col-form-label">&nbsp;</label>
                      <div class="input-group">
                        <a class="btn btn-warning" href="{{ url('/analytics') }}"><i class="fa fa-refresh"></i></a>
                      </div>
                    </div>
                  </div>

                </div>

                <div class="card-body chart-block p-0">
                  {{-- <div id="chart-dash-2-line"></div> --}}
                  <div class="chart-container">
                    <div class="row">
                      <div class="col-12">
                        <div id="chart-dash-2-line"></div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>
        {{-- END CHART SALESMAN --}}
        <div class="col-xl-12 box-col-12 des-xl-100">
          <div class="row">

            <div class="col-xl-6 box-col-12">
              <div class="card">
                <div class="card-header">
                  <div class="form-group row col-12">
                    <div class="col-4">
                      <label class="col-form-label text-end">Start Date</label>
                      <div class="input-group">
                        <input class="form-control digits" type="date" data-language="en" placeholder="Start"
                          name="from_date" id="from_date">
                      </div>
                    </div>
                    <div class="col-4">
                      <label class="col-form-label text-end">End Date</label>
                      <div class="input-group">
                        <input class="form-control digits" type="date" data-language="en" placeholder="Start"
                          name="to_date" id="to_date">
                      </div>
                    </div>
                    <div class="col-2">
                      <label class="col-form-label text-end">&nbsp;</label>
                      <div class="input-group">
                        <button class="btn btn-primary" name="filter" id="filter">Filter</button>
                      </div>
                    </div>
                    <div class="col-2">
                      <label class="col-form-label text-end">&nbsp;</label>
                      <div class="input-group">
                        <a class="btn btn-warning" href="{{ url('/analytics') }}">Refresh</a>
                      </div>
                    </div>
                  </div>
                  <div class="header-top d-sm-flex justify-content-between align-items-center">
                    <h5>sales chart by Product</h5><br>

                  </div>
                </div>

                <div class="card-body chart-block p-0">
                  {{-- <div id="chart-dash-2-line"></div> --}}
                  <div class="chart-container">
                    <div class="row">
                      <div class="col-12">
                        <div id="chart-dash-1-line"></div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>

      </div>

    </div>


  </div>
  <!-- Container-fluid Ends-->
  @push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.js"></script>

    <script src="{{ asset('assets') }}/js/counter/jquery.counterup.min.js"></script>
    <script src="{{ asset('assets') }}/js/counter/counter-custom.js"></script>
    <script src="{{ asset('assets') }}/js/custom-card/custom-card.js"></script>
    <script src="{{ asset('assets') }}/js/datepicker/date-picker/datepicker.js"></script>
    <script src="{{ asset('assets') }}/js/datepicker/date-picker/datepicker.en.js"></script>
    <script src="{{ asset('assets') }}/js/datepicker/date-picker/datepicker.custom.js"></script>
    <script src="{{ asset('assets') }}/js/counter/jquery.counterup.min.js"></script>

    <script src="{{ asset('assets') }}/js/chart/chartist/chartist.js"></script>
    <script src="{{ asset('assets') }}/js/chart/chartist/chartist-plugin-tooltip.js"></script>
    <script src="{{ asset('assets') }}/js/chart/knob/knob.min.js"></script>
    <script src="{{ asset('assets') }}/js/chart/knob/knob-chart.js"></script>
    <script src="{{ asset('assets') }}/js/chart/apex-chart/apex-chart.js"></script>
    <script src="{{ asset('assets') }}/js/chart/apex-chart/stock-prices.js"></script>
    <script src="{{ asset('assets') }}/js/prism/prism.min.js"></script>
    <script src="{{ asset('assets') }}/js/clipboard/clipboard.min.js"></script>
    <script src="{{ asset('assets') }}/js/counter/jquery.waypoints.min.js"></script>
    <script src="{{ asset('assets') }}/js/counter/jquery.counterup.min.js"></script>
    <script src="{{ asset('assets') }}/js/counter/counter-custom.js"></script>
    <script src="{{ asset('assets') }}/js/custom-card/custom-card.js"></script>
    <script src="{{ asset('assets') }}/js/notify/bootstrap-notify.min.js"></script>
    <script src="{{ asset('assets') }}/js/vector-map/jquery-jvectormap-2.0.2.min.js"></script>
    <script src="{{ asset('assets') }}/js/vector-map/map/jquery-jvectormap-world-mill-en.js"></script>
    <script src="{{ asset('assets') }}/js/vector-map/map/jquery-jvectormap-us-aea-en.js"></script>
    <script src="{{ asset('assets') }}/js/vector-map/map/jquery-jvectormap-uk-mill-en.js"></script>
    <script src="{{ asset('assets') }}/js/vector-map/map/jquery-jvectormap-au-mill.js"></script>
    <script src="{{ asset('assets') }}/js/vector-map/map/jquery-jvectormap-chicago-mill-en.js"></script>
    <script src="{{ asset('assets') }}/js/vector-map/map/jquery-jvectormap-in-mill.js"></script>
    <script src="{{ asset('assets') }}/js/vector-map/map/jquery-jvectormap-asia-mill.js"></script>
    {{-- <script src="{{ asset('assets') }}/js/dashboard/default.js"></script> --}}
    <script src="{{ asset('assets') }}/js/notify/index.js"></script>
    <script src="{{ asset('assets') }}/js/datepicker/date-picker/datepicker.js"></script>
    <script src="{{ asset('assets') }}/js/datepicker/date-picker/datepicker.en.js"></script>
    <script src="{{ asset('assets') }}/js/datepicker/date-picker/datepicker.custom.js"></script>
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


        /* Start Chart Salesman */
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

        /* End Chart Salesman */

        /* Start Chart Product */
        var cData_product = JSON.parse(`<?php echo $data_product['chart_data']; ?>`);
        var num_product = cData_product.data;
        var text_product = cData_product.label;

        var options_product = {
          series: [{
            name: 'Net Profit',
            data: num_product
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
            categories: text_product,
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

        setTimeout(() => {
          var chart_product = new ApexCharts(document.querySelector("#chart-dash-1-line"), options_product);
          chart_product.render();
        }, 2000);

        /* End Chart Product */



      });
    </script>
  @endpush
@endsection
