<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

use App\Models\TimeStampFormat;

class Participation extends Pivot {
    use TimeStampFormat;
}