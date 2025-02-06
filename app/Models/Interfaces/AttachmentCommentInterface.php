<?php

namespace App\Models\Interfaces;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

interface AttachmentCommentInterface
{
    /**
     * @return BelongsTo
     */
    public function comment(): BelongsTo;

}
