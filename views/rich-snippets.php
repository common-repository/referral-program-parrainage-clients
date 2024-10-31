<script type="application/ld+json">
    {
        "@context": "https://schema.org/",
        "@type": "AggregateRating",
        "itemReviewed": {
            "@type": "Store",
            "image": "<?php echo $data['shopImage'] ?>",
            "name": "<?php echo $data['shopName'] ?>",
            "address": {
                "@type": "PostalAddress",
                "streetAddress": "<?php echo $data['shopAddress'] ?>"
            }
        },
        "ratingValue": "<?php echo $data['avgScore'] ?>",
        "ratingCount": "<?php echo $data['reviewsTotal'] ?>"
    }
</script>