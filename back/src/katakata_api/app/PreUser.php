<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PreUser extends Model
{
    protected $fillable = [
        'name', 'email', 'url_token', 'flag'
    ];
}
