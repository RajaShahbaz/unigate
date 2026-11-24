<?php

namespace App\Repositories\AdmissionRequirement;

use App\Models\AdmissionRequirement;
use App\Repositories\Base\BaseRepository;

class AdmissionRequirementRepository extends BaseRepository implements AdmissionRequirementInterface
{
    public function __construct(AdmissionRequirement $model)
    {
        parent::__construct($model, 'admission_requirements');
    }
}

