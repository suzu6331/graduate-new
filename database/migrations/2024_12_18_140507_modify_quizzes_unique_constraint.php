<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyQuizzesUniqueConstraint extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('quizzes', function (Blueprint $table) {
            // 既存の一意性制約を削除
            $table->dropUnique('quizzes_question_id_unique');

            // question_id と exam_type の複合一意性制約を追加
            $table->unique(['question_id', 'exam_type'], 'unique_question_exam');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quizzes', function (Blueprint $table) {
            // 複合一意性制約を削除
            $table->dropUnique('unique_question_exam');

            // 元の一意性制約を再追加
            $table->unique('question_id', 'quizzes_question_id_unique');
        });
    }
}
