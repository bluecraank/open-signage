<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\Presentation;
use App\Models\Slide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class PresentationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $presentations = Presentation::all();
        return view('presentations.index', compact('presentations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('presentations.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimetypes:application/pdf,video/mp4|max:100000',
            'name' => 'required|min:2|max:255',
            'description' => 'nullable|min:2|max:255',
        ]);

        $name = $request->input('name');
        $description = $request->input('description');
        $author = Auth::user()->name;

        if(!$name    || !$author) {
            return redirect()->back()->withInput()->withErrors([
                'message' => __('All fields are required'),
            ]);
        }

        $file = $request->file('file');

        if(!$file) {
            return redirect()->back()->withInput()->withErrors([
                'message' => __('File is required'),
            ]);
        }

        $presentation = Presentation::create([
            'name' => $name,
            'description' => "null",
            'author' => $author,
            'processed' => false,
        ]);

        $file_extension = "pdf";
        $type = "pdf";
        if($file->getMimeType() == "video/mp4") {
            $file_extension = "mp4";
            $type = "video";
        }

        File::makeDirectory(storage_path('app/public/presentations/'. $presentation->id), 0755, true, true);
        File::put(storage_path('app/public/presentations/'. $presentation->id . '/' . $presentation->id . '.' . $file_extension), file_get_contents($file));

        File::makeDirectory(public_path('data/presentations/'. $presentation->id), 0755, true, true);

        proc_open('php ' . base_path('artisan') . ' presentation:process ' . $presentation->id . ' ' . $type . ' > /dev/null &', [], $pipes);

        return redirect()->route('presentations.show', $presentation->id)->with('success', __('Presentation created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $presentation = Presentation::whereId($id)->firstOrFail();

        return view('presentations.show', compact('presentation'));
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $presentation = Presentation::whereId($id)->first();
        if(!$presentation) {
            return redirect()->back()->withErrors(['message' => __('Presentation not found')]);
        }

        $request->validate([
            'file' => 'nullable|file|mimetypes:application/pdf,video/mp4|max:100000',
            'name' => 'required|min:2|max:255|unique:presentations,name,' . $presentation->id . ',id',
            'description' => 'nullable|min:2|max:255',
        ]);

        if($request->has('name')) {
            $presentation->name = $request->input('name');
        }

        $presentation->description = "null";

        if($request->has('file')) {

            Log::create([
                'ip_address' => request()->ip(),
                'username' => Auth::user()->name,
                'action' => __('log.presentation_file_updated', ['name' => $presentation->name]),
            ]);


            $file = $request->file('file');

            $file_extension = "pdf";
            $type = "pdf";
            if($file->getMimeType() == "video/mp4") {
                $file_extension = "mp4";
                $type = "video";
            }

            File::deleteDirectory(public_path('data/presentations/'. $presentation->id));

            Slide::where('presentation_id', $presentation->id)->delete();

            File::makeDirectory(storage_path('app/public/presentations/'. $presentation->id), 0755, true, true);
            File::put(storage_path('app/public/presentations/'. $presentation->id . '/' . $presentation->id . '.' . $file_extension), file_get_contents($file));

            File::makeDirectory(public_path('data/presentations/'. $presentation->id), 0755, true, true);

            $presentation->processed = false;

            proc_open('php ' . base_path('artisan') . ' presentation:process ' . $presentation->id . ' ' . $type .' > /dev/null &', [], $pipes);

        }

        // Fix white screen bug
        $presentation->timestamps = false;

        $presentation->save();

        return redirect()->back()->with('success', __('Presentation updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $presentation = Presentation::whereId($id)->first();

        if(!$presentation) {
            return redirect()->back()->withErrors(['message' => __('Presentation not found')]);
        }

        if($presentation->groups->count() != 0) {
            return redirect()->back()->withErrors(['message' => __('Presentation is assigned to groups')]);
        }

        if($presentation->devices->count() != 0) {
            return redirect()->back()->withErrors(['message' => __('Presentation is assigned to devices')]);
        }

        // Delete all files
        File::cleanDirectory(public_path('data/presentations/'. $id));
        File::deleteDirectory(public_path('data/presentations/'. $id));

        $presentation->slides()->delete();
        Presentation::where('id', $id)->delete();

        return redirect()->route('presentations.index', $presentation->id)->with('success', __('Presentation deleted'));
    }

    static function getCurrentPresentationInProgress() {
        $presentation = Presentation::where('processed', false)->first();

        if(!$presentation) {
            return null;
        }

        return $presentation;
    }
}
