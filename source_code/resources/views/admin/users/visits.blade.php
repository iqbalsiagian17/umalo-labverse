@extends('layouts.admin.master')

@section('content')
    <h1>Statistik Kunjungan</h1>

    <div class="row mb-4">
        <!-- Kartu Jumlah Kunjungan Harian -->
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-header">
                    <h2>Kunjungan Harian</h2>
                </div>
                <div class="card-body">
                    <h3>{{ $dailyVisits->sum('total') }}</h3>
                    <p>Total kunjungan dalam 30 hari terakhir</p>
                </div>
            </div>
        </div>

        <!-- Kartu Jumlah Kunjungan Bulanan -->
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-header">
                    <h2>Kunjungan Bulanan</h2>
                </div>
                <div class="card-body">
                    <h3>{{ $monthlyVisits->sum('total') }}</h3>
                    <p>Total kunjungan dalam 12 bulan terakhir</p>
                </div>
            </div>
        </div>

        <!-- Kartu Jumlah Kunjungan Per Jam -->
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-header">
                    <h2>Kunjungan Per Jam</h2>
                </div>
                <div class="card-body">
                    <h3>{{ $hourlyVisits->sum('total') }}</h3>
                    <p>Total kunjungan dalam 24 jam terakhir</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Kartu Grafik Kunjungan Harian -->
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h2>Kunjungan Harian (30 Hari Terakhir)</h2>
                </div>
                <div class="card-body">
                    <canvas id="dailyVisitChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>

        <!-- Kartu Grafik Kunjungan Bulanan -->
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h2>Kunjungan Bulanan (12 Bulan Terakhir)</h2>
                </div>
                <div class="card-body">
                    <canvas id="monthlyVisitChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>

        <!-- Kartu Grafik Kunjungan Per Jam -->
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h2>Kunjungan Per Jam (24 Jam Terakhir)</h2>
                </div>
                <div class="card-body">
                    <canvas id="hourlyVisitChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Grafik Kunjungan Harian
            var dailyCtx = document.getElementById('dailyVisitChart').getContext('2d');
            var dailyVisitChart = new Chart(dailyCtx, {
                type: 'line',
                data: {
                    labels: @json($dailyVisits->pluck('date')),
                    datasets: [{
                        label: 'Kunjungan Harian (30 Hari Terakhir)',
                        data: @json($dailyVisits->pluck('total')),
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 2,
                        fill: false
                    }]
                },
                options: {
                    scales: {
                        x: {
                            type: 'time',
                            time: {
                                unit: 'day'
                            }
                        },
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Grafik Kunjungan Bulanan
            var monthlyCtx = document.getElementById('monthlyVisitChart').getContext('2d');
            var monthlyVisitChart = new Chart(monthlyCtx, {
                type: 'line',
                data: {
                    labels: @json($monthlyVisits->pluck('month')),
                    datasets: [{
                        label: 'Kunjungan Bulanan (12 Bulan Terakhir)',
                        data: @json($monthlyVisits->pluck('total')),
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 2,
                        fill: false
                    }]
                },
                options: {
                    scales: {
                        x: {
                            type: 'time',
                            time: {
                                unit: 'month'
                            }
                        },
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Grafik Kunjungan Per Jam
            var hourlyCtx = document.getElementById('hourlyVisitChart').getContext('2d');
            var hourlyVisitChart = new Chart(hourlyCtx, {
                type: 'line',
                data: {
                    labels: @json($hourlyVisits->pluck('hour')),
                    datasets: [{
                        label: 'Kunjungan Per Jam (24 Jam Terakhir)',
                        data: @json($hourlyVisits->pluck('total')),
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 2,
                        fill: false
                    }]
                },
                options: {
                    scales: {
                        x: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Jam dalam Sehari'
                            }
                        },
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>
@endsection
