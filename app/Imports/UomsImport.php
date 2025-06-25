<?php

namespace App\Imports;

use App\Models\Uom;
use App\Models\UomConversion;
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

class UomsImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError, SkipsOnFailure, WithBatchInserts, WithChunkReading
{
    use Importable, SkipsErrors, SkipsFailures;

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $uom = Uom::firstOrCreate(
            ['name' => $row['name']],
            [
                'uom_code' => $row['uom_code'],
                'name' => $row['name'],
                'is_active' => $row['is_active'] ?? 1,
                'created_by' => UserData()->id,
            ]
        );

        // UomConversion::firstOrCreate(
        //     [
        //         'base_unit_id' => $uom->id,
        //         'conversion_unit_id' => $uom->id,
        //         'conversion' => 1,
        //     ],
        //     [
        //         'base_unit_id' => $uom->id,
        //         'conversion_unit_id' => $uom->id,
        //         'conversion' => 1, // Default conversion rate
        //         'created_by' => UserData()->id,
        //         'is_show' => 0
        //     ]
        // );
        return $uom;
    }

    public function rules(): array
    {
        return [
            'uom_code' => 'required|unique:uoms,uom_code',
            'name' => 'required|unique:uoms,name',
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
