<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Helpers\MyLibrary;

class DashboardOrderTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {

        $users = [
            [
                'intDisplayOrder' => '[1,3,4,5,8,9,10,11,12]',
                'UserID' => '1',
                'txtWidgetSetting' => '{"widget_webhits":{"widget_name":"Website Hits","widget_id":"widget_webhits","widget_display":"Y"},"widget_leadstatistics":{"widget_name":"Leads Statistics","widget_id":"widget_leadstatistics","widget_display":"Y"},"widget_download":{"widget_name":"Document Views & Downloads","widget_id":"widget_download","widget_display":"Y"},"widget_feedbackleads":{"widget_name":"Feedback Leads","widget_id":"widget_feedbackleads","widget_display":"Y"},"widget_conatctleads":{"widget_name":"Contact Leads","widget_id":"widget_conatctleads","widget_display":"Y"},"widget_inapporval":{"widget_name":"In Approval","widget_id":"widget_inapporval","widget_display":"Y"},"widget_avl_workflow":{"widget_name":"Available Workflow","widget_id":"widget_avl_workflow","widget_display":"Y"},"widget_pending_workflow":{"widget_name":"Pending Workflow","widget_id":"widget_pending_workflow","widget_display":"Y"},"widget_recentactivity":{"widget_name":"Recent Activity","widget_id":"widget_recentactivity","widget_display":"Y"}}',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'intDisplayOrder' => '[1,3,4,5,8,9,10,11,12]',
                'UserID' => '2',
                'txtWidgetSetting' => '{"widget_webhits":{"widget_name":"Website Hits","widget_id":"widget_webhits","widget_display":"Y"},"widget_leadstatistics":{"widget_name":"Leads Statistics","widget_id":"widget_leadstatistics","widget_display":"Y"},"widget_download":{"widget_name":"Document Views & Downloads","widget_id":"widget_download","widget_display":"Y"},"widget_feedbackleads":{"widget_name":"Feedback Leads","widget_id":"widget_feedbackleads","widget_display":"Y"},"widget_conatctleads":{"widget_name":"Contact Leads","widget_id":"widget_conatctleads","widget_display":"Y"},"widget_inapporval":{"widget_name":"In Approval","widget_id":"widget_inapporval","widget_display":"Y"},"widget_avl_workflow":{"widget_name":"Available Workflow","widget_id":"widget_avl_workflow","widget_display":"Y"},"widget_pending_workflow":{"widget_name":"Pending Workflow","widget_id":"widget_pending_workflow","widget_display":"Y"},"widget_recentactivity":{"widget_name":"Recent Activity","widget_id":"widget_recentactivity","widget_display":"Y"}}',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
               'intDisplayOrder' => '[1,4,7,12]',
                'UserID' => '3',
                'txtWidgetSetting' => '{"widget_webhits":{"widget_name":"Website Hits","widget_id":"widget_webhits","widget_display":"Y"},"widget_download":{"widget_name":"Document Views & Downloads","widget_id":"widget_download","widget_display":"Y"},"widget_commentuser":{"widget_name":"Comments For user","widget_id":"widget_commentuser","widget_display":"Y"},"widget_recentactivity":{"widget_name":"Recent Activity","widget_id":"widget_recentactivity","widget_display":"Y"}}',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ];

        foreach ($users as $key => $value) {
            DB::table('dashboardorder')->insert($value);
        }
    }

}
