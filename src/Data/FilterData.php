<?php

namespace App\Data;

use App\Entity\Categorie;
use App\Entity\Variation;
use App\Entity\Reduction;

class FilterData
{


    /**
     * @var string
     */
    public $q;
    /**
     * @var Categorie[]
     */
    public $categories = [];

    /**
     * @var null|double
     */
    public $max;
    /**
     * @var null|double
     */
    public $min;

    /**
     * @var Variation[]
     */
    public $variations = [];

    /**
     * @var Reduction[]
     */
    public $reductions = [];
}