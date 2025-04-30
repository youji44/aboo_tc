<?php

namespace App\Export;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportGasBar implements FromCollection,WithHeadings {
    private $report;
    private $date;
    public function __construct($report,$date) {
        $this->report = $report;
        $this->date = $date==''?date('Y-m-d'):$date;
    }
    public function collection()
    {
        if($this->report == '0')
        return \DB::table('gasbar as g')
            ->LeftJoin('settings_gasbar as sg','sg.id','=','g.gasbar')
            ->select('g.*','sg.gasbar_task')
            ->orderby('g.created_at','DESC')
            ->get();
        else return \DB::table('gasbar as g')
            ->where('g.status',1)
            ->whereDate('g.date',$this->date)
            ->orderby('g.created_at','DESC')
            ->get();
    }

    public function headings(): array
    {
        // TODO: Implement headings() method.
        return array_keys((array)\DB::table('gasbar as g')
            ->LeftJoin('settings_gasbar as sg','sg.id','=','g.gasbar')
            ->select('g.*','sg.gasbar_task')->first());
    }
}