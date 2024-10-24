<?php 
    session_start(); 
    require_once("conn/dbh.php");

    require_once("create_functions.php");

    require_once("section_contents.php"); 
?>
<body id="create">
    <?php include_once("section_header.php"); ?>

    <main>

        <?php

        if(!isset($_GET['type'])) {

            header("location: /?error");

        } elseif(isset($_GET['item'])) { 
            
            $item = fetchItem($conn, $_GET['item']);
            renderCreatePrompt($_GET['type'], $item, $_SESSION['user-id']);
        
        } else {

            echo 
            '<section id="create_search">
            <input type="text" name="create-search" placeholder="Quick Search">
            <div class="results"></div>
            </section>';

        } 
        
        ?>

    </main>

    <?php include_once("section_footer.php"); ?>
    <?php include_once("create_js.php"); ?>
</body>
</html>