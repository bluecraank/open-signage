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
    protected $signature = 'presentation:process {id}';

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
                'number' => $i,
            ], [
                'presentation_id' => $presentation->id,
                'order' => $i,
                'name_on_disk' => $imagename,
                'name' => $random,
            ]);
        }

        $presentation->processed = true;

        Storage::delete(storage_path('app/public/presentations/'. $presentation->id . '/' . $presentation->id) . '.pdf');

        $presentation->save();
    }
}
