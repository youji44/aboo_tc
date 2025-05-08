<?php

namespace App\Http\Controllers;

use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class Utils
{
    public static function count(): array
    {
        $pid = Session::get('p_loc');
        return [];
    }

    public static function get_recorded_count($query, $date, $userid, $pending)
    {
        return $query->when($date!='', function($query) use ($date) {
            $query->whereDate('a.date', $date);
        })->when($userid!='', function($query) use ($userid) {
            $query->where('a.user_id', $userid);
        })->when(!$pending, function($query) use ($userid) {
            $query->where('a.status', '<', 2);
        }, function ($query) {
            $query->where('a.status', 0);
        })->count();
    }

    public static function convert_base64($url): string
    {
        try{
            $type = pathinfo($url, PATHINFO_EXTENSION);
            $data = file_get_contents($url);
            return 'data:image/' . $type . ';base64,' . base64_encode($data);
        }catch (\Exception $e){
            Log::info($e->getMessage());
            return '';
        }
    }

    public static function logo(): string
    {
        try{
            $url = public_path().'/mark.png';
            $type = pathinfo($url, PATHINFO_EXTENSION);
            $data = file_get_contents($url);
            return 'data:image/' . $type . ';base64,' . base64_encode($data);
        }catch (\Exception $e){
            Log::info($e->getMessage());
            return '';
        }
    }

    public static function get_location(): array
    {

        $user_locations = DB::table('user_locations')
            ->where('user_id', Sentinel::getUser()->id)
            ->first();
        $ids = array();
        if ($user_locations != null) {
            $ids = json_decode($user_locations->location_ids);
        }
        $locations = array();
        $plocations = DB::table('primary_location')
            ->where('status','<',2)
            ->orderBy('location')
            ->get();

        foreach ($plocations as $item) {
            if (in_array($item->id, $ids) || Sentinel::inRole('superadmin'))
                $locations[] = $item;
        }

        return $locations;
    }

    public static function form_item($type): string
    {
        return self::form_items()[$type];
    }

    public static function form_items(): array
    {
        return [
            '0'=>'Date and Time',
            '1'=>'Number Field',
            '2'=>'Text Field',
            '3'=>'Text Area',
            '4'=>'Multiple Choice',
            '5'=>'Image Uploader',
            '6'=>'Condition',
        ];
    }

}
