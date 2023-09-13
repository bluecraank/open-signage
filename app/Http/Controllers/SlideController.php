<?php

namespace App\Http\Controllers;

use App\Models\Slide;
use Illuminate\Http\Request;

class SlideController extends Controller
{
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $slide = Slide::whereId($id)->first();

        if(!$slide) {
            return redirect()->route('slides.index')->withErrors(['message' => __('Slide not found')]);
        }

        $slide->delete();

        return redirect()->back()->with('success', __('Slide deleted'));
    }
}
