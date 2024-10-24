<?php ?>

<script>

$(document).ready(function() {

    $('.like_button').click(function() {
        if($(this).hasClass('on')) {
            var action = "unlike";
        } else if($(this).hasClass('off')) {
            var action = "like";
        }

        var user_id = $(this).data("user-id");
        var item_id = $(this).data("item-id");

        $.ajax({

            context: this,
            url:'/profile_receive.php',
            method:"POST",
            data:{user_id:user_id, item_id:item_id, action:action},
            
            success: function() {

                if(action === "unlike") {
                    $(this).addClass('off');
                    $(this).removeClass('on');
                } else if(action === "like") {
                    $(this).addClass('on');
                    $(this).removeClass('off');
                }
            }
        });
    });

//     $('ul.star_container.open').hover(function() {
//         star_container = $(this);
//         $('.half_star').mouseover(function() {
//             if(!(star_container.hasClass('off'))) {
//                 let lim = $(this).data("nbr");
//                 for(let i = 0.5; i <= lim; i+=0.5) {
//                     $('.half_star[data-nbr="'+i+'"').addClass('hover');
//                 }
//                 for(let i = lim+0.5; i <= 5; i+=0.5) {
//                     $('.half_star[data-nbr="'+i+'"').removeClass('hover');
//                 }
//             };
//         });
//     }, function() {
//         $('.half_star').removeClass('hover');
//     });

//     $('.half_star').click(function() {
//         if(!$(this).parent('.star_container.open').hasClass('off')) {
//             let score = $(this).data("nbr");
//             $('input[name="rating"]').attr('value', score);
//             for(let i = 0; i <= score; i+=0.5) {
//                 $('.half_star[data-nbr="'+i+'"').addClass('on').removeClass('off');
//             }
//             for(let i = score+0.5; i <= 5; i+=0.5) {
//                 $('.half_star[data-nbr="'+i+'"').addClass('off').removeClass('on');
//             }
//         };
//     });

});

</script>

