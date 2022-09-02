<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class EmailtoFriend extends Model {

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'email_to_friend';
    protected $fillable = [
        'id',
        'varEmailName',
        'varFrommEmail',
        'varFriendName',
        'varFriendEmail',
        'txtEmailMessage',
        'chrDelete',
        'varIpAddress',
        'chrIsPrimary',
        'created_at',
        'updated_at'
    ];

}
