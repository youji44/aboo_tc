<?php

namespace App\Export;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportCart implements FromCollection,WithHeadings {
    private $report;
    private $date;
    public function __construct($report,$date) {
        $this->report = $report;
        $this->date = $date==''?date('Y-m-d'):$date;
    }

    public function collection()
    {
        if($this->report == '0')
            return \DB::table('hydrant_cart_filter_sump as hc')
                ->LeftJoin('grading_result as gr','gr.id','=','hc.filter_findings')
                ->LeftJoin('grading_result as gr_ed','gr_ed.id','=','hc.eductor_tank_levels')
                ->LeftJoin('fuel_equipment as fe','fe.id','=','hc.unit')
                ->select('hc.*','gr.grade as gr_grade','gr_ed.grade as ed_grade','fe.unit')
                ->get();
        else
            return
                \DB::table('hydrant_cart_filter_sump as hc')
                    ->LeftJoin('grading_result as gr','gr.id','=','hc.filter_findings')
                    ->LeftJoin('grading_result as gr_ed','gr_ed.id','=','hc.eductor_tank_levels')
                    ->LeftJoin('fuel_equipment as fe','fe.id','=','hc.unit')
                    ->where('hc.status',1)
                    ->whereDate('hc.date',$this->date)
                    ->orderby('hc.created_at','DESC')
                    ->select('hc.*','gr.grade as gr_grade','gr.color as gr_color','gr_ed.grade as ed_grade','gr_ed.color as ed_color','fe.unit')
                    ->get();
    }

    public function headings(): array
    {
        // TODO: Implement headings() method.
        return array_keys((array)\DB::table('hydrant_cart_filter_sump as hc')
            ->LeftJoin('grading_result as gr','gr.id','=','hc.filter_findings')
            ->LeftJoin('grading_result as gr_ed','gr_ed.id','=','hc.eductor_tank_levels')
            ->LeftJoin('fuel_equipment as fe','fe.id','=','hc.unit')
            ->select('hc.*','gr.grade as gr_grade','gr_ed.grade as ed_grade','fe.unit')->first());
    }
}