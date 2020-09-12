<?php

namespace App\Exports;

use App\redshift_table;
use App\calculations;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RedshiftExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
     public function headings(): array
    {
        return [
           ['galaxy_id', 'optical_u','optical_g', 'optical_r', 'optical_i', 'optical_z', 'infrared_three_six', 'infrared_four_five', 'infrared_five_eight', 'infrared_eight_zero', 'infrared_J', 'infrared_K', 'radio_one_four', 'redshift_result',],

        ];
    }
    public function collection()
    {
        return calculations::join('redshift_tables', 'calculation_ID', '=', 'calculationss.galaxy_ID')
            ->select('redshift_tables.assigned_calc_ID', 'redshift_tables.optical_u','redshift_tables.optical_g', 'redshift_tables.optical_r', 'redshift_tables.optical_i', 'redshift_tables.optical_z', 'redshift_tables.infrared_three_six', 'redshift_tables.infrared_four_five', 'redshift_tables.infrared_five_eight', 'redshift_tables.infrared_eight_zero', 'redshift_tables.infrared_J', 'redshift_tables.infrared_K', 'redshift_tables.radio_one_four','calculationss.redshift_result')->where('redshift_tables.user_ID', auth()->id())->get()


            // redshift_table::select('assigned_calc_ID','optical_u', 'optical_g', 'optical_r', 'optical_i', 'optical_z', 'infrared_three_six', 'infrared_four_five', 'infrared_five_eight', 'infrared_eight_zero', 'infrared_J', 'infrared_K', 'radio_one_four', 'redshift_result')->orderByDesc('updated_at')
            //                             ->where('user_ID', auth()->id())->get();

                                     ;
    }
}
