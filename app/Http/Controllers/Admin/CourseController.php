<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CourseController extends Controller
{

  public function index()
  {
    $pageTitle = "Courses";
    $courses = Course::all();
    $iframs = [];

    foreach ($courses as $course)
    {
      $res = $this->course($course->course_id);
      if ($res)
      {
        $otp = $res['otp'];
        $playbackInfo = $res['playbackInfo'];
        $link = "https://player.vdocipher.com/v2/?otp=$otp&playbackInfo=$playbackInfo";

        $iframs[] = $link;
      }
    }


    return view($this->activeTemplate . 'user.courses', compact('pageTitle', 'iframs'));
  }

  public function course($id)
  {
    $response = Http::withHeaders([
      'authorization' => "Apisecret " . env('VDOCIPHER_SCRET'),
    ])->post("https://dev.vdocipher.com/api/videos/$id/otp", []);

    return $response;
  }


  public function index_admin()
  {
    $pageTitle = "Courses";

    $coursesAll = Course::paginate(getPaginate());
    $courses = [];

    foreach ($coursesAll as $courseAll)
    {
      $link = "";
      $res = $this->course($courseAll->course_id);
      if ($res)
      {
        $otp = $res['otp'];
        $playbackInfo = $res['playbackInfo'];
        $link = "https://player.vdocipher.com/v2/?otp=$otp&playbackInfo=$playbackInfo";
      }

      $courses[] = [
        'id' => $courseAll->id,
        'course_id' => $courseAll->course_id,
        'link' => $link
      ];
    }


    return view('admin.courses.index', compact('pageTitle', 'courses', 'coursesAll'));
  }

  public function store(Request $request)
  {
    $data = $request->validate([
      'course_id' => 'required',
    ]);

    Course::create($data);

    $notify[] = ['success', 'Course saved successfully'];
    return back()->withNotify($notify);
  }

  public function delete($id)
  {
    $course = Course::find($id);

    $course->delete();

    $notify[] = ['success', 'Course Delted successfully'];
    return back()->withNotify($notify);
  }
}
