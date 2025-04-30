<?php

namespace App\Export;
use App\Models\AirlineWater;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportDrain implements FromCollection,WithHeadings {
    private $report;
    private $date;
    public function __construct($report,$date) {
        $this->report = $report;
        $this->date = $date==''?date('Y-m-d'):$date;
    }
    public function collection()
    {
        if($this->report == '0')
        return  \DB::table('low_point_drain_checks as dc')
            ->LeftJoin('grading_result as gr1','gr1.id','=','dc.rating_of_flush_into_bucket')
            ->LeftJoin('grading_result as gr2','gr2.id','=','dc.initial_white_bucket_rating')
            ->LeftJoin('settings_drain as sd','sd.id','=','dc.lpd_no')
            ->select('dc.*',
                'gr1.grade as gr1_grade',
                'gr2.grade as gr2_grade',
                'sd.location as sd_location',
                'sd.lpd_no as sd_lpd_no')
            ->get();
        else return \DB::table('low_point_drain_checks as dc')
            ->LeftJoin('grading_result as gr1','gr1.id','=','dc.rating_of_flush_into_bucket')
            ->LeftJoin('grading_result as gr2','gr2.id','=','dc.initial_white_bucket_rating')
            ->LeftJoin('settings_drain as sd','sd.id','=','dc.lpd_no')
            ->where('dc.status',1)
            ->whereDate('dc.checked_at',$this->date)
            ->select('dc.*',
                'gr1.grade as gr1_grade','gr1.color as gr1_color',
                'gr2.grade as gr2_grade','gr2.color as gr2_color',
                'sd.location as sd_location',
                'sd.lpd_no as sd_lpd_no')
            ->orderby('dc.created_at','DESC')
            ->get();
    }

    public function headings(): array
    {
        // TODO: Implement headings() method.
        return array_keys((array)\DB::table('low_point_drain_checks as dc')
            ->LeftJoin('grading_result as gr1','gr1.id','=','dc.rating_of_flush_into_bucket')
            ->LeftJoin('grading_result as gr2','gr2.id','=','dc.initial_white_bucket_rating')
            ->LeftJoin('settings_drain as sd','sd.id','=','dc.lpd_no')
            ->select('dc.*',
                'gr1.grade as gr1_grade',
                'gr2.grade as gr2_grade',
                'sd.location as sd_location',
                'sd.lpd_no as sd_lpd_no')->first());
    }
}