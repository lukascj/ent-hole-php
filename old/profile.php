<?php 
    session_start(); 
    require_once("conn/dbh.php");

    require_once("profile_functions.php");
    
    require_once("section_contents.php"); 
?>
<body id="profile">
    <?php include_once("section_header.php"); ?>

    <main>
        <?php

            $user = fetchUser($conn, $_GET['uid'], $_SESSION['user-uid'], $_SESSION['user-id']);
            renderProfile($user, $_SESSION['user-id']);

        ?>
    </main>

    <?php include_once("section_footer.php"); ?>
    <?php include_once("profile_js.php"); ?>
</body>
</html>