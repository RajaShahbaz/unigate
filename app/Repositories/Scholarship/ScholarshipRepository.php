<?php

namespace App\Repositories\Scholarship;

use App\Models\Scholarship;
use App\Repositories\Base\BaseRepository;

class ScholarshipRepository extends BaseRepository implements ScholarshipInterface
{
    public function __construct(Scholarship $model)
    {
        parent::__construct($model, 'scholarships');
    }
}

