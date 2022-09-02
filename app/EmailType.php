<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class EmailType extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table    = 'email_type';
    protected $fillable = [
        'id',
        'varEmailType',
        'chrDelete',
        'chrPublish',
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * This method get records
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */
    public static function getRecords()
    {
        return Self::Select(['id', 'varEmailType', 'chrPublish']);
    }
    
    public static function getRecordBySearch($search = false) {
        $response = false;
        $moduleFields = [
            'id',
        ];
        $pageFields = ['id'];
        $response = Self::select("id")->where('varEmailType', 'like', "%" . $search . "%")->pluck('id');
        return $response;
    }

    /**
     * This method handels record id scope
     * @return  Object
     * @since   2016-07-24
     * @author  NetQuick
     */
    public function scopeCheckRecordId($query, $id)
    {
        return $query->where('id', $id);
    }

    /**
     * This method handels record id scope
     * @return  Object
     * @since   2016-07-24
     * @author  NetQuick
     */
    public function scopeCheckEmailType($query, $type)
    {
        return $query->where('varEmailType', $type);
    }

    /**
     * This method handels publish scope
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */
    public function scopePublish($query)
    {
        return $query->where(['chrPublish' => 'Y']);
    }
    /**
     * This method handels delete scope
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */
    public function scopeDeleted($query)
    {
        return $query->where(['chrDelete' => 'N']);
    }

    public function emailLog()
    {
        return $this->hasOne('App\EmailLog', 'id', 'intFkEmailType');
    }

    /**
     * This method handels retrival of backend record list
     * @return  Object
     * @since   2017-10-24
     * @author  NetQuick
     */
    public static function getEmailTypes()
    {
        $response     = false;
        $moduleFields = ['id', 'varEmailType'];
        $response     = Self::getPowerPanelRecords($moduleFields)
            ->deleted()
            ->get();
        return $response;
    }

    /**
     * This method handels backend records
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */
    public static function getPowerPanelRecords($moduleFields = false)
    {
        $data     = [];
        $response = false;
        $response = self::select($moduleFields);

        if (count($data) > 0) {
            $response = $response->with($data);
        }
        return $response;
    }
}
