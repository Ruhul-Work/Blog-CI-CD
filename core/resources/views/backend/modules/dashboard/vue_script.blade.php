<script>
    var domainPath = '{{ env('APP_URL') }}';
    new Vue({
        el: '#app',
        data() {
            return {
                monthlySales: [],
                monthlyOrders: [],
                chart: null,
                totalOrders: 0,
                todayOrders: 0,
                totalSales: 0,
                todaySales: 0,
                pendingOrders: 0,
                completeOrders: 0,
                recentProducts: [],
                topSellingPackages: [],
                
            };
        },
        methods: {
            loadData() {
                axios.get(`${domainPath}api/dashboard/`)
                    .then(response => {
                        this.monthlySales = this.formatChartData(response.data.monthlySales);
                        this.monthlyOrders = this.formatChartData(response.data.monthlyOrders);
                        if (this.chart) {
                            this.updateChart();
                        } else {
                            this.initChart();
                        }
                        this.animateValue('todayOrders', 0, response.data.todayOrders, 2000);
                        this.animateValue('totalOrders', 0, response.data.totalOrders, 2000);
                        this.animateValue('pendingOrders', 0, response.data.pendingOrders, 2000);
                        this.animateValue('completeOrders', 0, response.data.completeOrders, 2000);
                        this.animateValue('todaySales', 0, response.data.todaySales, 2000, true);
                        this.animateValue('totalSales', 0, response.data.totalSales, 2000, true);
                        // Handle recent and low-stock products
                        this.recentProducts = response.data.recentProducts;
                        this.topSellingPackages = response.data.topSellingPackages;
                        this.lowStockProducts = response.data.lowStockProducts;
                    })
                    .catch(error => {
                        console.error('An error occurred while fetching data:', error);
                    });
            },
            formatChartData(data) {

                return Array.from({
                    length: 12
                }, (_, i) => {
                    const month = i + 1;
                    return [
                        new Date(new Date().getFullYear(), i).getTime(),
                        data[month] || 0,
                    ];
                });
            },
            initChart() {
                const options = {
                    chart: {
                        type: "line",
                        height: 300,
                        foreColor: "#999",
                        dropShadow: {
                            enabled: true,
                            top: -2,
                            left: 2,
                            blur: 5,
                            opacity: 0.06,
                        },
                    },
                    colors: ['#00E396', '#0090FF'],
                    stroke: {
                        curve: "smooth",
                        width: 3,
                    },
                    dataLabels: {
                        enabled: false
                    },
                    series: [{
                        name: "Sales",
                        data: this.monthlySales
                    },
                        {
                            name: "Orders",
                            data: this.monthlyOrders
                        },
                    ],
                    markers: {
                        size: 0
                    },
                    xaxis: {
                        type: "datetime",
                        axisBorder: {
                            show: false
                        },
                        axisTicks: {
                            show: false
                        },
                        labels: {
                            formatter: (val) => new Date(val).toLocaleDateString('en-GB', {
                                month: 'short',
                                year: 'numeric',
                            }),
                        },
                    },
                    yaxis: {
                        labels: {
                            offsetX: 14,
                            offsetY: -5,
                        },
                        tooltip: {
                            enabled: true
                        },
                    },
                    grid: {
                        padding: {
                            left: -5,
                            right: 5
                        },
                    },
                    tooltip: {
                        x: {
                            format: "dd MMM yyyy"
                        },
                    },
                    legend: {
                        position: 'top',
                        horizontalAlign: 'left',
                    },
                    fill: {
                        type: "solid",
                        fillOpacity: 0.7,
                    },
                };

                this.chart = new ApexCharts(
                    document.querySelector("#timeline-chart"),
                    options
                );
                this.chart.render();
            },
            updateChart() {
                this.chart.updateSeries([{
                    name: "Sales",
                    data: this.monthlySales
                },
                    {
                        name: "Orders",
                        data: this.monthlyOrders
                    },
                ]);
            },
            formatPrice(price) {

                return parseFloat(price).toFixed(0);
            },
            
            
            

            formatWithK(value) {
                if (value >= 1000000000) {
                    // Convert to billions with 'B'
                    return (value / 1000000000).toFixed(3) + 'B';
                } else if (value >= 10000000) {
                    // Convert to crores with 'Cr'
                    return (value / 10000000).toFixed(3) + 'Cr';
                } else if (value >= 1000000) {
                    // Convert to millions with 'M'
                    return (value / 1000000).toFixed(3) + 'M';
                } else if (value >= 100000) {
                    // Convert to lakhs with 'L'
                    return (value / 100000).toFixed(3) + 'L';
                } else if (value >= 1000) {
                    // Convert to thousands with 'k'
                    return (value / 1000).toFixed(2) + 'k';
                } else {
                    // Keep two decimal places for smaller values
                    return value.toFixed(2);
                }
            },

            getImageUrl(relativePath) {
                if (relativePath) {
                    return domainPath + relativePath;
                } else {
                    return domainPath +
                        'theme/frontend/assets/img/default/book.png';
                }
            },
            animateValue(refName, start, end, duration, isCurrency = false) {
                let startTimestamp = null;
                const step = (timestamp) => {
                    if (!startTimestamp) startTimestamp = timestamp;
                    const progress = Math.min((timestamp - startTimestamp) / duration, 1);
                    let value = progress * (end - start) + start;
                    if (isCurrency || value >= 1000) {
                        this[refName] = this.formatWithK(value);
                    } else {
                        this[refName] = Math.floor(value);
                    }

                    if (progress < 1) {
                        window.requestAnimationFrame(step);
                    }
                };
                window.requestAnimationFrame(step);
            },
        },
        mounted() {
            this.loadData();
        },
    });
</script>



