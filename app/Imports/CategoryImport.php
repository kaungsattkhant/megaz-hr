<?php

namespace App\Imports;

use App\Models\Category;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\Importable;

class CategoryImport implements OnEachRow, WithHeadingRow, WithValidation, SkipsOnError, SkipsOnFailure, WithBatchInserts, WithChunkReading
{
    use Importable, SkipsErrors, SkipsFailures;

    public function onRow(Row $row)
    {
        $data = $row->toArray();

        // Clean the data
        $categoryCode = trim($data['category_code']);
        $name = trim($data['name']);
        $isActive = $data['is_active'] ?? 1;

        // Update existing or create new
        Category::updateOrCreate(
            ['category_code' => $categoryCode], // Unique key
            [
                'name' => $name,
                'is_active' => $isActive,
            ]
        );
    }

    public function rules(): array
    {
        return [
            'category_code' => 'required',
            'name' => 'required',
        ];
    }

    public function batchSize(): int
    {
        return 500;
    }

    public function chunkSize(): int
    {
        return 500;
    }
}
