<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Traits\Uuids;

class Payment extends Model
{

    use Uuids;
    
    protected $fillable=[
       'PaymentId',
       'Date',
       'Time',
       'PaymentMethod',
       'PaymentType',
       'TopUpDuration',
       'RevenueDays',
       'PaymentReceiver',
       'AccountNumber',
       'UnitSerialNumber',
       'CurrentCustomerAgent',
       'PaymentStatus',
       'PaymentRejectReason',
       'Amount',
       'AmountUsed',
       'EWallet',
       'TopUpAmount',
       'ActivationAmount',
       'Currency',
       'RevenueGenerating',
       'PaymentPlanId',
       'PaymentPlanName',
       'Reversed',
       'PaymentReason',
       'TCode',
       'CustomerAgentId',
        'user_id'
    ];

   
}
