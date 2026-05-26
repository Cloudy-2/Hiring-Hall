<style>
    #preview-modal {
        z-index: 9999 !important;
    }
    .hs-overlay-backdrop {
        z-index: 9998 !important;
    }
</style>
<div id="preview-modal"
    class="hs-overlay hidden w-full h-full fixed top-0 start-0 z-[200] overflow-x-hidden overflow-y-auto pointer-events-none">
    <div
        class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto">
        <div
            class="flex flex-col bg-white border shadow-sm rounded-xl pointer-events-auto dark:bg-gray-800 dark:border-gray-700 dark:shadow-slate-700/[.7]">
            <div class="flex justify-between items-center py-3 px-4 border-b dark:border-gray-700">
                <h3 class="text-xl font-bold text-gray-800 dark:text-white">
                    Relationship Preview
                </h3>
                <button type="button"
                    class="hs-dropdown-toggle inline-flex flex-shrink-0 justify-center items-center h-8 w-8 rounded-md text-gray-500 hover:text-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 focus:ring-offset-white transition-all text-sm dark:focus:ring-gray-700 dark:focus:ring-offset-gray-800"
                    data-hs-overlay="#preview-modal">
                    <span class="sr-only">Close</span>
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            <div class="p-4 overflow-y-auto">
                <div class="grid grid-cols-12 gap-4" id="preview-content">
                    <!-- Content will be populated by JS -->
                    <div class="col-span-12 mb-2">
                        <label class="text-xs font-semibold text-gray-500 uppercase">Company Name</label>
                        <p id="preview-company_name" class="text-lg font-bold text-gray-800 dark:text-gray-200">-</p>
                    </div>
                    <div class="col-span-6 mb-2">
                        <label class="text-xs font-semibold text-gray-500 uppercase">Type</label>
                        <p id="preview-type" class="text-gray-800 dark:text-gray-200">-</p>
                    </div>
                    <div class="col-span-6 mb-2">
                        <label class="text-xs font-semibold text-gray-500 uppercase">Lead Source</label>
                        <p id="preview-lead_source" class="text-gray-800 dark:text-gray-200">-</p>
                    </div>
                    <div class="col-span-12 mb-2">
                        <label class="text-xs font-semibold text-gray-500 uppercase">Address</label>
                        <p id="preview-full-address" class="text-gray-800 dark:text-gray-200">-</p>
                    </div>
                    <div class="col-span-6 mb-2">
                        <label class="text-xs font-semibold text-gray-500 uppercase">Phone</label>
                        <p id="preview-phone" class="text-gray-800 dark:text-gray-200">-</p>
                    </div>
                    <div class="col-span-6 mb-2">
                        <label class="text-xs font-semibold text-gray-500 uppercase">Email</label>
                        <p id="preview-email" class="text-gray-800 dark:text-gray-200">-</p>
                    </div>
                    <div class="col-span-12 mb-2">
                        <label class="text-xs font-semibold text-gray-500 uppercase">Fax</label>
                        <p id="preview-fax" class="text-gray-800 dark:text-gray-200">-</p>
                    </div>
                    <div class="col-span-12 mb-2">
                        <label class="text-xs font-semibold text-gray-500 uppercase">Website</label>
                        <p id="preview-website" class="text-gray-800 dark:text-gray-200">-</p>
                    </div>
                    <div class="col-span-6 mb-2">
                        <label class="text-xs font-semibold text-gray-500 uppercase">License</label>
                        <p id="preview-license" class="text-gray-800 dark:text-gray-200">-</p>
                    </div>
                    <div class="col-span-6 mb-2">
                        <label class="text-xs font-semibold text-gray-500 uppercase">Insurance</label>
                        <p id="preview-insurance" class="text-gray-800 dark:text-gray-200">-</p>
                    </div>
                    <div class="col-span-12">
                        <label class="text-xs font-semibold text-gray-500 uppercase">Notes</label>
                        <p id="preview-notes" class="text-gray-800 dark:text-gray-200 italic">-</p>
                    </div>
                </div>
            </div>
            <div class="flex justify-end items-center gap-x-2 py-3 px-4 border-t dark:border-gray-700">
                <button type="button" class="ti-btn ti-btn-light" data-hs-overlay="#preview-modal">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>