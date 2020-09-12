<?php

namespace App\Imports;

use App\redshift_table;
use Maatwebsite\Excel\Concerns\ToModel;

class UsersImport implements ToModel
{
    public function model(array $calculation_row)
    {
        return new redshift_table([
        'calculation_ID' => $calculation_row[0],
        'optical_u' => $calculation_row[1],
    	'optical_g' => $calculation_row[2],
    	'optical_r' => $calculation_row[3],
    	'optical_i' => $calculation_row[4],
    	'optical_z' => $calculation_row[5],
    	'infrared_three_six' => $calculation_row[6],
    	'infrared_four_five' => $calculation_row[7],
    	'infrared_five_eight' => $calculation_row[8],
    	'infrared_eight_zero' => $calculation_row[9],
    	'infrared_J' => $calculation_row[10],
    	'infrared_K' => $calculation_row[11],
       	'radio_one_four' => $calculation_row[12],
        'user_ID' => auth()->id(),
        ]);
    }
}
