<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index(): View
    {
        $categories = Category::orderBy('created_at', 'desc')->paginate(15);
        return view('categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('categories.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean'
        ]);

        if ($request->hasFile('image')) {
            $disk = app()->environment('production') ? 's3' : 'public';
            $path = $request->file('image')->store('categories', $disk);
            $validated['image'] = $path;
        }

        $validated['is_active'] = $request->has('is_active');

        Category::create($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Categoria criada com sucesso!');
    }

    public function edit(Category $category): View
    {
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean'
        ]);

        if ($request->hasFile('image')) {
            // delete previous image from both disks (if any)
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
                Storage::disk('s3')->delete($category->image);
            }
            $disk = app()->environment('production') ? 's3' : 'public';
            $path = $request->file('image')->store('categories', $disk);
            $validated['image'] = $path;
        }

        $validated['is_active'] = $request->has('is_active');

        $category->update($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Categoria atualizada com sucesso!');
    }

    public function show(Category $category): View
    {
        return view('categories.show', compact('category'));
    }

    public function destroy(Category $category): RedirectResponse
    {
        // Verifica se há produtos nessa categoria
        if ($category->products()->exists()) {
            return back()->with('error', 'Não é possível deletar uma categoria com produtos. Remova os produtos primeiro.');
        }

        // delete image from both disks (if any)
        if ($category->image) {
            Storage::disk('public')->delete($category->image);
            Storage::disk('s3')->delete($category->image);
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Categoria deletada com sucesso!');
    }
}
