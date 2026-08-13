<?php

namespace App\Services;

use App\Models\Enquiry;
use App\Models\EnquiryActivity;
use App\Models\User;

class EnquiryActivityLogger
{
    /**
     * @param  array<string, mixed>  $meta
     */
    public function record(
        Enquiry $enquiry,
        string $type,
        string $message,
        ?User $user = null,
        array $meta = [],
    ): EnquiryActivity {
        return $enquiry->activities()->create([
            'user_id' => $user?->id,
            'type' => $type,
            'message' => $message,
            'meta' => $meta === [] ? null : $meta,
        ]);
    }
}
