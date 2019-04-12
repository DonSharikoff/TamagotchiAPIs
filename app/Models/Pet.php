<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pet extends Model {

    protected $fillable = [
        'name', 'path_to_img', 'user_id'
    ];

}
