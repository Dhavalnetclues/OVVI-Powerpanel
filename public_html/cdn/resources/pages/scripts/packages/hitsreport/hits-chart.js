function getChartColorsArray(e)
{
	if (null !== document.getElementById(e))
	{
		var e = document.getElementById(e).getAttribute("data-colors");
		return (e = JSON.parse(e)).map(function (e)
		{
			var t = e.replace(" ", "");
			if (-1 === t.indexOf(","))
			{
				var o = getComputedStyle(document.documentElement).getPropertyValue(t);
				return o || t
			}
			e = e.split(",");
			return 2 != e.length ? t : "rgba(" + getComputedStyle(document.documentElement).getPropertyValue(e[0]) + "," + e[1] + ")"
		})
	}
}

$('#pageHitsChartFilter').change(function () {
  $("#hits-chart").html('');
  var year = $('#pageHitsChartFilter').val();
  $.ajax({
      type: "POST",
      url: window.site_url + "/powerpanel/hits-report/mobilehist",
      data: {year: year},
      async: false,
      dataType: 'JSON',
      success: function (data) {
        hitsChartData(data);
      }
  });
});

function hitsChartData(json) {
    var chartColumnColors = getChartColorsArray("hits-chart"),
    options = {
       chart: {
          height: 350,
          type: "bar",
          toolbar: {
             show: !1
          }
       },
       plotOptions: {
          bar: {
             horizontal: !1,
             columnWidth: "45%",
             endingShape: "rounded"
          }
       },
       dataLabels: {
          enabled: !1
       },
       stroke: {
          show: !0,
          width: 2,
          colors: ["transparent"]
       },
       series: json[1],
       colors: chartColumnColors,
       xaxis: {
          categories: json[0]
       },
       grid: {
          borderColor: "#f1f1f1"
       },
       fill: {
          opacity: 1
       },
       tooltip: {
          y: {
             formatter: function (t) {
                return t
             }
          }
       }
    },
    chart = new ApexCharts(document.querySelector("#hits-chart"), options);
    chart.render().then(() => {
        setTimeout(function() {
            chart.dataURI().then(({ imgURI, blob }) => {
                $('#chart_div').val(imgURI);
            })
        }, 5000)
    });
}