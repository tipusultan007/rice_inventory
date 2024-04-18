@extends('tablar::page')

@section('content')
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <!-- Page pre-title -->
                    <div class="page-pretitle">
                        Overview
                    </div>
                    <h2 class="page-title">
                        ড্যাশবোর্ড
                    </h2>
                </div>
            </div>
        </div>
    </div>
    <!-- Page body -->
    <div class="page-body">
        <div class="container-xl">
            <div class="row row-deck row-cards">
                <div class="col-12">
                    <div class="row row-cards">
                        <div class="col-sm-6 col-lg-3">
                            <div class="card card-sm">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                            <span class="bg-primary text-white avatar"><!-- Download SVG icon from http://tabler-icons.io/i/currency-dollar -->
<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
     stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
     class="icon icon-tabler icons-tabler-outline icon-tabler-moneybag"><path stroke="none" d="M0 0h24v24H0z"
                                                                              fill="none"/><path
        d="M9.5 3h5a1.5 1.5 0 0 1 1.5 1.5a3.5 3.5 0 0 1 -3.5 3.5h-1a3.5 3.5 0 0 1 -3.5 -3.5a1.5 1.5 0 0 1 1.5 -1.5z"/><path
        d="M4 17v-1a8 8 0 1 1 16 0v1a4 4 0 0 1 -4 4h-8a4 4 0 0 1 -4 -4z"/></svg>
                            </span>
                                        </div>
                                        <div class="col">
                                            <div class="font-weight-medium">
                                                {{ $productQuantities['25'] }}
                                            </div>
                                            <div class="text-secondary">
                                                ২৫ কেজি বস্তা
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="card card-sm">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                            <span class="bg-info text-white avatar"><!-- Download SVG icon from http://tabler-icons.io/i/shopping-cart -->
<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
     stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
     class="icon icon-tabler icons-tabler-outline icon-tabler-moneybag"><path stroke="none" d="M0 0h24v24H0z"
                                                                              fill="none"/><path
        d="M9.5 3h5a1.5 1.5 0 0 1 1.5 1.5a3.5 3.5 0 0 1 -3.5 3.5h-1a3.5 3.5 0 0 1 -3.5 -3.5a1.5 1.5 0 0 1 1.5 -1.5z"/><path
        d="M4 17v-1a8 8 0 1 1 16 0v1a4 4 0 0 1 -4 4h-8a4 4 0 0 1 -4 -4z"/></svg>
                            </span>
                                        </div>
                                        <div class="col">
                                            <div class="font-weight-medium">
                                                {{ $productQuantities['50'] }}
                                            </div>
                                            <div class="text-secondary">
                                                ৫০ কেজি বস্তা
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="card card-sm">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                            <span class="bg-green text-white avatar"><!-- Download SVG icon from http://tabler-icons.io/i/brand-twitter -->
<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
     stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
     class="icon icon-tabler icons-tabler-outline icon-tabler-coin-taka"><path stroke="none" d="M0 0h24v24H0z"
                                                                               fill="none"/><path
        d="M8 8l.553 -.276a1 1 0 0 1 1.447 .894v6.382a2 2 0 0 0 2 2h.5a2.5 2.5 0 0 0 2.5 -2.5v-.5h-1"/><path
        d="M8 11h7"/><path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0"/></svg>
                            </span>
                                        </div>
                                        <div class="col">
                                            <div class="font-weight-medium">
                                                {{ $totalDue }}
                                            </div>
                                            <div class="text-secondary">
                                                ক্রেতা'র বকেয়া
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="card card-sm">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                            <span class="bg-red text-white avatar"><!-- Download SVG icon from http://tabler-icons.io/i/brand-facebook -->
<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
     stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
     class="icon icon-tabler icons-tabler-outline icon-tabler-coin-taka"><path stroke="none" d="M0 0h24v24H0z"
                                                                               fill="none"/><path
        d="M8 8l.553 -.276a1 1 0 0 1 1.447 .894v6.382a2 2 0 0 0 2 2h.5a2.5 2.5 0 0 0 2.5 -2.5v-.5h-1"/><path
        d="M8 11h7"/><path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0"/></svg>
                            </span>
                                        </div>
                                        <div class="col">
                                            <div class="font-weight-medium">
                                                {{ $supplierDue }}
                                            </div>
                                            <div class="text-secondary">
                                                সরবরাহকারী'র বকেয়া
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row my-3">
                <div class="col-6 my-3">
                    <div class="card">
                        <div class="card-header py-1">
                            <h3 class="card-title">আয়-ব্যয়</h3>
                        </div>
                        <div class="card-body">
                            <div id="chartIncomeExpense"></div>
                        </div>
                    </div>
                </div>

                <div class="col-6 my-3">
                    <div class="card">
                        <div class="card-header py-1">
                            <h3 class="card-title">ক্রয় - বিক্রয়</h3>
                        </div>
                        <div class="card-body">
                            <div id="chartSalesPurchases"></div>
                        </div>
                    </div>
                </div>

                <div class="col-12 my-3">
                    <div class="card">
                        <div class="card-header py-1 text-center">
                            <h4 class="card-title ">
                                {{ date('M Y') }}
                            </h4>
                        </div>
                        <div class="card-body">
                            <div id="chart-daily-sales-purchase"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            fetch('/chart-income-expense')
                .then(response => response.json())
                .then(data => {
                    let chartData = Object.keys(data.monthlyIncome).map(month => {
                        return {
                            month: month,
                            income: data.monthlyIncome[month] || 0,
                            expense: data.monthlyExpense[month] || 0
                        }
                    });

                    let categories = chartData.map(data => data.month);
                    let incomeSeries = chartData.map(data => data.income);
                    let expenseSeries = chartData.map(data => data.expense);

                    let monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

                    let labels = categories.map(month => monthNames[month - 1]);
                    let options = {
                        chart: {
                            type: 'bar',
                            toolbar: {
                                show: false,
                            },
                        },
                        plotOptions: {
                            bar: {
                                columnWidth: '50%',
                            }
                        },
                        fill: {
                            opacity: 1,
                        },
                        grid: {
                            padding: {
                                top: -20,
                                right: 0,
                                left: -4,
                                bottom: -4
                            },
                            strokeDashArray: 4,
                        },
                        series: [
                            {
                                name: 'Income',
                                data: incomeSeries,
                                color: '#206bc4'
                            },
                            {
                                name: 'Expense',
                                data: expenseSeries,
                                color: '#d63939'
                            }
                        ],
                        xaxis: {
                            categories: labels
                        }
                    };

                    let chartIncomeExpense = new ApexCharts(document.querySelector("#chartIncomeExpense"), options);
                    chartIncomeExpense.render();
                });
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            fetch('/chart-sales-purchases')
                .then(response => response.json())
                .then(data => {
                    let months = Object.keys(data.sales);
                    let salesData = Object.values(data.sales);
                    let purchaseData = Object.values(data.purchases);

                    let monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

                    let labels = months.map(month => monthNames[month - 1]);

                    let options = {
                        chart: {
                            type: 'bar',
                            height: 300
                        },
                        grid: {
                            padding: {
                                top: -20,
                                right: 0,
                                left: -4,
                                bottom: -4
                            },
                            strokeDashArray: 4,
                        },
                        fill: {
                            opacity: 1,
                        },
                        series: [
                            {
                                name: 'Sales',
                                data: salesData,
                                color: '#206bc4'
                            },
                            {
                                name: 'Purchases',
                                data: purchaseData,
                                color: '#d63939'
                            }
                        ],
                        xaxis: {
                            categories: labels
                        }
                    };

                    let chart = new ApexCharts(document.querySelector("#chartSalesPurchases"), options);
                    chart.render();
                });
        });
    </script>
    <script>
        // @formatter:off
        document.addEventListener("DOMContentLoaded", function () {
            fetch('/getDailySalePurchaseChartData')
                .then(response => response.json())
                .then(data => {
                    // Extract data from the response
                    const series = data.series;
                    const categories = data.xaxis.categories;
                    // Render the chart with the received data
                    window.ApexCharts && (new ApexCharts(document.getElementById('chart-daily-sales-purchase'), {
                        chart: {
                            type: "line",
                            fontFamily: 'inherit',
                            height: 400,
                            parentHeightOffset: 0,
                            toolbar: {
                                show: false,
                            },
                            animations: {
                                enabled: false
                            },
                        },
                        fill: {
                            opacity: 1,
                        },
                        stroke: {
                            width: 2,
                            lineCap: "round",
                            curve: "smooth",
                        },
                        series: series,
                        tooltip: {
                            theme: 'dark'
                        },
                        grid: {
                            padding: {
                                top: -20,
                                right: 0,
                                left: -4,
                                bottom: -4
                            },
                            strokeDashArray: 4,
                        },
                        xaxis: {
                            labels: {
                                padding: 0,
                            },
                            tooltip: {
                                enabled: false
                            },
                            categories: categories,

                        },
                        yaxis: {
                            labels: {
                                padding: 4
                            },
                        },
                        colors: [tabler.getColor("primary"),tabler.getColor("danger"), tabler.getColor("success"), tabler.getColor("orange")],
                        legend: {
                            show: true,
                            position: 'bottom',
                            offsetY: 12,

                            itemMargin: {
                                horizontal: 10,
                                vertical: 15
                            },
                        },
                    })).render();
                });
        });

        // @formatter:on
    </script>
@endsection
