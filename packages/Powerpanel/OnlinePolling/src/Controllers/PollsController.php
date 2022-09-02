<?php

namespace Powerpanel\OnlinePolling\Controllers;

use App\Helpers\MyLibrary;
use App\Http\Controllers\FrontController;
use App\Http\Traits\slug;
use Illuminate\Support\Facades\Redirect;
use Powerpanel\OnlinePolling\Models\Poll;
use Powerpanel\OnlinePolling\Models\PollLead;
use Request;

class PollsController extends FrontController
{

    use slug;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * This method loads Careers list view
     * @return  View
     * @since   2018-08-27
     * @author  NetQuick
     */
    public function index($alias = false)
    {

        $breadcrumb = [];
        $data = array();

        $pollObj = Poll::getFrontList(12);
        $moduelFrontPageUrl = MyLibrary::getFront_Uri('online-polling')['uri'];
        $breadcrumb['title'] = (!empty($pollObj->varTitle)) ? ucwords($pollObj->varTitle) : '';
        $breadcrumb['url'] = MyLibrary::getFront_Uri('online-polling')['uri'];
        $detailPageTitle = $breadcrumb['title'];
        $data['detailPageTitle'] = 'Polls';
        $data['pollObj'] = $pollObj;
        $data['moduleTitle'] = 'Polls';
        $data['modulePageUrl'] = $moduelFrontPageUrl;
        $data['breadcumbcurrentPageTitle'] = $detailPageTitle;
        $data['alias'] = $alias;
        $data['breadcrumb'] = $breadcrumb;

        return view('polls::frontview.poll', ['data' => $data]);
    }

    public function store()
    {
        $data = Request::all();
        $polldata = Poll::getRecordById($data['poll_id']);
        if ($polldata) {
            $question = json_decode($polldata->txtQuestionData);
            $txtQuestionData = array();
            foreach ($question as $key => $que) {
                foreach ($data as $datakey => $value) {
                    if ($datakey == str_slug($que->question, '_')) {
                        $txtQuestionData[$key]['question'] = $que->question;
                        $txtQuestionData[$key]['answere'] = $value;
                    }
                }
            }

            $poll_lead = new PollLead;
            $poll_lead->varTitle = strip_tags($polldata->varTitle);
            $poll_lead->txtQuestionData = (!empty($txtQuestionData) ? json_encode($txtQuestionData) : null);

            $poll_lead->txtMessage = strip_tags($data['message']);

            $poll_lead->varIpAddress = MyLibrary::get_client_ip();

            $poll_lead->save();

            /* Start this code for message */
            if (!empty($poll_lead->id)) {
                $recordID = $poll_lead->id;
//               Email_sender::contactUs($data, $complaint_lead->id);
                return redirect()->route('thankyou')->with(['form_submit' => true, 'message' => 'Thank you for your feedback.']); 
            } else {
                return redirect('/');
            }
        } else {
            //return contact form with errors
            if (!empty($data['back_url'])) {
                return redirect($data['back_url'] . '#online_poll_form')->withErrors($polldata)->withInput();
            } else {
                return Redirect::route('online-polling')->withErrors($polldata)->withInput();
            }
        }
    }

}
