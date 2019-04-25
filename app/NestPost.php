<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NestPost extends Model
{
    // Table Name
    protected $table = 'nest_post';
    // Primary Key
    public $primaryKey = 'id';
    // Timestamps
    public $timestamps = true;
}
