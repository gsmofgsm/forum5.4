<?php

namespace App;

use Illuminate\Support\Facades\Redis;

trait RecordVisits
{

    public function visits(): Visits
    {
        return new Visits($this);
    }
}