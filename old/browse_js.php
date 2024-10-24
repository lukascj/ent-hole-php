<script>

$(document).ready(function() {

    $('.list_section h2').text($('input[name="browse-title"]').val());

    $('select[name="sort-by"]').change(function() {
        var option_popularity = $('select[name="sort-by"] option[value="popularity"]');
        var option_rating = $('select[name="sort-by"] option[value="rating"]');
        var option_name = $('select[name="sort-by"] option[value="name"]');

        $('select[name="sort-by-rating"], select[name="sort-by-popularity"]').parents('.filter_option').remove();

        if(option_popularity.is(':selected')) {
            option_popularity.parents('.filter_option').after('<div class="filter_option extra"><select name="sort-by-popularity"><option value="week">This week</option><option value="month">This month</option><option value="all-time">All time</option></select></div>');
        } else if(option_rating.is(':selected')) {
            option_rating.parents('.filter_option').after('<div class="filter_option extra"><select name="sort-by-rating"><option value="highest-first">Highest first</option><option value="lowest-first">Lowest first</option></select></div>');
        } 
    });

    $('.list_section.users .user_container').hover(function() {
        $(this).find('p.name').addClass('hover');
    }, function() {
        $(this).find('p.name').removeClass('hover');
    });

    $('section#filter .arrow').click(function() {
        if($(this).hasClass('u')) {

            $(this).removeClass('u');
            $(this).addClass('d');
            $(this).css('right', '48.8%');

            filter = $(this).parents('main');
            height = 'calc(-' + filter.find('.filter_segment').first().height() + 'px - var(--global-gap) * 3.5)';
            filter.css('top', height);

            button = filter.find('button[type="submit"]');
            button.css('filter', 'opacity(0)');

        } else if($(this).hasClass('d')) {

            $(this).removeClass('d');
            $(this).addClass('u');
            $(this).css('right', 'var(--global-border-width-1)');

            filter = $(this).parents('main');
            filter.css('top', '0');

            button = filter.find('button[type="submit"]');
            button.css('filter', 'opacity(1)');
        }
    });

    $('.button.follow, .button.unfollow').click(function() {

        if($(this).hasClass('follow')) {
            var action = "follow";
        } else if($(this).hasClass('unfollow')) {
            var action = "unfollow";
        }

        var to_id = $(this).data("to-id");
        var from_id = $(this).data("from-id");

        $.ajax({

            context: this,
            url:'/profile_receive.php',
            method:"POST",
            data:{to_id:to_id, from_id:from_id, action:action},
            
            success: function() {

                if(action === "follow") {
                    $(this).addClass('unfollow');
                    $(this).removeClass('follow');
                    $(this).text('Unfollow');
                } else if(action === "unfollow") {
                    $(this).addClass('follow');
                    $(this).removeClass('unfollow');
                    $(this).text('Follow');
                }
            }
        });
    });

});

</script>