<section class="list activity">
    <h2>Recent Activity</h2>
    <div class="list-outer">
        <div class="arrow-btn l"></div>
        <ul class="list-inner">
            <?php if(isset($activity) && count($activity) > 0): foreach($activity as $a): ?>
                <li class="activity-wrap" data-ent-name="<?= htmlspecialchars($a['ent_name']); ?>" data-ent-date="<?= htmlspecialchars($a['ent_date']); ?>">
                    <div class="main">
                        <div class="user-wrap">
                            <a href="<?= htmlspecialchars("/users\/".$a['user_handle']); ?>"><?= htmlspecialchars($a['user_name']); ?></a>
                        </div>
                        <a class="activity" href="<?= htmlspecialchars("/users\/".$a['user_handle']."/entry-".$a['entry_id']) ?>">
                            <img class="poster" src="<?= htmlspecialchars("/img/posters/".$a['ent']) ?>" />
                            <div class="rating">
                                <!-- $a['rating'] -->
                                <!-- boolval($a['like']) -->
                            </div>
                        </a>
                    </div>
                    <div class="outer">
                        <p class="date"><?= date('Y-m-d', strtotime($a['entry_created_date'])); ?></p>
                        <?php if(boolval($a['rewatch'])): ?><div class="icon rewatch"></div><?php endif ?>
                        <?php if(isset($a['review_text'])): ?>
                            <div class="icon review"></div>
                            <?php if(boolval($a['spoilers'])): ?><div class="icon spoilers"></div><?php endif ?>
                        <?php endif ?>
                    </div>
                </li>
            <?php endforeach; ?>
                <li class="activity-wrap show-more">
                    <a href="/recent-activity">
                        <p class="text">Show more</p>
                        <div class="plus"></div>
                    </a>
                </li>
            <?php else: ?>
                <li class="activity-wrap find-more">
                    <a href="/users">
                        <p class="text">Find people to follow</p>
                        <div class="plus"></div>
                    </a>
                </li>
            <?php endif;?>
        </ul>
        <div class="arrow-btn r"></div>
    </div>
</section>
<section class="list popular">
    <h2>Popular</h2>
    <div class="list-outer">
        <div class="arrow-btn l"></div>
        <ul class="list-inner">
            <?php if(isset($popular) && count($popular) > 0): foreach($popular as $ent): ?>
                <li class="ent-wrap s" data-ent-name="<?= htmlspecialchars($ent['name']); ?>" data-ent-date="<?= htmlspecialchars($ent['date']); ?>">
                    <a href="<?= htmlspecialchars("/".$ent['type']."/".$ent['handle']); ?>">
                        <img class="poster" src="<?= htmlspecialchars("img/posters/".$ent['handle']); ?>" />
                    </a>
                </li>
            <?php endforeach; ?>
                <li class="ent-wrap show-more">
                    <a href="/popular">
                        <p class="text">Show more</p>
                        <div class="plus"></div>
                    </a>
                </li>
            <?php else: ?>
                <li class="ent-wrap find-more">
                    <a href="/browse">
                        <p class="text">Nothing is popular</p>
                        <div class="plus"></div>
                    </a>
                </li>
            <?php endif;?>
        </ul>
        <div class="arrow-btn r"></div>
    </div>
</section>