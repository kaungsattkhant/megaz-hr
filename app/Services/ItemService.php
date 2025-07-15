<?php

namespace App\Services;

use App\Models\UomConversion;

class ItemService
{
  public function calculateMinimumHoldingAmount($minHoldingQuantity, $minHoldingUomQuantity, $conversionRate)
  {
    if ($conversionRate > 0) {
      $minHoldingQuantity = (float) $minHoldingQuantity;
      $minHoldingUomQuantity = (float) $minHoldingUomQuantity;
      $conversionRate = (float) $conversionRate;

      return ($minHoldingQuantity * $conversionRate) + $minHoldingUomQuantity;
    }
    return 0;
  }

  public function uomConversionRate($baseUomId, $uomId)
  {
    return UomConversion::where('base_unit_id', $baseUomId)
      ->where('conversion_unit_id', $uomId)
      ->where('is_active', 1)
      ->first();
  }
}
