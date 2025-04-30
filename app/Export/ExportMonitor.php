<?php

namespace App\Export;
use App\Models\AirlineWater;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportMonitor implements FromCollection,WithHeadings {
    private $report;
    private $date;
    public function __construct($report,$date) {
        $this->report = $report;
        $this->date = $date==''?date('Y-m-d'):$date;
    }
    public function collection()
    {
        if($this->report == '0')
        return \DB::table('monitor_well as mw')
            ->LeftJoin('settings_monitor as sm','sm.id','=','mw.well_no')
            ->LeftJoin('grading_result as gr1','gr1.id','=','mw.bolts')
            ->LeftJoin('grading_result as gr2','gr2.id','=','mw.rubber_seals')
            ->LeftJoin('grading_result as gr3','gr3.id','=','mw.riser')
            ->LeftJoin('grading_result as gr4','gr4.id','=','mw.lock_and_caps')
            ->select('mw.*',
                'gr1.grade as gr1_grade',
                'gr2.grade as gr2_grade',
                'gr3.grade as gr3_grade',
                'gr4.grade as gr4_grade',
                'sm.well_no as sm_well_no'
            )
            ->get();
        else return \DB::table('monitor_well as mw')
            ->LeftJoin('settings_monitor as sm','sm.id','=','mw.well_no')
            ->LeftJoin('grading_result as gr1','gr1.id','=','mw.bolts')
            ->LeftJoin('grading_result as gr2','gr2.id','=','mw.rubber_seals')
            ->LeftJoin('grading_result as gr3','gr3.id','=','mw.riser')
            ->LeftJoin('grading_result as gr4','gr4.id','=','mw.lock_and_caps')
            ->where('mw.status',1)
            ->whereDate('mw.checked_at',$this->date)
            ->select('mw.*',
                'gr1.grade as gr1_grade','gr1.color as gr1_color',
                'gr2.grade as gr2_grade','gr2.color as gr2_color',
                'gr3.grade as gr3_grade','gr3.color as gr3_color',
                'gr4.grade as gr4_grade','gr4.color as gr4_color',
                'sm.well_no as sm_well_no'
            )
            ->orderby('mw.created_at','DESC')
            ->get();
    }

    public function headings(): array
    {
        // TODO: Implement headings() method.
        return array_keys((array)\DB::table('monitor_well as mw')
            ->LeftJoin('settings_monitor as sm','sm.id','=','mw.well_no')
            ->LeftJoin('grading_result as gr1','gr1.id','=','mw.bolts')
            ->LeftJoin('grading_result as gr2','gr2.id','=','mw.rubber_seals')
            ->LeftJoin('grading_result as gr3','gr3.id','=','mw.riser')
            ->LeftJoin('grading_result as gr4','gr4.id','=','mw.lock_and_caps')
            ->select('mw.*',
                'gr1.grade as gr1_grade',
                'gr2.grade as gr2_grade',
                'gr3.grade as gr3_grade',
                'gr4.grade as gr4_grade',
                'sm.well_no as sm_well_no'
            )->first());
    }
}