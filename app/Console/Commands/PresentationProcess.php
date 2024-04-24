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


        File::delete(storage_path('app/public/presentations/' . $presentation->id . '/' . $presentation->id) . '.pdf');
        File::deleteDirectory(storage_path('app/public/presentations/' . $presentation->id . '/'));

        $presentation->processed = true;
        $presentation->total_slides = count($images);
        $presentation->save();

        Log::create([
            'ip_address' => request()->ip(),
            'username' => "System",
            'action' => __('log.presentation_file_success_updated', ['name' => $presentation->name, 'type' => 'pdf', 'pages' => $i]),
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
