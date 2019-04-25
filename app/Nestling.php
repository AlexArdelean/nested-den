<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Nestling extends Model
{
    // Table Name
    protected $table = 'nestlings';
    // Primary Key
    public $primaryKey = 'id';
    // Timestamps
    public $timestamps = true;

    public function nest()
    {
        return $this->belongsTo('App\Nest', 'id');
    }
}
