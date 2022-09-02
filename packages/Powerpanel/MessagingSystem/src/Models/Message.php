<?php

namespace Powerpanel\MessagingSystem\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon\Carbon;

class Message extends Model {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'messages';
    protected $fillable = [
        'fk_record_id', 'txt_message'
    ];

    static function messages() {
        $response = false;
        $query = '';
        $query = DB::table('messages')->select('messages.*', 'images.txt_image_url', 'images.txt_thumb_url', 'images.var_image_slug', 'images.txt_image_alt_tag', 'images.var_image_extension')
                        ->leftJoin('users', 'messages.fk_record_id', '=', 'users.id')
                        ->leftJoin('images', 'users.fk_image_id', '=', 'images.id')
                        ->where('messages.chr_publish', '=', 'Y')
                        ->where('messages.chr_delete', '=', 'N')
                        ->orderBy('messages.updated_at', 'DESC')
                        ->groupBy('messages.id')
                        ->take(10)->get();
        $response = $query;
        return $response;
    }

    static function update_read_status($id = false) {
        $response = false;
        $query = '';
        if (!empty($id)) {
            $query = DB::table('messages')
                    ->where('id', $id)
                    ->update(['chr_read' => 'Y']);
        }
        $response = $query;
        return $response;
    }

    static function messageCount() {
        $response = false;
        $query = '';
        $query = DB::table('messages')
                ->where('chr_publish', '=', 'Y')
                ->where('chr_delete', '=', 'N')
                ->where('chr_read', '=', 'N')
                ->orderBy('updated_at', 'DESC')
                ->count();
        $response = $query;
        return $response;
    }

}
