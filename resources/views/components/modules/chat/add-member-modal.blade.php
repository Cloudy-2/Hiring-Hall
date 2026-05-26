{{-- Add Member Modal Component --}}
@props(['conversationId'])

<div id="add-member-modal" class="fixed inset-0 z-40 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    {{-- Backdrop --}}
    <div class="fixed inset-0 bg-black/80 backdrop-blur-sm transition-opacity" onclick="closeAddMemberModal()"></div>

    {{-- Modal Content --}}
    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative transform overflow-hidden rounded-2xl bg-white shadow-2xl transition-all w-full max-w-md">
                {{-- Header --}}
                <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 rounded-full bg-white/20 flex items-center justify-center">
                                <i class="bi bi-person-plus-fill text-white text-lg"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-white" id="modal-title">Add New Member</h3>
                                <p class="text-xs text-indigo-100">Search and add users to this conversation</p>
                            </div>
                        </div>
                        <button type="button" onclick="closeAddMemberModal()"
                            class="rounded-full p-1.5 text-white/80 hover:text-white hover:bg-white/10 transition">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>
                </div>

                {{-- Body --}}
                <div class="px-6 py-5">
                    {{-- Search Input --}}
                    <div class="mb-4">
                        <label for="member-search" class="block text-sm font-medium text-slate-700 mb-1.5">
                            Search Users
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="bi bi-search text-slate-400"></i>
                            </span>
                            <input type="text" id="member-search" placeholder="Type name or email..."
                                class="w-full pl-10 pr-4 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                                oninput="searchMembers(this.value)">
                        </div>
                    </div>

                    {{-- Search Results --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">
                            Available Users
                        </label>
                        <div id="member-search-results" class="border rounded-lg bg-slate-50 max-h-48 overflow-y-auto">
                            <div class="px-4 py-8 text-center text-sm text-slate-400">
                                <i class="bi bi-person-gear text-2xl mb-2 block"></i>
                                Start typing to search for users
                            </div>
                        </div>
                    </div>

                    {{-- Selected Members --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">
                            Selected Members <span id="selected-count" class="text-slate-400">(0)</span>
                        </label>
                        <div id="selected-members" class="flex flex-wrap gap-2 min-h-[40px] p-2 border rounded-lg bg-white">
                            <span class="text-xs text-slate-400" id="no-selected-msg">No members selected yet</span>
                        </div>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="bg-slate-50 px-6 py-4 flex items-center justify-end gap-3">
                    <button type="button" onclick="closeAddMemberModal()"
                        class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 transition">
                        Cancel
                    </button>
                    <button type="button" onclick="submitAddMembers()"
                        class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition flex items-center gap-2">
                        <i class="bi bi-person-plus"></i>
                        Add Members
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Ensure SweetAlert2 appears on top --}}
<style>
    .swal2-container {
        z-index: 99999 !important;
    }
</style>
