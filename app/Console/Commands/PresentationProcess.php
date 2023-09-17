<?php

namespace App\Console\Commands;

use App\Models\Presentation;
use App\Models\Slide;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class PresentationProcess extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'presentation:process {id} {type}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process images of a presentation';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $id = $this->argument('id');
        $presentation = Presentation::where('id', $id)->firstOrFail();
        $type = $this->argument('type');

        if($type == 'pdf') {
            $this->processPdf($presentation);
        } else if($type == 'video') {
            $this->processVideo($presentation);
        }
    }

    public function processPdf($presentation) {
        $pdf = new \Spatie\PdfToImage\Pdf(storage_path('app/public/presentations/'. $presentation->id . '/' . $presentation->id) . '.pdf');

        $pages = $pdf->getNumberOfPages();
        for($i = 1; $i <= $pages; $i++) {
            $random = Str::random(7);
            $imagename = $random . '-' . $i . '.jpg';
            $pdf->setResolution(300);
            $pdf->setPage($i)->saveImage(public_path('data/presentations/'. $presentation->id . '/' . $imagename));
            $pdf->setResolution(25);
            $pdf->setPage($i)->saveImage(public_path('data/presentations/'. $presentation->id . '/preview-' . $imagename));

            Slide::updateOrCreate([
                'presentation_id' => $presentation->id,
                'order' => $i,
            ], [
                'presentation_id' => $presentation->id,
                'name_on_disk' => $imagename,
                'name' => $random,
            ]);
        }

        $presentation->processed = true;

        Storage::delete(storage_path('app/public/presentations/'. $presentation->id . '/' . $presentation->id) . '.pdf');

        $presentation->save();
    }

    public function processVideo($presentation) {
        $path = storage_path('app/public/presentations/'. $presentation->id . '/' . $presentation->id) . '.mp4';
        $video = new \FFMpeg\FFMpeg();
        $video = $video->open($path);

        // Get first frame as screenshot
        $frame = $video->frame(1);
        $frame->save(public_path('data/presentations/'. $presentation->id . '/video-preview.jpg'));
        $video->close();

        // Save video to public
        $video = new \FFMpeg\FFMpeg();
        $video->open(storage_path('app/public/presentations/'. $presentation->id . '/' . $presentation->id) . '.mp4')
            ->export()
            ->toDisk('public')
            ->inFormat(new \FFMpeg\Format\Video\X264('libmp3lame', 'libx264'))
            ->save('data/presentations/'. $presentation->id . '/video.mp4');

        $presentation->processed = true;

        Storage::delete(storage_path('app/public/presentations/'. $presentation->id . '/' . $presentation->id) . '.mp4');

        $presentation->save();
    }
}
