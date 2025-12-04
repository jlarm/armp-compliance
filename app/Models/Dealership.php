<?php

declare(strict_types=1);

namespace App\Models;

use App\Observers\DealershipObserver;
use Carbon\CarbonInterface;
use Database\Factories\DealershipFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property-read int $id
 * @property string $uuid
 * @property-read string $name
 * @property-read CarbonInterface $created_at
 * @property-read CarbonInterface $updated_at
 */
#[ObservedBy(DealershipObserver::class)]
final class Dealership extends Model
{
    /** @use HasFactory<DealershipFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    public function casts(): array
    {
        return [
            'id' => 'integer',
            'uuid' => 'string',
            'name' => 'string',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    public function stores(): HasMany
    {
        return $this->hasMany(Store::class);
    }
}
