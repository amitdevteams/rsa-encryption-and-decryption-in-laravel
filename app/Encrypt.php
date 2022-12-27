<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Encrypt extends Model
{
    protected $fillable = ['name','public_key'];
}
