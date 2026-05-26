<form action="{{ route('contact.update', $id) }}" method="POST" enctype="multipart/form-data" autocapitalize="true"
    autocomplete="off">
    @csrf
    <div class="box-body">

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div>
                <h6 class="font-bold text-2xl text-gray-700 dark:text-white">
                    <strong>Relationship Details</strong>
                </h6>
                <span class="text-sm text-gray-600 dark:text-gray-300">
                    You can modify the relationship details here.
                </span>
            </div>
            <div class="inline-flex items-center gap-2">
                <button type="button" data-hs-overlay="#create-contact"
                    class="inline-flex items-center gap-2 text-dark rounded-md border bg-white border-slate-300  px-3 py-2 text-sm font-medium  hover:bg-gray-500">
                    <i class="bi bi-plus-circle me-1"></i>
                    <span class="mx-1">New Contact</span>
                </button>
            </div>
        </div>

        <hr class="mb-3 !mt-3">
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show custom-alert-icon shadow-sm flex items-center mx-3">
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
        <button type="submit" class="bg-indigo-500 text-white px-4 py-2 rounded-md !hover:bg-green-800 transition">
            <i class="bi bi-check2-circle"></i>
            <span class="mx-1">Save Changes</span>
        </button>
    </div>
</form>