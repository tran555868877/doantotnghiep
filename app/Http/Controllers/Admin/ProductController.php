<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index()
    {
        return view('admin.products.index', [
            'products' => Product::with('category')->latest()->paginate(20),
        ]);
    }

    public function create()
    {
        return view('admin.products.form', [
            'product' => new Product(),
            'categories' => Category::orderBy('name')->get(),
            'categoryTree' => Category::query()
                ->whereNull('parent_id')
                ->with(['children' => fn ($query) => $query->orderBy('name')])
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['slug'] = Str::slug($data['name']).'-'.Str::random(4);
        $data['sku'] = $data['sku'] ?: 'SKU-'.strtoupper(Str::random(8));
        $data['attributes'] = $this->parseAttributes($data['attributes'] ?? '');
        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_active'] = $request->boolean('is_active');
        $data['thumbnail'] = $this->storeUploadedImage($request, 'thumbnail_file', 'products');
        $data['gallery'] = $this->storeGalleryImages($request);
        Product::create($data);

        return redirect()->route('admin.products.index')->with('success', 'Đã tạo sản phẩm.');
    }

    public function edit(Product $product)
    {
        return view('admin.products.form', [
            'product' => $product,
            'categories' => Category::orderBy('name')->get(),
            'categoryTree' => Category::query()
                ->whereNull('parent_id')
                ->with(['children' => fn ($query) => $query->orderBy('name')])
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $data = $this->validated($request, $product->id);
        $data['slug'] = Str::slug($data['name']).'-'.$product->id;
        $data['attributes'] = $this->parseAttributes($data['attributes'] ?? '');
        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_active'] = $request->boolean('is_active');
        $data['thumbnail'] = $this->storeUploadedImage($request, 'thumbnail_file', 'products', $product->thumbnail);
        $data['gallery'] = array_values(array_filter(array_merge($product->gallery ?? [], $this->storeGalleryImages($request))));
        $product->update($data);

        return redirect()->route('admin.products.index')->with('success', 'Đã cập nhật sản phẩm.');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Đã xóa sản phẩm.');
    }

    protected function validated(Request $request, ?int $productId = null): array
    {
        return $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['nullable', 'string', 'max:255', Rule::unique('products', 'sku')->ignore($productId)],
            'brand' => ['nullable', 'string', 'max:255'],
            'origin_country' => ['nullable', 'string', 'max:255'],
            'age_group' => ['nullable', 'string', 'max:255'],
            'unit' => ['nullable', 'string', 'max:50'],
            'price' => ['required', 'numeric'],
            'sale_price' => ['nullable', 'numeric'],
            'stock' => ['required', 'integer'],
            'rating' => ['nullable', 'numeric'],
            'thumbnail_file' => ['nullable', 'image', 'max:4096'],
            'gallery_files.*' => ['nullable', 'image', 'max:4096'],
            'short_description' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'attributes' => ['nullable', 'string'],
            'is_featured' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]);
    }

    protected function parseAttributes(string $input): array
    {
        return collect(explode(PHP_EOL, $input))
            ->filter()
            ->mapWithKeys(function ($row) {
                [$key, $value] = array_pad(explode(':', $row, 2), 2, '');

                return [trim($key) => trim($value)];
            })
            ->all();
    }

    protected function storeUploadedImage(Request $request, string $field, string $folder, ?string $currentPath = null): ?string
    {
        if (! $request->hasFile($field)) {
            return $currentPath;
        }

        $file = $request->file($field);
        $targetDirectory = public_path('uploads/'.$folder);

        if (! is_dir($targetDirectory)) {
            mkdir($targetDirectory, 0755, true);
        }

        $fileName = now()->format('YmdHis').'-'.Str::random(8).'.'.$file->getClientOriginalExtension();
        $file->move($targetDirectory, $fileName);

        return '/uploads/'.$folder.'/'.$fileName;
    }

    protected function storeGalleryImages(Request $request): array
    {
        if (! $request->hasFile('gallery_files')) {
            return [];
        }

        $targetDirectory = public_path('uploads/products/gallery');
        if (! is_dir($targetDirectory)) {
            mkdir($targetDirectory, 0755, true);
        }

        $paths = [];
        foreach ($request->file('gallery_files') as $index => $file) {
            $fileName = now()->format('YmdHis').'-'.Str::random(6).'-'.$index.'.'.$file->getClientOriginalExtension();
            $file->move($targetDirectory, $fileName);
            $paths[] = '/uploads/products/gallery/'.$fileName;
        }

        return $paths;
    }
}
