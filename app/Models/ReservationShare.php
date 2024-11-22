<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property array $payload
 * @property string $token
 * @property \Illuminate\Support\Carbon $expires_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Database\Factories\ReservationShareFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservationShare newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservationShare newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservationShare query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservationShare whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservationShare whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservationShare whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservationShare wherePayload($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservationShare whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservationShare whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class ReservationShare extends Model
{
    use HasFactory;

    /**
     * The attributes that aren't mass assignable.
     */
    protected $guarded = [];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'payload' => 'array',
            'expires_at' => 'datetime',
        ];
    }

    /**
     * Determine if the reservation share has expired.
     */
    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }
}
