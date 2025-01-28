<?php

namespace Database\Seeders;

use App\Models\Feature;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class FeatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $features = [
            'staff',
            'role',
            'task',
            'complaint',
            'department',
            'area',
            'room',
            'table',
            'service',
            'item',
            'uom conversion',
            'uom',
            'menu',
            'item usage forecast',
            'purchase order',
            'purchase order confirmation',
            'inventory',
            'inventory confirmation',
            'inventory transfer list',
            'inventory receive list',
            'supplier',
            'cashbook',
            'fixed asset',
            'customer',
            'purchase order item left',
            'purchase order item left confirmation',
            'pos',
            'room discount',
            'package',
            'menu service discount',
            'account payables',
            'inventory stocks',
            'crm',
            'sale target',
            'duty',
            // financial
            'asset depreciation balance',
            'journal',
            'staff balance',
            'prepaid',
            'ar',
            'financial report', 
            //financial
            'skill',
            'cooking place',
            'custom task',
            'feature',
            'accessory',
            'MRP',
            'Objective',
            'Ktv Product Tree'
        ];
        foreach ($features as $feature) {
            Feature::create([
                'name' => Str::title($feature),
                'slug' => Str::slug($feature, '-')
            ]);
        }
    }
}
