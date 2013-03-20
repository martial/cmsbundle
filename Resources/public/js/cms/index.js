
if(gg_id.length > 0 ) {

google.load('visualization', '1', {packages: ['corechart']});
google.setOnLoadCallback(loadCharts);

}

function loadCharts() {

    $.ajax({
        url: Routing.generate('scrclub_cms_charts'),
        success: function(data) {

            if (data == 'Error') {
                $('#charts').html('{% trans %}error.google.identification{% endtrans %}');
            } else {
                displayCharts(data);
                $('#charts').css('margin-bottom', '-156px');
            }

        }

    });

}

function displayCharts(data) {

    var data = google.visualization.arrayToDataTable(data);
    data.addColumn({type: 'string', role: 'tooltip'});

    var options = {
        colors: ['#D6D6D6'],
        title: '',
        animation: {
            duration: 1000,
            easing: 'out'
        },
        vAxis: {baselineColor: '#FFFFFF',
            gridlines: {
                count: 0
            }},
        hAxis: {baselineColor: '#FFFFFF',
            gridlines: {
                count: 0
            }},
        vAxes: [
            {}
        ],
        hAxes: [
            {}
        ],

        min: 0,
        height: 126,
        width: 850,
        fontName: 'Helvetica Neue',
        legend: {position: 'right'},
        tooltip: {isHtml: true}
    };

    var chart = new google.visualization.LineChart(document.getElementById('charts'));
    chart.draw(data, options);

    $('#charts').hide(0);
    $('#charts').show(1000);

}

$(document).ready(function() {

    $('.sortable').nestedSortable({

        handle: 'div',
        helper: 'clone',
        items: 'li',
        rootID: 'root',
        opacity: 1,
        tolerance: 'pointer',
        placeholder: 'placeholder',
        forcePlaceholderSize: true,

        update: function(event, ui) {

            var arraied = $('.sortable').nestedSortable('toArray', {startDepthCount: 0});

            $.ajax({
                type: 'post',
                cache: false,
                url: Routing.generate('scrclub_cms_editnodetree', {_locale:locale}),
                data: {data: arraied},
                success: function(data) {

                }

            });

        }

    });



       




});

if(gs_token.length > 0 ) {


var oldnum = 0, newnum = 0;
function hitCounter() {
    $.getJSON("https://api.gosquared.com/latest/concurrents?callback=?&api_key="+gs_api+"&site_token="+gs_token+"", function(data) {
        newnum = data.visitors;
        if (newnum != oldnum) {
            var diff = newnum - oldnum;
            var j = 1;
            updatey(oldnum, j, diff);
        }
        $('#counter').text('Currently ' + newnum + ' visitors online.');
        oldnum = newnum;
    });
}
setInterval(function() {
    hitCounter();
}, 3000);
hitCounter();

}


