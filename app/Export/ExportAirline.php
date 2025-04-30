<?php

namespace App\Export;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportAirline implements FromCollection,WithHeadings {
    private $report;
    private $date;
    public function __construct($report,$date) {
        $this->report = $report;
        $this->date = $date==''?date('Y-m-d'):$date;
    }

    public function collection()
    {
        if($this->report == '0')
            return  \DB::table('airline_water_test_record as ar')
                ->LeftJoin('settings_airline as sa','sa.id','=','ar.airline')
                ->select(\DB::raw('DATE_FORMAT(ar.date, "%Y-%m-%d") as fdate'),'ar.*','sa.logo as sa_logo')
                ->where('ar.status',0)
                ->orderby('created_at','DESC')
                ->get();
        else
            return \DB::table('airline_water_test_record as ar')
                ->LeftJoin('settings_airline as sa','sa.id','=','ar.airline')
                ->select(\DB::raw('DATE_FORMAT(ar.date, "%Y-%m-%d") as fdate'),'ar.*','sa.logo as sa_logo')
                ->where('ar.status',1)
                ->whereDate('ar.date',$this->date)
                ->orderby('ar.created_at','DESC')
                ->get();
    }

    public function headings(): array
    {
        return array_keys((array) \DB::table('airline_water_test_record as ar')
            ->LeftJoin('settings_airline as sa','sa.id','=','ar.airline')
            ->select(\DB::raw('DATE_FORMAT(ar.date, "%Y-%m-%d") as fdate'),'ar.*','sa.logo as sa_logo')
            ->where('ar.status',0)
            ->orderby('created_at','DESC')
            ->first());
    }
}