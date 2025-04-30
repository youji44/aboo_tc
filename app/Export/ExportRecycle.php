<?php

namespace App\Export;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportRecycle implements FromCollection,WithHeadings {
    private $report;
    private $date;
    public function __construct($report,$date) {
        $this->report = $report;
        $this->date = $date==''?date('Y-m-d'):$date;
    }
    public function collection()
    {
        if($this->report == '0')
        return  \DB::table('recycle_area as ra')
            ->leftjoin('settings_recycle as sr','sr.id','=','ra.recycle_no')
            ->leftjoin('grading_result as gr1','gr1.id','=','ra.garbage_bins_condition')
            ->leftjoin('grading_result as gr2','gr2.id','=','ra.spill_kit_bins_condition')
            ->select('ra.*',
                'sr.recycle_no as sr_recycle_no',
                'gr1.grade as gr1_grade','gr1.color as gr1_color',
                'gr2.grade as gr2_grade','gr2.color as gr2_color'
            )
            ->get();
        else return \DB::table('recycle_area as ra')
            ->leftjoin('settings_recycle as sr','sr.id','=','ra.recycle_no')
            ->leftjoin('grading_result as gr1','gr1.id','=','ra.garbage_bins_condition')
            ->leftjoin('grading_result as gr2','gr2.id','=','ra.spill_kit_bins_condition')
            ->where('ra.status',1)
            ->whereDate('ra.checked_at',$this->date)
            ->orderby('ra.created_at','DESC')
            ->select('ra.*',
                'sr.recycle_no as sr_recycle_no',
                'gr1.grade as gr1_grade','gr1.color as gr1_color',
                'gr2.grade as gr2_grade','gr2.color as gr2_color'
            )
            ->get();
    }

    public function headings(): array
    {
        // TODO: Implement headings() method.
        return array_keys((array)\DB::table('recycle_area as ra')
            ->leftjoin('settings_recycle as sr','sr.id','=','ra.recycle_no')
            ->leftjoin('grading_result as gr1','gr1.id','=','ra.garbage_bins_condition')
            ->leftjoin('grading_result as gr2','gr2.id','=','ra.spill_kit_bins_condition')
            ->select('ra.*',
                'sr.recycle_no as sr_recycle_no',
                'gr1.grade as gr1_grade','gr1.color as gr1_color',
                'gr2.grade as gr2_grade','gr2.color as gr2_color'
            )->first());
    }
}