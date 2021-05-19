am4core.ready(function () {
    // Themes begin
    am4core.useTheme(am4themes_animated);
    // Themes end

    var chart = am4core.create("chart_money", am4charts.XYChart);

    $.ajax({
        url: site_url + '/dashboard/get_data_sales',
        success: function(data) {
            data = JSON.parse(data)
            chart_data = [];
            let dateGet = new Date()
            let this_date = dateGet.getDate()
            let past_date = this_date - 6
            let future_date = this_date + 1
            let value_data = []
            for (let i = 0; i < 7; i++) {
                if (data[i]) {
                    value_data[data[i].date] = data[i].total
                }
            }
            for (let i = past_date; i < future_date; i++) {
                let date = new Date()
                let value = []
                date.setDate(i)
                if (value_data[i]) {
                    value.push(value_data[i])
                } else {
                    value.push(0)
                }
                chart_data.push({
                    date: date,
                    value: value
                })
            }
            chart.data = chart_data
        }
    })

    // Create axes
    var dateAxis = chart.xAxes.push(new am4charts.DateAxis());
    dateAxis.renderer.minGridDistance = 60;

    var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());

    // Create series
    var series = chart.series.push(new am4charts.LineSeries());
    series.dataFields.valueY = "value";
    series.dataFields.dateX = "date";
    series.tooltipText = "{value}"

    series.tooltip.pointerOrientation = "vertical";

    chart.cursor = new am4charts.XYCursor();
    chart.cursor.snapToSeries = series;
    chart.cursor.xAxis = dateAxis;

    //chart.scrollbarY = new am4core.Scrollbar();
    // chart.scrollbarX = new am4core.Scrollbar();

}); // end am4core.ready()

am4core.ready(function () {

    // Themes begin
    am4core.useTheme(am4themes_animated);
    // Themes end

    // Create chart instance
    if ($('#customer_chart').length > 0) {
        var chartPie = am4core.create("customer_chart", am4charts.PieChart);

        $.ajax({
            url: site_url + '/dashboard/get_data_customer',
            success: function(data) {
                data = JSON.parse(data)
                chartPie.data = data
            }
        })
    
        // Add and configure Series
        var pieSeries = chartPie.series.push(new am4charts.PieSeries());
        pieSeries.dataFields.value = "value";
        pieSeries.dataFields.category = "jenis_kelamin";
        pieSeries.slices.template.stroke = am4core.color("#fff");
        pieSeries.slices.template.strokeWidth = 2;
        pieSeries.slices.template.strokeOpacity = 1;
    
        // This creates initial animation
        pieSeries.hiddenState.properties.opacity = 1;
        pieSeries.hiddenState.properties.endAngle = -90;
        pieSeries.hiddenState.properties.startAngle = -90;
    }

}); // end am4core.ready()