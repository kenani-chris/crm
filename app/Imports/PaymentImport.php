<?php

namespace App\Imports;

use App\Payment;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithValidation;

use Maatwebsite\Excel\Concerns\WithStartRow;

use Auth;

use DB;

class PaymentImport implements ToModel, WithStartRow 
{

    use Importable;

    /**
   * @param array $row
   *
   * @return \Illuminate\Database\Eloquent\Model|null
   */
   public function model(array $row)
   {

      return new Payment([
        'PaymentId'=>$row[0],
        'Date'=>$row[1],
        'Time'=>$row[2],
        'PaymentMethod'=>$row[3],
        'PaymentType'=>$row[4],
        'TopUpDuration'=>$row[5],
        'RevenueDays'=>$row[6],
        'PaymentReceiver'=>$row[7],
        'AccountNumber'=>$row[8],
        'UnitSerialNumber'=>$row[9],
        'CurrentCustomerAgent'=>$row[10],
        'PaymentStatus'=>$row[11],
        'PaymentRejectReason'=>$row[12],
        'Amount'=>$row[13],
        'AmountUsed'=>$row[14],
        'EWallet'=>$row[15],
        'TopUpAmount'=>$row[16],
        'ActivationAmount'=>$row[17],
        'Currency'=>$row[18],
        'RevenueGenerating'=>$row[19],
        'PaymentPlanId'=>$row[20],
        'PaymentPlanName'=>$row[21],
        'Reversed'=>$row[22],
        'PaymentReason'=>$row[23],
        'TCode'=>$row[24],
        'CustomerAgentId'=>$row[25],
        'user_id'=>Auth::user()->id
       ]);

   }


   public function startRow(): int
   {
       return 2;
   }


 

}
