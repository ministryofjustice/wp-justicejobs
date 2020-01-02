/*
Displays correct copy of previous search in filter
*/
jQuery(document).ready(function ($) {

    function getQueryVariable(variable) {
        var query = window.location.search.substring(1);
        var vars = query.split("&");
        for (var i = 0; i < vars.length; i++) {
            var pair = vars[i].split("=");
            if (pair[0] == variable) {
                return pair[1];
            }
        }
        return (false);
    }

    var roleType = getQueryVariable("role-type");
    var salaryRange = getQueryVariable("salary-range")
    var workingPattern = getQueryVariable("working-pattern")
    var roleTypeName;
    var salaryRangeName;
    var workingPatternName;

    // bring the user back to view results after a search action has taken place
    if (getQueryVariable('s') !== false) {
        $('html, body').stop(true, true).animate({
            scrollTop: $(".search_contain").offset().top - $('#main-nav-hook').outerHeight()
        }, 400);
    }

    if (roleType.length) {
        $('#role-type').parent().siblings('.dropdown__list').children('li').each(function () {
            if ($(this).attr('data-slug') == roleType) {
                roleTypeName = $(this).html();
            }
        });
        $('#role-type').attr('value', roleTypeName);
    }

    if (salaryRange.length) {
        $('#salary-range').parent().siblings('.dropdown__list').children('li').each(function () {
            if ($(this).attr('data-slug') == salaryRange) {
                salaryRangeName = $(this).html();
            }
        });
        $('#salary-range').attr('value', salaryRangeName);
    }

    if (workingPattern.length) {
        $('#working-pattern').parent().siblings('.dropdown__list').children('li').each(function () {
            if ($(this).attr('data-slug') == workingPattern) {
                workingPatternName = $(this).html();
            }
        });
        $('#working-pattern').attr('value', workingPatternName);
    }

});
