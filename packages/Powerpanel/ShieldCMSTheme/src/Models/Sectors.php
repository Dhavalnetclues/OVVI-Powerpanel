<?php

namespace Powerpanel\ShieldCMSTheme\Models;

use Illuminate\Database\Eloquent\Model;

class Sectors extends Model
{

    protected $table = 'sectors';
    protected $fillable = [
        'id',
        'value',
        'name',
    ];

    public static function getSectorsListForRole()
    {
        $response = false;
        $moduleFields = [
            'value',
            'name',
        ];

        $query = Self::Select($moduleFields);

        $response = $query->get();
        return $response;
    }

    public static function getSectorsList($userRoleSector)
    {
        $response = false;
        $moduleFields = ['name', 'value'];
        $query = Self::Select($moduleFields);
        if ($userRoleSector != 'all') {
            $query->where('value', $userRoleSector);
        }
        $response = $query->get();
        return $response;
    }

}
