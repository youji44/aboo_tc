<?php namespace App\Http\Controllers\Main;

use App\Http\Controllers\WsController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;

class QuizController extends WsController
{

    public function index(Request $request)
    {
        try {

            $pid = Session::get('p_loc');
            if(!Sentinel::check()) return Redirect::back()->with('error', 'You are not authorized to use this function');
            $user_id = Sentinel::getUser()->id;

            $courses = DB::table('tc_settings_courses as w')
                ->leftJoin('primary_location as pl', 'pl.id', '=', 'w.pid')
                ->where('w.pid',$pid)
                ->where('w.status', '<', 2)
                ->select('w.id', 'w.course_title','pl.location as plocation')
                ->orderBy('w.id')
                ->get();

            foreach ($courses as $index => $item) {
                $item->quiz_count = DB::table('tc_settings_courses_details_quiz')
                    ->where('course_id', $item->id)
                    ->where('status','<',2)
                    ->count();
                $item->passed = DB::table('tc_training_course_quiz')
                    ->where('user_id', $user_id)
                    ->where('course_id', $item->id)
                    ->value('took');
                $item->grade = DB::table('tc_training_course_quiz')
                    ->where('user_id', $user_id)
                    ->where('course_id', $item->id)
                    ->value('grade');
            }

            return View('main.quiz.index', compact('courses'));
        }catch(\Exception $e){
            Log::info($e->getMessage());
            return back()->with('error', "Failed!");
        }
    }

    public function quiz(Request $request)
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

            if(DB::table('tc_settings_courses_details_quiz')
                ->where('course_id', $course_id)
                ->where('status','<',2)
                ->count() < 1){
                return Redirect::back()->with('warning', 'You can not start this Quiz because there is not any quiz still.');
            }

            $quiz = DB::table('tc_settings_courses_details_quiz')
                ->where('course_id', $course_id)
                ->where('status','<',2)
                ->select('id','question')
                ->get();

            foreach ($quiz as $item) {
                $item->answers = DB::table('tc_settings_courses_details_quiz_options as w')
                    ->leftJoin('tc_training_course_quiz_answer as a', function($join) use ($course_id, $user_id, $item) {
                        $join->on('a.quiz_id', '=', 'w.quiz_id')
                            ->where('a.course_id', $course_id)
                            ->where('a.user_id', $user_id)
                            ->where('a.quiz_id', $item->id);
                    })
                    ->where('w.quiz_id', $item->id)
                    ->select('w.id', 'w.value', 'w.name', 'w.correct', 'a.answer')
                    ->get();
            }

            $course->quiz_count = count($quiz);
            $course->timing = DB::table('tc_training_course_quiz')
                ->where('pid', $pid)
                ->where('course_id', $course_id)
                ->where('user_id', $user_id)
                ->value('timing');

            return View('main.quiz.quiz', compact('quiz','course'));

        }catch(\Exception $e){
            Log::info($e->getMessage());
            return back()->with('error', "Failed!");
        }
    }

    public function timing(Request $request)
    {
        $user_id = '';
        $user_name = '';
        if(Sentinel::check()) {
            $user_id = Sentinel::getUser()->id;
            $user_name = Sentinel::getUser()->name;
        }

        $pid = Session::get('p_loc');
        $course_id = $request->get('cid');
        $timing = $request->get('timing');

        try {
            DB::beginTransaction();

            $course_quiz = DB::table('tc_training_course_quiz')
                ->where('pid', $pid)
                ->where('course_id', $course_id)
                ->where('user_id', $user_id)
                ->where('status','<',2)
                ->first();

            if($course_quiz){
                DB::table('tc_training_course_quiz')
                    ->where('pid', $pid)
                    ->where('course_id', $course_id)
                    ->where('user_id', $user_id)
                    ->where('status','<',2)
                    ->update(
                        [
                            'timing' => $timing,
                        ],
                    );
            }else{
                DB::table('tc_training_course_quiz')
                    ->insert(
                        [
                            'pid' => $pid,
                            'user_id' => $user_id,
                            'user_name' => $user_name,
                            'timing' => $timing,
                            'course_id' => $course_id,
                        ],
                    );
            }

            DB::commit();
            return response()->json(['success'=>true]);

        }catch(\Exception $e){
            Log::info($e->getMessage());
            DB::rollBack();
            return response()->json(['success'=>false]);
        }
    }

    public function submit(Request $request)
    {
        $user_id = '';
        $user_name = '';
        if(Sentinel::check()) {
            $user_id = Sentinel::getUser()->id;
            $user_name = Sentinel::getUser()->name;
        }
        $pid = Session::get('p_loc');
        $course_id = $request->get('cid');

        try {
            DB::beginTransaction();

            $is_correct = 0;
            $quiz = DB::table('tc_settings_courses_details_quiz as w')
                ->where('w.course_id', $course_id)
                ->where('w.status','<',2)
                ->select('w.id','w.question')
                ->get();

            foreach ($quiz as $index => $item) {
                $answer = $request->get('option_'.$item->id);
                DB::table('tc_training_course_quiz_answer')->updateOrInsert(
                    [
                        'course_id' => $course_id,
                        'quiz_id' => $item->id,
                        'user_id' => $user_id,
                        'pid' => $pid,
                    ],
                    [
                        'course_id' => $course_id,
                        'quiz_id' => $item->id,
                        'user_id' => $user_id,
                        'user_name' => $user_name,
                        'pid' => $pid,
                        'answer'=>$answer
                    ]);

                $item->correct = DB::table('tc_settings_courses_details_quiz_options')
                    ->where('quiz_id', $item->id)
                    ->whereNotNull('correct')
                    ->where('correct', '!=', '')
                    ->value('correct');
                if($item->correct == $answer) $is_correct++;

            }

            $grade = count($quiz) > 0? ($is_correct * 100 / count($quiz)):0;

            DB::table('tc_training_course_quiz')
                ->where('pid', $pid)
                ->where('course_id', $course_id)
                ->where('user_id', $user_id)
                ->where('status','<',2)
                ->update(
                    [
                        'took' => $grade < 80 ? 2 : 3,
                        'grade' => number_format($grade,'0','.',''),
                    ],
                );

            DB::commit();
            return Redirect::route('course.quiz')->with('success', 'Successful Submitted!');

        }catch(\Exception $e){
            Log::info($e->getMessage());
            DB::rollBack();
            return Redirect::route('course.quiz')->with('error', 'Failed submitting!');
        }
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
