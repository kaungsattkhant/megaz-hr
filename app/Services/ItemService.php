<?php

namespace App\Services;

use App\Models\UomConversion;

class ItemService
{
  public function calculateMinimumHoldingAmount($minHoldingBaseUomQuantity, $minHoldingUomQuantity, $conversionRate)
  {
    if ($conversionRate > 0) {
      $minHoldingBaseUomQuantity = (float) $minHoldingBaseUomQuantity;
      $minHoldingUomQuantity = (float) $minHoldingUomQuantity;
      $conversionRate = (float) $conversionRate;

      return ($minHoldingBaseUomQuantity * $conversionRate) + $minHoldingUomQuantity;
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
