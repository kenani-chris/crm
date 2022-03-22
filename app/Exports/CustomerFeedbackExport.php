<?php

namespace App\Exports;

use App\Models\ToyotaCase;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class CustomerFeedbackExport implements FromCollection,ShouldAutoSize,WithHeadings
{

    public function  __construct($request)
    {
        $this->request= $request;
    }

    public function headings(): array
    {
        return [
            'Customer Name',
            'Order Number',
            'Telephone One',
            'Telephone Two',
            'Advisor/Consultant',
            'Dept',
            'Branch',
            'Brand',
            'VOC',
            'Staff Comment',
            'Status',
            'Feedback Type',
            'Comment Summary',
            'Date/Time',
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $request = $this->request;
        

        $records=ToyotaCase::with([
                                'user',
                                'escalate.user',
                                'campaign',
                                'branch',
                                'brand',
                                'member.contact',
                                'voc_category',
                                'classification_type',
                                'classification'
                            ])
                            ->when((!empty($request->date_from) && !empty($request->date_to)), function($q) use($request){
                                $q->whereDate('created_at', '>=',date('Y-m-d', strtotime($request->date_from)))->whereDate('created_at', '<=',date('Y-m-d', strtotime($request->date_to)));
                            })
                            ->when(Auth::user()->role->slug=='advisor' || Auth::user()->role->slug=='champion', function($q){
                                $q->whereHas( 'escalate.user', function($q) {
                                 $q->where('id', Auth::user()->id);
                             });
                            })
                            ->when((!empty($request->campaign)), function($q) use($request){
                                $q->with('campaign')->whereHas('campaign', function($query) use($request){
                                    $query->where('id','ILIKE','%'.$request->campaign.'%');
                                });
                            })
                            ->when((!empty($request->branch)), function($q) use($request){
                                $q->with('branch')->whereHas('branch', function($query) use($request){
                                    $query->where('id','ILIKE','%'.$request->branch.'%');
                                });
                            })
                            ->when((!empty($request->brand)), function($q) use($request){
                                $q->with('brand')->whereHas('brand', function($query) use($request){
                                    $query->where('id','ILIKE','%'.$request->brand.'%');
                                });
                            })
                            ->when((!empty($request->feedback)), function($q) use($request){
                                $q->with('classification_type')->whereHas('classification_type', function($query) use($request){
                                    $query->where('id','ILIKE','%'.$request->feedback.'%');
                                });
                            })
                            ->when((!empty($request->advisor)), function($q) use($request){
                                $q->with('escalate.user')->whereHas('escalate.user', function($query) use($request){
                                    $query->where('id','ILIKE','%'.$request->advisor.'%');
                                });
                            })
                            ->get();
            
        $exportedData =  collect();
        

        foreach ($records as  $singleRecordComplete) {
            if(empty($singleRecordComplete->comments) && ($singleRecordComplete->classification_type->slug == "enquiries" || $singleRecordComplete->classification_type->slug == "negative")){
                $isClosed = 'Open';
            }else{
                $isClosed = 'Closed';
            }

            $singleRecord = [];
            array_push($singleRecord, $singleRecordComplete->member->contact->customer);
            array_push($singleRecord, $singleRecordComplete->member->contact->order_number);
            array_push($singleRecord, $singleRecordComplete->member->contact->telephone_one);
            array_push($singleRecord, $singleRecordComplete->member->contact->telephone_two);
            array_push($singleRecord, isset($singleRecordComplete->escalate->user->name) ? $singleRecordComplete->escalate->user->name : '');
            array_push($singleRecord, $singleRecordComplete->campaign->name);
            array_push($singleRecord, $singleRecordComplete->branch->name);
            array_push($singleRecord, $singleRecordComplete->brand->name);
            array_push($singleRecord, $singleRecordComplete->voc_customer);
            array_push($singleRecord, $singleRecordComplete->comments);
            array_push($singleRecord, $isClosed);
            array_push($singleRecord, $singleRecordComplete->classification_type->name);
            array_push($singleRecord, $singleRecordComplete->classification->name);
            array_push($singleRecord, date('Y-m-d H:i:s', strtotime($singleRecordComplete->created_at)));

            $exportedData->push($singleRecord);
        }

        return $exportedData;
    }
}
