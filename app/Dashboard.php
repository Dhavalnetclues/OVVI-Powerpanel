<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Dashboard extends Model {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    static function get_contact_leads() {
        return DB::table('contactus_lead')
                        ->select('contactus_lead.*')
                        ->where('chr_delete', '=', 'N')
                        ->orderBy('created_at', 'DESC')
                        ->take(5)
                        ->get();
    }

    static function get_faqs() {
        return DB::table('faq')
                        ->select('faq.*')
                        ->where('chr_delete', '=', 'N')
                        ->orderBy('order', 'ASC')
                        ->take(5)
                        ->get();
    }

    static function get_user_comments() {
        return DB::table('comments as C')
                        ->select('C.*', 'P.varTitle as Title')
                        ->leftJoin('cms_page as P', 'P.id', '=', 'C.fkMainRecord')
                        ->where('C.chrDelete', '=', 'N')
                        ->where('C.UserID', '=', auth()->user()->id)
                        ->where('C.Fk_ParentCommentId', '=', '0')
                        ->groupBy('C.intRecordID')
                        ->groupBy('C.fkMainRecord')
                        ->groupBy('C.varModuleNameSpace')
                        ->orderBy('C.created_at', 'desc')
                        ->get();
    }

    public static function get_comments_user($request) {
        $id = $request->id;
        $intRecordID = $request->intRecordID;
        $fkMainRecord = $request->fkMainRecord;
//        $namespace = $request->namespace;
        $varModuleNameSpace = $request->varModuleNameSpace;
        $Comments = DB::table('comments')->where('fkMainRecord', $fkMainRecord)->where('intRecordID', $intRecordID)->where('varModuleNameSpace', $varModuleNameSpace)->where('chrDelete', 'N')->orderBy('created_at', 'asc')->get();
        return $Comments;
    }

    public static function get_usercomments($id) {
        $Comments_user = DB::table('comments')->where('Fk_ParentCommentId', $id)->where('chrDelete', 'N')->orderBy('created_at', 'asc')->get();
        return $Comments_user;
    }

    public static function get_letest_comments($id) {
        $Comments_user = DB::table('comments')->select('id')->where('fkMainRecord', $id)->where('Fk_ParentCommentId', '0')->where('chrDelete', 'N')->orderBy('created_at', 'desc')->first();
        return $Comments_user->id;
    }

    public static function get_recent_activity() {
        $recent_activity = DB::table('log')
                ->select('*')
                ->where('chrDelete', '=', 'N')
                ->where('chrPublish', '=', 'Y')
                ->where('fkIntUserId', '=', auth()->user()->id)
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();
        return $recent_activity;
    }

}
