<?php
namespace App\Imports;
use App\Models\ItemType;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\Importable;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Row;

class ItemTypeImport implements OnEachRow, WithHeadingRow
{
    use Importable;

    public function onRow(Row $row)
    {
        $data = $row->toArray();
        Log::info('Reach Item Type Import', $data);

        $itemType = ItemType::updateOrCreate(
            ['item_type_code' => trim($data['item_type_code'])],
            [
                'name' => trim($data['name']),
                'is_active' => $data['is_active'] ?? 1,
            ]
        );

        Log::info('Successfully Item Type Import', ['id' => $itemType->id]);
    }
}
