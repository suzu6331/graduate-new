<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuizzesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            $table->string('question_id');
            $table->string('exam_type'); // 'FE' または 'AP'
            $table->integer('year');
            $table->string('season');
            $table->text('mondai');
            $table->text('kaitou')->nullable();
            $table->integer('answer');
            $table->timestamps();
    
            // 複合ユニークキー
            $table->unique(['question_id', 'exam_type']);
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quizzes');
    }
}
