<?php 
    session_start(); 
    require_once("conn/dbh.php");

    require_once("forms_functions.php");

    require_once("section_contents.php"); 
?>
<body id="forms">
    <?php include_once("section_header.php"); ?>

    <main>
        <?php

            renderForms();

        ?>
    </main>

    <?php include_once("section_footer.php"); ?>
    <?php include_once("forms_js.php"); ?>
</body>
</html>



