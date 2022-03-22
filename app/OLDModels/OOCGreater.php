<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Traits\Uuids;

class OOCGreater extends Model
{

    use Uuids;

    protected $table = 'o_o_c_greaters';

    protected $appends=['agent'];


    
    protected $fillable=[
        'AccountNumber',
        'UnitSerialNumber',
        'CustomerName',
        'CustomerIdentificationType',
        'CustomerIdentification',
        'CustomerPhoneNumber',
        'CustomerPhoneNumberAlternative',	
        'Financier',
        'PaymentReceiver',
        'AccountStatus',
        'AccountSubStatus',
        'RegistrationDate',
        'ActivationDate',
        'LastTopUpDate',
        'CloseDate',
        'EWallet',
        'Currency',
        'UnitCredit',
        'ExpiryDate',
        'TotalTopUps',
        'TotalCreditDays',
        'ContractLengthDays',
        'RemainingDays',
        'DaysoutofCredit',
        'PaymentPlanId',
        'PaymentPlanName',
        'PaymentPlanPrice',
        'PaymentPlanOutstandingBalance',	
        'AmountPaid',
        'Product',
        'ProductVersion',
        'CustomerAgent',
        'CustomerAgentPhoneNumber',
        'CustomerAgentPhoneNumberAlternative',
        'CustomerAgentManager',
        'CustomerAgentManagerPhoneNumber',
        'CustomerAgentManagerPhoneNumberAlternative',
        'Tier1',
        'InstallerName',
        'OriginalCustomerAgent',
        'Address',
        'Village',
        'SubRegion',
        'Region',
        'Latitude',	
        'Longitude',
        'LocationType',
        'LocationProvider',
        'CustomerId',
        'TotalNonRevenueCreditDays',
        'LifetimeUtilisation',
        'DaysSinceActivation',
        'DaysInCredit',	
        'LastTopupPhoneNumber',
        'CustomerAgentId',
        'lastDisposition',
        'no_of_followups',
        'payment_promise_time',
        'payment_promise_date',
        'user_id'
    ];


    public function payment(){

        return $this->belongsTo('App\Payment','UnitSerialNumber','UnitSerialNumber');

    }

    public function survey(){

        return $this->hasMany('App\OOCGreaterSurvey','lead_id','id')->latest('created_at'); //->where('intro', 'Reachable')

    }


    public function agent(){
        return $this->belongsToThrough(
            OOCGreaterSurvey::class,
            User::class, 
            "id",        
            "lead_id",
            "user_id",
            "id"
        );
    }
    public function getAgentAttribute(){
        return OOCGreaterSurvey::select(['id','lead_id','user_id'])->with(['agent'])->where("lead_id",$this->id)->first();
    }

}
