<?php 
    session_start(); 
    require_once("conn/dbh.php");

    require_once("item_functions.php");

    require_once("section_contents.php"); 
?>
<body id="item">
    <?php include_once("section_header.php"); ?>
    

    <main>
        <?php
            if(isset($_SESSION['user-id'])) {
                $user_id = $_SESSION['user-id'];
            } else {
                $user_id = false;
            }
            $item = fetchItem($conn, $_GET['type'], $_GET['uid'], $user_id);
            renderItem($item, $user_id);
        ?>
    </main>

    <?php include_once("item_js.php"); ?>
    <?php include_once("section_footer.php"); ?>
</body>
</html>