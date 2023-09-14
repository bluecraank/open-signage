<?php

namespace App\Http\Controllers;

use App\Models\Presentation;
use App\Models\Slide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

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
            'file' => 'required|file|mimetypes:application/pdf|max:100000',
        ]);

        $name = $request->input('name');
        $description = $request->input('description');
        $author = Auth::user()->name;

        if(!$name || !$description || !$author) {
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
            'description' => $description,
            'author' => $author,
            'processed' => false,
        ]);

        File::makeDirectory(storage_path('app/public/presentations/'. $presentation->id), 0755, true, true);
        File::put(storage_path('app/public/presentations/'. $presentation->id . '/' . $presentation->id . '.pdf'), file_get_contents($file));

        File::makeDirectory(public_path('data/presentations/'. $presentation->id), 0755, true, true);

        proc_open('php ' . base_path('artisan') . ' presentation:process ' . $presentation->id . ' > /dev/null &', [], $pipes);

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
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $presentation = Presentation::whereId($id)->first();
        if(!$presentation) {
            return redirect()->back();
        }

        if($request->has('name')) {
            $presentation->name = $request->input('name');
        }

        if($request->has('description')) {
            $presentation->description = $request->input('description');
        }

        if($request->has('file')) {
            $request->validate([
                'file' => 'nullable|file|mimetypes:application/pdf|max:5000',
            ]);

            $file = $request->file('file');

            File::deleteDirectory(public_path('data/presentations/'. $presentation->id));
            File::delete(storage_path('app/public/presentations/'. $presentation->id . '/' . $presentation->id . '.pdf'));

            Slide::where('presentation_id', $presentation->id)->delete();

            File::makeDirectory(storage_path('app/public/presentations/'. $presentation->id), 0755, true, true);
            File::put(storage_path('app/public/presentations/'. $presentation->id . '/' . $presentation->id . '.pdf'), file_get_contents($file));

            File::makeDirectory(public_path('data/presentations/'. $presentation->id), 0755, true, true);

            $presentation->processed = false;

            proc_open('php ' . base_path('artisan') . ' presentation:process ' . $presentation->id . ' > /dev/null &', [], $pipes);

        }

        $presentation->save();

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Storage::delete(storage_path('app/public/presentations/'. $id . '/'));
        Presentation::where('id', $id)->delete();
        return redirect()->back();
    }
}
