<?php

namespace App\Repositories\AppFaq;

use App\Repositories\Base\BaseInterface;

interface AppFaqInterface extends BaseInterface
{
    /**
     * Get all active FAQs ordered
     */
    public function getActiveFaqs();

    /**
     * Update FAQ order
     */
    public function updateOrder($id, $order);

    /**
     * Toggle FAQ status
     */
    public function toggleStatus($id);
}



