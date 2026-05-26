<x-app-layout>

    <x-slot name="title">Feedback Hub</x-slot>
    <x-slot name="url_1">{"link": "/client/list", "text": "Feedback"}</x-slot>
    <x-slot name="active">Hub</x-slot>
    <x-slot name="buttons"></x-slot>

    <link rel="stylesheet" href="/assets/libs/gridjs/theme/mermaid.min.css">
    <link href="/assets/libs/node-waves/waves.min.css" rel="stylesheet">

    <link rel="stylesheet" href="/assets/libs/quill/quill.snow.css">
    <link rel="stylesheet" href="/assets/libs/quill/quill.bubble.css">
    <link rel="stylesheet" href="/assets/libs/filepond/filepond.min.css">
    <link rel="stylesheet" href="/assets/libs/filepond-plugin-image-preview/filepond-plugin-image-preview.min.css">
    <link rel="stylesheet" href="/assets/libs/filepond-plugin-image-edit/filepond-plugin-image-edit.min.css">

    <div class="grid grid-cols-12 gap-6">
        <div class="xl:col-span-12 col-span-12">
            <div class="box custom-box">
                <div class="box-header">
                    <div class="box-body">
                        <form
                            action=""
                            method="POST">
                            @csrf
                            @if (isset($welcomeMessage))
                                @method('PUT')
                            @endif

                            <div class="mb-3">
                                <label for="client_id" class="form-label">Subject</label>
                                <input type="text" class="form-control">
                            </div>

                            <div class="mb-3 mt-5">

                                <input class="form-control xl:col-span-12 hidden" name="welcome_message"
                                    id="welcome_message" class="" style="width: 200% !important" required>
                                <div class="xl:col-span-12 col-span-12">
                                    <label class="form-label">Message : </label>
                                    <div contenteditable="false" id="project-descriptioin-editor"
                                        oninput="updateDescription()" class=" border p-2 min-h-[00px]">

                                    </div>
                                </div>

                                {{ old('welcome_message', $welcomeMessage->welcome_message ?? '') }}

                            </div>

                            <div class="mb-3">
                                
                            </div>

                            <button type="submit"
                                class="btn btn-primary">{{ isset($welcomeMessage) ? 'Update' : 'Create' }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- Custom-Switcher JS -->
    <script src="/assets/js/custom-switcher.min.js"></script>

    <!-- Quill Editor JS -->
    <script src="/assets/libs/quill/quill.js"></script>

    <!-- Filepond JS -->
    <script src="/assets/libs/filepond/filepond.min.js"></script>
    <script src="/assets/libs/filepond-plugin-image-preview/filepond-plugin-image-preview.min.js"></script>
    <script src="/assets/libs/filepond-plugin-image-exif-orientation/filepond-plugin-image-exif-orientation.min.js">
    </script>
    <script src="/assets/libs/filepond-plugin-file-validate-size/filepond-plugin-file-validate-size.min.js"></script>
    <script src="/assets/libs/filepond-plugin-file-encode/filepond-plugin-file-encode.min.js"></script>
    <script src="/assets/libs/filepond-plugin-image-edit/filepond-plugin-image-edit.min.js"></script>
    <script src="/assets/libs/filepond-plugin-file-validate-type/filepond-plugin-file-validate-type.min.js"></script>
    <script src="/assets/libs/filepond-plugin-image-crop/filepond-plugin-image-crop.min.js"></script>
    <script src="/assets/libs/filepond-plugin-image-resize/filepond-plugin-image-resize.min.js"></script>
    <script src="/assets/libs/filepond-plugin-image-transform/filepond-plugin-image-transform.min.js"></script>

    <!-- Flat Picker JS -->
    <script src="/assets/libs/flatpickr/flatpickr.min.js"></script>

    <script>
        (function() {
            "use strict"

            flatpickr("#startDate", {
                enableTime: true,
                dateFormat: "Y-m-d H:i",
            });

            flatpickr("#endDate", {
                enableTime: true,
                dateFormat: "Y-m-d H:i",
            });

            // const multipleCancelButton = new Choices('#assigned-team-members', {
            //     allowHTML: true,
            //     removeItemButton: true,
            // });

            // const multipleCancelButton_x = new Choices('#invite-client', {
            //     allowHTML: true,
            //     removeItemButton: true,
            // });

            var toolbarOptions = [
                [{
                    'header': [1, 2, 3, 4, 5, 6, false]
                }],
                [{
                    'font': ['Rubic']
                }],
                ['bold', 'italic', 'underline', 'strike'],
                ['blockquote', 'code-block'],
                [{
                    'list': 'ordered'
                }, {
                    'list': 'bullet'
                }],
                [{
                    'indent': '-1'
                }, {
                    'indent': '+1'
                }],
                [{
                    'direction': 'rtl'
                }],
                [{
                    'color': []
                }, {
                    'background': []
                }],
                [{
                    'align': []
                }],
                ['image', 'video', 'link'],
                ['clean']
            ];
            var quill = new Quill('#project-descriptioin-editor', {
                modules: {
                    toolbar: toolbarOptions
                },
                theme: 'snow'
            });

            document.querySelector('form').addEventListener('submit', function() {
                document.querySelector('#welcome_message').value = quill.root.innerHTML;
            });

            FilePond.registerPlugin(
                FilePondPluginImagePreview,
                FilePondPluginImageExifOrientation,
                FilePondPluginFileValidateSize,
                FilePondPluginFileEncode,
                FilePondPluginImageEdit,
                FilePondPluginFileValidateType,
                FilePondPluginImageCrop,
                FilePondPluginImageResize,
                FilePondPluginImageTransform
            );

            const MultipleElement = document.querySelector('.multiple-filepond');
            FilePond.create(MultipleElement);
        })();

        function updateDescription() {
            document.querySelector('#welcome_message').value = document.querySelector('#project-descriptioin-editor')
                .innerHTML;
        }
    </script>

    <style>
        .choices__list--multiple .choices__item {
            border-width: 1px !important;
            border-style: solid !important;
            border-color: rgb(255, 255, 255) !important;
            background-color: #FFBE5F !important;
            color: #303030;
            padding-top: 2px !important;
        }

        .choices__button {
            padding-left: 30px !important;
            color: #000 !important;
        }
    </style>

</x-app-layout>
