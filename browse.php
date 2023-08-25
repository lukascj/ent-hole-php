<?php 
    session_start(); 
    require_once("conn/dbh.php");

    require_once("browse_functions.php");

    require_once("section_contents.php"); 
?>
<body id="browse">
    <?php include_once("section_header.php"); ?>

    <?php if(isset($_GET['users']) && !isset($_GET['search'])): ?>
        <input type="hidden" name="browse-title" value="Users" />
    <?php elseif(isset($_GET['users']) && isset($_GET['search'])): ?>
        <input type="hidden" name="browse-title" value="You searched for user: <?php echo $_GET['search']; ?>" />
    <?php elseif(isset($_GET['search'])): ?>
        <input type="hidden" name="browse-title" value="You searched for: <?php echo $_GET['search']; ?>" />
    <?php elseif(isset($_GET['recent'])): ?>
        <input type="hidden" name="browse-title" value="Recent activity from people you follow" />
    <?php elseif(isset($_GET['popular'])): ?>
        <input type="hidden" name="browse-title" value="Popular" />
    <?php else: ?>
        <input type="hidden" name="browse-title" value="Browse" />
    <?php endif; ?>

    <main>

        <?php 
            if(isset($_GET['users'])) {
                $listitems = fetchListBrowse($conn, $_GET, 'users');
                renderListBrowse($_SESSION['user-id'], $listitems, 'users');
            } else {
                if(!isset($_GET['recent'])) {
                    if(!isset($_GET['popular'])) {
                        if(isset($_GET['search'])) {
                            renderBrowseFilter($conn, $_GET['search']);
                        } else {
                            renderBrowseFilter($conn, false);
                        }
                    } 
                    $listitems = fetchListBrowse($conn, $_GET, 'items');
                    renderListBrowse($_SESSION['user-id'], $listitems, 'items');
                } else {
                    $listitems = fetchListBrowseRecent($conn, $_SESSION['user-id']);
                    renderListBrowse($_SESSION['user-id'], $listitems, 'activity');
                }
            }
        ?>

    </main>

    <?php include_once("section_footer.php"); ?>
    <?php include_once("browse_js.php"); ?>
</body>
</html>