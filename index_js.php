<script>

$(document).ready(function() {

    $('.list_section .list').each(function() {
        if($(this).children('li').length <= 5) {
            var button_selector = $(this).parents('.list_section').find('.arrow');
            console.log(button_selector);
            button_selector.css('display', 'none');
        }
    });

    $('.list_section .arrow').click(function() {
        var list_selector = $(this).parents('.list_section').find('.list');
            
        var children_count = list_selector.children('li').length;
        // var children_width = list_selector.children('li').width;
        var lim_factor = parseInt(list_selector.parents('.list_limits').width()) + parseInt(list_selector.css('gap'));

        var lim = 0;
        for(let i = children_count; i > 5; i--) {
            if(i % 5 == 1) {lim -= lim_factor;}
        }

        if($(this).hasClass('r')) {
            if(parseInt(list_selector.css('left')) > lim && !list_selector.is(':animated')) {
                list_selector.animate({left: "-="+lim_factor+"px"});
            }
        } else if($(this).hasClass('l')) {
            if(parseInt(list_selector.css('left')) < 0 && !list_selector.is(':animated')) {
                list_selector.animate({left: "+="+lim_factor+"px"});
            }
        }
    });

    $('select[name=popular]').on('change', function() {
        $(this).parent().submit();
    });
});

</script>