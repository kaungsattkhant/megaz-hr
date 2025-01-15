<?php

namespace App\Imports;

use App\Models\Item;
use Illuminate\Validation\Rule;
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

class ItemsImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError, SkipsOnFailure, WithBatchInserts, WithChunkReading
{

    use Importable, SkipsErrors, SkipsFailures;


    private $importedCodes = [];
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Item([
            'name' => $row['name'],
            'code' => $row['code'],
            'category_id' => $row['category_id'],
            'item_type_id' => $row['item_type_id'],
            'base_uom_id' => $row['base_uom_id'],
            'uom_id' => $row['uom_id'],
            'lead_time' => $row['lead_time'],
            'minimum_holding_amount' => $row['minimum_holding_amount']
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => 'required',
            'code' => [
                'required',
                Rule::unique('items', 'code'), // Check uniqueness in the database
                function ($attribute, $value, $fail) {
                    // Check uniqueness within the file
                    if (in_array($value, $this->importedCodes)) {
                        $fail("The $attribute '{$value}' is duplicated in the file.");
                    } else {
                        $this->importedCodes[] = $value;
                    }
                },
            ],
            'category_id' => 'required',
            'item_type_id' => 'required',
            'base_uom_id' => 'required',
            'uom_id' => 'required',
            'lead_time' => 'required',
            'minimum_holding_amount' => 'required'
        ];
    }


    public function batchSize(): int
    {
        return 1000;
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
