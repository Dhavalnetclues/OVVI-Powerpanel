<?php

namespace App;

use Cache;
use DB;
use Illuminate\Database\Eloquent\Model;

class Folder extends Model {

    protected $table = 'folder';
    protected $fillable = [
        'id',
        'type',
        'foldername',
        'date'
    ];
    
}
