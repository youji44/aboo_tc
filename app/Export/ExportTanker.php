<?php

namespace App\Export;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportTanker implements FromCollection,WithHeadings {
    private $report;
    private $date;
    public function __construct($report,$date) {
        $this->report = $report;
        $this->date = $date==''?date('Y-m-d'):$date;
    }
    public function collection()
    {
        if($this->report == '0')
        return \DB::table('tanker_filter_sump as ts')
            ->LeftJoin('fuel_equipment as fe','fe.id','=','ts.unit')
            ->LeftJoin('grading_result as gr1','gr1.id','=','ts.low_point_white_bucket')
            ->LeftJoin('grading_result as gr2','gr2.id','=','ts.filter_sump_white_bucket')
            ->LeftJoin('grading_result as gr3','gr3.id','=','ts.eductor_tank_levels')
            ->select('ts.*','fe.unit as fe_unit',
                'gr1.grade as gr1_grade',
                'gr2.grade as gr2_grade',
                'gr3.grade as gr3_grade'
            )
            ->get();
        else return \DB::table('tanker_filter_sump as ts')
            ->LeftJoin('fuel_equipment as fe','fe.id','=','ts.unit')
            ->LeftJoin('grading_result as gr1','gr1.id','=','ts.low_point_white_bucket')
            ->LeftJoin('grading_result as gr2','gr2.id','=','ts.filter_sump_white_bucket')
            ->LeftJoin('grading_result as gr3','gr3.id','=','ts.eductor_tank_levels')
            ->where('ts.status',1)
            ->whereDate('ts.date',$this->date)
            ->select('ts.*','fe.unit as fe_unit',
                'gr1.grade as gr1_grade','gr1.color as gr1_color',
                'gr2.grade as gr2_grade','gr2.color as gr2_color',
                'gr3.grade as gr3_grade','gr3.color as gr3_color'
            )
            ->orderby('ts.created_at','DESC')
            ->get();
    }

    public function headings(): array
    {
        // TODO: Implement headings() method.
        return array_keys((array)\DB::table('tanker_filter_sump as ts')
            ->LeftJoin('fuel_equipment as fe','fe.id','=','ts.unit')
            ->LeftJoin('grading_result as gr1','gr1.id','=','ts.low_point_white_bucket')
            ->LeftJoin('grading_result as gr2','gr2.id','=','ts.filter_sump_white_bucket')
            ->LeftJoin('grading_result as gr3','gr3.id','=','ts.eductor_tank_levels')
            ->select('ts.*','fe.unit as fe_unit',
                'gr1.grade as gr1_grade',
                'gr2.grade as gr2_grade',
                'gr3.grade as gr3_grade'
            )->first());
    }
}