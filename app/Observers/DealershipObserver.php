<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Dealership;
use Str;

final class DealershipObserver
{
    public function creating(Dealership $dealership): void
    {
        $dealership->uuid = (string) Str::uuid();
    }
}
