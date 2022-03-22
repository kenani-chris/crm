<?php

namespace App\Exports;

use App\User;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class UsersExport implements FromQuery, WithHeadings,WithMapping
{
    use Exportable;
    /**
    * @return \Illuminate\Support\Collection
    */
    public function query()
    {
        return User::query();
    }

     /**
    * @var User $user
    */
    public function map($user): array
    {

       
        return [
            $user->name,
            $user->email,
            $user->level,
            $user->created_at
        ];
    }

    public function headings(): array
    {
        return ["Name", "Email",'Role',"Date Created"];
    }
}
