/****************** Google Analytics  *******************/

if (gg_id && gg_id.length > 0) {

    google.load('visualization', '1', {packages:['corechart']});
    google.setOnLoadCallback(loadCharts);

}

function loadCharts() {

    $.ajax({
        url    :Routing.generate('scrclub_cms_charts'),
        success:function (data) {

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
    data.addColumn({type:'string', role:'tooltip'});

    var options = {
        colors   :['#D6D6D6'],
        title    :'',
        animation:{
            duration:1000,
            easing  :'out'
        },
        vAxis    :{baselineColor:'#FFFFFF',
            gridlines           :{
                count:0
            }},
        hAxis    :{baselineColor:'#FFFFFF',
            gridlines           :{
                count:0
            }},
        vAxes    :[
            {}
        ],
        hAxes    :[
            {}
        ],

        min     :0,
        height  :126,
        width   :850,
        fontName:'Helvetica Neue',
        legend  :{position:'right'},
        tooltip :{isHtml:true}
    };

    var chart = new google.visualization.LineChart(document.getElementById('charts'));
    chart.draw(data, options);

    $('#charts').hide(0);
    $('#charts').show(1000);

}

$(document).ready(function () {

    $('.sortable').nestedSortable({

        handle              :'div',
        helper              :'clone',
        items               :'li',
        rootID              :'root',
        opacity             :1,
        tolerance           :'pointer',
        placeholder         :'placeholder',
        forcePlaceholderSize:true,

        update:function (event, ui) {

            var arraied = $('.sortable').nestedSortable('toArray', {startDepthCount:0});

            $.ajax({
                type   :'post',
                cache  :false,
                url    :Routing.generate('scrclub_cms_editnodetree', {_locale:locale}),
                data   :{data:arraied},
                success:function (data) {

                }

            });

        }

    });




});

/****************** GoSquared  *******************/



if (gs_token.length > 0) {


    var oldnum = -1, newnum = 0;

    var origText = $("#counter").html();
    $("#counter").html('');
    //$("#counter").shuffleLetters();
    var currentText = origText;

    function hitCounter() {

        $.getJSON("https://api.gosquared.com/latest/concurrents?callback=?&api_key=" + gs_api + "&site_token=" + gs_token + "", function (data) {
            newnum = data.visitors;

            if(newnum > 0 && newnum != oldnum) {
                console.log("shuffle");
                currentText = translations['currently']+ ' ' + newnum + ' ' + translations['visitors.online'];
                $("#counter").shuffleLetters({
                    "text": currentText
                });
            }

            if(newnum == 0 && newnum != oldnum) {
                currentText = origText;
                $("#counter").shuffleLetters({
                    "text": currentText
                });
            }




            // $('#counter').text(translations['currently']+ ' ' + newnum + ' ' + translations['visitors.online']);
            oldnum = newnum;
        });
    }

    setInterval(function () {
        hitCounter();
    }, 3000);
    hitCounter();

}

/****************** Main page Jquery  *******************/


$(function () {

    $('.delete_node').each(function () {

        $(this).click(function () {

            var li = $($(this).parents().get()[4]);
            var id = li.attr('id').slice(5);

            bootbox.confirm(translations['wanadoo.this'], function (result) {

                if (result) {
                    $.ajax({
                        type   :'post',
                        cache  :false,
                        url    :deleteNodePath,
                        data   :{data:id},
                        success:function (data) {
                            newAlert("success", translations['delete.success']);
                            li.remove();
                        }
                    });
                }
            });
        })
    });

    $('.switch-active').each(function () {

        $(this).on('switch-change', function (e, data) {

            var li = $($(this).parents().get()[1]);
            var id = li.attr('id').slice(5);

            var result = {
                id    :id,
                active:data.value
            }

            $.ajax({
                type   :'post',
                cache  :false,
                url    :updateActiveNodePath,
                data   :result,
                success:function (data) {
                    //newAlert("success", "{{ 'update.success' | trans }}");
                }
            });
        })
    });

})


