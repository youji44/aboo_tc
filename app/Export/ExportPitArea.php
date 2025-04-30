<?php

namespace App\Export;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportPitArea implements FromCollection,WithHeadings {
    private $report;
    private $date;
    public function __construct($report,$date) {
        $this->report = $report;
        $this->date = $date==''?date('Y-m-d'):$date;
    }
    public function collection()
    {
        if($this->report == '0')
        return \DB::table('pit_area as pa')
            ->LeftJoin('settings_pit as sp','sp.id','=','pa.pit')
            ->select('pa.*','sp.pit as sp_pit')
            ->orderby('pa.created_at','DESC')
            ->get();
        else return \DB::table('pit_area as pa')
            ->where('pa.status',1)
            ->whereDate('pa.date',$this->date)
            ->select('pa.*')
            ->orderby('pa.created_at','DESC')
            ->get();
    }

    public function headings(): array
    {
        // TODO: Implement headings() method.
        return array_keys((array)\DB::table('pit_area as pa')
            ->LeftJoin('settings_pit as sp','sp.id','=','pa.pit')
            ->select('pa.*','sp.pit as sp_pit')->first());
    }
}