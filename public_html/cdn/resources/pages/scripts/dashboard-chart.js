$(document).ready(function() {
    if (liveUsersEnabled) {
        liveUsersChart();
        // setInterval(function() {
        //     liveUsersChart();
        // }, 10000);
    }
    let year = $('.card-header-dropdown a.LeadFilter:nth-child(2)').data('value');
    LeadFilter(year);

    year = $('.dashboard-header a.docChartFilter:nth-child(2)').data('value');
    docChartFilter(year, 'dashboard');

    $("#pageHitsChartFilter").change(function() {
        var timeslab = $(this).find("option:selected").data("timeparam");
        var year = $("#pageHitsChartFilter").val();
        $.ajax({
            type: "POST",
            url: window.site_url + "/powerpanel/dashboard/mobilehist",
            data: {
                year: year,
                timeparam: timeslab
            },
            async: false,
            dataType: "JSON",
            success: function(data) {
                pageHitsChart(data);
            },
        });
    });

    $("#searchChartFilter").change(function() {
        var timeslab = $(this).find("option:selected").data("timeparam");
        var year = $("#searchChartFilter").val();
        $.ajax({
            type: "POST",
            url: window.site_url + "/powerpanel/dashboard/search-chart",
            data: {
                year: year,
                timeparam: timeslab
            },
            async: false,
            dataType: "JSON",
            success: function(data) {
                searchChart(data);
            },
        });
    });

    var dataTableAvailableWf = $("#availablewf-for-roles").DataTable({
        paging: false,
        ordering: false,
        info: false,
        oLanguage: {
            sEmptyTable: "No Workflow available.",
        },
    });

    $("#searchfilter-available-wf").keyup(function() {
        dataTableAvailableWf.search(this.value).draw();
    });

    var dataTablePendingWf = $("#pendingwf-for-roles").DataTable({
        paging: false,
        ordering: false,
        info: false,
        oLanguage: {
            sEmptyTable: "No Workflow pending.",
        },
    });

    $("#searchfilter-pending-wf").keyup(function() {
        dataTablePendingWf.search(this.value).draw();
    });
});

function searchChart(json) {
    google.charts.load("current", {
        packages: ["corechart"]
    });
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        var data = google.visualization.arrayToDataTable(json);
        var options = {
            title: "",
        };
    }
}

$(".LeadFilter").click(function() {
    var year = $(this).attr('data-value');
    $(".LeadFilter").removeClass('active');
    $(this).addClass('active');
    LeadFilter(year);
});

function LeadFilter(year) {
    let text = $('.card-header-dropdown a.LeadFilter.active').text().trim();
    
    $("#curve_chart").html('');
    $.ajax({
        type: "POST",
        url: window.site_url + "/powerpanel/dashboard/LeadChart",
        data: {
            year: year
        },
        async: false,
        dataType: "JSON",
        success: function(data) {
            $('#currentLeadFilter').html(text + ' <i class="mdi mdi-chevron-down ms-1"></i>')
            LeadChart(data);
        },
    });
}

function LeadChart(json) {
    var chartDonutBasicColors = getChartColorsArray("curve_chart"),
        options = {
            series: json[1],
            labels: json[0],
            chart: {
                height: 333,
                type: "donut"
            },
            legend: {
                position: "bottom"
            },
            stroke: {
                show: !1
            },
            dataLabels: {
                dropShadow: {
                    enabled: !1
                }
            },
            colors: chartDonutBasicColors
        };
    (chart = new ApexCharts(document.querySelector("#curve_chart"), options)).render();
}

document.querySelectorAll(".layout-rightside-btn").forEach(function(e) {
        var t = document.querySelector(".layout-rightside-col");
        e.addEventListener("click", function() {
            t.classList.contains("d-block") ? (t.classList.remove("d-block"), t.classList.add("d-none")) : (t.classList.remove("d-none"), t.classList.add("d-block"))
        })
    }), window.addEventListener("resize", function() {
        var e = document.querySelector(".layout-rightside-col");
        document.querySelectorAll(".layout-rightside-btn").forEach(function() {
            window.outerWidth < 1699 || 3440 < window.outerWidth ? e.classList.remove("d-block") : 1699 < window.outerWidth && (console.log("yesss"), e.classList.add("d-block"))
        })
    }),
    document.querySelector(".overlay") != null ?
    document.querySelector(".overlay").addEventListener("click", function() {
        1 == document.querySelector(".layout-rightside-col").classList.contains("d-block") && document.querySelector(".layout-rightside-col").classList.remove("d-block")
    }) : '', window.addEventListener("load", function() {
        var e = document.querySelector(".layout-rightside-col");
        document.querySelectorAll(".layout-rightside-btn").forEach(function() {
            window.outerWidth < 1699 || 3440 < window.outerWidth ? e.classList.remove("d-block") : 1699 < window.outerWidth && e.classList.add("d-block")
        })
    });


function getChartColorsArray(t) {
    if (null !== document.getElementById(t)) {
        var t = document.getElementById(t).getAttribute("data-colors");
        return (t = JSON.parse(t)).map(function(t) {
            var e = t.replace(" ", "");
            if (-1 === e.indexOf(",")) {
                var o = getComputedStyle(document.documentElement).getPropertyValue(e);
                return o || e
            }
            t = t.split(",");
            return 2 != t.length ? e : "rgba(" + getComputedStyle(document.documentElement).getPropertyValue(t[0]) + "," + t[1] + ")"
        })
    }
}

function liveUsersChart() {
    $.ajax({
        type: "POST",
        url: window.site_url + "/powerpanel/dashboard/ajax",
        data: {
            type: 'liveusers'
        },
        async: false,
        dataType: "JSON",
        success: function(data) {
            liveUsersChartInit(data);
        },
    });
}

function liveUsersChartInit(data) {
    var vectorMapWorldLineColors = getChartColorsArray("users-by-country"),
        worldlinemap = new jsVectorMap({
            map: "world_merc",
            selector: "#users-by-country",
            panOnDrag: false,
            zoomOnScroll: true,
            zoomButtons: true,
            zoomAnimate:true,
            zoomMax:100,
            markers: data.markers,
            lines: data.lines,
            regionStyle: {
                initial: {
                    stroke: "#9599ad",
                    strokeWidth: .25,
                    fill: vectorMapWorldLineColors,
                    fillOpacity: 1
                }
            },
            lineStyle: {
                animation: true,
                strokeDasharray: "6 3 6"
            }
        });
}

$(".docChartFilter").click(function() {
    var year = $(this).attr('data-value');
    $(".docChartFilter").removeClass('active');
    $(this).addClass('active');
    docChartFilter(year, 'dashboard');
});

$("#pageHitsChartFilter").change(function() {
    var year = $('#pageHitsChartFilter').val();
    docChartFilter(year, 'report');
});

function docChartFilter(year, type) {
    $("#doc-chart").html('');
    $.ajax({
        type: "POST",
        url: window.site_url + "/powerpanel/document-report/doc-chart",
        data: {
            year: year,
            type: type
        },
        async: false,
        dataType: "JSON",
        success: function(data) {
            docChartData(data);
        },
    });
}

function docChartData(json) {
    var linechartcustomerColors = getChartColorsArray("doc-chart"),
        options = {
            series: json[1],
            chart: {
                height: 370,
                type: "line",
                toolbar: {
                    show: !1
                }
            },
            stroke: {
                width: [10, 2, 10, 2],
                curve: "smooth"
            },
            fill: {
                opacity: [1, .3, 1, .2]
            },
            markers: {
                size: [0, 0, 0, 0],
                strokeWidth: 2,
                hover: {
                    size: 4
                }
            },
            xaxis: {
                categories: json[0],
                axisTicks: {
                    show: !1
                },
                axisBorder: {
                    show: !1
                }
            },
            grid: {
                show: !0,
                xaxis: {
                    lines: {
                        show: !0
                    }
                },
                yaxis: {
                    lines: {
                        show: !1
                    }
                },
                padding: {
                    top: 0,
                    right: -2,
                    bottom: 15,
                    left: 10
                }
            },
            legend: {
                show: !0,
                horizontalAlign: "center",
                offsetX: 0,
                offsetY: -5,
                markers: {
                    width: 9,
                    height: 9,
                    radius: 6
                },
                itemMargin: {
                    horizontal: 10,
                    vertical: 0
                }
            },
            plotOptions: {
                bar: {
                    columnWidth: "30%",
                    barHeight: "70%"
                }
            },
            colors: linechartcustomerColors,
            tooltip: {
                shared: !0,
                y: [{
                    formatter: function(e) {
                        return void 0 !== e ? e.toFixed(0) : e
                    }
                }]
            }
        },
        chart = new ApexCharts(document.querySelector("#doc-chart"), options);
    chart.render().then(() => {
        setTimeout(function() {
            chart.dataURI().then(({
                imgURI,
                blob
            }) => {
                $('#chart_div').val(imgURI);
            })
        }, 5000)
    });
}

$(document).on("click", ".dashboard_checkbox", function() {
    $('body').loader(loaderConfig);
    var widgetkey = $(this).val();
    if ($(this).prop('checked') == true) {
        var widget_disp = 'Y';
    } else {
        var widget_disp = 'N';
    }
    $.ajax({
        type: "POST",
        url: site_url + "/powerpanel/dashboard/updatedashboardsettings",
        data: {
            'widgetkey': widgetkey,
            'widget_disp': widget_disp
        },
        async: false,
        beforeSend: function() {
            $('body').loader(loaderConfig);
        },
        success: function(data) {
            window.location.reload();
        },
        error: function(xhr, ajaxOptions, thrownError) {
            $.loader.close(true);
            alert("Error:" + thrownError);
            window.location.reload();
        }
    });
});