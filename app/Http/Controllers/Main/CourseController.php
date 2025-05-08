<?php namespace App\Http\Controllers\Main;

use App\Http\Controllers\Utils;
use App\Http\Controllers\WsController;
use App\Models\QcfIncidentReporting;
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

    public function incident_print($id){

        try {
            $pid = Session::get('p_loc');

            if(!$incident_reporting = DB::table('qcf_incident_report as w')
                ->leftJoin('qcf_settings_incident_type as t','t.id','=','w.incident_type')
                ->leftJoin('qcf_settings_incident_locations as l','l.id','=','w.location_id')
                ->leftJoin('qcf_settings_incident_departments as d','d.id','=','w.department_id')
                ->where('w.id',$id)
                ->where('w.pid',$pid)
                ->select('w.id as rid','w.status as w_status','w.*','t.type','t.color',
                    'l.location_name','l.location_code','d.department_name'
                )
                ->first()){
                return "<div class='alert alert-warning'>There is no data!</div>";
            }

            $settings_notifications  = DB::table('qcf_settings_incident_notification as n')
                ->where('n.status','<',2)
                ->whereIn('n.id',json_decode($incident_reporting->notifications))
                ->select('n.notification')
                ->orderBy('n.notification')
                ->pluck('n.notification')
                ->toArray();
            $incident_reporting->notifications = implode(', ',$settings_notifications);

            $images = [];
            if($incident_reporting->images){
                if(json_decode($incident_reporting->images)){
                    foreach (json_decode($incident_reporting->images) as $img){
                        if($base64_img = Utils::convert_base64(public_path().'/uploads/'.$img))
                        {
                            $images[] = $base64_img;
                        }
                    }
                }
            }
            $incident_reporting->images = $images;

            $forms = DB::table('qcf_settings_incident_type')
                ->where('id', $incident_reporting->incident_type)
                ->where('status','<',2)
                ->value('forms');

            $form_details = [];
            if($forms && $forms_array = json_decode($forms)){
                $form_details  = DB::table('qcf_settings_incident_forms_details as d')
                    ->leftJoin('qcf_settings_incident_forms as f','f.id','=','d.form_id')
                    ->leftJoin('qcf_incident_report_forms as r','r.detail_id','=','d.id')
                    ->leftJoin('grading_result as gr','gr.id','=','r.condition_field')
                    ->whereIn('f.id', $forms_array)
                    ->where('r.report_id', $id)
                    ->select('d.*','f.form_name','f.id as fid',
                        'r.date_time','r.text_field','r.number_field','r.textarea_field','r.condition_field','r.image_field','selection_field',
                        'gr.result as gr_result','gr.color as gr_color','gr.value as gr_value')
                    ->get();

                foreach ($form_details as $item){
                    $item->selection_field = DB::table('qcf_settings_incident_forms_details_options')
                        ->where('form_detail_id',$item->id)
                        ->where('value',$item->selection_field)
                        ->value('name');

                    $image_field = [];
                    if($item->image_field){
                        if(json_decode($item->image_field)){
                            foreach (json_decode($item->image_field) as $img){
                                if($base64_img = Utils::convert_base64(public_path().'/uploads/'.$img))
                                {
                                    $image_field[] = $base64_img;
                                }
                            }
                        }
                    }
                    $item->image_field = $image_field;
                }
            }

            $form_details = count($form_details)>0?$form_details
                ->groupBy('fid') // Group by airline
                ->map(function ($rows, $fid) {
                    return [
                        'form_name' =>DB::table('qcf_settings_incident_forms')->where('id',$fid)->value('form_name'),
                        'fid' => $fid,
                        'rows' => $rows,
                    ];
                }):[];

            return View('qcf.incident_report.print',compact('incident_reporting','form_details'));
        }catch(\Exception $e){
            Log::info($e->getMessage());
            return back()->with('error', "Loading Failed!");
        }
    }

    public function incident_add($id, Request $request)
    {
        $pid = Session::get('p_loc');
        $date = $request->get('date',date('Y-m-d'));
        $settings_incident_type = DB::table('qcf_settings_incident_type')
            ->where('status','<',2)
            ->select('id','type','color')
            ->orderBy('type')
            ->get();

        $settings_incident_notification = DB::table('qcf_settings_incident_notification')
            ->where('status','<',2)
            ->select('id','notification')
            ->get();

        $settings_incident_locations = DB::table('qcf_settings_incident_locations')
            ->where('status','<',2)
            ->select('id','location_name','location_code')
            ->get();

        $settings_incident_departments = DB::table('qcf_settings_incident_departments')
            ->where('status','<',2)
            ->select('id','department_name')
            ->get();

        if($id=='0'){
            //add
            return view('qcf.incident_report.add',compact('date',
                'settings_incident_type',
                'settings_incident_notification',
                'settings_incident_departments',
                'settings_incident_locations',
            ));
        } else {
            //Edit

            if(!$incident_reporting = DB::table('qcf_incident_report')
                ->where('id',$id)
                ->first()){
                return Redirect::back()->with('warning', "There is no data for editing.");
            }

            $multi_selections = DB::table('settings_airline')
                ->where('status','<',2)
                ->select('id','airline_name as selection_name')
                ->get();

            $grading_condition = DB::table('grading_result')
                ->where('grading_type','condition')
                ->where('status','<', 2)
                ->select('id','grade','result','color')
                ->get();

            $forms = DB::table('qcf_settings_incident_type')
                ->where('id', $incident_reporting->incident_type)
                ->where('status','<',2)
                ->value('forms');

            $form_details = [];
            if($forms && $forms_array = json_decode($forms)){
                $form_details  = DB::table('qcf_settings_incident_forms_details as d')
                    ->leftJoin('qcf_settings_incident_forms as f','f.id','=','d.form_id')
                    ->leftJoin('qcf_incident_report_forms as r','r.detail_id','=','d.id')
                    ->whereIn('f.id', $forms_array)
                    ->where('r.report_id', $id)
                    ->select('d.*','f.form_name','f.id as fid',
                        'r.date_time','r.text_field','r.number_field','r.textarea_field','r.condition_field','r.image_field','r.selection_field')
                    ->get();

                foreach ($form_details as $item){
                    $item->form_details_options = DB::table('qcf_settings_incident_forms_details_options')
                        ->where('form_detail_id',$item->id)
                        ->select('form_detail_id','name','value')
                        ->get();
                }
            }

            $form_details = count($form_details)>0?$form_details
                ->groupBy('form_name') // Group by airline
                ->map(function ($rows, $form_name) {
                    return [
                        'form_name' => $form_name,
                        'rows' => $rows,
                    ];
                }):[];

            return View('qcf.incident_report.add',compact('incident_reporting','date',
                'settings_incident_type',
                'settings_incident_notification',
                'settings_incident_departments',
                'settings_incident_locations',
                'form_details','grading_condition','multi_selections'
            ));
        }
    }

    public function incident_add_forms(Request $request)
    {
        $type_id = $request->get('tid');
        $incident_report_id = $request->get('rid');
        $grading_condition = DB::table('grading_result')
            ->where('grading_type','condition')
            ->where('status','<', 2)
            ->select('id','grade','result','color')
            ->get();
        $multi_selections = DB::table('settings_airline')
            ->where('status','<',2)
            ->select('id','airline_name as selection_name')
            ->get();
        $forms = DB::table('qcf_settings_incident_type')
            ->where('id', $type_id)
            ->where('status','<',2)
            ->value('forms');

        $form_details = [];

        if($forms && $forms_array = json_decode($forms)){
            if($incident_report_id){
                $form_details  = DB::table('qcf_settings_incident_forms_details as d')
                    ->leftJoin('qcf_settings_incident_forms as f','f.id','=','d.form_id')
                    ->leftJoin('qcf_incident_report_forms as r','r.detail_id','=','d.id')
                    ->whereIn('f.id', $forms_array)
                    ->where('r.report_id', $incident_report_id)
                    ->select('d.*','f.form_name','f.id as fid',
                        'r.date_time','r.text_field','r.number_field','r.textarea_field','r.condition_field','r.image_field','r.selection_field')
                    ->get();
            }else{
                $form_details  = DB::table('qcf_settings_incident_forms_details as d')
                    ->leftJoin('qcf_settings_incident_forms as f','f.id','=','d.form_id')
                    ->whereIn('f.id', $forms_array)
                    ->select('d.*','f.form_name','f.id as fid')
                    ->get();
            }
        }

        foreach ($form_details as $item){
            $item->form_details_options = DB::table('qcf_settings_incident_forms_details_options')
                ->where('form_detail_id',$item->id)
                ->select('form_detail_id','name','value')
                ->get();
        }

        $form_details = count($form_details)>0?$form_details
            ->groupBy('form_name') // Group by airline
            ->map(function ($rows, $form_name) {
                return [
                    'form_name' => $form_name,
                    'rows' => $rows,
                ];
            }):[];

        return view('qcf.incident_report.add_forms',compact('form_details','grading_condition','multi_selections'));
    }

    public function incident_save(Request $request)
    {
        $user_id = '';
        $user_name = '';
        if(Sentinel::check()) {
            $user_id = Sentinel::getUser()->id;
            $user_name = Sentinel::getUser()->name;
        }

        $pid = Session::get('p_loc');

        $id = $request->get('id');
        $date = $request->get('date');
        $time = $request->get('time');

        $incident_type = $request->get('incident_type');
        $location_id = $request->get('location_id');
        $department_id = $request->get('department_id');
        $notifications = $request->get('notifications');
        $incident_title = $request->get('incident_title');
        $comments = $request->get('comments');

        $forms = DB::table('qcf_settings_incident_type')
            ->where('id', $incident_type)
            ->where('status','<',2)
            ->value('forms');
        $form_details = [];

        if($forms && $forms_array = json_decode($forms)){
            $form_details  = DB::table('qcf_settings_incident_forms_details as d')
                ->leftJoin('qcf_settings_incident_forms as f','f.id','=','d.form_id')
                ->whereIn('f.id', $forms_array)
                ->select('d.*','f.form_name','f.id as fid')
                ->get();
        }

        try {
            DB::beginTransaction();
            if($id){
                $images = null;
                if(count($request->get('images',[])) > 0){
                    $images = $request->get('images',[]);
                    if(count($images) > 25){
                        return Redirect::back()->with('warning', "The images for uploading should be less than 25");
                    }
                    $images = json_encode($images);
                }

                DB::table('qcf_incident_report')->where('id',$id)->update([
                    'date' => $date,
                    'time' => $time,
                    'pid' => $pid,
                    'incident_type'=> $incident_type,
                    'location_id' => $location_id,
                    'department_id' => $department_id,
                    'notifications' => json_encode($notifications),
                    'incident_title' => $incident_title,
                    'comments' => $comments,
                    'images'=> $images,
                    'updated_at'=> date('Y-m-d H:i:s'),
                    'geo_latitude' => Session::get('geo_lat'),
                    'geo_longitude' => Session::get('geo_lng')
                ]);

                foreach ($form_details as $item){

                    $uploader_images = $request->get('uploader_images_'.$item->id);
                    $date_time = $request->get('datetime_'.$item->id)?date('Y-m-d H:i', strtotime($request->get('datetime_'.$item->id))):'';

                    DB::table('qcf_incident_report_forms')
                        ->where('report_id', $id)
                        ->where('form_id',$item->fid)
                        ->where('detail_id',$item->id)
                        ->update([
                            'date_time' => $date_time,
                            'text_field' => $request->get('text_field_'.$item->id),
                            'number_field' => $request->get('number_field_'.$item->id),
                            'textarea_field' => $request->get('textarea_field_'.$item->id),
                            'condition_field' => $request->get('condition_field_'.$item->id),
                            'selection_field' => $request->get('multiple_option_'.$item->id),
                            'image_field' => $uploader_images?json_encode($uploader_images):null,
                            ]);
                }

            }else{
                $images = null;
                if(count($request->get('images',[])) > 0) $images = json_encode($request->get('images',[]));

                $db = new QcfIncidentReporting();
                $db->user_id = $user_id;
                $db->user_name = $user_name;
                $db->date = $date;
                $db->time = $time;
                $db->pid = $pid;
                $db->location_id = $location_id;
                $db->department_id = $department_id;
                $db->notifications = json_encode($notifications);

                $db->incident_type = $incident_type;
                $db->incident_title = $incident_title;

                $db->images = $images;
                $db->comments = $comments;
                $db->geo_latitude = Session::get('geo_lat');
                $db->geo_longitude = Session::get('geo_lng');

                $db->save();

                foreach ($form_details as $item){

                    $uploader_images = $request->get('uploader_images_'.$item->id);
                    $date_time = $request->get('datetime_'.$item->id)?date('Y-m-d H:i', strtotime($request->get('datetime_'.$item->id))):'';

                    DB::table('qcf_incident_report_forms')->insert([
                        'report_id' => $db->id,
                        'form_id' => $item->fid,
                        'detail_id' => $item->id,
                        'date_time' => $date_time,
                        'text_field' => $request->get('text_field_'.$item->id),
                        'number_field' => $request->get('number_field_'.$item->id),
                        'textarea_field' => $request->get('textarea_field_'.$item->id),
                        'condition_field' => $request->get('condition_field_'.$item->id),
                        'selection_field' => $request->get('multiple_option_'.$item->id),
                        'image_field' => $uploader_images?json_encode($uploader_images):null,
                    ]);
                }
            }

            DB::commit();

            $msg = $id?"Successful Updated!":"Successful Added!";
            return Redirect::route('incident.reporting')->with('success', $msg);

        }catch(\Exception $e){
            DB::rollBack();
            Log::info($e->getMessage());
            return Redirect::route('incident.reporting')->with('error', "Failed Adding");
        }
    }

    public function incident_delete(Request $request)
    {
        $id = $request->get('id');
        if(DB::table('qcf_incident_report')->where('id',$id)->update(['status'=>2]))
            return;// Redirect::route('main.fuel_weekly')->with('success', 'Successful Deleted!');
        else
            return;// Redirect::route('main.fuel_weekly')->with('error', 'Failed Deleting!');
    }

    public function incident_comments($id, Request $request)
    {
        $mode = $request->get('mode');
        $comments = DB::table('qcf_incident_report_comments')
            ->where('report_id', $id)
            ->where('flag', '<', 2)
            ->get();
        if($mode == "view" && count($comments) < 1) return '<div class="alert alert-warning">There is no any comments</div>';

        return view('qcf.incident_report.comments_edit',compact('mode','id','comments'));
    }

    public function incident_comments_save(Request $request)
    {
        $user_id = '';
        $user_name = '';
        if(Sentinel::check()) {
            $user_id = Sentinel::getUser()->id;
            $user_name = Sentinel::getUser()->name;
        }

        $id = $request->get('rid');
        $date = date('Y-m-d');
        $time = date('H:i');
        $comments = $request->get('additional_comments');

        try {
            DB::beginTransaction();

            DB::table('qcf_incident_report_comments')->insert([
                'report_id'=>$id,
                'date'=>$date,
                'time'=>$time,
                'user_name'=>$user_name,
                'user_id'=>$user_id,
                'comments'=>$comments
            ]);
            DB::commit();

            return Redirect::back()->with('success', "Successful created a comment");

        }catch(\Exception $e){
            DB::rollBack();
            Log::info($e->getMessage());
            return Redirect::back()->with('error', "Failed Adding");
        }
    }

    private function getFirstCharactersFromEachWord($string): string
    {
        // Explode the string into an array of words
        $words = explode(' ', $string);
        // Initialize an array to hold the first characters
        $firstCharacters = [];
        // Loop through each word
        foreach ($words as $word) {
            // Add the first character of the word to the array
            if (!empty($word)) { // Check if the word is not empty to avoid errors
                $firstCharacters[] = strtoupper($word[0]);
            }
        }
        // Join the first characters back into a single string (if needed)
        return implode('', $firstCharacters);
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
