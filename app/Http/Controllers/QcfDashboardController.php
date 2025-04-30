<?php namespace App\Http\Controllers;

use Carbon\Carbon;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class QcfDashboardController extends WsController
{
    public function index(Request $request)
    {
        if(Sentinel::inRole('readonly')){
            return Redirect::route('reports');
        }
        try {

            DB::beginTransaction();

            $counts = Utils::count('',true);

            $incident_count = $counts['qcf_incident'];
            $audit_count = $counts['audit'];

            $last7 = array();
            $last7_daily = array();
            $today = date('M d');
            $date = date('Y-m-d');
            $last7[] = $today;


            $total_date = Utils::count($date);
            $last7_daily[] = $total_date['total'];

            for ($i = 0; $i < 6; $i++){
                $today = date('M d', strtotime(' -1 day',strtotime($today)));
                $last7[] = $today;
                $date = date('Y-m-d', strtotime(' -1 day',strtotime($date)));
                $total_date = Utils::count($date);
                $last7_daily[] = $total_date['total'];
            }
            $last7 = array_reverse($last7);
            $last7_daily = array_reverse($last7_daily);

            $record = array();
            $users = DB::table('users as u')
                    ->leftjoin('role_users as ru', 'ru.user_id', '=', 'u.id')
                    ->leftjoin('roles as r', 'r.id', '=', 'ru.role_id')
                    ->leftjoin('activations as a', 'a.user_id', '=', 'u.id')
                ->where('a.completed',1)
                ->where('r.slug','!=','readonly')
                //->where('r.slug','!=','superadmin')
                ->select('u.*')
                ->orderby('u.name','ASC')
                ->get();

            $colors = ['#C8C8C8','#CDCDCD','#D2D2D2','#D7D7D7','#DCDCDC','#E1E1E1','#E6E6E6','#EBEBEB','#F0F0F0','#F5F5F5','#FAFAFA'];
            $pie_color = $colors;
            $total = $this->total()+$this->total(date('Y-m-d', strtotime(' -1 day',strtotime(date('Y-m-d')))));
            $percent = array();
            $i = 0;
            foreach ($users as $user){
                $today = $this->recorded($user->id);
                $yesterday = $this->recorded($user->id,date('Y-m-d', strtotime(' -1 day',strtotime(date('Y-m-d')))));
                if($today > 0 || $yesterday > 0){
                    $rec['user'] = $user->name;
                    $rec['today'] = $today;
                    $rec['yesterday'] = $yesterday;
                    $rec['percent'] = $total==0?0:ceil(($rec['today']+$rec['yesterday'])*100/$total);
                    $rec['color'] = $colors[$i%count($colors)];
                    $record[] = $rec;
                    $percent[] = $rec['percent'];
                    $i++;
                }
            }

            DB::commit();
            return View('dashboard',compact('audit_count','incident_count','last7','last7_daily','record','pie_color','percent'));
        }catch(\Exception $e){
            Log::info($e->getMessage());
            DB::rollBack();
            return back()->with('error', "Loading Failed!");
        }
    }

    private function recorded($userid, $date=''){
        if($date == '') $date = date('Y-m-d');
        $total_date = Utils::count($date, false, $userid);
        $total = $total_date['total'];
        return $total;
    }

    private function total($date=''){
        if($date =='') $date = date('Y-m-d');
        $total_date = Utils::count($date);
        $total = $total_date['total'];
        return $total;
    }

}
