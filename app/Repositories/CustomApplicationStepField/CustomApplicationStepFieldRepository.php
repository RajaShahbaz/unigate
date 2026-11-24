<?php

namespace App\Repositories\CustomApplicationStepField;

use App\Models\CustomApplicationStepField;
use App\Repositories\Base\BaseRepository;

class CustomApplicationStepFieldRepository extends BaseRepository implements CustomApplicationStepFieldInterface
{
    public function __construct(CustomApplicationStepField $model)
    {
        parent::__construct($model, 'custom_application_step_fields');
    }
}

