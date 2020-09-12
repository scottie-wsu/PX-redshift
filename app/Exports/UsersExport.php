<?php

namespace App\Exports;

use App\redshift_table;
use Maatwebsite\Excel\Concerns\FromCollection;

class UsersExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return redshift_table::all();
    }
}
