<?php

namespace App\Exports;

use App\calculations;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Facades\Excel;


class RedshiftExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */

    use Exportable;

     public function headings(): array
    {
        return [
           ['galaxy_id', 'redshift_result', 'redshift_alt_result', 'method' ,'optical_u', 'optical_v', 'optical_g', 'optical_r', 'optical_i', 'optical_z', 'infrared_three_six', 'infrared_four_five', 'infrared_five_eight', 'infrared_eight_zero', 'infrared_H', 'infrared_J', 'infrared_K', 'radio_one_four',],
        ];
    }
    public function collection()
    {

		$userId = auth()->id();

		$completed = DB::select("
			SELECT assigned_calc_id, redshift_result, redshift_alt_result, method_name ,optical_u, optical_v, optical_g, optical_r, optical_i, optical_z,
			 infrared_three_six, infrared_four_five, infrared_five_eight, infrared_eight_zero, infrared_H, infrared_J, infrared_K, radio_one_four
			FROM redshifts
			INNER JOIN calculations on redshifts.calculation_id = calculations.galaxy_id
			INNER JOIN jobs on redshifts.job_id = jobs.job_id
			INNER JOIN users on jobs.user_id = users.id
			INNER JOIN methods on calculations.method_id = methods.method_id
			WHERE (status = 'COMPLETED' OR status = 'READ')
			AND users.id = " . $userId);

		foreach($completed as $job){
			if(isset($job->redshift_result)){
				$job->redshift_result = floatval($job->redshift_result);
			}

			$exploded = explode("alt_result/",$job->redshift_alt_result);
			if(isset($exploded[1])){
				$job->redshift_alt_result = $exploded[1];
			}
			$job->method_name = $job->method_name;
			$job->optical_v = floatval($job->optical_v);
			$job->optical_g = floatval($job->optical_g);
			$job->optical_r = floatval($job->optical_r);
			$job->optical_i = floatval($job->optical_i);
			$job->optical_z = floatval($job->optical_z);
			$job->infrared_three_six = floatval($job->infrared_three_six);
			$job->infrared_four_five = floatval($job->infrared_four_five);
			$job->infrared_five_eight = floatval($job->infrared_five_eight);
			$job->infrared_eight_zero = floatval($job->infrared_eight_zero);
			$job->infrared_H = floatval($job->infrared_H);
			$job->infrared_J = floatval($job->infrared_J);
			$job->infrared_K = floatval($job->infrared_K);
			$job->radio_one_four = floatval($job->radio_one_four);
		}

		return new Collection($completed);

    }
}
