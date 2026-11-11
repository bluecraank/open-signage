<?php

namespace App\Console\Commands;

use App\Models\Log;
use App\Models\Presentation;
use App\Models\Slide;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Imagick;

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

        if ($this->argument('type') === 'video') {
            $this->processVideo($presentation);
            return;
        }

        $this->processPdf($presentation);
    }

    public function processPdf($presentation)
    {

        $i = 0;
        $images = [];

        while(true) {
            $random = Str::random(7);
            $imagename = $random . '-' . $i . '.jpg';

            $image = $this->processPdfPageToImage(storage_path('app/public/presentations/' . $presentation->id . '/' . $presentation->id) . '.pdf', $i, $presentation->id, $imagename);

            if ($image) {
                $images[] = $imagename;
            } else {
                break;
            }

            Slide::updateOrCreate([
                'presentation_id' => $presentation->id,
                'order' => $i + 1,
                'type' => 'image',
            ], [
                'name_on_disk' => $imagename,
                'name' => $random,
            ]);

            $i++;
        }

        $this->finish($presentation, count($images), 'pdf');
    }

    /**
     * Videos are not converted, the uploaded mp4 is used as a single slide.
     */
    public function processVideo($presentation)
    {
        $source = storage_path('app/public/presentations/' . $presentation->id . '/' . $presentation->id . '.mp4');
        $slides = 0;

        if (File::exists($source)) {
            $random = Str::random(7);
            $videoname = $random . '-v.mp4';

            File::ensureDirectoryExists(public_path('data/presentations/' . $presentation->id));
            File::move($source, public_path('data/presentations/' . $presentation->id . '/' . $videoname));

            Slide::updateOrCreate([
                'presentation_id' => $presentation->id,
                'order' => 1,
                'type' => 'video',
            ], [
                'name_on_disk' => $videoname,
                'name' => $random,
            ]);

            $slides = 1;
        }

        $this->finish($presentation, $slides, 'video');
    }

    private function finish($presentation, int $slides, string $type)
    {
        File::deleteDirectory(storage_path('app/public/presentations/' . $presentation->id . '/'));

        $presentation->processed = true;
        $presentation->total_slides = $slides;
        $presentation->save();

        Log::create([
            'ip_address' => '127.0.0.1',
            'username' => "System",
            'action' => __('log.presentation_file_success_updated', ['name' => $presentation->name, 'type' => $type, 'pages' => $slides]),
        ]);
    }

    public function processPdfPageToImage($pdf, $page, $pres_id, $imagename) {
        $image = new Imagick();
        $image->setResolution(300, 300);
        try {
            $image->readImage($pdf . '[' . $page . ']');
        } catch (\ImagickException $e) {
            return false;
        }

        $image->setImageFormat('jpg');


        if ($image->getImageWidth() == 0) {
            return false;
        }

        // Compress
        $image->setImageCompressionQuality(100);

        // Resize
        $image->resizeImage(config('app.image_width'), config('app.image_height'), Imagick::FILTER_LANCZOS, 1);

        $image->writeImage(public_path('data/presentations/' . $pres_id . '/' . $imagename));

        $image->clear();

        return true;
    }
}
