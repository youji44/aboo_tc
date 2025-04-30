<?php namespace App\Http\Controllers\Setting;

use App\Http\Controllers\WsController;
use App\Models\QcfSettingsIncidentNotification;
use App\Models\QcfSettingsIncidentType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class QcfIncidentSettingController extends WsController
{

    /**
     * Settings Incident Type & Notification
     */
    public function index(Request $request)
    {
        $pid = Session::get('p_loc');
        $incident_types = DB::table('qcf_settings_incident_type as st')
            ->where('st.status','<',2)
            ->orderBy('st.type')
            ->select('st.id','st.type','st.color','st.forms')
            ->get();
        foreach ($incident_types as $item){
            $forms_id = json_decode($item->forms)??[];
            $forms  = DB::table('qcf_settings_incident_forms as st')
                ->where('st.status','<',2)
                ->whereIn('st.id',$forms_id)
                ->select('st.form_name')
                ->orderBy('st.form_name')
                ->pluck('st.form_name')->toArray();
            $item->forms = implode(', ', $forms);
        }

        $incident_notifications = DB::table('qcf_settings_incident_notification')
            ->where('status','<',2)
            ->orderBy('id')
            ->get();

        $incident_locations = DB::table('qcf_settings_incident_locations as sl')
            ->leftJoin('primary_location as pl','pl.id','=','sl.pid')
            ->where('sl.status','<',2)
            ->orderBy('sl.location_name')
            ->select('sl.*','pl.location as p_location_name')
            ->get();

        $incident_departments = DB::table('qcf_settings_incident_departments')
            ->where('status','<',2)
            ->orderBy('id')
            ->get();

        $incident_forms = DB::table('qcf_settings_incident_forms as sf')
            ->leftJoin('qcf_settings_incident_forms_details as sd', 'sd.form_id', '=', 'sf.id')
            ->where('sf.status', '<', 2)
            ->groupBy('sf.id', 'sf.form_name') // Group by the right columns
            ->select('sf.id', 'sf.form_name', DB::raw('count(sd.id) as count'))
            ->orderBy('sf.id')
            ->get();

        return view('settings.qcf.incident.index', compact('incident_types',
            'incident_notifications',
            'incident_locations',
            'incident_departments',
            'incident_forms',
        ));
    }

    public function type_edit($id)
    {
        try{
            $incident_type = DB::table('qcf_settings_incident_type')
                ->where('id',$id)
                ->where('status','<',2)
                ->first();

            $forms = DB::table('qcf_settings_incident_forms')
                ->where('status','<',2)
                ->get();

        }catch (\Exception $e){
            Log::info($e->getMessage());
        }
        return view('settings.qcf.incident.type_edit', compact('incident_type','forms'));
    }
    /**
     *
     */
    public function type_save(Request $request)
    {
        $user_id = '';
        $user_name = '';
        if(Sentinel::check()) {
            $user_id = Sentinel::getUser()->id;
            $user_name = Sentinel::getUser()->name;
        }

        $id = $request->get('id');
        $type = $request->get('type');
        $details = $request->get('details');
        $color = $request->get('color');
        $forms = $request->get('forms',[]);

        try {
            DB::beginTransaction();
            if($id){
                DB::table('qcf_settings_incident_type')->where('id',$id)->update([
                    'type'=>$type,
                    'color'=>$color,
                    'details'=>$details,
                    'forms'=>json_encode($forms),
                ]);
            }else{
                $db = new QcfSettingsIncidentType();
                $db->user_id = $user_id;
                $db->user_name = $user_name;
                $db->pid = Session::get('p_loc');
                $db->type = $type;
                $db->color = $color;
                $db->details = $details;
                $db->forms = json_encode($forms);
                $db->save();
            }
            DB::commit();
            return Redirect::route('qcf.settings.incident')->with('success', "Successful Added!");

        }catch(\Exception $e){
            DB::rollBack();
            Log::info($e->getMessage());
            return Redirect::route('qcf.settings.incident')->with('error', "Failed Adding");
        }
    }

    public function type_delete(Request $request)
    {
        $id = $request->get('id');
        if(DB::table('qcf_settings_incident_type')->where('id',$id)->update(['status'=>2]))
            return Redirect::route('qcf.settings.incident')->with('success', 'Successful Deleted!');
        else
            return Redirect::route('qcf.settings.incident')->with('error', 'Failed Deleting!');
    }

    /**
     * QCF incident notification
     */

    public function notification_edit($id)
    {
        try{
            $incident_notification = DB::table('qcf_settings_incident_notification')
                ->where('id',$id)
                ->where('status','<',2)
                ->first();

        }catch (\Exception $e){
            Log::info($e->getMessage());
        }
        return view('settings.qcf.incident.notification_edit', compact('incident_notification'));
    }
    /**
     *
     */
    public function notification_save(Request $request)
    {
        $user_id = '';
        $user_name = '';
        if(Sentinel::check()) {
            $user_id = Sentinel::getUser()->id;
            $user_name = Sentinel::getUser()->name;
        }

        $id = $request->get('id');
        $notification = $request->get('notification');

        try {
            DB::beginTransaction();
            if($id){
                DB::table('qcf_settings_incident_notification')->where('id',$id)->update([
                    'notification'=>$notification,
                ]);
            }else{
                $db = new QcfSettingsIncidentNotification();
                $db->user_id = $user_id;
                $db->user_name = $user_name;
                $db->pid = Session::get('p_loc');
                $db->notification = $notification;
                $db->save();
            }
            DB::commit();
            return Redirect::route('qcf.settings.incident')->with('success', "Successful Added!");

        }catch(\Exception $e){
            DB::rollBack();
            Log::info($e->getMessage());
            return Redirect::route('qcf.settings.incident')->with('error', "Failed Adding");
        }
    }
    public function notification_delete(Request $request)
    {
        $id = $request->get('id');
        if (DB::table('qcf_settings_incident_notification')->where('id', $id)->update(['status' => 2]))
            return Redirect::back()->with('success', 'Successful Deleted!');
        else
            return Redirect::back()->with('error', 'Failed Deleting!');
    }

    /**
     * QCF incident Locations
     */

    public function location_edit($id)
    {
        try{
            $incident_location = DB::table('qcf_settings_incident_locations')
                ->where('id',$id)
                ->where('status','<',2)
                ->first();

            $locations = DB::table('primary_location')
                ->where('status','<', 2)
                ->select('id','location')
                ->get();

        }catch (\Exception $e){
            Log::info($e->getMessage());
        }
        return view('settings.qcf.incident.location_edit', compact('incident_location','locations'));
    }
    /**
     *
     */
    public function location_save(Request $request)
    {
        $user_id = '';
        $user_name = '';
        if(Sentinel::check()) {
            $user_id = Sentinel::getUser()->id;
            $user_name = Sentinel::getUser()->name;
        }
        $id = $request->get('id');
        $pid = $request->get('pid');
        $location_name = $request->get('location_name');
        $location_code = $request->get('location_code');
        $location_latitude = $request->get('location_latitude');
        $location_longitude = $request->get('location_longitude');

        try {
            DB::beginTransaction();
            if ($id) {
                DB::table('qcf_settings_incident_locations')->where('id', $id)->update([
                    'pid' => $pid,
                    'location_name' => $location_name,
                    'location_code' => $location_code,
                    'location_latitude' => $location_latitude,
                    'location_longitude' => $location_longitude,
                ]);
            }
            else
            {
                DB::table('qcf_settings_incident_locations')->insert([
                    'user_id' => $user_id,
                    'user_name' => $user_name,
                    'pid' => $pid,
                    'location_name' => $location_name,
                    'location_code' => $location_code,
                    'location_latitude' => $location_latitude,
                    'location_longitude' => $location_longitude,
                ]);
            }
            DB::commit();
            return Redirect::route('qcf.settings.incident')->with('success', "Successful Added!");

        }catch(\Exception $e){
            DB::rollBack();
            Log::info($e->getMessage());
            return Redirect::route('qcf.settings.incident')->with('error', "Failed Adding");
        }
    }
    public function location_delete(Request $request)
    {
        $id = $request->get('id');
        if (DB::table('qcf_settings_incident_locations')->where('id', $id)->update(['status' => 2]))
            return Redirect::back()->with('success', 'Successful Deleted!');
        else
            return Redirect::back()->with('error', 'Failed Deleting!');
    }

    /**
     * QCF incident Departments
     */

    public function department_edit($id)
    {
        try{
            $incident_department = DB::table('qcf_settings_incident_departments')
                ->where('id',$id)
                ->where('status','<',2)
                ->first();

        }catch (\Exception $e){
            Log::info($e->getMessage());
        }
        return view('settings.qcf.incident.department_edit', compact('incident_department'));
    }
    /**
     *
     */
    public function department_save(Request $request)
    {
        $user_id = '';
        $user_name = '';
        if(Sentinel::check()) {
            $user_id = Sentinel::getUser()->id;
            $user_name = Sentinel::getUser()->name;
        }

        $id = $request->get('id');
        $department_name = $request->get('department_name');

        try {
            DB::beginTransaction();
            if ($id) {
                DB::table('qcf_settings_incident_departments')->where('id', $id)->update([
                    'department_name' => $department_name,
                ]);
            }
            else
            {
                DB::table('qcf_settings_incident_departments')->insert([
                    'user_id' => $user_id,
                    'user_name' => $user_name,
                    'pid' => Session::get('p_loc'),
                    'department_name' => $department_name,
                ]);
            }
            DB::commit();
            return Redirect::route('qcf.settings.incident')->with('success', "Successful Added!");

        }catch(\Exception $e){
            DB::rollBack();
            Log::info($e->getMessage());
            return Redirect::route('qcf.settings.incident')->with('error', "Failed Adding");
        }
    }
    public function department_delete(Request $request)
    {
        $id = $request->get('id');
        if (DB::table('qcf_settings_incident_departments')->where('id', $id)->update(['status' => 2]))
            return Redirect::back()->with('success', 'Successful Deleted!');
        else
            return Redirect::back()->with('error', 'Failed Deleting!');
    }


    /**
     * QCF incident forms
     */

    public function forms_edit($id)
    {
        try{
            $incident_forms = DB::table('qcf_settings_incident_forms')
                ->where('id',$id)
                ->where('status','<',2)
                ->first();

        }catch (\Exception $e){
            Log::info($e->getMessage());
        }
        return view('settings.qcf.incident.forms_edit', compact('incident_forms'));
    }
    public function forms_save(Request $request)
    {
        $user_id = '';
        $user_name = '';
        if(Sentinel::check()) {
            $user_id = Sentinel::getUser()->id;
            $user_name = Sentinel::getUser()->name;
        }

        $id = $request->get('id');
        $form_name = $request->get('form_name');

        try {
            DB::beginTransaction();
            if ($id) {
                DB::table('qcf_settings_incident_forms')->where('id', $id)->update([
                    'form_name' => $form_name,
                ]);
            }
            else
            {
                DB::table('qcf_settings_incident_forms')->insert([
                    'user_id' => $user_id,
                    'user_name' => $user_name,
                    'pid' => Session::get('p_loc'),
                    'form_name' => $form_name,
                ]);
            }
            DB::commit();
            return Redirect::route('qcf.settings.incident')->with('success', "Successful Added!");

        }catch(\Exception $e){
            DB::rollBack();
            Log::info($e->getMessage());
            return Redirect::route('qcf.settings.incident')->with('error', "Failed Adding");
        }
    }
    public function forms_delete(Request $request)
    {
        $id = $request->get('id');
        if (DB::table('qcf_settings_incident_forms')->where('id', $id)->update(['status' => 2]))
            return Redirect::back()->with('success', 'Successful Deleted!');
        else
            return Redirect::back()->with('error', 'Failed Deleting!');
    }

    public function forms_details($id)
    {
        try{
            $forms_details = DB::table('qcf_settings_incident_forms_details')
                ->where('form_id',$id)
                ->where('status','<',2)
                ->get();
            $form = DB::table('qcf_settings_incident_forms')->where('id',$id)->select('id','form_name')->first();

            return view('settings.qcf.incident.forms_index', compact('forms_details','form'));

        }catch (\Exception $e){
            Log::info($e->getMessage());
            return Redirect::back()->with('error','Failed!');
        }
    }

    public function forms_manage_edit($id, Request $request)
    {
        try{
            $forms_detail = DB::table('qcf_settings_incident_forms_details')
                ->where('id',$id)
                ->where('status','<',2)
                ->first();
            $form_id = $request->get('fid');

            $form_details_options = DB::table('qcf_settings_incident_forms_details_options')
                ->where('form_detail_id',$id)
                ->select('form_detail_id','value','name')
                ->get();

            return view('settings.qcf.incident.forms_manage', compact('forms_detail','form_id', 'form_details_options'));

        }catch (\Exception $e){
            Log::info($e->getMessage());
            return Redirect::back()->with('error','Failed');
        }
    }

    public function forms_manage_save(Request $request)
    {
        $user_id = '';
        $user_name = '';
        if(Sentinel::check()) {
            $user_id = Sentinel::getUser()->id;
            $user_name = Sentinel::getUser()->name;
        }

        $id = $request->get('id');
        $form_id = $request->get('form_id');
        $form_item = $request->get('form_item');
        $description = $request->get('description');
        $input_type = $request->get('input_type');
        $required = $request->get('required')=='on'?1:0;
        $options = $request->get('options', []);

        try {
            DB::beginTransaction();
            if ($id) {
                DB::table('qcf_settings_incident_forms_details')->where('id', $id)->update([
                    'item' => $form_item,
                    'description' => $description,
                    'input_type' => $input_type,
                    'required' => $required,
                ]);

                DB::table('qcf_settings_incident_forms_details_options')->where('form_detail_id',$id)->delete();
                foreach ($options as $key=>$item){
                    DB::table('qcf_settings_incident_forms_details_options')->insert([
                        'form_detail_id' => $id,
                        'value' => $key,
                        'name' => $item,
                    ]);
                }
            }
            else
            {
                $detail_id = DB::table('qcf_settings_incident_forms_details')->insertGetId([
                    'user_id' => $user_id,
                    'user_name' => $user_name,
                    'form_id' => $form_id,
                    'item' => $form_item,
                    'description' => $description,
                    'input_type' => $input_type,
                    'required' => $required,
                ]);

                foreach ($options as $key=>$item){
                    DB::table('qcf_settings_incident_forms_details_options')->insert([
                        'form_detail_id' => $detail_id,
                        'value' => $key,
                        'name' => $item,
                    ]);
                }
            }
            DB::commit();
            return Redirect::back()->with('success', "Successful Added!");

        }catch(\Exception $e){
            DB::rollBack();
            Log::info($e->getMessage());
            return Redirect::back()->with('error', "Failed Adding");
        }
    }
    public function forms_manage_delete(Request $request)
    {
        $id = $request->get('id');
        if (DB::table('qcf_settings_incident_forms_details')->where('id', $id)->delete())
            return Redirect::back()->with('success', 'Successful Deleted!');
        else
            return Redirect::back()->with('error', 'Failed Deleting!');
    }
}
