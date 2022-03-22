<?php

namespace App\Imports;

use App\OOCLess;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithStartRow;

use Auth;

use DB;

class LessOOCImport implements ToModel,WithStartRow
{

    use Importable;

    /**
   * @param array $row
   *
   * @return \Illuminate\Database\Eloquent\Model|null
   */
   public function model(array $row)
   {
    

    return new OOCLess([
        'AccountNumber'=>$row[0],
        'UnitSerialNumber'=>$row[1],
        'CustomerName'=>$row[2],
        'CustomerIdentificationType'=>$row[3],
        'CustomerIdentification'=>$row[4],
        'CustomerPhoneNumber'=>$row[5],
        'CustomerPhoneNumberAlternative'=>$row[6],	
        'Financier'=>$row[7],
        'PaymentReceiver'=>$row[8],
        'AccountStatus'=>$row[9],
        'AccountSubStatus'=>$row[10],
        'RegistrationDate'=>$row[11],
        'ActivationDate'=>$row[12],
        'LastTopUpDate'=>$row[13],
        'CloseDate'=>$row[14],
        'EWallet'=>$row[15],
        'Currency'=>$row[16],
        'UnitCreditExpiryDate'=>$row[17],
        'TotalTopUps'=>$row[18],
        'TotalCreditDays'=>$row[19],
        'ContractLengthDays'=>$row[20],
        'RemainingDays'=>$row[21],
        'DaysoutofCredit'=>$row[22],
        'PaymentPlanId'=>$row[23],
        'PaymentPlanName'=>$row[24],
        'PaymentPlanPrice'=>$row[25],
        'PaymentPlanOutstandingBalance'=>$row[26],	
        'AmountPaid'=>$row[27],
        'Product'=>$row[28],
        'ProductVersion'=>$row[29],
        'CustomerAgent'=>$row[30],
        'CustomerAgentPhoneNumber'=>$row[31],
        'CustomerAgentPhoneNumberAlternative'=>$row[32],
        'CustomerAgentManager'=>$row[33],
        'CustomerAgentManagerPhoneNumber'=>$row[34],
        'CustomerAgentManagerPhoneNumberAlternative'=>$row[35],
        'Tier1'=>$row[36],
        'InstallerName'=>$row[37],
        'OriginalCustomerAgent'=>$row[38],
        'Address'=>$row[39],
        'Village'=>$row[40],
        'SubRegion'=>$row[41],
        'Region'=>$row[42],
        'Latitude'=>$row[43],	
        'Longitude'=>$row[44],
        'LocationType'=>$row[45],
        'LocationProvider'=>$row[46], //
        'CustomerId'=>$row[47],
        'TotalNonRevenueCreditDays'=>$row[48],
        'LifetimeUtilisation'=>$row[49],
        'DaysSinceActivation'=>$row[50],
        'DaysInCredit'=>$row[51],	
        'LastTopupPhoneNumber'=>$row[52],
        'CustomerAgentId'=>$row[53],
        'user_id'=>Auth::user()->id
       ]);


   }

   public function startRow(): int
   {
       return 2;
   }




}