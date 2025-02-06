<?php

namespace App\Models\Interfaces;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

interface CommentParentInterface
{
    /**
     * @return BelongsTo
     */
    public function parent(): BelongsTo;

}
