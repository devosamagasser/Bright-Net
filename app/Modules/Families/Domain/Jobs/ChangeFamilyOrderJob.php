<?php

namespace App\Modules\Families\Domain\Jobs;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Modules\Families\Domain\Models\Family;

class ChangeFamilyOrderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Family $family;
    public Family $familyBefore;

    public function __construct(Family $family, Family $familyBefore)
    {
        $this->family = $family;
        $this->familyBefore = $familyBefore;
    }

    public function handle(): void
    {
        DB::transaction(function (): void {
            $currentOrder = $this->family->order;
            $newOrder = $this->familyBefore->order;

            if ($currentOrder === $newOrder) {
                return;
            }

            if ($currentOrder < $newOrder) {
                Family::query()
                    ->where('subcategory_id', $this->family->subcategory_id)
                    ->where('order', '>', $currentOrder)
                    ->where('order', '<=', $newOrder)
                    ->decrement('order');
            } else {
                Family::query()
                    ->where('subcategory_id', $this->family->subcategory_id)
                    ->where('order', '<', $currentOrder)
                    ->where('order', '>=', $newOrder)
                    ->increment('order');
            }

            $this->family->update(['order' => $newOrder]);
        });
    }
}
