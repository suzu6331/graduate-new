<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('scores', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            // exam_type: FE (基本情報), AP (応用情報) 等
            $table->enum('exam_type', ['FE','AP']);
            $table->integer('correct_answers');
            $table->integer('total_questions');
            $table->integer('score');
            $table->timestamps();

            // user_id -> usersテーブルのidを参照 (外部キー制約はオプション)
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('scores');
    }
};

