<?php
/**
 * The Blogs class handels bannner queries
 * ORM implemetation.
 * @package   Netquick powerpanel
 * @license   http://www.opensource.org/licenses/BSD-3-Clause
 * @version   1.1
 * @since       2017-07-20
 * @author    NetQuick
 */
namespace Powerpanel\VisualComposer\Models;

use App\Modules;
use Illuminate\Database\Eloquent\Model;
use Cache;
use DB;

class VisualComposer extends Model {
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'visualcomposer';
    protected $fillable = [
        'id',
        'fkParentID',
        'varTitle',
        'varIcon',
        'varClass',
        'varTemplateName',
        'varModuleName',
        'created_at',
        'updated_at'
    ];
}
