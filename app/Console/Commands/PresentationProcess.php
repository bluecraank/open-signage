<?php

namespace App\Console\Commands;

use App\Models\Presentation;
use App\Models\Slide;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
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

        if ($type == 'pdf') {
            $this->processPdf($presentation);
        } else if ($type == 'video') {
            $this->processVideo($presentation);
        }
    }

    public function processPdf($presentation)
    {
        $pdf = new \Spatie\PdfToImage\Pdf(storage_path('app/public/presentations/' . $presentation->id . '/' . $presentation->id) . '.pdf');

        $pages = $pdf->getNumberOfPages();
        for ($i = 1; $i <= $pages; $i++) {
            $random = Str::random(7);
            $imagename = $random . '-' . $i . '.jpg';
            $pdf->setResolution(300);
            $pdf->setPage($i)->saveImage(public_path('data/presentations/' . $presentation->id . '/' . $imagename));
            $pdf->setResolution(25);
            $pdf->setPage($i)->saveImage(public_path('data/presentations/' . $presentation->id . '/preview-' . $imagename));

            Slide::updateOrCreate([
                'presentation_id' => $presentation->id,
                'order' => $i,
                'type' => 'image',
            ], [
                'name_on_disk' => $imagename,
                'name' => $random,
            ]);
        }

        $presentation->processed = true;

        File::delete(storage_path('app/public/presentations/' . $presentation->id . '/' . $presentation->id) . '.pdf');
        File::deleteDirectory(storage_path('app/public/presentations/' . $presentation->id . '/'));

        $presentation->save();
    }

    public function processVideo($presentation)
    {
        $random = Str::random(7);
        $videoname = $random . '-' . 'v' . '.mp4';
        $previewname = 'preview-' . $random . '-v' . '.jpg';

        // Save video to public
        $ffmpeg = \FFMpeg\FFMpeg::create();

        $video = $ffmpeg->open(storage_path('app/public/presentations/' . $presentation->id . '/' . $presentation->id) . '.mp4');

        $timeCode = \FFMpeg\Coordinate\TimeCode::fromSeconds(0);
        $frame = $video->frame($timeCode);

        $frame->save(public_path('data/presentations/' . $presentation->id . '/' . $previewname));

        $video->save(new \FFMpeg\Format\Video\X264(), public_path('data/presentations/' . $presentation->id . '/' . $videoname) );

        Slide::updateOrCreate([
            'presentation_id' => $presentation->id,
            'order' => 1,
            'type' => 'video',
        ], [
            'name_on_disk' => $videoname,
            'name' => $random,
        ]);

        $presentation->processed = true;

        File::delete(storage_path('app/public/presentations/' . $presentation->id . '/' . $presentation->id) . '.mp4');
        File::deleteDirectory(storage_path('app/public/presentations/' . $presentation->id . '/'));

        $presentation->save();
    }
}
