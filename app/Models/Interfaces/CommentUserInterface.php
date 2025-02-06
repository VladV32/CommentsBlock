<?php

namespace App\Models\Interfaces;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

interface CommentUserInterface
{
    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo;

}
