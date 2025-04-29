<?php

namespace App\Http\Controllers;

use App\Models\{Product, Category, Supplier};
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\ProductRequest;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProductExport;
use App\Imports\ProductImport;

class ProductController extends Controller
{
    public function index(Request $request): View|array
    {
        $products = Product::with(['category', 'supplier', 'stock'])
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
            })
            ->paginate(10);

        if ($request->ajax()) {
            return [
                'products' => $products->items(),
                'pagination' => [
                    'total' => $products->total(),
                    'per_page' => $products->perPage(),
                    'current_page' => $products->currentPage(),
                    'last_page' => $products->lastPage(),
                ]
            ];
        }

        return view('products.index', [
            'products' => $products,
            'categories' => Category::all(),
            'suppliers' => Supplier::all()
        ]);
    }

    public function store(ProductRequest $request)
    {
        $validated = $request->validated();

        if ($request->hasFile('picture')) {
            $validated['picture'] = $request->file('picture')->store('products', 'public');
        }

        $product = Product::create($validated);

        return $request->ajax()
            ? response()->json(['success' => true, 'product' => $product])
            : redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    public function show(Product $product)
    {
        return response()->json($product);
    }

    public function update(ProductRequest $request, Product $product)
    {
        $validated = $request->validated();

        if ($request->hasFile('picture')) {
            if ($product->picture) {
                Storage::disk('public')->delete($product->picture);
            }

            $validated['picture'] = $request->file('picture')->store('products', 'public');
        }

        $product->update($validated);

        return $request->ajax()
            ? response()->json(['success' => true, 'product' => $product])
            : redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        if ($product->picture) {
            Storage::disk('public')->delete($product->picture);
        }

        $product->delete();

        return response()->json(['success' => true]);
    }

    public function export()
    {
        return Excel::download(new ProductExport, 'products.xlsx');
    }

    public function import(Request $request)
    {
        Excel::import(new ProductImport, $request->file('file'));

        return back()->with('success', 'Products imported successfully.');
    }
}
