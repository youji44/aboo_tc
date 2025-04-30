<?php

namespace App\Export;
use App\Models\AirlineWater;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportEye implements FromCollection,WithHeadings {
    private $report;
    private $date;
    public function __construct($report,$date) {
        $this->report = $report;
        $this->date = $date==''?date('Y-m-d'):$date;
    }
    public function collection()
    {
        if($this->report == '0')
        return \DB::table('eye_wash_inspection as ei')
            ->LeftJoin('fuel_equipment as fe','fe.id','=','ei.unit')
            ->select('ei.*','fe.unit as fe_unit')
            ->get();
        else return \DB::table('eye_wash_inspection as ei')
            ->LeftJoin('fuel_equipment as fe','fe.id','=','ei.unit')
            ->select('ei.*','fe.unit as fe_unit')
            ->where('ei.status',1)
            ->whereDate('ei.checked_at',$this->date)
            ->orderby('ei.created_at','DESC')
            ->get();
    }

    public function headings(): array
    {
        // TODO: Implement headings() method.
        return array_keys((array)\DB::table('eye_wash_inspection as ei')
            ->LeftJoin('fuel_equipment as fe','fe.id','=','ei.unit')
            ->select('ei.*','fe.unit as fe_unit')->first());
    }
}