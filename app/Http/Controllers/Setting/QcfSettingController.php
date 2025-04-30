<?php namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Utils;
use App\Http\Controllers\WsController;
use App\Models\PrimaryLocation;
use App\Models\QcfSettingsFuelMonthly;
use App\Models\QcfSettingsFuelQuarterly;
use App\Models\QcfSettingsPointOfCategory;
use App\Models\QcfSettingsPointOfFleet;
use App\Models\QcfSettingsPointOfTask;
use App\Models\SettingsFuelMonthly;
use App\Models\SettingsFuelQuarterly;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class QcfSettingController extends WsController
{
    /**
     * index, add, save, delete, update
     */
    /**
     * Settings Fuel Monthly
     */
    public function fuel_monthly_index(Request $request)
    {

        $fuel_monthly = DB::table('fuel_equipment as fe')
            ->leftJoin('qcf_settings_fuel_monthly as sw','sw.unit','=','fe.id')
            ->select('fe.id as fe_id','fe.unit as fe_unit',Utils::unit_type(),'sw.*')
            ->where('fe.status','<',2)
            ->where('fe.fuel_equipment_monthly',1)
            ->orderBy('fe.unit','asc')
            ->get();

        return view('settings.qcf.fuel_monthly.index', compact('fuel_monthly'));
    }

    public function fuel_monthly_edit($id)
    {
        try{
            if(!$fuel_monthly = DB::table('fuel_equipment as fe')
                ->leftJoin('qcf_settings_fuel_monthly as sw','sw.unit','=','fe.id')
                ->select('fe.id as fe_id','fe.unit as fe_unit','sw.*')
                ->where('fe.id',$id)
                ->where('fe.status','<',2)
                ->first()){
                return 'Error';
            }
        }catch (\Exception $e){
            Log::info($e->getMessage());
        }
        return view('settings.qcf.fuel_monthly.edit', compact('fuel_monthly'));
    }

    /**
     *
     */
    public function fuel_monthly_save(Request $request)
    {
        $user_id = '';
        $user_name = '';
        if(Sentinel::check()) {
            $user_id = Sentinel::getUser()->id;
            $user_name = Sentinel::getUser()->name;
        }

        $unit = $request->get('unit_id');
        $button1 = $request->get('button1')=='on'?1:0;
        $button2 = $request->get('button2')=='on'?1:0;
        $button3 = $request->get('button3')=='on'?1:0;
        $hose_deadman = $request->get('hose_deadman')=='on'?1:0;
        $lift_deadman = $request->get('lift_deadman')=='on'?1:0;
        $lift_platform = $request->get('lift_platforms')=='on'?1:0;
        $water_sensor = $request->get('water_sensor')=='on'?1:0;

        try {
            DB::beginTransaction();
            if($fuel = DB::table('qcf_settings_fuel_monthly')->where('unit',$unit)->first()){
                DB::table('qcf_settings_fuel_monthly')->where('unit',$unit)->update([
                    'button1'=>$button1,
                    'button2'=>$button2,
                    'button3'=>$button3,
                    'hose_deadman'=>$hose_deadman,
                    'lift_deadman'=>$lift_deadman,
                    'lift_platforms'=>$lift_platform,
                    'water_sensor'=>$water_sensor,
                ]);
            }else{
                $db = new QcfSettingsFuelMonthly();
                $db->user_id = $user_id;
                $db->user_name = $user_name;
                $db->pid = Session::get('p_loc');
                $db->unit = $unit;
                $db->button1 = $button1;
                $db->button2 = $button2;
                $db->button3 = $button3;
                $db->hose_deadman = $hose_deadman;
                $db->lift_deadman = $lift_deadman;
                $db->lift_platforms = $lift_platform;
                $db->water_sensor = $water_sensor;

                $db->save();
            }
            DB::commit();
            return Redirect::route('qcf.settings.fuel_monthly')->with('success', "Successful Added!");

        }catch(\Exception $e){
            DB::rollBack();
            Log::info($e->getMessage());
            return Redirect::route('qcf.settings.fuel_monthly')->with('error', "Failed Adding");
        }
    }

    /**
     * Settings Fuel Quarterly
     */
    public function fuel_quarterly_index(Request $request)
    {
        $fuel_quarterly = DB::table('fuel_equipment as fe')
            ->leftJoin('qcf_settings_fuel_quarterly as sw','sw.unit','=','fe.id')
            ->select('fe.id as fe_id','fe.unit as fe_unit',Utils::unit_type(),'sw.*')
            ->where('fe.status','<',2)
            ->orderBy('fe.unit','asc')
            ->get();

        return view('settings.qcf.fuel_quarterly.index', compact('fuel_quarterly'));
    }

    public function fuel_quarterly_edit($id)
    {
        try{
            if(!$fuel_quarterly = DB::table('fuel_equipment as fe')
                ->leftJoin('qcf_settings_fuel_quarterly as sw','sw.unit','=','fe.id')
                ->select('fe.id as fe_id','fe.unit as fe_unit','sw.*')
                ->where('fe.id',$id)
                ->where('fe.status','<',2)
                ->first()){
                return 'Sorry, there is some Errors';
            }
        }catch (\Exception $e){
            Log::info($e->getMessage());
        }

        return view('settings.qcf.fuel_quarterly.edit', compact('fuel_quarterly'));
    }

    public function fuel_quarterly_save(Request $request)
    {
        $user_id = '';
        $user_name = '';
        if(Sentinel::check()) {
            $user_id = Sentinel::getUser()->id;
            $user_name = Sentinel::getUser()->name;
        }

        $unit = $request->get('unit_id');
        $water_check = $request->get('water_check')=='on'?1:0;
        $valve_check = $request->get('valve_check')=='on'?1:0;

        try {
            DB::beginTransaction();

            if($fuel = DB::table('qcf_settings_fuel_quarterly')->where('unit',$unit)->first()){
                DB::table('qcf_settings_fuel_quarterly')->where('unit',$unit)->update([
                    'water_check'=>$water_check,
                    'valve_check'=>$valve_check,
                ]);
            }else{
                $db = new QcfSettingsFuelQuarterly();
                $db->user_id = $user_id;
                $db->user_name = $user_name;
                $db->plocation_id = Session::get('p_loc');
                $db->unit = $unit;
                $db->water_check = $water_check;
                $db->valve_check = $valve_check;
                $db->save();
            }
            DB::commit();
            return Redirect::route('qcf.settings.fuel_quarterly')->with('success', "Successful Added!");

        }catch(\Exception $e){
            DB::rollBack();
            Log::info($e->getMessage());
            return Redirect::route('qcf.settings.fuel_quarterly')->with('error', "Failed Adding");
        }
    }

    /**
     * QCF Point of Inspections
     */
    /**
     * Maintenance Preventative
     * index, add, save, delete, update
     */

    public function prevent_index(Request $request)
    {
        $mode = $request->get('mode','task');
        $prevent = DB::table('qcf_settings_pointof_task as st')
            ->leftJoin('qcf_settings_pointof_category as sc','sc.id','=','st.category_id')
            ->leftJoin('primary_location as pl','pl.id','=','st.plocation_id')
            ->where('st.status','<',2)
            ->select('st.*','sc.category','pl.location')
            ->get();
        return view('settings.qcf.pointof.index', compact('prevent','mode'));
    }

    public function prevent_edit($id)
    {
        try{
            $locations  = DB::table('primary_location')->where('status','<',2)->orderBy('location')->get();
            $category  = DB::table('qcf_settings_pointof_category')->where('status','<',2)->orderBy('category')->get();

            $prevent = '';
            if($id != '0' && !$prevent = DB::table('qcf_settings_pointof_task')
                    ->where('id',$id)
                    ->where('status','<',2)
                    ->first()){
                return 'There is no data.';
            }

        }catch (\Exception $e){
            Log::info($e->getMessage());
        }
        return view('settings.qcf.pointof.edit', compact('prevent','locations','category'));
    }

    /**
     *
     */
    public function prevent_save(Request $request)
    {
        $user_id = '';
        $user_name = '';
        if(Sentinel::check()) {
            $user_id = Sentinel::getUser()->id;
            $user_name = Sentinel::getUser()->name;
        }

        $id = $request->get('id');
        $pid = $request->get('pid');
        $category_id = $request->get('category');
        $task = $request->get('task');

        try {
            DB::beginTransaction();
            if($id){
                DB::table('qcf_settings_pointof_task')->where('id',$id)->update([
                    'plocation_id'=>$pid,
                    'category_id'=>$category_id,
                    'task'=>$task,
                ]);
            }else{
                $db = new QcfSettingsPointOfTask();
                $db->user_id = $user_id;
                $db->user_name = $user_name;
                $db->plocation_id = $pid;
                $db->category_id = $category_id;
                $db->task = $task;
                $db->save();
            }
            DB::commit();
            return Redirect::route('qcf.settings.pointof.task')->with('success', "Successful Added!");

        }catch(\Exception $e){
            DB::rollBack();
            Log::info($e->getMessage());
            return Redirect::route('qcf.settings.pointof.task')->with('error', "Failed Adding");
        }
    }

    public function prevent_delete(Request $request)
    {
        $id = $request->get('id');
        if(DB::table('qcf_settings_pointof_task')->where('id',$id)->update(['status'=>2]))
            return Redirect::route('qcf.settings.pointof.task')->with('success', 'Successful Deleted!');
        else
            return Redirect::route('qcf.settings.pointof.task')->with('error', 'Failed Deleting!');
    }

    /**
     * Maintenance Preventative -  Assign fleet
     * index, add, save, delete, update
     */

    public function fleet_index(Request $request)
    {
        $mode = $request->get('mode','fleet');
        $fuel_equipment = DB::table('fuel_equipment')
            ->where('status','<',2)
            ->orderBy('unit')
            ->select('id','unit',Utils::unit_type())
            ->get();

        $prevent_category = DB::table('qcf_settings_pointof_category as sc')
            ->where('sc.status','<',2)
            ->select('sc.id','sc.category')
            ->get();

        foreach ($fuel_equipment as $item){
            $prevent_fleet = DB::table('qcf_settings_pointof_category as sc')
                ->leftJoin('qcf_settings_pointof_fleet as sf','sf.category_id','=','sc.id')
                ->where('sc.status','<',2)
                ->where('sf.unit_id',$item->id)
                ->select('sc.id','sf.selected','sc.category')
                ->get();
            $item->prevent_fleet = $prevent_fleet;
        }

        return view('settings.qcf.pointof.fleet', compact('prevent_category','fuel_equipment','mode'));
    }

    public function fleet_edit($id)
    {
        try{
            if( !$fuel_equipment = DB::table('fuel_equipment')
                ->where('status','<',2)
                ->where('id',$id)
                ->orderBy('unit')
                ->select('id','unit',Utils::unit_type())->first()){
                return 'There is no data.';
            }

            $prevent_category = DB::table('qcf_settings_pointof_category as sc')
                ->where('sc.status','<',2)
                ->select('sc.id','sc.category')
                ->get();

            $prevent_fleet = DB::table('qcf_settings_pointof_category as sc')
                ->leftJoin('qcf_settings_pointof_fleet as sf','sf.category_id','=','sc.id')
                ->where('sc.status','<',2)
                ->where('sf.unit_id',$id)
                ->select('sc.id','sf.selected','sc.category')
                ->get();

            $fuel_equipment->prevent_fleet = $prevent_fleet;

        }catch (\Exception $e){
            Log::info($e->getMessage());
        }
        return view('settings.qcf.pointof.fleet_edit', compact('fuel_equipment','prevent_category'));
    }

    /**
     *
     */
    public function fleet_update(Request $request)
    {
        $user_id = '';
        $user_name = '';
        if(Sentinel::check()) {
            $user_id = Sentinel::getUser()->id;
            $user_name = Sentinel::getUser()->name;
        }
        $id = $request->get('id');

        try {
            DB::beginTransaction();

            $fleet = QcfSettingsPointOfFleet::where('unit_id',$id)
                ->where('status','<',2)
                ->get();

            $prevent_category = QcfSettingsPointOfCategory::where('status','<',2)->get();

            if(count($fleet) > 0){
                QcfSettingsPointOfFleet::where('unit_id',$id)->delete();
            }

            foreach ($prevent_category as $item){
                $selected = $request->get('cat_'.$item->id)=='on'?1:0;
                $db = new QcfSettingsPointOfFleet();
                $db->user_id = $user_id;
                $db->user_name = $user_name;
                $db->unit_id = $id;
                $db->category_id = $item->id;
                $db->selected = $selected;
                $db->save();
            }

            DB::commit();
            return Redirect::route('qcf.settings.pointof.fleet')->with('success', "Successful Added!");

        }catch(\Exception $e){
            DB::rollBack();
            Log::info($e->getMessage());
            return Redirect::route('qcf.settings.pointof.fleet')->with('error', "Failed Adding");
        }
    }

    /**
     * Maintenance Preventative
     * index, add, save, delete, update
     */

    public function category_index(Request $request)
    {
        $mode = $request->get('mode','cat');
        $category = DB::table('qcf_settings_pointof_category as st')
            ->where('st.status','<',2)
            ->select('st.*')
            ->get();

        return view('settings.qcf.pointof.category', compact('category','mode'));
    }

    public function category_edit($id)
    {
        try{

            $category = null;
            if($id != '0'){
                if(!$category = DB::table('qcf_settings_pointof_category')
                    ->where('id',$id)
                    ->where('status','<',2)
                    ->first()){
                    return 'There is no data.';
                }
            }

        }catch (\Exception $e){
            Log::info($e->getMessage());
        }
        return view('settings.qcf.pointof.category_edit', compact('category'));
    }

    /**
     *
     */
    public function category_save(Request $request)
    {
        $user_id = '';
        $user_name = '';
        if(Sentinel::check()) {
            $user_id = Sentinel::getUser()->id;
            $user_name = Sentinel::getUser()->name;
        }

        $id = $request->get('id');
        $category = $request->get('category');


        try {
            DB::beginTransaction();
            if($id){
                $current_category = DB::table('qcf_settings_pointof_category')->where('id',$id)->value('category');
                if($category != $current_category && DB::table('qcf_settings_pointof_category')->where('id','!=',$id)
                        ->where('category', $category)->where('status','<',2)->count() > 0){
                    return Redirect::route('qcf.settings.pointof.category')->with('warning','Category exist already.')->withInput($request->input());
                }

                DB::table('qcf_settings_pointof_category')->where('id',$id)->update([
                    'plocation_id'=>Session::get('p_loc'),
                    'category'=>$category,
                ]);
            }else{
                $rule1 = array(
                    'category' => 'required|unique:settings_prevent_category',
                );

                $validator = Validator::make($request->all(), $rule1);
                if ($validator->fails()) {
                    return Redirect::route('qcf.settings.pointof.category')->with('warning','Category exist already.')->withInput($request->input());
                }
                $db = new QcfSettingsPointOfCategory();
                $db->plocation_id = Session::get('p_loc');
                $db->category = $category;
                $db->save();
            }
            DB::commit();
            return Redirect::route('qcf.settings.pointof.category')->with('success', "Successful Added!");

        }catch(\Exception $e){
            DB::rollBack();
            Log::info($e->getMessage());
            return Redirect::route('qcf.settings.pointof.category')->with('error', "Failed Adding");
        }
    }

    public function category_delete(Request $request)
    {
        $id = $request->get('id');
        if(DB::table('qcf_settings_pointof_category')->where('id',$id)->update(['status'=>2]))
            return Redirect::route('qcf.settings.pointof.category')->with('success', 'Successful Deleted!');
        else
            return Redirect::route('qcf.settings.pointof.category')->with('error', 'Failed Deleting!');
    }

}
