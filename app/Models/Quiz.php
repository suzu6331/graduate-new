<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// $quiz = Quiz::where('exam_type', 'AP')->first();
// $quiz->season;
class Quiz extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_id',
        'exam_type',
        'year',
        'season',
        'mondai',
        'kaitou',
        'answer',
    ];

    public function options()
    {
        return $this->hasMany(QuizOption::class);
    }
}
