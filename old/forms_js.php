<script>

$(document).ready(function() {
    $('.button[name="form_choice"]').click(function() {
        if($('section#login').length == 1) {
            $('section#login').after('<section id="signup"><div class="sub_header"><h2>Sign up</h2></div><form action="forms_receive.php" method="post" id="signup_form"><input type="text" name="name" placeholder="Name/Nickname..."><input type="text" name="email" placeholder="Email.."><input type="text" name="uid" placeholder="Username.."><input type="password" name="pwd" placeholder="Password.."><input type="password" name="pwdrepeat" placeholder="Repeat Password..."><button type="submit" name="submit-signup" class="button">Sign Up</button></form></section>');
            $('section#login').remove();
            $(this).text('Log in');
            
        } else if($('section#signup').length == 1) {
            $('section#signup').after('<section id="login" hidden><div class="sub_header"><h2>Log in</h2></div><form action="forms_receive.php" method="post" id="login_form"><input type="text" name="uid" placeholder="Username/Email..."><input type="password" name="pwd" placeholder="Password..."><button type="submit" name="submit-login" class="button">Log In</button></form></section>');
            $('section#signup').remove();
            $(this).text('Sign up');
        }
    });
});

</script>