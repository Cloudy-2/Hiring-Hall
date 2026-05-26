<form action="{{ route('contact.update', $id) }}" method="POST" enctype="multipart/form-data" autocapitalize="true"
    autocomplete="off">
    @csrf
    <div class="box-body">

        <h6 class="font-bold text-2xl text-gray-700 dark:text-white">
            <strong>Relationship Details</strong>
        </h6>
        <span>You can modify the relationship details here.</span>
        <hr class="mb-3 !mt-3">
        @if ($errors->any())
            <div
                class="alert alert-danger alert-dismissible fade show custom-alert-icon shadow-sm flex items-center mx-3">
                <div>
                    <strong class="text-danger">Whoops! Something went wrong:</strong>
                    <ul class="list-disc list-inside mt-2 mx-4">
                        @foreach ($errors->all() as $error)
                            <li class="text-dark"><i>{{ $error }}</i></li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        @include('pages.relationships.details.partials.company')
    </div>

    <div class="box-footer flex gap-3 justify-end ">
        <button type="button" onclick="remove_data({{ $id }}, 'company')"
            class="bg-gray-100 text-danger px-4 py-2 rounded-md !hover:bg-green-800 transition">
            <i class="bi bi-trash "></i>
            <span class="mx-1">Delete</span>
        </button>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md !hover:bg-green-800 transition">
            <i class="bi bi-check2-circle"></i>
            <span class="mx-1">Save Changes</span>
        </button>
    </div>
</form>
