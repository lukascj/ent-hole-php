<script>

$(document).ready(function() {
    $('.like_button').click(function() {

        console.log('hey');
        if($(this).hasClass('off')) {
            var action = 'like';
        } else if($(this).hasClass('on')) {
            var action = 'unlike';
        }

        var entry_id = $(this).data("entry-id");
        var user_id = <?php if(isset($_SESSION['user-id'])) { echo $_SESSION['user-id']; } else { echo "null"; } ?>;	

        $.ajax({

            context: this,
            url:'/entry_recieve.php',
            method:"POST",
            data:{user_id:user_id, entry_id:entry_id, action:action},

            success: function() {

                if(action === "like") {
                    $(this).removeClass('off');
                    $(this).addClass('on');
                } else if(action === "unlike") {
                    $(this).removeClass('on');
                    $(this).addClass('off');
                }
            }
        });
    });

    $('.button[name="delete"]').click(function() {

        var entry_id = $(this).data("entry-id");
        var user_id = <?php if(isset($_SESSION['user-id'])) { echo $_SESSION['user-id']; } else { echo "null"; } ?>;	
        var action = 'delete';

        $.ajax({
            url:'entry_recieve.php',
            method:"POST",
            data:{user_id:user_id, entry_id:entry_id, action:action}
        });
    });
});

</script>