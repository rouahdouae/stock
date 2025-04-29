<?php

namespace App\Imports;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ProductImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        // Skip if product already exists
        if (Product::find($row['id'])) {
            return;
        }

        // Get or create category
        $category = Category::firstOrCreate(['name' => $row['category']]);

        // Get or create supplier
        $supplier = Supplier::where(DB::raw("CONCAT(first_name, ' ', last_name)"), $row['supplier'])->first();

        if (!$supplier) {
            [$firstName, $lastName] = explode(' ', trim($row['supplier']), 2) + [1 => $row['supplier']];
            $supplier = Supplier::create([
                'first_name' => $firstName,
                'last_name' => $lastName,
                'phone' => ' ',
            ]);
        }

        return new Product([
            'id' => $row['id'],
            'name' => $row['name'],
            'description' => $row['description'],
            'price' => $row['price'],
            'category_id' => $category->id,
            'supplier_id' => $supplier->id
        ]);
    }

    public function rules(): array
    {
        return [
            // Tu peux activer les validations ici si besoin
        ];
    }

    public function headingRow(): int
    {
        return 5;
    }
}
