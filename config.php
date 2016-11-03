<?php
if (!function_exists('dd')) {
    function dd()
    {
        $args = func_get_args();
        echo "<script>";
        echo "console.log(".json_encode($args).")";
        echo "</script>";
    }
}
// Dribbble, and Twitter

$posts = require 'behance.php';
// date
// image
// title
// category
// url
foreach ($posts as $post): ?>
<div class="col-md-6 col-sm-6 selector col-xs-12">
	<div class="item item-color social-post post status-publish format-standard has-post-thumbnail hentry">
		<div class="inner-photo wow fadeInUp" data-wow-delay="0.3s">
			<a href="<?= $post['url'] ?>">
				<div class="item-inner">
					<div class="overlay">
						<div class="overlay-inner">
							<h3><?= $post['title'] ?></h3>
						</div>
					</div>
					<img src="<?= $post['image'] ?>" class="attachment-post-thumbnail size-post-thumbnail wp-post-image" alt="<?= $post['title'] ?>">
				</div>
			</a>
		</div>

		<div class="richard-captions-under-posts">
			<h3 class="title-outside-loop wow fadeInUp" data-wow-delay="0.3s">
				<a class="details-permalink" href="<?= $post['url'] ?>"><?= $post['title'] ?></a>
				<span class="content-inner">
					- <a href="<?= $post['url'] ?>"><?= $post['category'] ?></a>
				</span>
			</h3>
		</div>
		<div class="content"></div>
	</div>
</div>
<?php endforeach;
