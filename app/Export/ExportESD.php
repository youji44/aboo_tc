<?php

namespace App\Export;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportESD implements FromCollection,WithHeadings {
    private $report;
    private $date;
    public function __construct($report,$date) {
        $this->report = $report;
        $this->date = $date==''?date('Y-m-d'):$date;
    }

    public function collection()
    {
        if($this->report == '0')
            return  \DB::table('esd')
                ->leftjoin('settings_esd as se','se.id','=','esd.gate')
                ->leftjoin('grading_result as gr1','gr1.id','=','esd.button_visibility')
                ->leftjoin('grading_result as gr2','gr2.id','=','esd.button_condition')
                ->leftjoin('grading_result as gr3','gr3.id','=','esd.button_operational')
                ->leftjoin('grading_result as gr4','gr4.id','=','esd.tank_farm_notification')
                ->where('esd.status',0)
                ->select('esd.*','se.gate as se_gate',
                    'gr1.result as gr1_result',
                    'gr2.result as gr2_result',
                    'gr3.result as gr3_result',
                    'gr4.result as gr4_result',
                    'gr1.color as gr1_color',
                    'gr2.color as gr2_color',
                    'gr3.color as gr3_color',
                    'gr4.color as gr4_color')
                ->get();
        else
            return \DB::table('esd')
                ->leftjoin('settings_esd as se','se.id','=','esd.gate')
                ->leftjoin('grading_result as gr1','gr1.id','=','esd.button_visibility')
                ->leftjoin('grading_result as gr2','gr2.id','=','esd.button_condition')
                ->leftjoin('grading_result as gr3','gr3.id','=','esd.button_operational')
                ->leftjoin('grading_result as gr4','gr4.id','=','esd.tank_farm_notification')
                ->where('esd.status',1)
                ->whereDate('esd.checked_at',$this->date)
                ->select('esd.*','se.gate as se_gate',
                    'gr1.result as gr1_result',
                    'gr2.result as gr2_result',
                    'gr3.result as gr3_result',
                    'gr4.result as gr4_result',
                    'gr1.color as gr1_color',
                    'gr2.color as gr2_color',
                    'gr3.color as gr3_color',
                    'gr4.color as gr4_color')
                ->get();
    }

    public function headings(): array
    {
        return array_keys((array) \DB::table('esd')
            ->leftjoin('settings_esd as se','se.id','=','esd.gate')
            ->leftjoin('grading_result as gr1','gr1.id','=','esd.button_visibility')
            ->leftjoin('grading_result as gr2','gr2.id','=','esd.button_condition')
            ->leftjoin('grading_result as gr3','gr3.id','=','esd.button_operational')
            ->leftjoin('grading_result as gr4','gr4.id','=','esd.tank_farm_notification')
            ->where('esd.status',0)
            ->select('esd.*','se.gate as se_gate',
                'gr1.result as gr1_result',
                'gr2.result as gr2_result',
                'gr3.result as gr3_result',
                'gr4.result as gr4_result',
                'gr1.color as gr1_color',
                'gr2.color as gr2_color',
                'gr3.color as gr3_color',
                'gr4.color as gr4_color')
            ->first());
    }
}