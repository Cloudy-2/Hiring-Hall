<x-app-layout>

    <x-slot name="return">{"link": "#", "text": "Ticket"}</x-slot>
    <x-slot name="title">Submit Ticket</x-slot>
    <x-slot name="url_1">{"link": "#", "text": "Manage Tickets"}</x-slot>
    <x-slot name="active">Submit Ticket</x-slot>

    <form action="{{ route('tickets.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="grid grid-cols-12 gap-6">
            <div class="xl:col-span-12 col-span-12">
                <div class="box">
                    <div class="box-body">
                        <table
                            class="ti-custom-table ti-custom-table-head !border border-defaultborder dark:border-defaultborder/10">
                            <tbody>

                                <!-- Project Name -->
                                <tr class="border-b border-defaultborder dark:border-defaultborder/10">
                                    <td width="180"
                                        class="text-end border-2 border-defaultborder dark:border-defaultborder/10">
                                        Project Name:
                                    </td>
                                    <td colspan="3"
                                        class="border-2 border-defaultborder dark:border-defaultborder/10 !p-0 !m-0">
                                        <div class="relative p-1">
                                            <input type="text" name="proj_name" id="proj_name"
                                                class="ti-form-input rounded-sm ps-11 focus:z-10"
                                                placeholder="Enter Project Name here..." required>
                                            <div
                                                class="absolute inset-y-0 start-0 flex items-center ps-4 pointer-events-none z-20">
                                                <i class="bi bi-folder"></i>
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Category and Priority -->
                                <tr class="border-b border-defaultborder dark:border-defaultborder/10">
                                    <td class="text-end border-2 border-defaultborder dark:border-defaultborder/10">
                                        Category:</td>
                                    <td class="border-2 border-defaultborder dark:border-defaultborder/10 !p-0">
                                        <div class="relative p-1">
                                            <select name="category" class="form-select p-2 px-4">
                                                <option value="Bug">Bug</option>
                                                <option value="Suggestion">Suggestion</option>
                                                <option value="Question">Question</option>
                                                <option value="Feedback">Feedback</option>
                                            </select>
                                        </div>
                                    </td>
                                    <td class="text-end border-2 border-defaultborder dark:border-defaultborder/10">
                                        Priority:</td>
                                    <td class="border-2 border-defaultborder dark:border-defaultborder/10 !p-0">
                                        <div class="relative p-1">
                                            <select name="priority" class="form-select p-2 px-4">
                                                <option value="Low">Low</option>
                                                <option value="Medium" selected>Medium</option>
                                                <option value="High">High</option>
                                            </select>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Description -->
                                <tr class="border-b border-defaultborder dark:border-defaultborder/10">
                                    <td class="text-end border-2 border-defaultborder dark:border-defaultborder/10">
                                        Description:
                                    </td>
                                    <td colspan="3"
                                        class="border-2 border-defaultborder dark:border-defaultborder/10 !p-0 !m-0">
                                        <div class="relative p-1">
                                            <textarea name="description" rows="4" class="ti-form-input rounded-sm ps-11 focus:z-10"
                                                placeholder="Describe the issue or suggestion in detail..."></textarea>
                                            <div
                                                class="absolute top-2 start-0 flex items-center ps-4 pointer-events-none">
                                                <i class="bi bi-chat-dots"></i>
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                                <!-- File Upload -->
                                <tr class="border-b border-defaultborder dark:border-defaultborder/10">
                                    <td class="text-end border-2 border-defaultborder dark:border-defaultborder/10">
                                        Attachments:
                                    </td>
                                    <td colspan="3"
                                        class="border-2 border-defaultborder dark:border-defaultborder/10 !p-0">
                                        <div class="relative p-1">
                                            <input type="file" name="attachments[]" multiple
                                                accept=".jpg,.jpeg,.png,.pdf,.docx,.log,.txt,.mp4,.mov,.avi,.mkv"
                                                class="ti-form-input file-input rounded-sm ps-11">

                                            <small class="text-xs text-gray-500 block mt-1 ps-11">You can upload
                                                multiple files. Max 10MB each.</small>
                                            <div
                                                class="absolute inset-y-0 start-0 flex items-center pointer-events-none z-20 ps-4">
                                                <i class="bi bi-paperclip"></i>
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                            </tbody>
                        </table>

                        <div class="text-end mt-4">
                            <button type="submit" class="ti-btn bg-blue-600 text-white">
                                <i class="bi bi-send-check me-1"></i> Submit Ticket
                            </button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </form>

</x-app-layout>
