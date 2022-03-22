<?php

namespace App\Imports;

use App\BodyShopLeads;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithValidation;

use Maatwebsite\Excel\Concerns\WithStartRow;

use Auth;

use DB;

class BodyShopLeadsImport implements ToModel, WithStartRow 
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

    if(BodyShopLeads::where('Registration',$row[3])->where('DateOut',date('Y-m-d', strtotime($row[0])))->count()<=0){

      return new BodyShopLeads([
        'DateOut'=>$row[0],
        'CustomerName'=>$row[1],
        'MakeModel'=>$row[2],
        'Registration'=>$row[3],
        'CompanyName'=>$row[4],
        'MobileNumber'=>$row[5],
        'ServiceAdvisor'=>strtolower($row[6]),
        'user_id'=>Auth::user()->id
       ]);
      }

   }


   public function startRow(): int
   {
       return 2;
   }


   public function getRowCount(): int
    {
        return $this->rows;
    }


 

}
