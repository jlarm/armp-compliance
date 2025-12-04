<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Store;
use Str;

final class StoreObserver
{
    public function creating(Store $store): void
    {
        $store->uuid = (string) Str::uuid();
    }
}
