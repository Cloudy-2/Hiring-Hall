<h3 class="text-lg mx-2"><strong>Folders</strong></h3>
<div class="grid grid-cols-12 gap-3 shadow-none">
    @foreach ($drive as $file)
        @if ($file->is_folder)
            @include('modules.file-manager.partials.folder-card', [
                'file' => $file,
                'clientId' => $clientId,
            ])
        @endif
    @endforeach
</div>
