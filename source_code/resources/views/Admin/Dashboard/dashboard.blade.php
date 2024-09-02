@extends('layouts.admin.master')

@section('content')
    <div class="row">
    <div class="col-sm-6 col-md-3">
      <div class="card card-stats card-round">
        <div class="card-body">
          <div class="row align-items-center">
            <div class="col-icon">
              <div class="icon-big text-center icon-primary bubble-shadow-small">
                <i class="fas fa-users"></i>
              </div>
            </div>
            <div class="col col-stats ms-3 ms-sm-0">
              <div class="numbers">
                <p class="card-category">Customer</p>
                <h4 class="card-title">{{ $customerCount }}</h4> <!-- Display the customer count -->
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-md-3">
      <div class="card card-stats card-round">
        <div class="card-body">
          <div class="row align-items-center">
            <div class="col-icon">
              <div class="icon-big text-center icon-success bubble-shadow-small">
                <i class="fas fa-luggage-cart"></i>
              </div>
            </div>
            <div class="col col-stats ms-3 ms-sm-0">
              <div class="numbers">
                  <p class="card-category">Income</p>
                  <h4 class="card-title">Rp {{ number_format($totalSales, 2) }}</h4>
              </div>
          </div>          
          </div>
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-md-3">
      <div class="card card-stats card-round">
        <div class="card-body">
          <div class="row align-items-center">
            <div class="col-icon">
              <div class="icon-big text-center icon-info bubble-shadow-small">
                <i class="fas fa-user-check"></i>
              </div>
            </div>
            <div class="col col-stats ms-3 ms-sm-0">
              <div class="numbers">
                <p class="card-category">Order (Proses)</p>
                <h4 class="card-title">{{ $orderNotFinishCount }}</h4>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-md-3">
      <div class="card card-stats card-round">
        <div class="card-body">
          <div class="row align-items-center">
            <div class="col-icon">
              <div class="icon-big text-center icon-secondary bubble-shadow-small">
                <i class="far fa-check-circle"></i>
              </div>
            </div>
            <div class="col col-stats ms-3 ms-sm-0">
              <div class="numbers">
                <p class="card-category">Order (Selesai)</p>
                <h4 class="card-title">{{ $orderCount }}</h4> <!-- Display the order count -->
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <!-- Grafik Kunjungan Harian Berdasarkan Jam -->
    <div class="col-md-8">
      <div class="card shadow-sm">
          <div class="card-header text-white">
              <h4 class="card-title">
                  <i class="fas fa-chart-bar me-2"></i> Statistik Kunjungan Harian Berdasarkan Jam (Horizontal)
              </h4>
          </div>
          <div class="card-body">
              <canvas id="hourlyVisitHorizontalBarChart"></canvas>
          </div>
      </div>
  </div>
  
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
      document.addEventListener('DOMContentLoaded', function() {
          var ctx = document.getElementById('hourlyVisitHorizontalBarChart').getContext('2d');
          var hourlyVisitHorizontalBarChart = new Chart(ctx, {
              type: 'bar',
              data: {
                  labels: [
                      // Labels untuk setiap jam
                      @foreach ($hourlyVisits->keys() as $hour)
                          "{{ $hour }}:00",
                      @endforeach
                  ],
                  datasets: [{
                      label: 'Jumlah Kunjungan',
                      data: [
                          @foreach ($hourlyVisits as $visits)
                              {{ $visits }},
                          @endforeach
                      ],
                      backgroundColor: 'rgba(153, 102, 255, 0.2)',
                      borderColor: 'rgba(153, 102, 255, 1)',
                      borderWidth: 1
                  }]
              },
              options: {
                  indexAxis: 'y', // This property changes the chart to a horizontal bar chart
                  scales: {
                      x: {
                          beginAtZero: true,
                          title: {
                              display: true,
                              text: 'Jumlah Kunjungan'
                          }
                      },
                      y: {
                          title: {
                              display: true,
                              text: 'Jam'
                          }
                      }
                  }
              }
          });
      });
  </script>
  

    <!-- Statistik Pengunjung Hari Ini dan Waktu Kunjungan Rata-rata Hari Ini stacked vertically -->
    <div class="col-md-4">
        <div class="row">
            <div class="col-md-12 mb-3">
                <div class="card shadow-sm text-center">
                    <div class="card-header text-white">
                        <h4 class="card-title">
                            <i class="fas fa-users me-2"></i> Jumlah Pengunjung Hari Ini
                        </h4>
                    </div>
                    <div class="card-body">
                        <h2 class="display-4">{{ $visitorCountToday }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card shadow-sm text-center">
                    <div class="card-header text-white">
                        <h4 class="card-title">
                            <i class="fas fa-clock me-2"></i> Waktu Kunjungan Rata-rata Hari Ini
                        </h4>
                    </div>
                    <div class="card-body">
                        <h2 class="display-4">{{ gmdate('H:i:s', $averageVisitTimeToday) }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
              <div class="card shadow-sm text-center">
                <div class="card-header text-white">
                    <h4 class="card-title">
                        <i class="fas fa-comments me-2"></i> Chat Live by Tawk
                    </h4>
                </div>
                <div class="card-body">
                  <div class="row">
                      <div class="col-md-6">
                          <!-- Tawk.to Account Information -->
                          <h5 class="text-muted">Tawk.to Account</h5>
                          <p class="mb-1 text-start"><strong>Email:</strong> <a href="mailto:labserveags@gmail.com">labtekags@gmail.com</a></p>
                          <p class="mb-3 text-start"><strong>Password:</strong> <span class="text-danger">ags123.</span></p>
                                                </div>
                      <div class="col-md-6">
                          <!-- Google Account Information -->
                          <h5 class="text-muted">Google Account</h5>
                          <p class="mb-1 text-start"><strong>Email:</strong> <a href="mailto:labserveags@gmail.com">labtekeags@gmail.com</a></p>
                          <p class="mb-3 text-start"><strong>Password:</strong> <span class="text-danger">labtek123</span></p>
                      </div>
                  </div>
                  <!-- Button to Tawk.to Dashboard -->
                  <div class="text-center">
                      <button onclick="window.open('https://dashboard.tawk.to/login', 'newwindow', 'width=1200,height=600'); return false;" class="btn btn-success">
                          <i class="fas fa-external-link-alt me-2"></i> Go to Tawk.to Dashboard
                      </button>
                  </div>
              </div>
              
            </div>
        </div>
    </div>
</div>

@endsection
