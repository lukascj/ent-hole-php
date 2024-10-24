<?php 
    session_start(); // tillåter sessions
    require_once("conn.php"); // connect:ar till servern

    // hämtar funktioner för sidan
    require_once("index_functions.php");
    
    require_once("section_contents.php"); // html-head, kopplar css och js

    require_once("a_func.php");
?>

<body id="index">
    <?php include_once("section_header.php"); // html header ?> 

    <main>

        <?php 
            // om du är inloggad
            if(isset($_SESSION['user-id'])) {
                $recent = fetchRecent($conn, $_SESSION['user-id']);
                // om funktionen hittar aktivitet från de du följer
                renderListRecent($recent);
            } 

            if(isset($_GET['popular'])) {
                $popular = fetchPopular($conn, $_GET['popular']);
                renderListPopular($popular, $_GET['popular']);
            } else {
                $popular = fetchPopular($conn, 'week');
                renderListPopular($popular, 'week');
            }
        ?> 

    </main>

    <?php include_once("section_footer.php"); ?>
    <?php include_once("index_js.php"); ?>
</body>
</html>