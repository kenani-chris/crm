<?php

namespace App\Exports;

use Auth;
use App\Models\Brand;
use App\Models\Branch;
use App\Models\ToyotaCase;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class CSIReportExport implements FromCollection,ShouldAutoSize,WithHeadings
{
    public function  __construct($request,$id)
    {
        $this->request= $request;
        $this->id= $id;
    }

    public function headings(): array
    {
        return [
            'Distributor',
            'Date',
            'VOC Source',
            'Brand',
            'Job Card Number',
            'Customer Name',
            'VIN',
            'Reg number',
            'Branch',
            'SA/Sales_Person',
            'Customer Comment',
            'Type',
            'Department',
            'Comment Summary',
            'Action Required',
            'Status',
            'Staff Comments',
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $request = $this->request;
        $id = $this->id;

        // fixing the westland and kismu branch bug from backend because cant change the database
        $westlandBranchID       = Branch::select('id')->where('slug', 'westlands')->first()->id;
        $kisumuBranchID         = Branch::select('id')->where('slug', 'kisumu')->first()->id;
        $branchRequestID        = false;
        $brandRequest           = false;
        if($request->has('branch') && !empty($request->branch)){
            $branchRequestID    = Branch::select('id')->where('id', $request->branch)->first()->id;
            if($request->has('brand') && !empty($request->brand)){
                $brandRequest   = Brand::where('id',$request->brand)->first();
            }
        }

        $records=ToyotaCase::with([
                                'user',
                                'campaign',
                                'member.contact',
                                'branch',
                                'brand',
                                'escalate.user',
                                'campaign',
                                'voc_category',
                                'classification_type',
                                'classification'
                            ])
                            ->when((!empty($request->date_from) && !empty($request->date_to)), function($q) use($request){
                                $q->whereDate('updated_at', '>=',date('Y-m-d', strtotime($request->date_from)))->whereDate('updated_at', '<=',date('Y-m-d', strtotime($request->date_to)));
                            })
                            ->when(($request->has('brand') && !empty($request->brand)), function($q) use($request){
                                $q->whereHas('brand', function($q) use ($request)  {
                                    $q->where('id','like','%'.$request->brand.'%');
                            });

                            })
                            ->when(($request->has('branch') && !empty($request->branch)), function($q) use($request, $westlandBranchID, $kisumuBranchID, $branchRequestID, $brandRequest){
                                if($branchRequestID == $westlandBranchID){
                                    if(empty($brandRequest) || (isset($brandRequest->slug) && $brandRequest->slug == 'toyota') ){ 
                                        $q->whereHas('branch', function($query) use ($request)  {
                                            $query->where('id','like','%'.$request->branch.'%');
                                        });
                                        $q->whereHas('brand', function($query) {
                                            $query->where('slug','toyota');
                                        });
                                    }else{
                                        $q->whereHas('branch', function($q) {
                                            $q->whereRaw("true = false");
                                        });
                                    }
                                }elseif($branchRequestID == $kisumuBranchID){
                                    if(empty($brandRequest)){ 
                                        $q->whereHas('branch', function($query) use ($westlandBranchID, $kisumuBranchID)  {
                                            $query->where('id',$westlandBranchID);
                                            $query->orWhere('id',$kisumuBranchID);
                                        });
                                    }elseif(isset($brandRequest->slug) && $brandRequest->slug == 'toyota'){
                                        $q->whereHas('branch', function($query) use ($kisumuBranchID)  {
                                            $query->Where('id',$kisumuBranchID);
                                        });
                                    }elseif(isset($brandRequest->slug) && $brandRequest->slug != 'toyota'){
                                        $q->whereHas('branch', function($query) use ($westlandBranchID)  {
                                            $query->Where('id',$westlandBranchID);
                                        });
                                    }
                                }else{
                                    $q->whereHas('branch', function($q) use ($request)  {
                                        $q->where('id','like','%'.$request->branch.'%');
                                    });
                                }
                            })
                            ->whereHas('member', function($q){
                                $q->where('is_complete',true);
                            })
                            ->where('campaign_id', $id)
                            ->get();

        $exportedData =  collect();

        foreach ($records as  $singleRecordComplete) {
            
            if(empty($singleRecordComplete->comments) && ($singleRecordComplete->classification_type->slug == "enquiries" || $singleRecordComplete->classification_type->slug == "negative")){
                $is_closed = 'Open';
            }else{
                $is_closed = 'Closed';
            }

            // fixing the westland and kismu branch bug from backend because cant change the database
            $branch = '';
            if(isset($singleRecordComplete->branch)){
                if($singleRecordComplete->brand->slug != 'toyota' && ($singleRecordComplete->branch->slug == 'kisumu' || $singleRecordComplete->branch->slug == 'westlands')){
                    $branch = 'Kisumu';
                }elseif(isset($singleRecordComplete->branch->name)){
                    $branch = $singleRecordComplete->branch->name;
                }else{
                    $branch = $singleRecordComplete->branch_id;
                }
            }

            $singleRecord = [];
            array_push($singleRecord, 'Kenya');
            array_push($singleRecord, date('m/d/Y',strtotime($singleRecordComplete->member->updated_at)));
            array_push($singleRecord, 'CSI');
            array_push($singleRecord, isset($singleRecordComplete->brand->name) ? $singleRecordComplete->brand->name : $singleRecordComplete->brand_id);
            array_push($singleRecord, $singleRecordComplete->member->contact->order_number);
            array_push($singleRecord, $singleRecordComplete->member->contact->customer_description);
            array_push($singleRecord, $singleRecordComplete->member->contact->vin_number);
            array_push($singleRecord, $singleRecordComplete->member->contact->license_plate_number);
            array_push($singleRecord, isset($branch) ? $branch : "");
            array_push($singleRecord, isset($singleRecordComplete->member->contact->created_by)? isset(\App\Models\User::query()->firstWhere('pf_no',$singleRecordComplete->member->contact->created_by)->name) ? \App\Models\User::query()->firstWhere('pf_no',$singleRecordComplete->member->contact->created_by)->name : '' : '');
            array_push($singleRecord, $singleRecordComplete->voc_customer);
            array_push($singleRecord, $singleRecordComplete->classification_type->name);
            array_push($singleRecord, $singleRecordComplete->campaign->name);
            array_push($singleRecord, $singleRecordComplete->classification->name);
            array_push($singleRecord, ucfirst(\Illuminate\Support\Str::lower($singleRecordComplete->action)));
            array_push($singleRecord, $is_closed);
            array_push($singleRecord, (isset($singleRecordComplete->comments)) ? $singleRecordComplete->comments : '');

            $exportedData->push($singleRecord);
        }

        return $exportedData;
    }
}
