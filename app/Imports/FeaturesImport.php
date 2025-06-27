<?php

namespace App\Imports;

use App\Models\Feature;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class FeaturesImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError, SkipsOnFailure, WithBatchInserts, WithChunkReading
{
    use Importable, SkipsErrors, SkipsFailures;

    /**
     * Handle the error during import.
     *
     * @param \Throwable $e
     */
    public function onError(\Throwable $e)
    {
        return ResponseData('Import failed', 500, $e->getMessage());
    }

    /**
     * Handle the failure during import.
     *
     * @param \Maatwebsite\Excel\Validators\Failure[] $failures
     */
    public function onFailure(\Maatwebsite\Excel\Validators\Failure ...$failures)
    {
        foreach ($failures as $failure) {
            // Log or handle each failure

            return ResponseData('Import failed', 422, 'Validation errors occurred', implode(', ', $failure->errors()));
        }
    }
    public function model(array $row)
    {
        return new Feature([
            'module' => $row['module'],
            'name' => $row['name'],
            'slug' => $row['slug']
        ]);
    }
    public function rules(): array
    {
        return [
            'module' => 'required',
            'name' => 'required',
            'slug' => 'required',
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
