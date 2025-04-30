<?php namespace App\Http\Controllers\Setting;

use App\Http\Controllers\WsController;
use App\Models\SettingsAudit;
use App\Models\SettingsAuditQuestions;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class AuditSettingController extends WsController
{
    /**
     * index, add, save, delete, update
     */

    public function audit_index(Request $request)
    {
        try {
            DB::beginTransaction();
            $audit = DB::table('settings_audit as sa')
                ->leftJoin('primary_location as pl','pl.id','=','sa.plocation_id')
                ->where('sa.status','<',2)
                ->select('sa.*','pl.location')
//                ->orderBy('sa.title','ASC')
                ->get();
            foreach ($audit as $item){
                $item->questions = DB::table('settings_audit_questions')->where('audit_id',$item->id)->count();
            }

            DB::commit();
            return view('settings.audit.index',compact('audit'));
        }catch(\Exception $e){
            DB::rollBack();
            return back()->with('error', "Loading Failed!");
        }
    }

    public function audit_add(Request $request)
    {
        $locations = DB::table('primary_location')->where('status','<',2)->select('id','location')->orderBy('location')->get();
        return View('settings.audit.add',compact('locations'));
    }

    public function audit_edit($id)
    {
        try {
            DB::beginTransaction();
            if(!$audit = DB::table('settings_audit')->where('id',$id)->first()){
                return back()->with('error', "Loading Failed!");
            }
            $locations = DB::table('primary_location')->where('status','<',2)->select('id','location')->orderBy('location')->get();
            DB::commit();
            return view('settings.audit.edit',compact('audit','locations'));
        }catch(\Exception $e){
            Log::info($e->getMessage());
            DB::rollBack();
            return back()->with('error', "Loading Failed!");
        }
    }

    /**
     *
     */
    public function audit_save(Request $request)
    {
        $user_id = '';
        $user_name = '';
        if(Sentinel::check()) {
            $user_id = Sentinel::getUser()->id;
            $user_name = Sentinel::getUser()->name;
        }

        $location = $request->get('plocation_id');
        $title = $request->get('title');

        try {
            DB::beginTransaction();

            $db = new SettingsAudit();
            $db->user_id = $user_id;
            $db->user_name = $user_name;
            $db->plocation_id = $location;
            $db->title = $title;
            $db->save();

            DB::commit();
            return Redirect::route('settings.audit')->with('success', "Successful Added!");

        }catch(\Exception $e){
            DB::rollBack();
            Log::info($e->getMessage());
            return Redirect::route('settings.audit')->with('error', "Failed Adding");
        }
    }

    public function audit_delete(Request $request)
    {
        $id = $request->get('id');
        if(DB::table('settings_audit')->where('id',$id)->update(['status'=>2]))
            return Redirect::route('settings.audit')->with('success', 'Successful Deleted!');
        else
            return Redirect::route('settings.audit')->with('error', 'Failed Deleting!');

    }

    public function audit_update(Request $request)
    {
        $id = $request->get('id');
        $user_id = '';
        $user_name = '';
        if(Sentinel::check()) {
            $user_id = Sentinel::getUser()->id;
            $user_name = Sentinel::getUser()->name;
        }

        $location = $request->get('plocation_id');
        $title = $request->get('title');

        try {
            DB::beginTransaction();

            DB::table('settings_audit')->where('id',$id)->update([
                'user_id' => $user_id,
                'user_name' => $user_name,
                'plocation_id' => $location,
                'title' => $title,
                'updated_at'=> date('Y-m-d H:i:s')
            ]);

            DB::commit();
            return Redirect::route('settings.audit')->with('success', "Successful Updated!");

        }catch(\Exception $e){
            DB::rollBack();
            Log::info($e->getMessage());
            return Redirect::route('settings.audit')->with('error', "Failed Updating");
        }
    }

    public function audit_manage($id)
    {
        try {
            DB::beginTransaction();
            $audit_questions = DB::table('settings_audit_questions')->where('audit_id',$id)
//                ->orderBy('question','ASC')
                ->get();
            $aid = $id;
            DB::commit();
            return view('settings.audit.topic.index',compact('audit_questions','aid'));
        }catch(\Exception $e){
            Log::info($e->getMessage());
            DB::rollBack();
            return back()->with('error', "Loading Failed!");
        }
    }

    public function topic_add(Request $request)
    {
        $aid = $request->get('aid');
        return View('settings.audit.topic.add',compact('aid'));
    }

    public function topic_edit($id,Request $request)
    {
        try {
            DB::beginTransaction();
            $aid = $request->get('aid');
            if(!$audit_question = DB::table('settings_audit_questions')->where('id',$id)->first()){
                return back()->with('error', "Loading Failed!");
            }
            DB::commit();
            return view('settings.audit.topic.edit',compact('audit_question','aid'));
        }catch(\Exception $e){
            Log::info($e->getMessage());
            DB::rollBack();
            return back()->with('error', "Loading Failed!");
        }
    }

    /**
     *
     */
    public function topic_save(Request $request)
    {
        $user_id = '';
        $user_name = '';
        if(Sentinel::check()) {
            $user_id = Sentinel::getUser()->id;
            $user_name = Sentinel::getUser()->name;
        }

        $question = $request->get('question');
        $aid = $request->get('audit_id');
        try {
            DB::beginTransaction();

            $db = new SettingsAuditQuestions();
            $db->user_id = $user_id;
            $db->user_name = $user_name;
            $db->question = $question;
            $db->audit_id = $aid;
            $db->save();

            DB::commit();
            return Redirect::route('settings.audit.manage',$aid)->with('success', "Successful Added!");

        }catch(\Exception $e){
            DB::rollBack();
            Log::info($e->getMessage());
            return Redirect::route('settings.audit.manage',$aid)->with('error', "Failed Adding");
        }
    }

    public function topic_delete(Request $request)
    {
        $id = $request->get('id');
        if(DB::table('settings_audit_questions')->where('id',$id)->delete())
            return Redirect::route('settings.audit.manage',$id)->with('success', 'Successful Deleted!');
        else
            return Redirect::route('settings.audit.manage',$id)->with('error', 'Failed Deleting!');

    }

    public function topic_update(Request $request)
    {
        $id = $request->get('id');
        $user_id = '';
        $user_name = '';
        if(Sentinel::check()) {
            $user_id = Sentinel::getUser()->id;
            $user_name = Sentinel::getUser()->name;
        }

        $question = $request->get('question');
        $aid = $request->get('audit_id');

        try {
            DB::beginTransaction();

            DB::table('settings_audit_questions')->where('id',$id)->update([
                'user_id' => $user_id,
                'user_name' => $user_name,
                'question' => $question,
                'updated_at'=> date('Y-m-d H:i:s')
            ]);

            DB::commit();
            return Redirect::route('settings.audit.manage',$aid)->with('success', "Successful Updated!");

        }catch(\Exception $e){
            DB::rollBack();
            Log::info($e->getMessage());
            return Redirect::route('settings.audit.manage',$aid)->with('error', "Failed Updating");
        }
    }
}
