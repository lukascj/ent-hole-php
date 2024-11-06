<?php ob_start(); ?>
<form action="login" method="post" id="login-form">
    <input type="text" name="handle/email" placeholder="Handle or Email..">
    <input type="password" name="pwd" placeholder="Password..">
    <button type="submit" name="submit-login" class="button">Log in</button>
</form>
<?php $loginForm = ob_get_clean(); ob_start(); ?>
<form action="signup" method="post" id="signup-form">
    <input type="text" name="name" placeholder="Name...">
    <input type="text" name="email" placeholder="Email..">
    <input type="text" name="handle" placeholder="Handle..">
    <input type="password" name="pwd" placeholder="Password..">
    <input type="password" name="pwdre" placeholder="Repeat...">
    <button type="submit" name="submit-signup" class="button">Sign up</button>
</form>
<?php $signupForm = ob_get_clean(); ?>

<button type="button" name="form-choice" value="login" class="button form-switch">Switch to login</button>

<section id="form">
    <h2>Sign up</h2>
    <?= $signupForm ?>
    <?= $loginForm ?>
</section>

<script>
    $(document).ready(function() {
        const loginForm = $('#login-form');
        const signupForm = $('#signup-form');
        let currentForm = 'signup';
        const headerElem = $('section#form > h2');
        loginForm.css('display', 'none');
        $(document).on('click', '.button.form-switch', function() {
            if(currentForm == 'signup') {
                signupForm.css('display', 'none');
                loginForm.css('display', 'block');
                headerElem.text('Log in');
                currentForm = 'login';
                $(this).text('Switch to login');
            } else if(currentForm == 'login') {
                signupForm.css('display', 'block');
                loginForm.css('display', 'none');
                headerElem.text('Sign up');
                $(this).text('Switch to signup');
                currentForm = 'signup';
            }
        });
    });
</script>