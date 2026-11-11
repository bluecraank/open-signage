<?php

namespace App\Http\Controllers;

use App\Models\Slide;
use Illuminate\Support\Facades\File;

class SlideController extends Controller
{
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $slide = Slide::whereId($id)->first();

        if(!$slide) {
            return redirect()->back()->withErrors(['message' => __('Slide not found')]);
        }

        File::delete(public_path('data/presentations/' . $slide->presentation_id . '/' . $slide->name_on_disk));

        $slide->delete();

        // Monitors reload when the presentation has been updated
        $slide->presentation?->touch();

        return redirect()->back()->with('success', __('Slide deleted'));
    }
}
