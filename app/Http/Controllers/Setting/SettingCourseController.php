<?php namespace App\Http\Controllers\Setting;

use App\Http\Controllers\WsController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class SettingCourseController extends WsController
{

    /**
     * Settings Course index
     */
    public function index(Request $request)
    {
        $courses = DB::table('tc_settings_courses as w')
            ->leftJoin('primary_location as pl', 'pl.id', '=', 'w.pid')
            ->where('w.status', '<', 2)
            ->select('w.id', 'w.course_title','pl.location as plocation')
            ->orderBy('w.id')
            ->get();

        foreach ($courses as $index => $item) {
            $item->topic_count = DB::table('tc_settings_courses_details_topic')
                ->where('course_id', $item->id)
                ->where('status','<',2)
                ->count();
            $item->quiz_count = DB::table('tc_settings_courses_details_quiz')
                ->where('course_id', $item->id)
                ->where('status','<',2)
                ->count();
        }

        return view('settings.course.index', compact('courses'));
    }

    public function edit($id)
    {
        try{
            $course = DB::table('tc_settings_courses')
                ->where('id',$id)
                ->where('status','<',2)
                ->first();

        }catch (\Exception $e){
            Log::info($e->getMessage());
        }
        return view('settings.course.edit', compact('course'));
    }
    public function save(Request $request)
    {
        $user_id = '';
        $user_name = '';
        if(Sentinel::check()) {
            $user_id = Sentinel::getUser()->id;
            $user_name = Sentinel::getUser()->name;
        }

        $id = $request->get('id');
        $course_title = $request->get('course_title');

        try {
            DB::beginTransaction();
            if ($id) {
                DB::table('tc_settings_courses')
                    ->where('id', $id)
                    ->update([
                    'course_title' => $course_title,
                ]);
            }
            else
            {
                DB::table('tc_settings_courses')->insert([
                    'user_id' => $user_id,
                    'user_name' => $user_name,
                    'pid' => Session::get('p_loc'),
                    'course_title' => $course_title,
                ]);
            }
            DB::commit();
            return Redirect::route('settings.course')->with('success', "Successful Added!");

        }catch(\Exception $e){
            DB::rollBack();
            Log::info($e->getMessage());
            return Redirect::route('settings.course')->with('error', "Failed Adding");
        }
    }
    public function delete(Request $request)
    {
        $id = $request->get('id');
        if (DB::table('tc_settings_courses')->where('id', $id)->update(['status' => 2]))
            return Redirect::back()->with('success', 'Successful Deleted!');
        else
            return Redirect::back()->with('error', 'Failed Deleting!');
    }

    /**
     * Settings Course Detail -  Manage Task
     * */
    public function course_detail(Request $request)
    {
        $id = $request->get('cid');
        try{
            $topics = DB::table('tc_settings_courses_details_topic')
                ->where('course_id',$id)
                ->where('status','<',2)
                ->get();

            $quiz = DB::table('tc_settings_courses_details_quiz')
                ->where('course_id',$id)
                ->where('status','<',2)
                ->select('id','course_id','question')
                ->get();

            foreach ($quiz as $item) {
                $item->choices = DB::table('tc_settings_courses_details_quiz_options')
                    ->where('quiz_id',$item->id)
                    ->select('id','value','name','correct')
                    ->get();
            }

            $course = DB::table('tc_settings_courses')->where('id',$id)->select('id','course_title')->first();

            return view('settings.course.course_index', compact('topics','quiz','course'));

        }catch (\Exception $e){
            Log::info($e->getMessage());
            return Redirect::back()->with('error','Failed!');
        }
    }

    public function topic_edit($id, Request $request)
    {
        $course_id = $request->get('cid');
        try{
            $topic = DB::table('tc_settings_courses_details_topic')
                ->where('id',$id)
                ->where('status','<',2)
                ->first();

            $course = DB::table('tc_settings_courses')
                ->where('id',$course_id)
                ->first();

            return view('settings.course.topic', compact('course','topic'));

        }catch (\Exception $e){
            Log::info($e->getMessage());
            return Redirect::back()->with('error','Failed');
        }
    }

    public function topic_save(Request $request)
    {
        $user_id = '';
        $user_name = '';
        if(Sentinel::check()) {
            $user_id = Sentinel::getUser()->id;
            $user_name = Sentinel::getUser()->name;
        }

        $id = $request->get('id');
        $course_id = $request->get('cid');
        $topic_title = $request->get('topic_title');
        $description = $request->get('description');

        try {
            DB::beginTransaction();

            $attach_files = null;
            if(count($request->get('attach_files',[])) > 0){
                $attach_files = $request->get('attach_files',[]);
                if(count($attach_files) > 8){
                    return Redirect::back()->with('warning', "The files for uploading should be less than 8");
                }
                $attach_files = json_encode($attach_files);
            }

            DB::table('tc_settings_courses_details_topic')->updateOrInsert(
                ['id'=>$id],
                [
                    'user_id' => $user_id,
                    'user_name' => $user_name,
                    'course_id' => $course_id,
                    'topic_title' => $topic_title,
                    'description' => $description,
                    'attach_files' => $attach_files
                ]);

            DB::commit();
            return Redirect::route('settings.course.detail', ['id' => $id, 'cid' => $course_id])->with('success', "Successful Saved!");

        }catch(\Exception $e){
            DB::rollBack();
            Log::info($e->getMessage());
            return Redirect::back()->with('error', "Failed Saving");
        }
    }
    public function topic_delete(Request $request)
    {
        $id = $request->get('id');
        if (DB::table('tc_settings_courses_details_topic')->where('id', $id)->update(['status' => 2]))
            return Redirect::back()->with('success', 'Successful Deleted!');
        else
            return Redirect::back()->with('error', 'Failed Deleting!');
    }

    public function topic_detail($id)
    {
        try {
            if(!$topic = DB::table('tc_settings_courses_details_topic')
                ->where('id', $id)
                ->select('id','course_id','topic_title','description','attach_files')
                ->first()){
                return Redirect::back()->with('error', 'There is some errors');
            }

            return View('settings.course.topic_detail', compact('topic'));

        }catch(\Exception $e){
            Log::info($e->getMessage());
            return back()->with('error', "Failed!");
        }
    }

    public function quiz_edit($id, Request $request)
    {
        try{
            $quiz = DB::table('tc_settings_courses_details_quiz')
                ->where('id',$id)
                ->where('status','<',2)
                ->first();

            $course_id = $request->get('cid');
            $course = DB::table('tc_settings_courses')
                ->where('id',$course_id)
                ->select('id','course_title')->first();

            $course_quiz_options = DB::table('tc_settings_courses_details_quiz_options')
                ->where('quiz_id',$id)
                ->select('id','quiz_id','value','name','correct')
                ->get();

            return view('settings.course.edit_quiz', compact('quiz','course', 'course_quiz_options'));

        }catch (\Exception $e){
            Log::info($e->getMessage());
            return Redirect::back()->with('error','Failed');
        }
    }

    public function quiz_save(Request $request)
    {
        $user_id = '';
        $user_name = '';
        if(Sentinel::check()) {
            $user_id = Sentinel::getUser()->id;
            $user_name = Sentinel::getUser()->name;
        }

        $id = $request->get('id');
        $course_id = $request->get('cid');
        $question = $request->get('question');
        $options = $request->get('options', []);
        $answer = $request->get('answer');

        try {
            DB::beginTransaction();
            if ($id) {
                DB::table('tc_settings_courses_details_quiz')
                    ->where('id', $id)
                    ->update([
                    'question' => $question,
                ]);
                DB::table('tc_settings_courses_details_quiz_options')->where('quiz_id',$id)->delete();
            }
            else
            {
                $id = DB::table('tc_settings_courses_details_quiz')->insertGetId([
                    'user_id' => $user_id,
                    'user_name' => $user_name,
                    'course_id' => $course_id,
                    'question' => $question,
                ]);
            }

            foreach ($options as $key=>$item){
                DB::table('tc_settings_courses_details_quiz_options')->insert([
                    'quiz_id' => $id,
                    'value' => $key,
                    'name' => $item,
                    'correct' => $key==$answer?$key:'',
                ]);
            }

            DB::commit();
            return Redirect::route('settings.course.detail',['cid'=>$course_id])->with('success', "Successful Added!");

        }catch(\Exception $e){
            DB::rollBack();
            Log::info($e->getMessage());
            return Redirect::back()->with('error', "Failed Adding");
        }
    }
    public function quiz_delete(Request $request)
    {
        $id = $request->get('id');

        if (DB::table('tc_settings_courses_details_quiz')->where('id', $id)->update(['status' => 2]))
            return Redirect::back()->with('success', 'Successful Deleted!');
        else
            return Redirect::back()->with('error', 'Failed Deleting!');
    }
}
