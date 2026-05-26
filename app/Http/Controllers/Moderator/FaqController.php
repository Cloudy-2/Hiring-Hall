<?php

namespace App\Http\Controllers\Moderator;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    protected function ensureModerator(Request $request)
    {
        $user = $request->user();

        if (! $user || ! in_array($user->role, ['moderator', 'admin', 'super_admin'])) {
            abort(403);
        }

        return $user;
    }

    public function index(Request $request)
    {
        $this->ensureModerator($request);

        $category = $request->input('category');

        $query = Faq::with('creator');

        if ($category) {
            $query->inCategory($category);
        }

        $faqs = $query->ordered()->paginate(20)->withQueryString();
        $categories = Faq::getCategories();

        return view('moderator.faqs.index', compact('faqs', 'categories', 'category'));
    }

    public function create(Request $request)
    {
        $this->ensureModerator($request);

        $categories = Faq::getCategories();

        return view('moderator.faqs.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $user = $this->ensureModerator($request);

        $validated = $request->validate([
            'question' => 'required|string|max:500',
            'answer' => 'required|string|max:10000',
            'category' => 'nullable|string|max:100',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        Faq::create([
            'question' => $validated['question'],
            'answer' => $validated['answer'],
            'category' => $validated['category'] ?: null,
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => $validated['is_active'] ?? true,
            'created_by' => $user->id,
        ]);

        return redirect()->route('moderator.faqs.index')
            ->with('status', 'FAQ created successfully.');
    }

    public function edit(Request $request, Faq $faq)
    {
        $this->ensureModerator($request);

        $categories = Faq::getCategories();

        return view('moderator.faqs.edit', compact('faq', 'categories'));
    }

    public function update(Request $request, Faq $faq)
    {
        $this->ensureModerator($request);

        $validated = $request->validate([
            'question' => 'required|string|max:500',
            'answer' => 'required|string|max:10000',
            'category' => 'nullable|string|max:100',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $faq->update([
            'question' => $validated['question'],
            'answer' => $validated['answer'],
            'category' => $validated['category'] ?: null,
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => $validated['is_active'] ?? true,
        ]);

        return redirect()->route('moderator.faqs.index')
            ->with('status', 'FAQ updated successfully.');
    }

    public function destroy(Request $request, Faq $faq)
    {
        $this->ensureModerator($request);

        $faq->delete();

        return redirect()->route('moderator.faqs.index')
            ->with('status', 'FAQ deleted successfully.');
    }

    public function toggleActive(Request $request, Faq $faq)
    {
        $this->ensureModerator($request);

        $faq->update(['is_active' => ! $faq->is_active]);

        $action = $faq->is_active ? 'activated' : 'deactivated';

        return redirect()->back()->with('status', 'FAQ has been '.$action.'.');
    }

    public function updateOrder(Request $request)
    {
        $this->ensureModerator($request);

        $validated = $request->validate([
            'orders' => 'required|array',
            'orders.*.id' => 'required|exists:faqs,id',
            'orders.*.sort_order' => 'required|integer|min:0',
        ]);

        foreach ($validated['orders'] as $item) {
            Faq::where('id', $item['id'])->update(['sort_order' => $item['sort_order']]);
        }

        return response()->json(['status' => 'ok']);
    }
}
