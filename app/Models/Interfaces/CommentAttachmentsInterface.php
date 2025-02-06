<?php

namespace App\Models\Interfaces;

use Illuminate\Database\Eloquent\Relations\HasMany;

interface CommentAttachmentsInterface
{
    /**
     * @return HasMany
     */
    public function attachments(): HasMany;

}
