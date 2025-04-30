<?php

namespace App\Export;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportFire implements FromCollection,WithHeadings {
    private $report;
    private $date;
    public function __construct($report,$date) {
        $this->report = $report;
        $this->date = $date==''?date('Y-m-d'):$date;
    }

    public function collection()
    {
        if($this->report == '0')
            return  \DB::table('fire_extinguisher as fe')
                ->leftjoin('settings_fire as sf','sf.id','=','fe.location_name')
                ->leftjoin('settings_fire_type as sft','sft.id','=','sf.exttype')
                ->leftjoin('grading_result as gr','gr.id','=','fe.condition')
                ->where('fe.status',0)
                ->select('fe.*',
                    'sf.location_name as sf_location_name',
                    'sft.fire_extinguisher_type as sft_exttype',
                    'sf.extid',
                    'sf.size',
                    'sf.quantity',
                    'sf.location_latitude',
                    'sf.location_longitude',
                    'gr.grade',
                    'gr.result',
                    'gr.color'
                )
                ->orderby('fe.date','DESC')
                ->get();
        else
            return \DB::table('fire_extinguisher as fe')
                ->leftjoin('settings_fire as sf','sf.id','=','fe.location_name')
                ->leftjoin('settings_fire_type as sft','sft.id','=','sf.exttype')
                ->leftjoin('grading_result as gr','gr.id','=','fe.condition')
                ->where('fe.status',1)
                ->whereDate('fe.checked_at',$this->date)
                ->select('fe.*',
                    'sf.location_name as sf_location_name',
                    'sft.fire_extinguisher_type as sft_exttype',
                    'sf.extid',
                    'sf.size',
                    'sf.quantity',
                    'sf.location_latitude',
                    'sf.location_longitude',
                    'gr.grade',
                    'gr.result',
                    'gr.color'
                )
                ->orderby('fe.date','DESC')
                ->get();
    }

    public function headings(): array
    {
        return array_keys((array) \DB::table('fire_extinguisher as fe')
            ->leftjoin('settings_fire as sf','sf.id','=','fe.location_name')
            ->leftjoin('settings_fire_type as sft','sft.id','=','sf.exttype')
            ->leftjoin('grading_result as gr','gr.id','=','fe.condition')
            ->where('fe.status',0)
            ->select('fe.*',
                'sf.location_name as sf_location_name',
                'sft.fire_extinguisher_type as sft_exttype',
                'sf.extid',
                'sf.size',
                'sf.quantity',
                'sf.location_latitude',
                'sf.location_longitude',
                'gr.grade',
                'gr.result',
                'gr.color'
            )
            ->first());
    }
}