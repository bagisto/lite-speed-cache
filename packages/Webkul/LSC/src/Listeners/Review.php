<?php

namespace Webkul\LSC\Listeners;

use Webkul\LSC\Support\DebuggableLSCache as LSCache;
use Webkul\Product\Repositories\ProductReviewRepository;

class Review
{
    /**
     * Create a new listener instance.
     *
     * @return void
     */
    public function __construct(protected ProductReviewRepository $productReviewRepository) {}

    /**
     * After review is updated
     *
     * @param  \Webkul\Product\Contracts\Review  $review
     * @return void
     */
    public function afterUpdate($review)
    {
        LSCache::purgeTags(['product_'.$review->product->id]);
    }

    /**
     * Before review is deleted
     *
     * @param  \Webkul\Product\Contracts\Review  $review
     * @return void
     */
    public function beforeDelete($reviewId)
    {
        $review = $this->productReviewRepository->find($reviewId);

        LSCache::purgeTags(['product_'.$review->product->id]);
    }
}
