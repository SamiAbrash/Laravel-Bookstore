<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'author',
        'publisher',
        'firstPubDate',
        'ifTranslator',
        'description',
        'isbn',
        'pages',
        'ifChapters',
        'cover',
    ];

    protected $casts = [
        'firstPubDate' => 'date',
        'ifTranslator' => 'string',
        'ifChapters' => 'string',
    ];

    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    public function usersWhoWantToRead()
    {
        return $this->belongsToMany(User::class, 'want_to_read')->withTimestamps();
    }
}