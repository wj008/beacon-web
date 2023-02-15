function createEcharts(elId, name, data) {
    const chartDom = document.getElementById(elId);
    const myChart = echarts.init(chartDom);

    const option = {
        tooltip: {
            trigger: 'item'
        },
        legend: {
            orient: 'vertical',
            left: 'left'
        },
        series: [
            {
                name: 'Access From',
                type: 'pie',
                radius: ['40%', '70%'],
                data: data,
                emphasis: {
                    itemStyle: {
                        shadowBlur: 10,
                        shadowOffsetX: 0,
                        shadowColor: 'rgba(0, 0, 0, 0.5)'
                    }
                }
            }
        ]
    };
    myChart.setOption(option);
}

$(function () {
    $('a.edit-comment').each(function () {
        $(this).on('success', function (ev, ret) {
            console.log(ret);
            if (ret.status) {
                $('#comment-' + ret.id).html(ret.comment);
            }
        });
    });
})

