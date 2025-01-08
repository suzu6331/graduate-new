<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('quizzes', function (Blueprint $table) {
            $table->unique(['question_id', 'exam_type'], 'unique_question_exam');
        });
    }
    
    public function down()
    {
        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropUnique('unique_question_exam');
        });
    }    
};
