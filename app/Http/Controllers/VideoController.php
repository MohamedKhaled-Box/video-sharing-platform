<?php

namespace App\Http\Controllers;

use App\Jobs\ConvertVideoForStreaming;
use App\Models\Convertedvideo;
use App\Models\Like;
use App\Models\Video;
use App\Models\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;


class VideoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $videos = auth()->user()->videos->sortByDesc('created_at');
        $title = 'اخر الفيديوهات المرفوعه';
        return view('videos.my-videos', compact('videos', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('videos.uploader');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'image' => 'image|required',
            'video' => 'required|mimetypes:video/mp4,video/webm',
        ]);

        $randomPath = Str::random(16);
        $videoPath =  $randomPath . '.' . $request->video->getClientOriginalExtension();
        $imagePath =  $randomPath . '.' . $request->image->getClientOriginalExtension();
        $image = Image::make($request->image)->resize(320, 180);
        $path = Storage::put($imagePath, $image->stream());
        $request->video->storeAs('/', $videoPath, 'public');
        $video = new Video();
        $video->disk = 'public';
        $video->title = $request->title;
        $video->image_path = $imagePath;
        $video->video_path = $videoPath;
        $video->user_id = auth()->id();
        $video->save();

        $view = new View();
        $view->video_id = $video->id;
        $view->user_id = auth()->id();
        $view->views_number = 0;
        $view->save();
        // dd([
        //     'WASABI_ACCESS_KEY_ID' => env('WASABI_ACCESS_KEY_ID'),
        //     'WASABI_SECRET_ACCESS_KEY' => env('WASABI_SECRET_ACCESS_KEY'),
        //     'WASABI_BUCKET' => env('WASABI_BUCKET'),
        // ]);
        ConvertVideoForStreaming::dispatch($video);


        return redirect()->back()->with('success', 'your video will be ready after processing');
    }

    /**
     * Display the specified resource.
     */
    public function show(Video $video)
    {
        $countLike = Like::where('video_id', $video->id)->where('like', '1')->count();
        $countDislike = Like::where('video_id', $video->id)->where('like', '0')->count();

        $user = Auth::user();
        if (Auth::check()) {
            $userLike = $user->likes()->where('video_id', $video->id)->first();
        } else {
            $userLike = 0;
        }

        if (Auth::check()) {
            auth()->user()->videoInHistory()->attach($video->id);
        }

        $comments = $video->comments->sortByDesc('created_at');

        return view('videos.show-video', compact('video', 'countLike', 'countDislike', 'userLike', 'comments'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Video $video)
    {
        return view('videos.edit-video', compact('video'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Video $video)
    {
        $this->validate($request, [
            'title' => 'required',
        ]);

        $video = Video::where('id', $video->id)->first();

        if ($request->has('image')) {

            $randomPath = Str::random(16);
            $newPath =  $randomPath . '.' . $request->image->getClientOriginalExtension();

            Storage::delete($video->image_path);

            $image = Image::make($request->image)->resize(320, 180);
            //Store with stream();
            $path = Storage::put($newPath, $image->stream());

            $video->image_path = $newPath;
        }

        $video->title = $request->title;

        $video->save();

        return redirect('/videos')->with(
            'success',
            'تم تعديل معلومات الفيديو بنجاح'
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Video $video)
    {
        $convertedVideos = Convertedvideo::where('video_id', '=', $video->id)->get();
        // dd($convertedVideos);
        foreach ($convertedVideos as $convertVideo) {
            Storage::delete([
                $convertVideo->mp4_format_240,
                $convertVideo->mp4_format_360,
                $convertVideo->mp4_format_480,
                $convertVideo->mp4_format_720,
                $convertVideo->mp4_format_1080,
                $convertVideo->webm_format_240,
                $convertVideo->webm_format_360,
                $convertVideo->webm_format_480,
                $convertVideo->webm_format_720,
                $convertVideo->webm_format_1080,
                $video->image_path
            ]);
        }
        $video->delete();
        return back()->with('success', 'deleted');
    }
    public function search(Request $request)
    {
        $videos = Video::where('title', 'like', "%{$request->term}%")->paginate(12);
        $title = "searching for " . $request->term;
        return view('videos.my-videos', compact('videos', 'title'));
    }
    public function addView(Request $request)
    {
        $views = View::where('video_id', $request->videoId)->first();

        $views->views_number++;

        $views->save();

        $viewsNumbers = $views->views_number;
        return response()->json(['viewsNumbers' => $viewsNumbers]);
    }
    public function mostViewedVideos()
    {
        $mostViewedVideos = View::orderBy('views_number', 'Desc')
            ->take(10)
            ->get(['user_id', 'video_id', 'views_number']);

        $videoNames = [];
        $videoViews = [];
        foreach ($mostViewedVideos as $view) {
            array_push($videoNames, Video::find($view->video_id)->title);
            array_push($videoViews, $view->views_number);
        }

        return view('admin.most-Viewed-Videos', compact('mostViewedVideos'))->with('videoNames', json_encode($videoNames, JSON_NUMERIC_CHECK))->with('videoViews', json_encode($videoViews, JSON_NUMERIC_CHECK));
    }
}
