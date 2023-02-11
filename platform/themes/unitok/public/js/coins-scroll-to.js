$(document).ready(function () {
    "use strict"; // start of use strict

    var html = '<a href="javascript:;" data-label="launch_details">Launch details</a>' +
        '<a href="javascript:;" data-label="performance">Performance</a>' +
        '<a href="javascript:;" data-label="allocation">Allocation</a>' +
        '<a href="javascript:;" data-label="fund_raising">Fund raising</a>' +
        '<a href="javascript:;" data-label="release_schedule">Release Schedule</a>' +
        '<a href="javascript:;" data-label="technology">Technology</a>' +
        '<a href="javascript:;" data-label="ecosystem">Ecosystem</a>' +
        '<a href="javascript:;" data-label="investors">Investors</a>' +
        '<a href="javascript:;" data-label="community">Community</a>' +
        '<a href="javascript:;" data-label="orientation">Orientation</a>' +
        '<a href="javascript:;" data-label="roadmap">Roadmap</a>';
    $("#scroll_to").html(html);

    $('#scroll_to a').on('click', function() {
        var label = $(this).data("label");
        $('html, body').animate({
            scrollTop: $('label[for="'+label+'"]').offset().top - 70
        }, 300);
    });

    var distance = $('#scroll_to').offset().top;
    $(window).scroll(function () {
        if ($(window).scrollTop() >= distance) {
            $('#scroll_to').closest(".meta-boxes").addClass("affix");

        } else {
            $('#scroll_to').closest(".meta-boxes").removeClass("affix");
        }
    });
});