<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Brand;
use App\Models\Answer;
use App\Models\Branch;
use App\Models\Report;
use App\Models\Contact;
use App\Models\Campaign;
use App\Models\Question;
use App\Models\ToyotaCase;
use App\Imports\LeadImport;
use App\Models\Disposition;
use Illuminate\Http\Request;
use App\Models\Classification;
use App\Models\DispositionType;
use App\Models\AwarenessCreation;
use App\Mail\NegativeFeedbackMail;
use App\Models\ClassificationType;
use Illuminate\Support\Facades\DB;
use App\Exports\LeadsTrackerExport;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class LeadController extends Controller
{
    public function __construct(){

        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->flash();
        $contacts = Contact::with('member')
                    ->whereHas('member', function($q) use ($request)  {
                        $q->where('last_called_at', null);
                        // $q->oldest('next_call_scheduled_at');
                        $q->whereNull('call_ended_at');
                        $q->where('attempts', '<=', env('MAX_CALL_ATTEMPTS'));
                        $q->where('is_complete', false);
                        $q->where('is_active', false);
                        $q->where('is_enabled', true);
                        $q->whereIn('campaign_id', auth()->user()->role->level == 1 ? Campaign::query()->get('id')->toArray() : [auth()->user()->campaign_id]);
                    })
                    ->when(($request->has('campaign_filter') && !empty($request->campaign_filter)), function($q) use($request){
                        $q->whereHas('member', function($q) use ($request)  {
                            $q->where('campaign_id',$request->campaign_filter);
                        });
                    })
                    ->when(($request->has('phone') && !empty($request->phone)), function($q) use($request){
                        $q->where(function($q) use ($request){
                            $q->where('telephone_one','like','%'.$request->phone.'%');
                            $q->orWhere('telephone_two','like','%'.$request->phone.'%');
                        });
                    })
                    ->latest()
                    ->paginate(15);

        $campaigns = Campaign::all()->unique('slug')->values();
        return view('leads.index',[
            'contacts' => $contacts,
            'campaigns' => $campaigns,
        ]);
    }

    public function callback(Request $request){
        $request->flash();
        $callbackDispositionID = Disposition::where('slug','call-back')->first()->id;
        $contacts = Contact::with('member')
                    ->whereHas('member', function($q) use ($request, $callbackDispositionID)  {
                        $q->where('disposition_id', $callbackDispositionID);
                        $q->whereDate('next_call_scheduled_at', '<=', date('Y-m-d H:i:s', strtotime(now())));
                        $q->where('attempts', '<=', env('MAX_CALL_ATTEMPTS'));
                        $q->where('is_complete', false);
                        $q->where('is_active', false);
                        $q->where('is_enabled', true);
                        $q->whereIn('campaign_id', auth()->user()->role->level == 1 ? Campaign::query()->get('id')->toArray() : [auth()->user()->campaign_id]);
                    })
                    ->when(($request->has('campaign_filter') && !empty($request->campaign_filter)), function($q) use($request){
                        $q->whereHas('member', function($q) use ($request)  {
                            $q->where('campaign_id',$request->campaign_filter);
                        });
                    })
                    ->when(($request->has('phone') && !empty($request->phone)), function($q) use($request){
                        $q->where(function($q) use ($request){
                            $q->where('telephone_one','like','%'.$request->phone.'%');
                            $q->orWhere('telephone_two','like','%'.$request->phone.'%');
                        });
                    })
                    ->latest()
                    ->paginate(15);

        $campaigns = Campaign::all()->unique('slug')->values();

        return view('leads.index',[
            'contacts' => $contacts,
            'campaigns' => $campaigns,
            'callback' => true,
        ]);
    }

    public function unreachable(Request $request){
        $unreachableDispositionType = DispositionType::where('slug','not-reachable')->first();
        $unreachableDisposition = $unreachableDispositionType->disposition->pluck('id');
        $request->flash();
        $contacts = Contact::with('member')
                    ->whereHas('member', function($q) use ($request, $unreachableDisposition)  {
                        // $q->where('last_called_at', null);
                        // $q->oldest('next_call_scheduled_at');
                        $q->whereIn('disposition_id', $unreachableDisposition);
                        $q->whereNotNull('call_ended_at');
                        $q->where('call_ended_at', '<=', date('Y-m-d H:i:s', strtotime(now()->subHours(2))));
                        $q->where('attempts', '<=', env('MAX_CALL_ATTEMPTS'));
                        $q->where('is_complete', false);
                        $q->where('is_active', false);
                        $q->where('is_enabled', true);
                        $q->whereIn('campaign_id', auth()->user()->role->level == 1 ? Campaign::query()->get('id')->toArray() : [auth()->user()->campaign_id]);
                    })
                    ->when(($request->has('campaign_filter') && !empty($request->campaign_filter)), function($q) use($request){
                        $q->whereHas('member', function($q) use ($request)  {
                            $q->where('campaign_id',$request->campaign_filter);
                        });
                    })
                    ->when(($request->has('phone') && !empty($request->phone)), function($q) use($request){
                        $q->where(function($q) use ($request){
                            $q->where('telephone_one','like','%'.$request->phone.'%');
                            $q->orWhere('telephone_two','like','%'.$request->phone.'%');
                        });
                    })
                    ->latest()
                    ->paginate(15);

        $campaigns = Campaign::all()->unique('slug')->values();
        return view('leads.index',[
            'contacts' => $contacts,
            'campaigns' => $campaigns,
            'unreachable' => true,
        ]);
    }

    public function leadsTracker(Request $request){
        $request->flash();
        $campaigns=Campaign::all()->unique('slug')->values();
        $branches=Branch::all()->unique('slug')->values();
        $brands=Brand::all()->unique('slug')->values();

        $leadsTrackers = Contact::select(DB::raw('count(id) as leadcount, created_at'))
                        ->groupBy('created_at')
                        ->when(($request->has('campaign_filter') && !empty($request->campaign_filter)), function($q) use($request){
                            $q->whereHas('member', function($q) use ($request)  {
                                $q->where('campaign_id',$request->campaign_filter);
                            });
                        })
                        ->when((!empty($request->date_from) && !empty($request->date_to)), function($q) use($request){
                            $q->whereDate('created_at', '>=',date('Y-m-d', strtotime($request->date_from)))->whereDate('created_at', '<=',date('Y-m-d', strtotime($request->date_to)));
                        })
                        ->when(($request->has('branch_filter') && !empty($request->branch_filter)), function($q) use($request){
                            $q->whereHas('member', function($q) use ($request)  {
                                $q->where('branch_id',$request->branch_filter);
                            });
                        })
                        ->when(($request->has('brand_filter') && !empty($request->brand_filter)), function($q) use($request){
                            $q->whereHas('member', function($q) use ($request)  {
                                $q->where('brand_id',$request->brand_filter);
                            });
                        })
                        ->latest()
                        ->paginate(15);

        return view('leads.leadstracker',[
            'campaigns'     => $campaigns,
            'branches'      => $branches,
            'brands'        => $brands,
            'leadsTrackers'  => $leadsTrackers

        ]);
    }

    public function leadsTrackerExport(Request $request){
        ini_set('max_execution_time', 3000);

        return Excel::download(new LeadsTrackerExport($request), 'LeadsTrackerExport.xlsx');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(Auth::user()->role->slug!='admin'){
            abort(403);
        }
        $campaigns = Campaign::all()->unique('slug')->values();
        return view('leads.create',[
            'campaigns' => $campaigns,
        ]);
    }

    /**)
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(Auth::user()->role->slug!='admin'){
            abort(403);
        }
        $rules = [
            'channel' => ['required'],
            'leads_csv' => ['required']
        ];

        $this->validate($request, $rules);
        
        $campaignsID = $request->channel;

        Excel::import(new LeadImport($campaignsID), request()->file('leads_csv'));
        

        return redirect()->route('leads.create');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function call(Request $request, Contact $contact)
    {
        $member = $contact->member->first();
        $campaign = Campaign::findOrFail($member->campaign_id);
        $branch = Branch::findOrFail($member->branch_id);
        $brand = Brand::findOrFail($member->brand_id);
        $lastCalled = $member->last_called_at;

        //specifies that the call button has been clicked
        if ($request->isMethod('get')){
            $contact->member()->update([
                'user_id' => $request->user()->id,
                'attempts' => ($member->attempts + 1),
                'last_called_at' => date('Y-m-d H:i', strtotime(now()))
            ]);
        }

        //terminate reachable survery
        if($request->action == 'terminate'){
            $disposition_type_request = DispositionType::where('slug', 'reachable')->first();
            $dispositions = $disposition_type_request->disposition;

            return view('leads.call',[
                'contact'       => $contact,
                'campaignName'  => $campaign->name,
                'branchName'    => $branch->name,
                'brandName'     => $brand->name,
                'lastCalled'    => $lastCalled,
                'dispositions'  => $dispositions,
                'step'          => 'step-two-reach-terminate',
            ]);
        }
        //Step Two reachable terminate
        if($request->has('disposition_reach') && !empty($request->disposition_reach)){
            $contact->member()->update([
                'disposition_id' => $request->disposition_reach,
            ]);

            return redirect()->route('leads.user');
        }
        
        //Step One Reachable / Not Reachable
        if($request->has('disposition_type') && !empty($request->disposition_type)){

            $disposition_type_request = DispositionType::findOrFail($request->disposition_type);

            if($disposition_type_request->slug == 'reachable'){
                $contact->member()->update([
                    'is_reachable' => true,
                ]);
                session(['questionNo' => 1]);
                $question      = $campaign->question()->where('priority',session('questionNo'))->first();
                $answers      = $question->answer;
                
                return view('leads.call',[
                    'contact'       => $contact,
                    'campaignName'  => $campaign->name,
                    'branchName'    => $branch->name,
                    'brandName'     => $brand->name,
                    'lastCalled'    => $lastCalled,
                    'question'      => $question,
                    'answers'       => $answers,
                    'step'          => 'reachable-questions',
                ]);

                
            }elseif($disposition_type_request->slug == 'not-reachable'){
                $dispositions = $disposition_type_request->disposition;
                
                return view('leads.call',[
                    'contact'       => $contact,
                    'campaignName'  => $campaign->name,
                    'branchName'    => $branch->name,
                    'brandName'     => $brand->name,
                    'lastCalled'    => $lastCalled,
                    'dispositions'  => $dispositions,
                    'step'          => 'step-two-unreach',
                ]);
                
            }
        }

        //Step Two UnReachable
        if($request->has('disposition_unreach') && !empty($request->disposition_unreach)){
            $contact->member()->update([
                'disposition_id' => $request->disposition_unreach,
                'is_reachable' => false,
                'call_ended_at' => date('Y-m-d H:i', strtotime(now())),
            ]);

            return redirect()->route('leads.user');
        }

        //Reachable Questions
        if($request->has('answer') && !empty($request->answer)){
            $answer = Answer::findOrFail($request->answer);
            $answerQuestion = Question::findOrFail($answer->question_id);

            // check for text_box
            if ($answer->has_text_box) {
                // do an update or create on this
                Report::updateOrCreate(
                    [
                        'branch_id' => $member->branch_id,
                        'brand_id' => $member->brand_id,
                        'campaign_id' => $member->campaign_id,
                        'member_id' => $member->id,
                        'question_id' => $answerQuestion->id,
                        'question_priority' => $answerQuestion->priority,
                    ],
                    [
                        'user_id' => auth()->user()->id,
                        'classification_id' => isset($request->classification_id) ? $request->classification_id : null,
                        'classification_type_id' => isset($request->classificationType) ? $request->classificationType : null,
                        'answer_id' => $request->answer,
                        'text_box_answer' => $request->answer_text,
                        'is_complete' => true
                    ]
                );
                if($answer->go_to_voc){
                    $toyataCaseClassificationType = ClassificationType::findOrFail($request->classificationType);
                    ToyotaCase::updateOrCreate(
                        [
                            'branch_id' => $member->branch_id,
                            'brand_id' => $member->brand_id,
                            'campaign_id' => $member->campaign_id,
                            'member_id' => $member->id,
                        ],
                        [
                            'user_id'                   => auth()->user()->id,
                            'voc_category_id'           => '92a574e9-dc2f-4d92-a43b-9f52ea3f50e5',
                            'classification_id'         => isset($request->classification_id) ? $request->classification_id : null,
                            'classification_type_id'    => isset($request->classificationType) ? $request->classificationType : null,
                            'voc_customer'              => $request->answer_text,
                            'action'                    => isset($request->action_required) ? $request->action_required : 'NO',
                            'is_negative'               => (isset($request->classificationType) && $toyataCaseClassificationType->slug == 'negative') ? true : false,
                        ]
                    );
                    AwarenessCreation::updateOrCreate(
                        [
                            'member_id' => $member->id,
                        ],
                        [
                            'aware'         => $request->awareness_creation_aware,
                            'satisfaction'  => $request->awareness_creation_satisfaction,
                            'comment'       => $request->awareness_creation_comment,
                        ]
                    );
                    $mailClassificationType = ClassificationType::select('name','slug')->where('id',$request->classificationType)->first();
                    $mailClassification = Classification::select('name','slug')->where('id',$request->classification_id)->first();
                    $mailAdvisor = User::select('name','email')->where('pf_no',$contact->created_by)->first();
                    if(isset($mailClassificationType) && $mailClassificationType->slug == 'negative'){
                        retry(5, function () use ($contact, $member, $campaign, $branch, $brand, $mailClassificationType, $mailClassification, $mailAdvisor, $request) {
                            Mail::to($mailAdvisor->email)->send(new NegativeFeedbackMail($contact, $member, $campaign, $branch, $brand, $mailClassificationType, $mailClassification, $mailAdvisor, $request));

                        }, 100);
                    }

                    
                }
            } else {
                // do an update or create on this
                Report::updateOrCreate(
                    [
                        'branch_id' => $member->branch_id,
                        'brand_id' => $member->brand_id,
                        'campaign_id' => $member->campaign_id,
                        'member_id' => $member->id,
                        'question_id' => $answerQuestion->id,
                        'question_priority' => $answerQuestion->priority,
                    ],
                    [
                        'user_id' => auth()->user()->id,
                        'classification_id' => isset($request->classification_id) ? $request->classification_id : null,
                        'classification_type_id' => isset($request->classificationType) ? $request->classificationType : null,
                        'answer_id' => $request->answer,
                        'is_complete' => true
                    ]
                );
                
            }

            if($answer->answer =='Ask the customer for a convenient time.'){
                $callbackDisposition = Disposition::where('slug', 'call-back')->first()->id;
                $contact->member()->update([
                    'disposition_id' => $callbackDisposition,
                    'call_ended_at' => date('Y-m-d H:i', strtotime(now())),
                    'next_call_scheduled_at' => date('Y-m-d H:i:s',strtotime($request->answer_date)),
                ]);
                return redirect()->route('leads.user');
            }else{
                $questionPriority   = $answer->redirect_to_priority;
                if(session('questionNo') == 1 && $questionPriority == $campaign->question->count()){
                    $notContactOwnerDisposition = Disposition::where('slug', 'not-contact-owner')->first()->id;
                    $contact->member()->update([
                        'disposition_id' => $notContactOwnerDisposition,
                    ]);
                    session('direct_end_survey', true);
                }elseif(session('questionNo') == 2 && $questionPriority == $campaign->question->count()){
                    $notInterestedDisposition = Disposition::where('slug', 'not-interested')->first()->id;
                    $contact->member()->update([
                        'disposition_id' => $notInterestedDisposition,
                    ]);
                    session('direct_end_survey', true);
                }
                session(['questionNo' => $questionPriority]);
            }
            
            $question           = $campaign->question()->where('priority',session('questionNo'))->first();
            $answers            = $question->answer;
            if(!empty($answers->first()) && $answers->first()->has_text_box){
                $campaignsAll = Campaign::all()->unique('slug')->values();
                $classificationTypes = ClassificationType::all()->unique('slug')->values();
                
                return view('leads.call',[
                    'contact'               => $contact,
                    'campaignName'          => $campaign->name,
                    'branchName'            => $branch->name,
                    'brandName'             => $brand->name,
                    'lastCalled'            => $lastCalled,
                    'question'              => $question,
                    'answers'               => $answers,
                    'campaignsAll'          => $campaignsAll,
                    'classificationTypes'   => $classificationTypes,
                    'step'                  => 'reachable-questions',
                ]);
            }else{
                return view('leads.call',[
                    'contact'       => $contact,
                    'campaignName'  => $campaign->name,
                    'branchName'    => $branch->name,
                    'brandName'     => $brand->name,
                    'lastCalled'    => $lastCalled,
                    'question'      => $question,
                    'answers'       => $answers,
                    'step'          => 'reachable-questions',
                ]);
            }
            
        }

        // Survery Completed
        if($request->has('survey_completed') && !empty($request->survey_completed) && $request->survey_completed=='true'){
            if(session()->has('direct_end_survey') && session('direct_end_survey') == true){
                $member->update([
                    'is_active' => true,
                    'is_complete' => false,
                    'call_ended_at' => date('Y-m-d H:i', strtotime(now()))
                ]);
                session()->forget('direct_end_survey');
            }else{
                $completeCallDisposition = Disposition::where('slug', 'complete-call')->first()->id;
                $member->update([
                    'disposition_id' => $completeCallDisposition,
                    'is_active' => true,
                    'is_complete' => true,
                    'call_ended_at' => date('Y-m-d H:i', strtotime(now()))
                ]);
            }
            return redirect()->route('leads.user');
        }

        $DispositionTypes = DispositionType::all();
    
        session()->forget('questionNo');

        return view('leads.call',[
            'contact' => $contact,
            'campaignName' => $campaign->name,
            'branchName' => $branch->name,
            'brandName' => $brand->name,
            'lastCalled' => $lastCalled,
            'DispositionTypes' => $DispositionTypes,
        ]);
    }

    public function commentSummary(Request $request){
        $class_type = $request->classType;
        $campaign   = $request->campaign;

        $classifications = Classification::where('campaign_id', $campaign)->where('classification_type_id',$class_type)->get();

        return response()->json($classifications);
    }

    public function download(){
        return Storage::disk('local')->download('public/downloads/lead-upload-template.csv');
    }
}
