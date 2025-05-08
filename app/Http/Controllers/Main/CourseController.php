<?php namespace App\Http\Controllers\Main;

use App\Http\Controllers\WsController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;

class CourseController extends WsController
{
    public function index(Request $request)
    {
        try {
            $pid = Session::get('p_loc');
            $courses = DB::table('tc_settings_courses as w')
                ->leftJoin('primary_location as pl', 'pl.id', '=', 'w.pid')
                ->where('w.pid',$pid)
                ->where('w.status', '<', 2)
                ->select('w.id', 'w.course_title','pl.location as plocation')
                ->orderBy('w.id')
                ->get();

            foreach ($courses as $index => $item) {
                $item->topic_count = DB::table('tc_settings_courses_details_topic')
                    ->where('course_id', $item->id)
                    ->where('status','<',2)
                    ->count();
            }

            return View('main.course.index', compact('courses'));
        }catch(\Exception $e){
            Log::info($e->getMessage());
            return back()->with('error', "Failed!");
        }
    }

    public function topic(Request $request)
    {
        try {

            $pid = Session::get('p_loc');
            if(!Sentinel::check()) return Redirect::back()->with('error', 'You are not authorized to use this function');
            $user_id = Sentinel::getUser()->id;

            $course_id = $request->get('cid');
            if(!$course = DB::table('tc_settings_courses')
                ->where('id', $course_id)
                ->select('id','course_title')
                ->first()){
                return Redirect::back()->with('error', 'There is some errors');
            }

            $topic = DB::table('tc_settings_courses_details_topic')
                ->where('course_id', $course_id)
                ->where('status','<',2)
                ->select('id','topic_title')
                ->get();

            foreach ($topic as $index => $item) {
                $item->reviewed = DB::table('tc_training_course_topic')
                    ->where('pid',$pid)
                    ->where('course_id',$course_id)
                    ->where('topic_id',$item->id)
                    ->where('user_id',$user_id)
                    ->value('reviewed');
            }

            $topic_id = $request->get('tid');
            $exit = $request->get('exit');
            if($exit == '1'){
                $this->get_duration($user_id, $course_id, $topic_id);
            }

            return View('main.course.topic', compact('topic','course'));

        }catch(\Exception $e){
            Log::info($e->getMessage());
            return back()->with('error', "Failed!");
        }
    }

    public function detail(Request $request)
    {
        try {

            $pid = Session::get('p_loc');
            if(!Sentinel::check()) return Redirect::back()->with('error', 'You are not authorized to use this function');
            $user_id = Sentinel::getUser()->id;
            $user_name = Sentinel::getUser()->name;

            $topic_id = $request->get('tid');

            if(!$topic = DB::table('tc_settings_courses_details_topic')
                ->where('id', $topic_id)
                ->select('id','course_id','topic_title','description','attach_files')
                ->first()){
                return Redirect::back()->with('error', 'There is some errors');
            }

            /**
             * Begin Course
             */
            DB::table('tc_training_course_topic')->updateOrInsert(
                ['user_id'=>$user_id, 'course_id'=>$topic->course_id, 'topic_id'=>$topic_id],
                [
                    'pid'=>$pid,
                    'user_id'=>$user_id,
                    'user_name'=>$user_name,
                    'topic_id'=>$topic_id,
                    'course_id'=>$topic->course_id,
                    'reviewed'=>1,
                    'date'=>date('Y-m-d H:i:s'),
                    'exit'=>date('Y-m-d H:i:s')
                ]
            );

            return View('main.course.detail', compact('topic'));

        }catch(\Exception $e){
            Log::info($e->getMessage());
            return back()->with('error', "Failed!");
        }
    }

    public function get_duration($user_id, $course_id, $topic_id)
    {
        $pid = Session::get('p_loc');
        DB::table('tc_training_course_topic')
            ->where('pid', $pid)
            ->where('user_id', $user_id)
            ->where('course_id', $course_id)
            ->where('topic_id', $topic_id)
            ->update([
                'exit' => now()  // Sets exit time to current timestamp
            ]);

        DB::table('tc_training_course_topic')
            ->where('pid', $pid)
            ->where('user_id', $user_id)
            ->where('course_id', $course_id)
            ->where('topic_id', $topic_id)
            ->update([
                'duration' => DB::raw("COALESCE(duration, 0) + TIMESTAMPDIFF(SECOND, `date`, `exit`)"),
            ]);
    }

    public function getDownload(Request $request)
    {
        try{
            $filename = $request->get('file');
            $file= public_path(). "/uploads/files/".$filename;
            return Response::download($file, $filename);
        }catch (\Exception $e){
            return null;
        }
    }

}
