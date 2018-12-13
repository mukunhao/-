<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Log extends Model
{
    protected $table = 'dashuju_log';

    public function getall()
    {
        return $this->get();
    }

}
