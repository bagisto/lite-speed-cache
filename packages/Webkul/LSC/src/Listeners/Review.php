<?php

namespace Webkul\LSC\Listeners;

use Illuminate\Support\Facades\Log;
use Litespeed\LSCache\LSCache;
use Webkul\LSC\Traits\DeletesAllCache;
use Webkul\Product\Repositories\ProductReviewRepository;

class Review
{
    use DeletesAllCache;

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
        $this->deletePrivCache();
    }

    /**
     * Before review is deleted
     *
     * @param  \Webkul\Product\Contracts\Review  $review
     * @return void
     */
    public function beforeDelete($reviewId)
    {
        $this->productReviewRepository->find($reviewId);

        $this->deletePrivCache();
    }
}
