<script>

$(document).ready(function() {

    $('.button.follow, .button.unfollow').click(function() {

        console.log('hey');

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