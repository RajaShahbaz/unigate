<?php

namespace App\Repositories\CustomApplicationStep;

use App\Models\CustomApplicationStep;
use App\Repositories\Base\BaseRepository;

class CustomApplicationStepRepository extends BaseRepository implements CustomApplicationStepInterface
{
    public function __construct(CustomApplicationStep $model)
    {
        parent::__construct($model, 'custom_application_steps');
    }
}

