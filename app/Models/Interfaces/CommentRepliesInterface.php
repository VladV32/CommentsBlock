<?php

namespace App\Models\Interfaces;

use Illuminate\Database\Eloquent\Relations\HasMany;

interface CommentRepliesInterface
{
    /**
     * @return HasMany
     */
    public function replies(): HasMany;

}
