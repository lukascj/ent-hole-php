<?php 
    session_start(); 
    require_once("conn/dbh.php");

    require_once("entry_functions.php");

    require_once("section_contents.php"); 
?>
<body id="entry">
    <?php include_once("section_header.php"); ?>
    <main>
    <?php if(isset($_GET['id'])) {

        $entry = fetchEntry($conn, $_GET['id'], $_SESSION['user-id']);
        renderEntry($entry);

    } elseif($_GET['list'] === 'ratings') {

        $entry = fetchUserRatings($conn, $_GET['user_uid']);
        renderUserRatings($entry);

    } elseif($_GET['list'] === 'reviews') {

        $reviews = fetchUserReviews($conn, $_GET['user_uid']);
        renderUserReviews($reviews);

    } elseif($_GET['list'] === 'logs') {

        $logs = fetchDiary($conn, $_GET['user_uid']);
        renderDiary($entry);

    } elseif($_GET['list'] === 'item-reviews') {

        $item_uid = $_GET['uid'];

        $reviews = fetchItemReviews($conn, $_SESSION['user-id'], $item_uid);
        renderItemReviews($reviews);

    } else { header("location: /?error"); } ?>
    </main>
    <?php include_once("section_footer.php"); ?>
    <?php include_once("entry_js.php"); ?>
</body>
</html>