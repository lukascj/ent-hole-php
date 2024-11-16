<h1>Browse</h1>
<section class="list popular">
    <h2>Popular</h2>
    <div class="list-outer">
        <div class="arrow-btn l"></div>
        <ul class="list-inner">
            <?php if(isset($popular['all']) && count($popular['all']) > 0): foreach($popular['all'] as $ent): ?>
                <li class="ent-wrap s" data-ent-name="<?= htmlspecialchars($ent['name']); ?>" data-ent-date="<?= htmlspecialchars($ent['date']); ?>">
                    <a href="<?= htmlspecialchars("/".$ent['type']."/".$ent['handle']); ?>">
                        <img class="poster" src="<?= htmlspecialchars("img/".$ent['handle']); ?>" />
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
<section class="list popular-horror">
    <h2>Popular in Horror</h2>
    <div class="list-outer">
        <div class="arrow-btn l"></div>
        <ul class="list-inner">
            <?php if(isset($popular['horror']) && count($popular['horror']) > 0): foreach($popular['horror'] as $ent): ?>
                <li class="ent-wrap s" data-ent-name="<?= htmlspecialchars($ent['name']); ?>" data-ent-date="<?= htmlspecialchars($ent['date']); ?>">
                    <a href="<?= htmlspecialchars("/".$ent['type']."/".$ent['handle']); ?>">
                        <img class="poster" src="<?= htmlspecialchars("img/".$ent['handle']); ?>" />
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
<section class="list top-rated">
    <h2>Highest Rated</h2>
    <div class="list-outer">
        <div class="arrow-btn l"></div>
        <ul class="list-inner">
            <?php if(isset($highestRated) && count($highestRated) > 0): foreach($highestRated as $ent): ?>
                <li class="ent-wrap s" data-ent-name="<?= htmlspecialchars($ent['name']); ?>" data-ent-date="<?= htmlspecialchars($ent['date']); ?>">
                    <a href="<?= htmlspecialchars("/".$ent['type']."/".$ent['handle']); ?>">
                        <img class="poster" src="<?= htmlspecialchars("img/".$ent['handle']); ?>" />
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
<section class="list random">
    <h2>Random</h2>
    <div class="list-outer">
        <div class="arrow-btn l"></div>
        <ul class="list-inner">
            <?php if(isset($random) && count($random) > 0): foreach($random as $ent): ?>
                <li class="ent-wrap s" data-ent-name="<?= htmlspecialchars($ent['name']); ?>" data-ent-date="<?= htmlspecialchars($ent['date']); ?>">
                    <a href="<?= htmlspecialchars("/".$ent['type']."/".$ent['handle']); ?>">
                        <img class="poster" src="<?= htmlspecialchars("img/".$ent['handle']); ?>" />
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