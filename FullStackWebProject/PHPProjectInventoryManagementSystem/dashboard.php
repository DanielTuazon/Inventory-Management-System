
<?php    
    session_start();

    if(!isset($_SESSION['user'])) header('location: login.php');

    $user = $_SESSION['user'];

    include('database/po_status_pie_graph.php');

    include('database/supplier_product_bar_graph.php');

    include('database/delivery_history.php');

?>

<!DOCTYPE html>
<html>
    <head>
        <title>Dashboard - Inventory Management System</title>
        <link rel="stylesheet" type="text/css" href="css/login.css?v=<?= time(); ?>">
        <script src="https://kit.fontawesome.com/9b6ef7dac1.js" crossorigin="anonymous"></script>
    </head>
    <body>
        <div id="dashboardMainContainer">
            <?php include('partials/app-sidebar.php') ?>
            <div class="dashboard_content_container" id="dashboard_content_container">
                <?php include('partials/app-topnav.php') ?>
                <div class="dashboard_content">
                    <div class="dashboard_content_main dashboard_charts">
                        <div class="col50">
                            <figure class="highcharts-figure">
                                <div id="container"></div>
                                <p class="highcharts-description">
                                Here is the breakdown of the purchase orders by status. To see what
                                the current numbers of each respective statuses are, you can hover over them.
                                </p>
                            </figure>
                        </div>
                        <div class="col50">
                            <figure class="highcharts-figure">
                                <div id="containerBarChart"></div>
                                <p class="highcharts-description">
                                Here shows how many products each suppliers produces that we received.
                                To see the current numbers of each respective supplier, you can hover over them.
                                </p>
                            </figure>
                        </div>
                        <div width="100%" id="deliveryHistory">


                        </div>
                    </div>
                </div>
            </div>
        </div>
    <script src="js/script.js">
    </script>

    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>

    <script>
        var graphData = <?= json_encode($results) ?>;
        
        Highcharts.chart('container', {
            chart: {
                type: 'pie',
                DataLabelsOverflowValue: 'justify'
            },
            title: {
                text: 'Purchase Orders By Status',
                align: 'left'
            },
            tooltip: {
                pointFormatter: function(){
                    var point = this,
                        series = point.series;

                    return `<b>${series.name}</b>: <b>${point.y}</b>`
                }
            },
            subtitle: {
                text:
                'Source:<a href="./view-order.php" target="_default"> Product Orders</a>'
            },
            plotOptions: {
                series: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: [{
                        enabled: true,
                        distance: 20
                    }, 
                    {
                        enabled: true,
                        distance: -40,
                        format: '{point.percentage:.2f}%',
                        style: {
                            fontSize: '1.4em',
                            textOutline: 'none',
                            opacity: 0.7
                        },
                        filter: {
                            operator: '>',
                            property: 'percentage',
                            value: 10
                        }
                    }]
                }
            },
            series: [
                {
                    name: 'Status',
                    colorByPoint: true,
                    data: graphData
                }
            ]
        });

        var barGraphData = <?= json_encode($bar_chart_data) ?>;
        var barGraphCategories = <?= json_encode($categories) ?>;

        Highcharts.chart('containerBarChart', {
            chart: {
                type: 'column',
                DataLabelsOverflowValue: 'justify'
            },
            title: {
                text: 'Product Count Assigned To Supplier',
                align: 'left'
            },
            subtitle: {
                text:
                    'Source: <a target="_blank" ' +
                    'href="./supplier-view.php"> Supplier View</a>',
                align: 'left'
            },
            xAxis: {
                categories: barGraphCategories,
                crosshair: true,
                accessibility: {
                    description: 'Suppliers'
                }
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Product Count'
                }
            },
            tooltip: {
                pointFormatter: function(){
                    var point = this,
                        series = point.series;
                    
                    return `<b>${point.category}</b>: ${point.y}`
                }
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                }
            },
            series: [
                {
                    name: 'Suppliers',
                    data: barGraphData
                }
            ],
        });

        var lineCategories = <?= json_encode($line_categories) ?>;
        var lineData = <?= json_encode($line_data) ?>;

        Highcharts.chart('deliveryHistory', {
            chart: {
                type: 'spline'
            },
            title: {
                text: 'Delivery History Per Day',
                align: 'left'
            },

            subtitle: {
                text: 'By Job Category. Source: <a href="./product-view.php" target="_blank">View Product</a>.',
                align: 'left'
            },

            yAxis: {
                title: {
                    text: 'Product Delivered'
                }
            },

            xAxis: {
                categories: lineCategories
            },

            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle'
            },

            plotOptions: {
                series: {
                    label: {
                        connectorAllowed: false
                    },
                }
            },

            series: [{
                name: 'Product Delivered',
                data: lineData
            }],

            responsive: {
                rules: [{
                    condition: {
                        maxWidth: 500
                    },
                    chartOptions: {
                        legend: {
                            layout: 'horizontal',
                            align: 'center',
                            verticalAlign: 'bottom'
                        }
                    }
                }]
            }

        });

    </script>
    </body>
</html>