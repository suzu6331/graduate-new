<?php

namespace App\Http\Controllers;

use App\Models\Score;
use Illuminate\Http\Request;

class RankingController extends Controller
{
    public function index()
    {
        // 全試験共通のランキング上位10件を取得
        $topScores = Score::with('user')
            ->orderByDesc('score')
            ->orderBy('created_at') // スコアが同じ場合、早く作成されたものを上に
            ->limit(10)
            ->get();

        return view('ranking.index', compact('topScores'));
    }

    public function feRanking()
    {
        // 基本情報(FE)のみのランキング
        $topScores = Score::with('user')
            ->where('exam_type', 'FE')
            ->orderByDesc('score')
            ->limit(10)
            ->get();

        return view('ranking.fe', compact('topScores'));
    }

    public function apRanking()
    {
        // 応用情報(AP)のみのランキング
        $topScores = Score::with('user')
            ->where('exam_type', 'AP')
            ->orderByDesc('score')
            ->limit(10)
            ->get();

        return view('ranking.ap', compact('topScores'));
    }
}
