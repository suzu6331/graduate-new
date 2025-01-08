<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_id',
        'option_index',
        'option_text',
    ];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }
}
