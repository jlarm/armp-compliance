<?php

declare(strict_types=1);

namespace App\Models;

use App\Observers\StoreObserver;
use Carbon\CarbonInterface;
use Database\Factories\StoreFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read int $id
 * @property string $uuid
 * @property-read int $dealership_id
 * @property-read string $name
 * @property-read string $address
 * @property-read string $city
 * @property-read string $state
 * @property-read string $zip
 * @property-read string|null $phone
 * @property-read string $timezone
 * @property-read CarbonInterface $created_at
 * @property-read CarbonInterface $updated_at
 */
#[ObservedBy(StoreObserver::class)]
final class Store extends Model
{
    /** @use HasFactory<StoreFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    public function casts(): array
    {
        return [
            'id' => 'integer',
            'uuid' => 'string',
            'dealership_id' => 'integer',
            'name' => 'string',
            'address' => 'string',
            'city' => 'string',
            'state' => 'string',
            'zip' => 'string',
            'phone' => 'string',
            'timezone' => 'string',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function dealership(): BelongsTo
    {
        return $this->belongsTo(Dealership::class);
    }
}
