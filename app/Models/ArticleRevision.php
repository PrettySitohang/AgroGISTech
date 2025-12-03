<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArticleRevision extends Model
{
    use HasFactory;

    protected $table = 'article_revisions';
    protected $primaryKey = 'revision_id';

    // created_at aktif, updated_at dimatikan
    public $timestamps = true;
    const UPDATED_AT = null;

    protected $fillable = [
        'article_id',
        'editor_id',
        'title_before',
        'content_before',
        'notes',
    ];

    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class, 'article_id', 'id');
    }

    public function editor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'editor_id', 'id');
    }
}
