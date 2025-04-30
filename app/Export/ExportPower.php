<?php

namespace App\Export;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportPower implements FromCollection,WithHeadings {
    private $report;
    private $date;
    public function __construct($report,$date) {
        $this->report = $report;
        $this->date = $date==''?date('Y-m-d'):$date;
    }

    public function collection()
    {
        if($this->report == '0')
            return  \DB::table('power_wash as pw')
                ->leftjoin('settings_hydrant as sh1','sh1.id','=','pw.gate')
                ->leftjoin('settings_hydrant as sh2','sh2.id','=','pw.pit')
                ->leftjoin('grading_result as gr','gr.id','=','pw.annual_cleaning')
                ->where('pw.status',0)
                ->select('pw.*','sh1.gate as sh_gate','sh2.pit as sh_pit','gr.result','gr.color')
                ->orderby('pw.created_at','DESC')
                ->get();
        else
            return \DB::table('power_wash as pw')
                ->leftjoin('settings_hydrant as sh1','sh1.id','=','pw.gate')
                ->leftjoin('settings_hydrant as sh2','sh2.id','=','pw.pit')
                ->leftjoin('grading_result as gr','gr.id','=','pw.annual_cleaning')
                ->where('pw.status',1)
                ->whereDate('pw.checked_at',$this->date)
                ->select('pw.*','sh1.gate as sh_gate','sh2.pit as sh_pit','gr.result','gr.color')
                ->orderby('pw.created_at','DESC')
                ->get();
    }

    public function headings(): array
    {
        return array_keys((array) \DB::table('power_wash as pw')
            ->leftjoin('settings_hydrant as sh1','sh1.id','=','pw.gate')
            ->leftjoin('settings_hydrant as sh2','sh2.id','=','pw.pit')
            ->leftjoin('grading_result as gr','gr.id','=','pw.annual_cleaning')
            ->where('pw.status',0)
            ->select('pw.*','sh1.gate as sh_gate','sh2.pit as sh_pit','gr.result','gr.color')
            ->first());
    }
}