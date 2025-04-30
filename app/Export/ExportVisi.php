<?php

namespace App\Export;
use App\Models\AirlineWater;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportVisi implements FromCollection,WithHeadings {
    private $report;
    private $date;
    public function __construct($report,$date) {
        $this->report = $report;
        $this->date = $date==''?date('Y-m-d'):$date;
    }
    public function collection()
    {
        if($this->report == '0')
        return \DB::table('visi_jar_cleaning as vc')
            ->LeftJoin('grading_result as gr','gr.id','=','vc.visi_jar_condition')
            ->LeftJoin('fuel_equipment as fe','fe.id','=','vc.unit')
            ->select('vc.*','gr.grade as gr_grade','gr.color as gr_color','fe.unit as fe_unit')
            ->get();
        else return \DB::table('visi_jar_cleaning as vc')
            ->LeftJoin('grading_result as gr','gr.id','=','vc.visi_jar_condition')
            ->LeftJoin('fuel_equipment as fe','fe.id','=','vc.unit')
            ->select('vc.*','gr.grade as gr_grade','gr.color as gr_color','fe.unit as fe_unit')
            ->where('vc.status',1)
            ->whereDate('vc.checked_at',$this->date)
            ->orderby('vc.created_at','DESC')
            ->get();
    }

    public function headings(): array
    {
        // TODO: Implement headings() method.
        return array_keys((array)\DB::table('visi_jar_cleaning as vc')
            ->LeftJoin('grading_result as gr','gr.id','=','vc.visi_jar_condition')
            ->LeftJoin('fuel_equipment as fe','fe.id','=','vc.unit')
            ->select('vc.*','gr.grade as gr_grade','gr.color as gr_color','fe.unit as fe_unit')->first());
    }
}