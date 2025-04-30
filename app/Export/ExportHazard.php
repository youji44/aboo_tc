<?php

namespace App\Export;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportHazard implements FromCollection,WithHeadings {
    private $report;
    private $date;
    public function __construct($report,$date) {
        $this->report = $report;
        $this->date = $date==''?date('Y-m-d'):$date;
    }

    public function collection()
    {
        if($this->report == '0')
            return  \DB::table('hazard_material as hm')
                ->leftjoin('settings_hazard as sh','sh.id','=','hm.task')
                ->select('hm.*','sh.hazard_material_task as sh_task')
                ->where('hm.status',0)
                ->orderby('hm.created_at','DESC')
                ->get();
        else
            return \DB::table('hazard_material as hm')
                ->leftjoin('settings_hazard as sh','sh.id','=','hm.task')
                ->select('hm.*','sh.hazard_material_task as sh_task')
                ->where('hm.status',1)
                ->whereDate('hm.checked_at',$this->date)
                ->orderby('hm.created_at','DESC')
                ->get();
    }

    public function headings(): array
    {
        return array_keys((array) \DB::table('hazard_material as hm')
            ->leftjoin('settings_hazard as sh','sh.id','=','hm.task')
            ->select('hm.*','sh.hazard_material_task as sh_task')
            ->first());
    }
}