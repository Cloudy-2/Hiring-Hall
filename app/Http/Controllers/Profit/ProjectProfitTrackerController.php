<?php

namespace App\Http\Controllers\Profit;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Clients;
use App\Models\Lead;
use App\Models\ProjectProfitTracker;
use App\Models\V2_ProfitTracker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectProfitTrackerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function index()
    // {
    //     return view('pages.apps.profit.project.index');
    // }
    // public function create()
    // {
    //     return view('pages.apps.profit.project.forms.create');
    // }

    // public function store(Request $request)
    // {
    //     $validated = $request->validate([
    //         'company' => 'required|string|max:255',
    //         'project' => 'required|string|max:255',
    //         'address' => 'nullable|string',
    //         'total_price' => 'nullable|numeric',
    //         'sf' => 'nullable|numeric',
    //         'vendio' => 'nullable|numeric',
    //         'bid' => 'nullable|string',
    //         'devi' => 'nullable|string',
    //         'scope' => 'nullable|string',
    //         'sent' => 'nullable|date',
    //         'contact_name' => 'nullable|string',
    //         'email' => 'nullable|email',
    //         'phone' => 'nullable|string',
    //         'award_date' => 'nullable|date',
    //         'follow_up' => 'nullable|string',
    //     ]);

    //     ProjectProfitTracker::create($validated);

    //     return redirect()->route('projects.index')->with('success', 'Project created successfully.');
    // }

    // /**
    //  * Display the specified resource.
    //  */
    // public function show(ProjectProfitTracker $projectProfitTracker)
    // {
    //     //
    // }

    // /**
    //  * Show the form for editing the specified resource.
    //  */
    // public function edit(ProjectProfitTracker $projectProfitTracker)
    // {
    //     //
    // }

    // /**
    //  * Update the specified resource in storage.
    //  */
    // public function update(Request $request, ProjectProfitTracker $projectProfitTracker)
    // {
    //     //
    // }

    // /**
    //  * Remove the specified resource from storage.
    //  */
    // public function destroy(ProjectProfitTracker $projectProfitTracker)
    // {
    //     //
    // }

    // public function generic()
    // {
    //     $user = Auth::user();
    //     $clientId = null;

    //     if ($user->role === 'Virtual Assistant') {
    //         $clientId = $user->company;
    //     } elseif ($user->role === 'Sub-Client') {
    //         $clientId = Clients::where('email', $user->email)->value('id');
    //     } else {
    //         $clientId = Lead::where('email', $user->email)->value('id');
    //     }

    //     $leads = V2_ProfitTracker::where('user_id', $clientId)->get();

    //     // Count the total number of rows and columns
    //     $rowCount = $leads->max('row_index') + 1; // Assuming row_index is zero-based, add 1 for count
    //     $colCount = $leads->max('column_index') + 1; // Assuming column_index is zero-based, add 1 for count

    //     // Pass the leads, rowCount, and colCount to the view
    //     return view('pages.apps.profit.tracker.index', compact('leads', 'rowCount', 'colCount'));
    // }

    // public function updateCell(Request $request)
    // {
    //     $user = Auth::user();
    //     $clientId = null;

    //     if ($user->role === 'Virtual Assistant') {
    //         $clientId = $user->company;
    //     } elseif ($user->role === 'Sub-Client') {
    //         $clientId = Clients::where('email', $user->email)->value('id');
    //     } else {
    //         $clientId = Lead::where('email', $user->email)->value('id');
    //     }

    //     $request->validate([
    //         'row' => 'required|integer',
    //         'column' => 'required|integer',
    //         'value' => 'nullable|string',
    //     ]);

    //     $cell = V2_ProfitTracker::updateOrCreate(['row_index' => $request->row, 'column_index' => $request->column, 'user_id' => $clientId], ['value' => $request->value]);

    //     return response()->json(['success' => true, 'cell' => $cell]);
    // }

    public function form()
    {
        return view('modules.profit-tracker.calculator');
    }
}
