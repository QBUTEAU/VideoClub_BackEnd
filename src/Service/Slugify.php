<?php

namespace App\Service;

use Cocur\Slugify\Slugify as CocurSlugify;

class Slugify
{
    public function slugify($stringToSlugify)
    {
        $slugify = new CocurSlugify();
        return $slugify->slugify($stringToSlugify);
    }
}
