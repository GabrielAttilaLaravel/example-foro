<?php

namespace App;

use GrahamCampbell\Markdown\Facades\Markdown;
use Illuminate\Database\Eloquent\Model as StandarModel;

class Model extends StandarModel
{
    public function convertToHtml($value)
    {
        return Markdown::convertToHtml(e($value));
    }
}
