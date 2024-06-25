@extends('layouts.app_1')

@section('content')
<main id="main" class="main">

<div class="pagetitle">
  <h1>Home</h1>
</div><!-- End Page Title -->

<section class="section dashboard">
  <div class="row">

    <!-- Left side columns -->
    <div class="col-lg-12">
      <div class="row">

        <!-- Sales Card -->
        <div class="col-xxl-4">
          <div class="card info-card sales-card">
            <div class="card-body">
              <h5 class="card-title">Total Advertisements </h5>

              <div class="d-flex align-items-center">
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                  <i class="bi bi-newspaper"></i>
                </div>
                <div class="ps-3">
                  <h6>{{ $advertisements }}</h6>

                </div>
              </div>
            </div>

          </div>
        </div><!-- End Sales Card -->

        <!-- Revenue Card -->
        <div class="col-xxl-4">
          <div class="card info-card revenue-card">
            <div class="card-body">
              <h5 class="card-title">Total Bills </h5>

              <div class="d-flex align-items-center">
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                  <i class="bi bi-cash"></i>
                </div>
                <div class="ps-3">
                  <h6>{{$bills}}</h6>
                </div>
              </div>
            </div>
          </div>
        </div><!-- End Revenue Card -->

        <!-- Customers Card -->
        <div class="col-xxl-4">

          <div class="card info-card customers-card">
            <div class="card-body">
              <h5 class="card-title">Total Departments this month</h5>

              <div class="d-flex align-items-center">
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                  <i class="bi bi-people"></i>
                </div>
                <div class="ps-3">
                  <h6>{{ $hod }}</h6>
                </div>
              </div>

            </div>
          </div>

        </div><!-- End Customers Card -->



    <!-- Right side columns -->
    <!-- <div class="col-lg-4"> -->

   

      <!-- Budget Report -->
      <!-- <div class="card">
        <div class="filter">
          <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
            <li class="dropdown-header text-start">
              <h6>Filter</h6>
            </li>

            <li><a class="dropdown-item" href="#">Today</a></li>
            <li><a class="dropdown-item" href="#">This Month</a></li>
            <li><a class="dropdown-item" href="#">This Year</a></li>
          </ul>
        </div>

        <div class="card-body pb-0">
          <h5 class="card-title">Advertisement Report <span>| This Month</span></h5>

          <div id="budgetChart" style="min-height: 400px;" class="echart"></div>

          <script>
            document.addEventListener("DOMContentLoaded", () => {
              var budgetChart = echarts.init(document.querySelector("#budgetChart")).setOption({
                legend: {
                  data: ['Allocated Budget', 'Actual Spending']
                },
                radar: {

                  indicator: [{
                      name: 'Sales',
                      max: 6500
                    },
                    {
                      name: 'Administration',
                      max: 16000
                    },
                    {
                      name: 'Information Technology',
                      max: 30000
                    },
                    {
                      name: 'Customer Support',
                      max: 38000
                    },
                    {
                      name: 'Development',
                      max: 52000
                    },
                    {
                      name: 'Marketing',
                      max: 25000
                    }
                  ]
                },
                series: [{
                  name: 'Budget vs spending',
                  type: 'radar',
                  data: [{
                      value: [4200, 3000, 20000, 35000, 50000, 18000],
                      name: 'Allocated Budget'
                    },
                    {
                      value: [5000, 14000, 28000, 26000, 42000, 21000],
                      name: 'Actual Spending'
                    }
                  ]
                }]
              });
            });
          </script>

        </div>
      </div> -->

          

       

    </div><!-- End Right side columns -->
     <!-- <div class="col-lg-8">
                    <div class="card">
                          <div class="card-body">
                          <h5 class="card-title">Newspapers</h5>
                          <form id="form2">

                          <div class="row mb-3">
                              <label class="col-sm-4"><b></b></label>
                              <label for="inputText" class="col-sm-3"><b>Newspaper</b></label>
                                <div class="col-sm-2">
                                <label for="inputText" class="sub_total"><b>Count</b></label>
                              </div>
                            </div>
                            @foreach($empanelledCounts as $empanelled)

                            <div class="row mb-3">
                              <label class="col-sm-4"><b></b></label>
                              <label for="inputText" class="col-sm-3">{{$empanelled->news_name}}:</label>
                                <div class="col-sm-2">
                                <label for="inputText" class="sub_total"><b>{{$empanelled->assigned_news->count()}}</b></label>
                              </div>
                            </div>
                            @endforeach
                          </form>
                    </div>                    
                    </div> -->

                    <div class="col-lg-12">
                      <div class="card">
                        <div class="card-body">
                          <h5 class="card-title">Newspapers Count</h5>
                          <div id="newspaperChart" style="height: 400px;"></div>
                        </div>
                      </div>
                    </div>

  </div>
</section>
<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
</main><!-- End #main -->
@endsection
<script>
  document.addEventListener("DOMContentLoaded", () => {
    // Newspaper Count Data
    const newspaperData = @json($empanelledCounts->pluck('assigned_news')->map->count());
    const newspaperNames = @json($empanelledCounts->pluck('news_name'));

    const options = {
      chart: {
        type: 'bar',
        height: 500, // Adjust the height to accommodate more bars
      },
      series: [{
        name: 'Newspapers Count',
        data: newspaperData,
      }],
      xaxis: {
        categories: newspaperNames,
        labels: {
          rotate: -45, // Rotate the x-axis labels for better readability
          trim: false, // Disable label trimming
        },
      },
      dataLabels: {
        enabled: true,
        formatter: function (val) {
          return val > 0 ? val : ''; // Display data labels only for bars with count greater than 5
        },
      },
      plotOptions: {
        bar: {
          
          horizontal: false, // Set to true for horizontal bars, false for vertical bars
        },
      },
      yaxis: {
        max: Math.max(...newspaperData) + 10, // Add some padding to the maximum value for better visualization
      },
      tooltip: {
        y: {
          formatter: function (val) {
            return val + ' ads'; // Customize the tooltip label
          },
        },
      },
    };

    const newspaperChart = new ApexCharts(document.querySelector("#newspaperChart"), options);
    newspaperChart.render();
  });
</script>
