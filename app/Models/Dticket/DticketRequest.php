<?php

namespace App\Models\Dticket;

use App\Casts\Iban;
use App\Models\User;
use App\Notifications\DticketRequestStatusChanged;
use App\Notifications\DticketRequestSubmitted;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\App;
use Illuminate\Support\HtmlString;

class DticketRequest extends Model
{
    use HasFactory;

    /**
     * Get the casts for the model.
     *
     * @return array<string, string|class-string<\Illuminate\Contracts\Database\Eloquent\CastsAttributes>>
     */
    protected function casts(): array
    {
        return [
            'attachments' => 'array',
            'attachment_filenames' => 'array',
            'reason_for_rejection' => 'array',
            'banking_iban' => Iban::class,
        ];
    }

    /**
     * Define listeners for the model events.
     */
    protected static function booted(): void
    {
        // Calculate the number of months between the start and end date before creating the model.
        static::creating(function (DticketRequest $dticketRequest) {
            $startsAt = Carbon::parse($dticketRequest->exclude_starts_at);
            $endsAt = Carbon::parse($dticketRequest->exclude_ends_at);

            $dticketRequest->number_of_months = ceil($startsAt->diffInMonths($endsAt));
        });

        // Notify the user when the dticket request has been created.
        static::created(function (DticketRequest $dticketRequest) {
            $dticketRequest->user->notify(new DticketRequestSubmitted($dticketRequest));
        });

        // Notify the user when the status of the dticket request has been changed.
        static::updated(function (DticketRequest $dticketRequest) {
            if (! $dticketRequest->isDirty('status')) {
                return;
            }

            $dticketRequest->user->notify(new DticketRequestStatusChanged($dticketRequest));
        });
    }

    /**
     * Get the user that submitted the dticket request.
     *
     * @return BelongsTo<\App\Models\User>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Determine if the dticket request is pending.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Determine if the dticket request is rejected.
     */
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    /**
     * Determine if the dticket request is approved.
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Determine if the dticket request is paid.
     */
    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    /**
     * Get the exclude period as text.
     */
    public function monthsLabel(): string
    {
        return Carbon::parse($this->exclude_starts_at)->format('d.m.Y').' - '.Carbon::parse($this->exclude_ends_at)->format('d.m.Y');
    }

    /**
     * Get the translated status label for the dticket request.
     */
    public function statusLabel(): string
    {
        return __('stura.dticket.status.'.$this->status);
    }

    /**
     * Get the reason for exemption as text
     */
    public function reasonText(): string
    {
        return new HtmlString(
            $this->dticket_config->exemptionReasonsAsTranslatedOptions()[$this->reason]
            .' '.
            $this->dticket_config->exemptionReasonsAsTranslatedDescriptions()[$this->reason]
        );
    }

    /**
     * Get the translated reason for rejection for the dticket request.
     */
    public function rejectionText(): string
    {
        if ($this->reason_for_rejection === null) {
            return '';
        }

        return $this->reason_for_rejection[App::getLocale()];
    }

    /**
     * Get the config for the semester.
     */
    public function dticketConfig(): Attribute
    {
        return Attribute::get(
            fn () => DticketConfiguration::for($this->semester)
        );
    }
}
