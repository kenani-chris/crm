<?php

namespace App\Imports;

use App\Catalog;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithValidation;
use Auth;
use App\CatalogHistory;

use DB;



class CatalogsImport implements ToModel,WithValidation, WithHeadingRow
{

    use Importable;

    /**
   * @param array $row
   *
   * @return \Illuminate\Database\Eloquent\Model|null
   */
   public function model(array $row)
   {
      return new Catalog([
            'book_of_business'=>$row['book_of_business'],
            'priority_leads'=>$row['priority_leads'],
            'stall_resolved'=>$row['stall_resolved'],
            'owner_email'=>$row['owner_email'],
            'brand_token'=>$row['token'],
            'contact_name'=>$row['name'],
            'time_in_state'=>$row['time_in_state'],
            'days_in_state'=>$row['days_in_state'],
            'days_since_unstall'=>$row['days_since_unstall'],
            'latest_admin_comment'=>$row['latest_admin_comment'],
            'last_admin_comment_date'=>$row['last_admin_comment_date'],
            'special_instructions'=>$row['special_instructions'],
            'number_of_stockists'=>$row['number_of_stockists'],
            'admin2_brand_onboarding_tab'=>$row['admin2_brand_onboarding_tab'],
            'product_imagery_url'=>$row['product_imagery_url'],
            'lifestyle_imagery_url'=>$row['lifestyle_imagery_url'],
            'user_id'=>Auth::user()->id
       ]);

    

        


   }


   public function rules(): array
   {
       return [
           //'*.product_photograph' =>'unique:catalogs'
           '*.brand_token' => 'unique:catalogs'
       ];
   }


   public function catalogHistory($catalog, $status){

    $historyCatalog=new CatalogHistory();

   $historyCatalog->catalog_id=$catalog;
   $historyCatalog->history_status=$status;
   $historyCatalog->user_id=Auth::user()->id;
   $historyCatalog->user_level=Auth::user()->level;

    $save=$historyCatalog->save();
}


}
