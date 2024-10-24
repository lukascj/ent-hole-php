<?php

function renderHeader($user_uid, $item_id, $search_users) {

    if($item_id !== false) {
        $insert_id = '<input type="hidden" name="itemid" value="'.$item_id.'">';
    } else {
        $insert_id = '';
    }
    if($user_uid !== false) {

        $create = 
        '<form action="/create" method="get" class="create_form">
        '.$insert_id.'
        <select name="type">
        <option value="review">Review</option>
        <option value="log">Diary Entry</option>
        </select>
        <button class="button" type="submit">Create</button>
        </form>';

        $profile = '<a href="/user-'.$user_uid.'" class="button">Profile</a>';
        $acc = '<a href="/section_header_receive.php?logout" class="button">Log Out</a>';
    } else {
        $create = '';
        $profile = '';
        $acc = '<a href="/forms" class="button">Log in</a>';
    }
    if($search_users) {
        $form =
        '<form action="/browse_recieve.php" method="get" class="search_form">
        <input type="hidden" name="users">
        <input class="search_bar" type="text" name="search" placeholder="Search">
        <button class="button" type="submit">Search</button>
        </form>';
    } else {
        $form =
        '<form action="/browse_recieve.php" method="get" class="search_form">
        <input class="search_bar" type="text" name="search" placeholder="Search">
        <button class="button" type="submit">Search</button>
        </form>';
    }

    $html = 
    '<header>
    <a href="/" class="button">Home</a>
    <a href="/browse" class="button">Browse</a>
    '.$profile
    .$form
    .$create
    .$acc.'
    </div>
    </nav>
    </header>';

    echo $html;
    return;
}