<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lead extends Model
{
    use HasFactory;

    public const SOURCE_WEB = 'Web';

    public const SOURCE_ADS = 'Ads';

    public const SOURCE_REFERRAL = 'Referral';

    public const SOURCES = [
        self::SOURCE_WEB,
        self::SOURCE_ADS,
        self::SOURCE_REFERRAL,
    ];

    public const STATUS_NEW = 'New';

    public const STATUS_IN_PROGRESS = 'In Progress';

    public const STATUS_WON = 'Won';

    public const STATUS_LOST = 'Lost';

    public const STATUSES = [
        self::STATUS_NEW,
        self::STATUS_IN_PROGRESS,
        self::STATUS_WON,
        self::STATUS_LOST,
    ];

    protected $fillable = [
        'name',
        'email',
        'phone',
        'company',
        'source',
        'status',
        'assigned_to',
        'follow_up_date',
        'notes',
        'customer_id',
    ];

    protected function casts(): array
    {
        return [
            'follow_up_date' => 'date',
        ];
    }

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function isWon(): bool
    {
        return $this->status === self::STATUS_WON;
    }
}
