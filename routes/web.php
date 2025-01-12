<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\StartController;
use App\Http\Controllers\RankingController;


// スタート画面（認証不要）
Route::get('/', function () {
    return redirect()->route('login');
});

// 認証ルート
require __DIR__.'/auth.php';

// 認証が必要なルート
Route::middleware(['auth', 'verified'])->group(function () {
    // ダッシュボード
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // プロフィール関連
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // 基本情報関連
    Route::get('/questions/form', [QuestionController::class, 'showForm'])->name('questions.form');
    Route::post('/questions/start', [QuestionController::class, 'startExam'])->name('questions.start');
    Route::post('/start', [QuestionController::class, 'initializeExam'])->name('questions.initialize');
    Route::get('/exam', [QuestionController::class, 'showExam'])->name('questions.exam');
    Route::post('/exam', [QuestionController::class, 'handleExam'])->name('questions.handle');
    Route::get('/result', [QuestionController::class, 'showResult'])->name('questions.result');

    Route::get('/ranking', [RankingController::class, 'index'])->name('ranking.index');
    Route::get('/ranking/fe', [RankingController::class, 'feRanking'])->name('ranking.fe');
    Route::get('/ranking/ap', [RankingController::class, 'apRanking'])->name('ranking.ap');

});

// 応用情報試験
Route::prefix('ap')->name('questions.ap.')->group(function () {
    Route::get('/form', [QuestionController::class, 'showAPForm'])->name('form');
    Route::post('/start', [QuestionController::class, 'startAPExam'])->name('start');
    Route::post('/initialize', [QuestionController::class, 'initializeAPExam'])->name('initialize');
    Route::get('/exam', [QuestionController::class, 'showAPExam'])->name('exam');
    Route::post('/exam', [QuestionController::class, 'handleAPExam'])->name('handle');
    Route::get('/result', [QuestionController::class, 'showAPResult'])->name('result');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/ranking', [RankingController::class, 'index'])->name('ranking.index');
    Route::get('/ranking/fe', [RankingController::class, 'feRanking'])->name('ranking.fe');
    Route::get('/ranking/ap', [RankingController::class, 'apRanking'])->name('ranking.ap');
});