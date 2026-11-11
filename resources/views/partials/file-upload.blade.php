{{-- File picker with drag and drop, the handlers live in resources/js/app.js --}}
<div id="drop_zone" ondrop="window.dropHandler(event)" ondragover="window.dragOverHandler(event)">
    <i class="bi bi-cloud-arrow-up"></i>
    <div class="mb-3">{{ __('Drop a PDF or MP4 file here or choose one') }}</div>
    <div id="file-upload" class="mx-auto" style="max-width: 28rem">
        <input class="form-control file-input" type="file" name="file" @if ($required ?? false) required @endif
            accept="application/pdf,video/mp4" id="formFile">
    </div>
    <div class="small mt-2">{{ __('PDF: one slide per page. MP4: played as a video, use H.264.') }}</div>
</div>
