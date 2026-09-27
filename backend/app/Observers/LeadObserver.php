<?php

namespace App\Observers;

use App\Models\Customer;
use App\Models\Lead;
use Illuminate\Support\Facades\DB;

class LeadObserver
{
    /**
     * Handle the Lead "created" event.
     */
    public function created(Lead $lead): void
    {
        $this->handleCustomerConversion($lead);
    }

    /**
     * Handle the Lead "updated" event.
     */
    public function updated(Lead $lead): void
    {
        $this->handleCustomerConversion($lead);
    }

    /**
     * Convert lead to customer if status is Won and not linked yet.
     */
    protected function handleCustomerConversion(Lead $lead): void
    {
        if ($lead->status === Lead::STATUS_WON) {
            DB::transaction(function () use ($lead) {
                // Find existing customer by email or create new
                $customer = Customer::firstOrCreate(
                    ['email' => $lead->email],
                    [
                        'name' => $lead->name,
                        'phone' => $lead->phone,
                        'company' => $lead->company,
                    ]
                );

                if ($lead->customer_id !== $customer->id) {
                    $lead->customer_id = $customer->id;
                    $lead->saveQuietly();
                }
            });
        }
    }
}
