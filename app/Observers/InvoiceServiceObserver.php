<?php

namespace App\Observers;

use App\Models\Service;
use App\Models\InvoiceService;

class InvoiceServiceObserver
{
    /**
     * Handle the InvoiceService "created" event.
     */
    public function created(InvoiceService $invoiceService): void
    {
        //
    }

    /**
     * Handle the InvoiceService "updated" event.
     */
    public function updated(InvoiceService $invoiceService): void
    {
        //
        Service::where('id', $invoiceService->service_id)->update([
            'is_active' => $invoiceService->is_active,
        ]);
    }

    /**
     * Handle the InvoiceService "deleted" event.
     */
    public function deleted(InvoiceService $invoiceService): void
    {
        //
    }

    /**
     * Handle the InvoiceService "restored" event.
     */
    public function restored(InvoiceService $invoiceService): void
    {
        //
    }

    /**
     * Handle the InvoiceService "force deleted" event.
     */
    public function forceDeleted(InvoiceService $invoiceService): void
    {
        //
    }
}
