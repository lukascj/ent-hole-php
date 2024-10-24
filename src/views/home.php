<section class="list activity">
    <h2>Recent Activity</h2>
    <div class="list-outer">
        <div class="arrow-btn l"></div>
        <ul class="list-inner">
            <?php if(count($activity) > 0): foreach($activity as $a): extract($a) ?>
                <li class="activity-wrap" data-ent-name="<?= htmlspecialchars($ent_name); ?>" data-ent-date="<?= htmlspecialchars($ent_date); ?>">
                    <div class="main">
                        <div class="user-wrap">
                            <a href="<?= $user_url; ?>"><?= $user; ?></a>
                        </div>
                        <a class="activity" href="<?= $entry_url; ?>">
                            <img class="poster" src="<?= $img_path; ?>" />
                            <div class="rating">
                                <?= $stars ?>
                                <?= $review ?>
                            </div>
                        </a>
                    </div>
                    <div class="outer">
                        <p class="date"><?= date('Y-m-d', strtotime($date)); ?></p>
                        <?php if($rewatch): ?><div class="icon rewatch"></div><?php endif ?>
                        <?php if($review): ?>
                            <div class="icon review"></div>
                            <?php if($spoilers): ?><div class="icon spoilers"></div><?php endif ?>
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