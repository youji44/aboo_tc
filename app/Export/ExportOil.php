<?php

namespace App\Export;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportOil implements FromCollection,WithHeadings {

    private $report;
    private $date;
    public function __construct($report,$date) {
        $this->report = $report;
        $this->date = $date==''?date('Y-m-d'):$date;
    }

    public function collection()
    {

        if($this->report == '0')

            return  \DB::table('oil_water_separator as o')
                ->LeftJoin('settings_oil as so','so.id','=','o.location')
                ->LeftJoin('grading_result as gr1','gr1.id','=','o.boom')
                ->LeftJoin('grading_result as gr2','gr2.id','=','o.spill_kit_level')
                ->where('o.status',0)
                ->select('o.*',
                    'gr1.grade as gr1_grade', 'gr1.result as gr1_result',
                    'gr1.color as gr1_color',
                    'gr2.color as gr2_color',
                    'gr2.grade as gr2_grade', 'gr2.result as gr2_result',
                    'so.location as so_location',
                    'so.location_code as so_location_code'
                )
                ->orderby('o.created_at','DESC')
                ->get();
        else
            return \DB::table('oil_water_separator as o')
                ->LeftJoin('settings_oil as so','so.id','=','o.location')
                ->LeftJoin('grading_result as gr1','gr1.id','=','o.boom')
                ->LeftJoin('grading_result as gr2','gr2.id','=','o.spill_kit_level')
                ->where('o.status',1)
                ->whereDate('o.date',$this->date)
                ->select('o.*',
                    'gr1.grade as gr1_grade', 'gr1.result as gr1_result',
                    'gr1.color as gr1_color',
                    'gr2.color as gr2_color',
                    'gr2.grade as gr2_grade', 'gr2.result as gr2_result',
                    'so.location as so_location',
                    'so.location_code as so_location_code'
                )
                ->orderby('o.created_at','DESC')
                ->get();
    }

    public function headings(): array
    {

            // TODO: Implement headings() method.
            return array_keys((array)\DB::table('oil_water_separator as o')
                ->LeftJoin('settings_oil as so','so.id','=','o.location')
                ->LeftJoin('grading_result as gr1','gr1.id','=','o.boom')
                ->LeftJoin('grading_result as gr2','gr2.id','=','o.spill_kit_level')
                ->select('o.*',
                    'gr1.grade as gr1_grade', 'gr1.result as gr1_result',
                    'gr1.color as gr1_color',
                    'gr2.color as gr2_color',
                    'gr2.grade as gr2_grade', 'gr2.result as gr2_result',
                    'so.location as so_location',
                    'so.location_code as so_location_code'
                )
                ->first());

    }
}