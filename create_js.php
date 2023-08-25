<?php require_once("create_functions.php"); ?>

<script>

$(document).ready(function() {

    $('.like_button').click(function() {
        if($(this).hasClass('off')) {
            $(this).removeClass('off');
            $(this).addClass('on');
            $('input[name="like"]').attr('value', '1');
            // todo: ngn animation?
        } else if($(this).hasClass('on')) {
            $(this).removeClass('on');
            $(this).addClass('off');
            $('input[name="like"]').attr('value', '0');
        }
    });

    $('button[name="toggle-rating"]').click(function() {
        if($(this).hasClass('add')) {
            $('input[name="rating"]').attr('value', 0);
            $('.star_container').first().removeClass('off');
            $('.star_container').first().addClass('on');
            $(this).removeClass('add');
            $(this).addClass('remove');
            $(this).text('Remove rating');
        } else if($(this).hasClass('remove')) {
            $('input[name="rating"]').attr('value', 'null');
            $('.star_container').first().removeClass('on');
            $('.star_container').first().addClass('off');
            $(this).removeClass('remove');
            $(this).addClass('add');
            $(this).text('Add rating');
            for(let i = 0; i <= 5; i+=0.5) {
                $('.half_star[data-nbr="'+i+'"').addClass('off').removeClass('on');
            }
        }
    });

    $('ul.star_container.open').hover(function() {
        star_container = $(this);
        $('.half_star').mouseover(function() {
            if(!(star_container.hasClass('off'))) {
                let lim = $(this).data("nbr");
                for(let i = 0.5; i <= lim; i+=0.5) {
                    $('.half_star[data-nbr="'+i+'"').addClass('hover');
                }
                for(let i = lim+0.5; i <= 5; i+=0.5) {
                    $('.half_star[data-nbr="'+i+'"').removeClass('hover');
                }
            };
        });
    }, function() {
        $('.half_star').removeClass('hover');
    });

    $('.half_star').click(function() {
        if(!$(this).parent('.star_container.open').hasClass('off')) {
            let score = $(this).data("nbr");
            $('input[name="rating"]').attr('value', score);
            for(let i = 0; i <= score; i+=0.5) {
                $('.half_star[data-nbr="'+i+'"').addClass('on').removeClass('off');
            }
            for(let i = score+0.5; i <= 5; i+=0.5) {
                $('.half_star[data-nbr="'+i+'"').addClass('off').removeClass('on');
            }
        };
    });

    $('section#create_search input').focus(function() {
        $(this).keyup(function() {
            
            let str = $(this).val()
            
            if(str.length > 0) {

                $.ajax({

                    context: this,
                    url:'create_receive.php',
                    method:"POST",
                    data:{csearch:"on", input:str},

                    success: function(data) {

                        items = JSON.parse(data);
                        if(items.length > 0) {
                            let type = '<?php echo $_GET['type']; ?>';
                            let result = '';

                            items.forEach(function(item) {
                                result += '<form action="/create" method="get">';
                                result += '<input type="hidden" name="item" value="'.concat(item['uid'].toString(), '">');
                                result += '<input type="hidden" name="type" value="'.concat(type, '">');
                                result += '<button type="submit" class="button">';
                                result += item['name'].concat(' (', item['year'].toString(), ')');
                                result += '</button></form>';
                            });

                            $(".results").empty();
                            $(".results").append(result);
                        } else {
                            $(".results").empty();
                        }
                    }
                });
            } else {
                $(".results").empty();
            }
        });
    });

    $('button[name="toggle-review"]').click(function() {
        if($(this).hasClass('add')) {
            $('.log_exclusive').after(<?php echo json_encode(preparePromptExclusive('review', '')); ?>);
            $(this).removeClass('add');
            $(this).addClass('remove');
            $(this).text('Remove Review');
        } else if($(this).hasClass('remove')) {
            $('.review_exclusive').remove();
            $(this).removeClass('remove');
            $(this).addClass('add');
            $(this).text('Attach Review');
        }
    });

    $('button[name="toggle-log"]').click(function() {
        if($(this).hasClass('add')) {
            $('.review_exclusive').before(<?php echo json_encode(preparePromptExclusive('log', '')); ?>);
            $(this).removeClass('add');
            $(this).addClass('remove');
            $(this).text('Remove Diary Entry');
        } else if($(this).hasClass('remove')) {
            $('.log_exclusive').remove();
            $(this).removeClass('remove');
            $(this).addClass('add');
            $(this).text('Attach Diary Entry');
        }
    });
});

</script>