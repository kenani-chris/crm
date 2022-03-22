<?php

namespace App\Imports;

use DateTime;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Brand;
use App\Models\Branch;
use App\Models\Member;
use App\Models\Contact;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

class LeadImport implements ToCollection, WithCalculatedFormulas, SkipsEmptyRows
{
    public function  __construct($campaignsID)
    {
        $this->campaignsID = $campaignsID;
    }

    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        $campaignsID = $this->campaignsID;

        $batch = Str::upper(Str::random(8));
        
        $i = -1;

        $dates = [];

        $duplicateEntries = 0;

        $nonExistingCreatedBy = [];

        foreach ($collection as $record) 
        {
            $i++;

            if($i == 0){
                continue;
            }

            $existingContact    = Contact::where('order_number', $record[7])->first();
            $existingTelephone  = Contact::where('telephone_two', $record[4])->with('member:contact_id,last_called_at')->first();
            if(isset($existingTelephone)){
                $existingTelephone = $existingTelephone->member->first()->last_called_at;
                $existingTelephone = Carbon::parse($existingTelephone)->addHours(72)->lessThan(now());
            }else{
                $existingTelephone = false;
            }

            if(isset($existingContact) || $existingTelephone){
                $duplicateEntries++;
                continue;
            }

            //checking users with pf_no with created_by in leads uploaded
            $existingUserpf_noCount       = User::select('id')->where('pf_no', $record[8])->first();
            if(empty($existingUserpf_noCount)){
                array_push($nonExistingCreatedBy, $record[8]);
            }

            $date_of_delivery = null;
            if (DateTime::createFromFormat('m/d/Y', $record[16]) !== FALSE) {
                $date_of_delivery = Carbon::createFromFormat('m/d/Y', $record[16]);
            }

            $contact = Contact::create([
                "customer"              => $record[0],
                "customer_description"  => $record[1],
                "license_plate_number"  => $record[2],
                "telephone_one"         => $record[3],
                "telephone_two"         => $record[4],
                "fax_number"            => $record[5],
                "contact_person"        => $record[6],
                "order_number"          => $record[7],
                "created_by"            => $record[8],
                "order_type"            => $record[9],
                "plant_code"            => $record[10],
                "dist_channel"          => $record[11],
                "odo_reading"           => $record[12],
                "odo_reading_unit"      => $record[13],
                "reason_for_visit"      => $record[14],
                "vehicle_model"         => $record[15],
                "date_of_delivery"      => $date_of_delivery,
                "cust_classification"   => $record[17],
                "description"           => $record[18],
                "vin_number"            => $record[19],
                "header_text"           => $record[20],
                "new_used_vehicle"      => $record[21],
                "gate_pass_ind"         => $record[22],
            ]);

            // extract brand code nad branch code here
            $brand_code = substr((string)$contact->plant_code, 0, 2);
            $branch_code = substr((string)$contact->plant_code, 2, 4);

            // assign branch and brand
            $branch = Branch::query()->firstWhere('code', $branch_code);
            $brand = Brand::query()->firstWhere('code', $brand_code);

            // add a new lead here
            $Member = Member::create([
                'campaign_id' => $campaignsID,
                'branch_id' => $branch ? $branch->id : null,
                'brand_id' => $brand ? $brand->id : null,
                'contact_id' => $contact->id,
                'batch' => $batch,
            ]);
        }

        session()->flash('leads-uploaded-success','Out of Total Leads <strong>' . $i . '</strong>, The succesfully uploaded leads were <strong>' .  ($i - $duplicateEntries) . '</strong>, And the duplicate leads were <strong>' . $duplicateEntries . '</strong>.');

        if(!empty($nonExistingCreatedBy)){
            session()->flash('leads-pfno-entries','Advisor/Champion with following pf_no <strong>' . implode(" ",$nonExistingCreatedBy) . '</strong> does not exists. Please add them as users');
        }
    }
}
