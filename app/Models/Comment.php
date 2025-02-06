<?php

namespace App\Models;

use App\Models\Interfaces\CommentAttachmentsInterface;
use App\Models\Interfaces\CommentParentInterface;
use App\Models\Interfaces\CommentRepliesInterface;
use App\Models\Interfaces\CommentUserInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int                             $id
 * @property int                             $user_id
 * @property string                          $text
 * @property null|int                        $parent_id
 * @property null|\Illuminate\Support\Carbon $created_at
 * @property null|\Illuminate\Support\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Attachment> $attachments
 * @property-read null|int $attachments_count
 * @property-read null|\App\Models\TFactory $use_factory
 * @property-read null|Comment $parent
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Comment> $replies
 * @property-read null|int $replies_count
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\CommentFactory                    factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment whereUserId($value)
 * @mixin \Eloquent
 * @mixin IdeHelperComment
 */
class Comment extends Model implements
    CommentAttachmentsInterface,
    CommentParentInterface,
    CommentRepliesInterface,
    CommentUserInterface
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'text',
        'parent_id',
    ];

    protected $casts = [
        'user_id'   => 'integer',
        'parent_id' => 'integer',
    ];

    /**
     * @inheritDoc
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @inheritDoc
     */
    public function replies(): HasMany
    {
        return $this->hasMany(Comment::class, 'parent_id')->orderByDesc('created_at');
    }

    /**
     * @inheritDoc
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'parent_id')->orderByDesc('created_at');
    }

    /**
     * @inheritDoc
     */
    public function attachments(): HasMany
    {
        return $this->hasMany(Attachment::class);
    }
}
