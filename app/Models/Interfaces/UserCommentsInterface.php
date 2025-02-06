<?php

namespace App\Models\Interfaces;

use Illuminate\Database\Eloquent\Relations\HasMany;

interface UserCommentsInterface
{
    /**
     * @return HasMany
     */
    public function comments(): HasMany;

}
