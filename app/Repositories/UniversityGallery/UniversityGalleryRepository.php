<?php

namespace App\Repositories\UniversityGallery;

use App\Models\UniversityGallery;
use App\Repositories\Base\BaseRepository;

class UniversityGalleryRepository extends BaseRepository implements UniversityGalleryInterface
{
    public function __construct(UniversityGallery $model)
    {
        parent::__construct($model, 'university_galleries');
    }
}

