<?php

namespace App\Repositories\AppFaq;

use App\Models\AppFaq;
use App\Repositories\Base\BaseRepository;

class AppFaqRepository extends BaseRepository implements AppFaqInterface
{
    public function __construct(AppFaq $model)
    {
        parent::__construct($model);
    }

    /**
     * Get all active FAQs ordered
     */
    public function getActiveFaqs()
    {
        return $this->model->active()->ordered()->get();
    }

    /**
     * Update FAQ order
     */
    public function updateOrder($id, $order)
    {
        return $this->model->findOrFail($id)->update(['order' => $order]);
    }

    /**
     * Toggle FAQ status
     */
    public function toggleStatus($id)
    {
        $faq = $this->model->findOrFail($id);
        $faq->status = !$faq->status;
        $faq->save();
        return $faq;
    }
}



