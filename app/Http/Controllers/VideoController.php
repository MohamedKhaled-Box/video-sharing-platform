<?php

namespace App\Http\Controllers;

use App\Jobs\ConvertVideoForStreaming;
use App\Models\Video;

use Illuminate\Http\Request;
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
        //
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Video $video)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Video $video)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Video $video)
    {
        //
    }
}