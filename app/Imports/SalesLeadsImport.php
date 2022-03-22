<?php

namespace App\Imports;

use App\SalesLeads;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithValidation;

use Maatwebsite\Excel\Concerns\WithStartRow;

use Auth;

use DB;

class SalesLeadsImport implements ToModel, WithStartRow 
{

    use Importable;

    private $rows = 0;

    /**
   * @param array $row
   *
   * @return \Illuminate\Database\Eloquent\Model|null
   */
   public function model(array $row)
   {

    if(SalesLeads::where('RegistrationVIN',$row[12])->where('TransactionDate',date('Y-m-d', strtotime($row[13])))->count()<=0){

        if(!empty($row[6])){
      return new SalesLeads([
        'DistributorNameOutletName'=>$row[0],
        'RetailOutletDealerCode'=>$row[1],
        'Title'=>$row[2],
        'Initials'=>$row[3],
        'Surname'=>$row[4],
        'Landline'=>$row[5],
        'Mobile'=>$row[6],
        'TransactionType'=>$row[7],
        'CompanyName'=>$row[8],
        'FleetGovernmentPrivate'=>$row[9],
        'ModelCode'=>$row[10],
        'ModelName'=>$row[11],
        'RegistrationVIN'=>$row[12],
        'TransactionDate'=>date('Y-m-d', strtotime($row[13])),
        'SalesPersonName'=>$row[14],
        'user_id'=>Auth::user()->id
       ]);
      }
    }

   }

  

   public function getRowCount(): int
    {
        return $this->rows;
    }

   public function startRow(): int
   {
       return 2;
   }


 

}
