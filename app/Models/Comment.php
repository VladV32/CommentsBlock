<?php

namespace App\Models;

use App\Models\Attachment as Attachment;
use App\Models\User as User;
use Database\Factories\CommentFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * 
 *
 * @property int $id
 * @property int $user_id
 * @property string $text
 * @property int|null $parent_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Attachment> $attachments
 * @property-read int|null $attachments_count
 * @property-read Comment|null $parent
 * @property-read Collection<int, Comment> $replies
 * @property-read int|null $replies_count
 * @property-read User $user
 * @method static CommentFactory factory($count = null, $state = [])
 * @method static Builder|Comment newModelQuery()
 * @method static Builder|Comment newQuery()
 * @method static Builder|Comment query()
 * @method static Builder|Comment whereCreatedAt($value)
 * @method static Builder|Comment whereId($value)
 * @method static Builder|Comment whereParentId($value)
 * @method static Builder|Comment whereText($value)
 * @method static Builder|Comment whereUpdatedAt($value)
 * @method static Builder|Comment whereUserId($value)
 * @mixin \Eloquent
 */
class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'text',
        'parent_id',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'parent_id' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function replies(): HasMany
    {
        return $this->hasMany(Comment::class, 'parent_id')->orderByDesc('created_at');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'parent_id')->orderByDesc('created_at');
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(Attachment::class);
    }
}
