<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Reservation extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'check_in',
        'check_out',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'check_in' => 'datetime',
        'check_out' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only include reservations with 'check_in' equal to today.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeCheckInToday(Builder $query): Builder
    {
        return $query->whereDate('check_in', Carbon::today());
    }

    /**
     * Set the slug attribute based on the name.
     *
     * @param string $value
     * @return void
     */
    public function setSlugAttribute(string $value): void
    {
        $this->attributes['slug'] = Str::slug($this->name); // generate slug based in 'name'
    }

    /**
     * Get the slug attribute and format it.
     *
     * @param string $value
     * @return string
     */
    public function getSlugAttribute(string $value): string
    {
        return Str::title($value); // return slug
    }
}
