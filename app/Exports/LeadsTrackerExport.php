<?php

namespace App\Exports;

use App\Models\Contact;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class LeadsTrackerExport implements FromCollection,ShouldAutoSize,WithHeadings,WithStrictNullComparison
{
    public function  __construct($request)
    {
        $this->request = $request;
    }

    public function headings(): array
    {
        return [
            '#',
            'Date',
            'No of Leads',
        ];
    }
    
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $request    = $this->request;


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
                        ->get();

        $exportedData =  collect();
        $leadsTrackerI = 1;
        foreach ( $leadsTrackers as $leadsTracker){
            $singleTrackerRecord = [];
            array_push($singleTrackerRecord, $leadsTrackerI);
            array_push($singleTrackerRecord, $leadsTracker->created_at);
            array_push($singleTrackerRecord, $leadsTracker->leadcount);
            $leadsTrackerI++;
            $exportedData->push($singleTrackerRecord);
        }

        return $exportedData;
    }
}
