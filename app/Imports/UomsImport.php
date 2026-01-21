<?php

namespace App\Imports;

use App\Models\Uom;
use App\Models\UomConversion;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Row;

class UomsImport implements OnEachRow, WithHeadingRow, WithValidation, SkipsOnError, SkipsOnFailure, WithBatchInserts, WithChunkReading
{
    use Importable, SkipsErrors, SkipsFailures;

    public function onRow(Row $row)
    {
        $data = $row->toArray();

        $name = trim($data['name']);
        $code = trim($data['uom_code']);
        $isActive = $data['is_active'] ?? 1;
        $userId = UserData()->id ?? null;

        // Update existing or create new Uom
        $uom = Uom::updateOrCreate(
            ['uom_code' => $code], // unique key
            [
                'uom_code' => $code,
                'name' => $name,
                'is_active' => $isActive,
                'created_by' => $userId,
            ]
        );

        // Optional: ensure self-conversion exists
        // UomConversion::firstOrCreate(
        //     [
        //         'base_unit_id' => $uom->id,
        //         'conversion_unit_id' => $uom->id,
        //     ],
        //     [
        //         'conversion' => 1, // Default conversion rate
        //         'created_by' => $userId,
        //         'is_show' => 0,
        //     ]
        // );
    }

    public function rules(): array
    {
        return [
            'uom_code' => 'required',
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
