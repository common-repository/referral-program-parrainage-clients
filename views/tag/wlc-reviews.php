<?php
if (isset($data)) {
?>
    <link rel="stylesheet" href="<?php echo WLC_PLUGIN_URL; ?>css/reviews-styles.css" />
    <div class="reviews">
        <div class="reviews-header">
            <h2>Note de la boutique</h2>
            <div class="avg-score"><img src="<?php echo "https://dj8z0bra0q3sp.cloudfront.net/widgets/img/rating/" . $param['star-color'] . "/stars-" . $data['avgScore'] . ".png" ?>"><?php echo " " . $data['avgScore'] . "/10" ?></div>
        </div>
        <div class=" reviews-body">
            <?php
            foreach ($data['reviews'] as $review) {
            ?>
                <div class="review">
                    <div class="review-content">
                        <div class="score"><img src="<?php echo "https://dj8z0bra0q3sp.cloudfront.net/widgets/img/rating/" . $param['star-color'] . "/stars-" . $review->score . ".png" ?>"><?php echo " " . $review->score . "/10" ?></div>

                        <div class=" comment"><?php echo $review->response_label ?></div>

                        <div class="customer-name"><strong><?php echo $review->nickname ?></strong></div>

                        <div class="rating-date"><?php echo date("d-m-Y", strtotime($review->date)) ?></div>
                    </div>
                </div>
            <?php
            }
            ?>
        </div>
        <div class="reviews-footer"><a class="pink" id="more-reviews-link" href="<?php echo $data['url'] ?>">Afficher plus d'avis</a></div>
    </div>
    <script>
        let moreReviews = document.getElementById('more-reviews-link');
        <?php
        if ($param['star-color'] == 'blue') {
        ?>
            moreReviews.classList.remove('pink');
            moreReviews.classList.add('blue');
        <?php
        }
        ?>
    </script>
<?php
}
