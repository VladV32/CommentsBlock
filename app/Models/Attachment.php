<?php

namespace App\Models;

use App\Models\Comment as Comment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int         $id
 * @property int         $comment_id
 * @property string      $path
 * @property null|Carbon $created_at
 * @property null|Carbon $updated_at
 * @property-read Comment $comment
 * @method static Builder|Attachment newModelQuery()
 * @method static Builder|Attachment newQuery()
 * @method static Builder|Attachment query()
 * @method static Builder|Attachment whereCommentId($value)
 * @method static Builder|Attachment whereCreatedAt($value)
 * @method static Builder|Attachment whereId($value)
 * @method static Builder|Attachment wherePath($value)
 * @method static Builder|Attachment whereUpdatedAt($value)
 * @mixin \Eloquent
 * @mixin IdeHelperAttachment
 */
class Attachment extends Model
{
    protected $fillable = [
        'comment_id',
        'path',
    ];

    public function comment(): BelongsTo
    {
        return $this->belongsTo(Comment::class);
    }
}
