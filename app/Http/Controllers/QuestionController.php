<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use HTMLPurifier;
use HTMLPurifier_Config;
use App\Models\Quiz;
use App\Models\QuizOption;

class QuestionController extends Controller
{
    public function showForm()
    {
        $yearlyData = [
            ['year' => "令和4年", 'menjo' => ['link' => 'start.php?year=2022&exam=menjo']],
            ['year' => "令和3年", 'menjo' => ['link' => 'start.php?year=2021&exam=menjo']],
            ['year' => "令和2年", 'menjo' => ['link' => 'start.php?year=2020&exam=menjo']],
            ['year' => "令和元年", 'fall' => ['link' => 'start.php?year=2019&exam=fall']],
            ['year' => "平成31年", 'spring' => ['link' => 'start.php?year=2019&exam=spring']],
            ['year' => "平成30年", 'spring' => ['link' => 'start.php?year=2018&exam=spring'], 'fall' => ['link' => 'start.php?year=2018&exam=fall']],
            ['year' => "平成29年", 'spring' => ['link' => 'start.php?year=2017&exam=spring'], 'fall' => ['link' => 'start.php?year=2017&exam=fall']],
            ['year' => "平成28年", 'spring' => ['link' => 'start.php?year=2016&exam=spring'], 'fall' => ['link' => 'start.php?year=2016&exam=fall']],
            ['year' => "平成27年", 'spring' => ['link' => 'start.php?year=2015&exam=spring'], 'fall' => ['link' => 'start.php?year=2015&exam=fall']],
            ['year' => "平成26年", 'spring' => ['link' => 'start.php?year=2014&exam=spring'], 'fall' => ['link' => 'start.php?year=2014&exam=fall']],
            ['year' => "平成25年", 'spring' => ['link' => 'start.php?year=2013&exam=spring'], 'fall' => ['link' => 'start.php?year=2013&exam=fall']],
            ['year' => "平成24年", 'spring' => ['link' => 'start.php?year=2012&exam=spring'], 'fall' => ['link' => 'start.php?year=2012&exam=fall']],
            ['year' => "平成23年", 'spring' => ['link' => 'start.php?year=2011&exam=spring'], 'fall' => ['link' => 'start.php?year=2011&exam=fall']],
        ];

        return view('questions.form', compact('yearlyData'));
    }

    // 応用情報のフォーム表示
    public function showAPForm()
    {
        $yearlyData = [
            [
                'year' => "令和6年",
                'year_numeric' => 2024,
                'spring' => ['exam' => 'spring'],
                'fall' => ['exam' => 'fall']
            ],
            [
                'year' => "令和5年",
                'year_numeric' => 2023,
                'spring' => ['exam' => 'spring'],
                'fall' => ['exam' => 'fall']
            ],
            [
                'year' => "令和4年",
                'year_numeric' => 2022,
                'spring' => ['exam' => 'spring'],
                'fall' => ['exam' => 'fall']
            ],
            [
                'year' => "令和3年",
                'year_numeric' => 2021,
                'spring' => ['exam' => 'spring'],
                'fall' => ['exam' => 'fall']
            ],
            [
                'year' => "令和2年",
                'year_numeric' => 2020,
                'fall' => ['exam' => 'fall']
            ],
            [
                'year' => "令和元年",
                'year_numeric' => 2019,
                'fall' => ['exam' => 'fall']
            ],
            [
                'year' => "平成31年",
                'year_numeric' => 2019,
                'spring' => ['exam' => 'spring']
            ],
            [
                'year' => "平成30年",
                'year_numeric' => 2018,
                'spring' => ['exam' => 'spring'],
                'fall' => ['exam' => 'fall']
            ],
            [
                'year' => "平成29年",
                'year_numeric' => 2017,
                'spring' => ['exam' => 'spring'],
                'fall' => ['exam' => 'fall']
            ],
            [
                'year' => "平成28年",
                'year_numeric' => 2016,
                'spring' => ['exam' => 'spring'],
                'fall' => ['exam' => 'fall']
            ],
            [
                'year' => "平成27年",
                'year_numeric' => 2015,
                'spring' => ['exam' => 'spring'],
                'fall' => ['exam' => 'fall']
            ],
            [
                'year' => "平成26年",
                'year_numeric' => 2014,
                'spring' => ['exam' => 'spring'],
                'fall' => ['exam' => 'fall']
            ],
            [
                'year' => "平成25年",
                'year_numeric' => 2013,
                'spring' => ['exam' => 'spring'],
                'fall' => ['exam' => 'fall']
            ],
            [
                'year' => "平成24年",
                'year_numeric' => 2012,
                'spring' => ['exam' => 'spring'],
                'fall' => ['exam' => 'fall']
            ],
            [
                'year' => "平成23年",
                'year_numeric' => 2011,
                'spring' => ['exam' => 'spring'],
                'fall' => ['exam' => 'fall']
            ],
        ];

        return view('questions.ap.form', compact('yearlyData'));
    }

    /**
     * 基本情報技術者試験の開始
     */
    public function startExam(Request $request)
    {
        $year = $request->input('year');
        $exam = $request->input('exam');

        if (!$year || !$exam) {
            return redirect()->back()->with('error', '年と試験区分を指定してください。');
        }

        return view('questions.start', compact('year', 'exam'));
    }

    public function startAPExam(Request $request)
    {
        $selection = $request->input('selection');

        // パラメータの値をログに記録
        \Log::info("startAPExam: selection={$selection}");

        if (!$selection) {
            return redirect()->back()->with('error', '選択肢を指定してください。');
        }

        // 'selection'パラメータを 'year_exam_ap' の形式で分割
        $parts = explode('_', $selection, 2);

        if (count($parts) !== 2) {
            \Log::error("startAPExam: Invalid selection format.");
            return redirect()->back()->with('error', '選択肢の形式が正しくありません。');
        }

        list($year, $exam_ap) = $parts;

        \Log::info("startAPExam: year={$year}, exam_ap={$exam_ap}");

        if (!$year || !$exam_ap) {
            return redirect()->back()->with('error', '年と試験区分を指定してください。');
        }

        // 応用情報用のセッションキーをクリア
        Session::forget(['years_ap', 'siken_ap', 'total_start_time_ap', 'end_time_ap', 'curt_question_ap', 'correct_answers_ap', 'question_order_ap']);

        // 基本情報用のセッションキーもクリア
        Session::forget(['years', 'siken', 'total_start_time', 'end_time', 'curt_question', 'correct_answers', 'question_order']);

        // 必要なセッションデータを設定
        Session::put('years_ap', [$year]); // 単一年度の場合、配列にする
        Session::put('siken_ap', $exam_ap); // 'spring', 'fall'
        Session::put('total_start_time_ap', time());
        Session::put('end_time_ap', time() + 3600); // 1時間後
        Session::put('curt_question_ap', 0);
        Session::put('correct_answers_ap', 0);

        // スタート画面に遷移
        return view('questions.ap.start', compact('year', 'exam_ap')); // 応用情報用スタートビューを返す
    }


    /**
     * 基本情報技術者試験の初期化処理
     */
    public function initializeExam(Request $request)
    {
        $request->validate([
            'year' => 'required|string',
            'exam' => 'required|string',
        ]);

        $year = $request->input('year');
        $exam = $request->input('exam'); // 'spring', 'fall', 'menjo'

        // 基本情報用のセッションキーをクリア
        Session::forget(['years', 'siken', 'total_start_time', 'end_time', 'curt_question', 'correct_answers', 'question_order']);

        // 応用情報用のセッションキーもクリア
        Session::forget(['years_ap', 'siken_ap', 'total_start_time_ap', 'end_time_ap', 'curt_question_ap', 'correct_answers_ap', 'question_order_ap']);

        // 必要なセッションデータを設定
        Session::put('years', [$year]); // 単一年度の場合、配列にする
        Session::put('siken', $exam); // 'spring', 'fall', 'menjo'
        Session::put('total_start_time', time());
        Session::put('end_time', time() + 3600); // 1時間後
        Session::put('curt_question', 0);
        Session::put('correct_answers', 0);

        return redirect()->route('questions.exam');
    }

    /**
     * 基本情報技術者試験の試験表示
     */
    public function showExam()
    {
        $years = Session::get('years', []);
        $siken = Session::get('siken', null);
        $end_time = Session::get('end_time');
        $examType = 'basic'; // 'FE' の場合

        // クイズをデータベースから取得
        $quizzes = Quiz::where('exam_type', 'FE')
            ->whereIn('year', $years)
            ->where('season', $siken === 'menjo' ? 'menjo' : $siken)
            ->with('options')
            ->get()
            ->toArray();

        // デバッグ用: 問題数を10問に制限
        $quizzes = array_slice($quizzes, 0, 80);

        if (empty($quizzes)) {
            \Log::error("showExam: No valid questions loaded.");
            return view('questions.exam', [
                'error_message' => '質問が読み込めませんでした。',
                'end_time' => $end_time,
                'examType' => $examType 
            ]);
        }

        Session::put('questions', $quizzes);

        // 初期化処理
        Session::put('curt_question', Session::get('curt_question', 0));
        Session::put('correct_answers', Session::get('correct_answers', 0));
        if (!Session::has('question_order')) {
            $order = range(0, count($quizzes) - 1);
            shuffle($order);
            Session::put('question_order', $order);
        }

        $curt_idx = Session::get('curt_question');
        $question_order = Session::get('question_order', []);
        $curt_id = $quizzes[$question_order[$curt_idx]]['question_id'] ?? null;
        $current_question = $quizzes[$question_order[$curt_idx]] ?? null;

        // 質問の処理
        if ($current_question && (count($current_question['options']) > 0 || isset($current_question['kaitou']))) {

            // 1. 'mondai' の画像数をカウントしてセッションに保存
            $mondai_image_count = $this->count_images($current_question['mondai']);
            Session::put('mondai_image_count', $mondai_image_count);

            // 'mondai' のコンテンツを処理
            $current_question['mondai'] = $this->process_content($current_question['mondai'], $current_question, 'basic', 'mondai');

            // 2. 'kaitou' の画像数をカウントしてセッションに保存
            if (isset($current_question['kaitou'])) {
                $kaitou_image_count = $this->count_images($current_question['kaitou']); 
                Session::put('kaitou_image_count', $kaitou_image_count);

                // 'kaitou' のコンテンツを処理（$section を 'kaitou' と指定）
                $current_question['kaitou'] = $this->process_content($current_question['kaitou'], $current_question, 'basic', 'kaitou');
            } else {
                Session::put('kaitou_image_count', 0);
            }
        } else {
            \Log::error("showExam: Question ID {$curt_id} is not in the correct format.", [
                'current_question' => $current_question
            ]);
            return view('questions.exam', [
                'error_message' => '質問の形式が正しくありません。',
                'end_time' => $end_time,
                'examType' => $examType
            ]);
        }

        $displayOptions = $this->get_display_options($current_question, 'basic');

        $examType = 'basic'; 

        return view('questions.exam', compact('quizzes', 'current_question', 'curt_idx', 'displayOptions', 'end_time', 'examType'));
    }

    /**
     * 応用情報技術者試験の初期化処理
     */
    public function initializeAPExam(Request $request)
    {
        $request->validate([
            'year' => 'required|string',
            'exam' => 'required|string',
        ]);

        $year = $request->input('year');
        $exam = $request->input('exam'); // 'spring', 'fall', 'menjo_ap'

        // 応用情報用のセッションキーをクリア
        Session::forget(['years_ap', 'siken_ap', 'total_start_time_ap', 'end_time_ap', 'curt_question_ap', 'correct_answers_ap', 'question_order_ap']);

        // 基本情報用のセッションキーもクリア
        Session::forget(['years', 'siken', 'total_start_time', 'end_time', 'curt_question', 'correct_answers', 'question_order']);

        // 必要なセッションデータを設定
        Session::put('years_ap', [$year]); // 単一年度の場合、配列にする
        Session::put('siken_ap', $exam); // 'spring', 'fall', 'menjo_ap'
        Session::put('total_start_time_ap', time());
        Session::put('end_time_ap', time() + 3600); // 1時間後
        Session::put('curt_question_ap', 0);
        Session::put('correct_answers_ap', 0);

        return redirect()->route('questions.ap.exam');
    }

    /**
     * 応用情報技術者試験の試験表示
     */
    public function showAPExam()
    {
        $years = Session::get('years_ap', []);
        $siken = Session::get('siken_ap', null);
        $end_time = Session::get('end_time_ap');
        $examType = 'ap';

        // クイズをデータベースから取得
        $quizzes = Quiz::where('exam_type', 'AP')
            ->whereIn('year', $years)
            ->where('season', $siken === 'menjo' ? 'menjo' : $siken)
            ->with('options')
            ->get()
            ->toArray();

        // デバッグ用: 問題数を10問に制限
        $quizzes = array_slice($quizzes, 0, 10);

        if (empty($quizzes)) {
            \Log::error("showAPExam: No valid questions loaded for applied info.");
            return view('questions.exam', [
                'error_message' => '質問が読み込めませんでした。',
                'end_time' => $end_time,
                'examType' => $examType
            ]);
        }

        Session::put('questions_ap', $quizzes);

        // 初期化処理
        Session::put('curt_question_ap', Session::get('curt_question_ap', 0));
        Session::put('correct_answers_ap', Session::get('correct_answers_ap', 0));
        if (!Session::has('question_order_ap')) {
            $order = range(0, count($quizzes) - 1);
            shuffle($order);
            Session::put('question_order_ap', $order);
        }

        $curt_idx = Session::get('curt_question_ap');
        $question_order = Session::get('question_order_ap', []);
        $curt_id = $quizzes[$question_order[$curt_idx]]['question_id'] ?? null;
        $current_question = $quizzes[$question_order[$curt_idx]] ?? null;

        // 質問の処理
        if ($current_question && (count($current_question['options']) > 0 || isset($current_question['kaitou']))) {

            // 1. 'mondai' の画像数をカウントしてセッションに保存
            $mondai_image_count = $this->count_images($current_question['mondai']);
            Session::put('mondai_image_count', $mondai_image_count);

            // 'mondai' のコンテンツを処理
            $current_question['mondai'] = $this->process_content($current_question['mondai'], $current_question, 'ap', 'mondai');

            // 2. 'kaitou' の画像数をカウントしてセッションに保存
            if (isset($current_question['kaitou'])) {
                $kaitou_image_count = $this->count_images($current_question['kaitou']); 
                Session::put('kaitou_image_count', $kaitou_image_count);

                // 'kaitou' のコンテンツを処理（$section を 'kaitou' と指定）
                $current_question['kaitou'] = $this->process_content($current_question['kaitou'], $current_question, 'ap', 'kaitou');
            } else {
                Session::put('kaitou_image_count', 0);
            }
        } else {
            \Log::error("showAPExam: Question ID {$curt_id} is not in the correct format for applied info.", [
                'current_question' => $current_question
            ]);
            return view('questions.exam', [
                'error_message' => '質問の形式が正しくありません。',
                'end_time' => $end_time,
                'examType' => $examType
            ]);
        }

        $displayOptions = $this->get_display_options($current_question, 'ap');

        return view('questions.exam', compact('quizzes', 'current_question', 'curt_idx', 'displayOptions', 'end_time', 'examType'));
    }

    /**
     * 基本情報技術者試験の試験処理
     */
    public function handleExam(Request $request)
    {
        $questions = Session::get('questions', []);
        $curt_idx = Session::get('curt_question', 0);
        $question_order = Session::get('question_order', []);
        $end_time = Session::get('end_time');
        $examType = 'basic'; // 追加

        if ($curt_idx >= count($questions)) {
            \Log::error("handleExam: curt_idx ($curt_idx) exceeds questions count (" . count($questions) . ")");
            return view('questions.exam', [
                'error_message' => '質問が見つかりませんでした。',
                'end_time' => $end_time,
                'examType' => $examType // 追加
            ]);
        }

        $curt_id = $questions[$question_order[$curt_idx]]['question_id'] ?? null;
        $current_question = $questions[$question_order[$curt_idx]] ?? null;

        // 質問の処理
        if ($current_question && (count($current_question['options']) > 0 || isset($current_question['kaitou']))) {

            // 1. 'mondai' の画像数をカウントしてセッションに保存
            $mondai_image_count = $this->count_images($current_question['mondai']);
            Session::put('mondai_image_count', $mondai_image_count);

            // 'mondai' のコンテンツを処理
            $current_question['mondai'] = $this->process_content($current_question['mondai'], $current_question, 'basic', 'mondai');

            // 2. 'kaitou' の画像数をカウントしてセッションに保存
            if (isset($current_question['kaitou'])) {
                $kaitou_image_count = $this->count_images($current_question['kaitou']); 
                Session::put('kaitou_image_count', $kaitou_image_count);

                // 'kaitou' のコンテンツを処理（$section を 'kaitou' と指定）
                $current_question['kaitou'] = $this->process_content($current_question['kaitou'], $current_question, 'basic', 'kaitou');
            } else {
                Session::put('kaitou_image_count', 0);
            }
        } else {
            \Log::error("handleExam: Question ID {$curt_id} is not in the correct format.", [
                'current_question' => $current_question
            ]);
            return view('questions.exam', [
                'error_message' => '質問の形式が正しくありません。',
                'end_time' => $end_time,
                'examType' => $examType // 追加
            ]);
        }   

        if ($request->has('submit_ans')) {
            $select_idx = $request->input('select');

            if (is_null($select_idx)) {
                $error_message = '回答を選択してください。';
                return view('questions.exam', [
                    'questions' => $questions,
                    'current_question' => $current_question,
                    'curt_idx' => $curt_idx,
                    'error_message' => $error_message,
                    'end_time' => $end_time,
                    'displayOptions' => $this->get_display_options($current_question, 'basic'),
                    'examType' => $examType // 追加
                ]);
            }

            if ($current_question) {
                // 正解インデックスの取得（0始まり）
                $ans_idx = intval($current_question['answer']);

                $is_correct = intval($select_idx) === $ans_idx;

                // 正解数をカウント
                if ($is_correct) {
                    Session::put('correct_answers', Session::get('correct_answers', 0) + 1);
                }

                // 選択肢が存在するか確認
                if (isset($current_question['options'])) {
                    // user_choice をインデックスとして設定
                    $user_choice = intval($select_idx);
                    // correct_choice は内容として保持
                    $correct_choice = $current_question['options'][$ans_idx]['option_text'] ?? null;
                } elseif (isset($current_question['kaitou'])) {
                    $user_choice = ''; // 選択肢は空
                    $correct_choice = ''; // 選択肢は空
                }

                return view('questions.exam', [
                    'questions' => $questions,
                    'current_question' => $current_question,
                    'user_choice' => $user_choice,
                    'correct_choice' => $correct_choice,
                    'is_correct' => $is_correct,
                    'curt_idx' => $curt_idx,
                    'end_time' => $end_time,
                    'displayOptions' => $this->get_display_options($current_question, 'basic'),
                    'examType' => $examType // 追加
                ]);
            }
        } 
        if ($request->has('next')) {
            // 次の質問へ移動
            $curt_idx++;
            Session::put('curt_question', $curt_idx);

            if ($curt_idx < count($questions)) {
                $current_question = $questions[$question_order[$curt_idx]] ?? null;

                // 質問の処理
                if ($current_question && (count($current_question['options']) > 0 || isset($current_question['kaitou']))) {

                    // 1. 'mondai' の画像数をカウントしてセッションに保存
                    $mondai_image_count = $this->count_images($current_question['mondai']);
                    Session::put('mondai_image_count', $mondai_image_count);

                    // 'mondai' のコンテンツを処理
                    $current_question['mondai'] = $this->process_content($current_question['mondai'], $current_question, 'basic', 'mondai');

                    // 2. 'kaitou' の画像数をカウントしてセッションに保存
                    if (isset($current_question['kaitou'])) {
                        $kaitou_image_count = $this->count_images($current_question['kaitou']); // 修正点
                        Session::put('kaitou_image_count', $kaitou_image_count);

                        // 'kaitou' のコンテンツを処理（$section を 'kaitou' と指定）
                        $current_question['kaitou'] = $this->process_content($current_question['kaitou'], $current_question, 'basic', 'kaitou');
                    } else {
                        Session::put('kaitou_image_count', 0);
                    }
                } else {
                    \Log::error("handleExam: Next question ID {$current_question['question_id']} is not in the correct format.", [
                        'current_question' => $current_question
                    ]);
                    return view('questions.exam', [
                        'error_message' => '次の質問の形式が正しくありません。',
                        'end_time' => $end_time,
                        'examType' => $examType // 追加
                    ]);
                }

                $displayOptions = $this->get_display_options($current_question, 'basic');

                return view('questions.exam', compact('questions', 'current_question', 'curt_idx', 'displayOptions', 'end_time', 'examType'));
            } else {
                // 全ての質問が終了した場合の処理
                return redirect()->route('questions.result')->with('success', '全ての問題が終了しました。');
            }
        }
    }

    /**
     * 応用情報技術者試験の試験処理
     */
    public function handleAPExam(Request $request)
    {
        $questions = Session::get('questions_ap', []);
        $curt_idx = Session::get('curt_question_ap', 0);
        $question_order = Session::get('question_order_ap', []);
        $end_time = Session::get('end_time_ap');
        $examType = 'ap'; // 追加

        if ($curt_idx >= count($questions)) {
            \Log::error("handleAPExam: curt_idx ($curt_idx) exceeds questions count (" . count($questions) . ") for applied info.");
            return view('questions.exam', [
                'error_message' => '質問が見つかりませんでした。',
                'end_time' => $end_time,
                'examType' => $examType // 追加
            ]);
        }

        $curt_id = $questions[$question_order[$curt_idx]]['question_id'] ?? null;
        $current_question = $questions[$question_order[$curt_idx]] ?? null;

        // 質問の処理
        if ($current_question && (count($current_question['options']) > 0 || isset($current_question['kaitou']))) {

            // 1. 'mondai' の画像数をカウントしてセッションに保存
            $mondai_image_count = $this->count_images($current_question['mondai']);
            Session::put('mondai_image_count', $mondai_image_count);

            // 'mondai' のコンテンツを処理
            $current_question['mondai'] = $this->process_content($current_question['mondai'], $current_question, 'ap', 'mondai');

            // 2. 'kaitou' の画像数をカウントしてセッションに保存
            if (isset($current_question['kaitou'])) {
                $kaitou_image_count = $this->count_images($current_question['kaitou']); 
                Session::put('kaitou_image_count', $kaitou_image_count);

                // 'kaitou' のコンテンツを処理（$section を 'kaitou' と指定）
                $current_question['kaitou'] = $this->process_content($current_question['kaitou'], $current_question, 'ap', 'kaitou');
            } else {
                Session::put('kaitou_image_count', 0);
            }
        } else {
            \Log::error("handleAPExam: Question ID {$curt_id} is not in the correct format for applied info.", [
                'current_question' => $current_question
            ]);
            return view('questions.exam', [
                'error_message' => '質問の形式が正しくありません。',
                'end_time' => $end_time,
                'examType' => 'ap'
            ]);
        }

        if ($request->has('submit_ans')) {
            $select_idx = $request->input('select');

            if (is_null($select_idx)) {
                $error_message = '回答を選択してください。';
                return view('questions.exam', [
                    'questions' => $questions,
                    'current_question' => $current_question,
                    'curt_idx' => $curt_idx,
                    'error_message' => $error_message,
                    'end_time' => $end_time,
                    'displayOptions' => $this->get_display_options($current_question, 'ap'),
                    'examType' => $examType // 追加
                ]);
            }

            if ($current_question) {
                // 正解インデックスの取得（0始まり）
                $ans_idx = intval($current_question['answer']);

                $is_correct = intval($select_idx) === $ans_idx;

                // 正解数をカウント
                if ($is_correct) {
                    Session::put('correct_answers_ap', Session::get('correct_answers_ap', 0) + 1);
                }

                // 選択肢が存在するか確認
                if (isset($current_question['options'])) {
                    // user_choice をインデックスとして設定
                    $user_choice = intval($select_idx);
                    // correct_choice は内容として保持
                    $correct_choice = $current_question['options'][$ans_idx]['option_text'] ?? null;
                } elseif (isset($current_question['kaitou'])) {
                    $user_choice = ''; // 選択肢は空
                    $correct_choice = ''; // 選択肢は空
                }

                return view('questions.exam', [
                    'questions' => $questions,
                    'current_question' => $current_question,
                    'user_choice' => $user_choice,
                    'correct_choice' => $correct_choice,
                    'is_correct' => $is_correct,
                    'curt_idx' => $curt_idx,
                    'end_time' => $end_time,
                    'displayOptions' => $this->get_display_options($current_question, 'ap'),
                    'examType' => $examType // 追加
                ]);
            }
        } 
        if ($request->has('next')) {
            // 次の質問へ移動
            $curt_idx++;
            Session::put('curt_question_ap', $curt_idx);

            if ($curt_idx < count($questions)) {
                $current_question = $questions[$question_order[$curt_idx]] ?? null;

                // 質問の処理
                if ($current_question && (count($current_question['options']) > 0 || isset($current_question['kaitou']))) {

                    // 1. 'mondai' の画像数をカウントしてセッションに保存
                    $mondai_image_count = $this->count_images($current_question['mondai']);
                    Session::put('mondai_image_count', $mondai_image_count);

                    // 'mondai' のコンテンツを処理
                    $current_question['mondai'] = $this->process_content($current_question['mondai'], $current_question, 'ap', 'mondai');

                    // 2. 'kaitou' の画像数をカウントしてセッションに保存
                    if (isset($current_question['kaitou'])) {
                        $kaitou_image_count = $this->count_images($current_question['kaitou']); // 修正点
                        Session::put('kaitou_image_count', $kaitou_image_count);

                        // 'kaitou' のコンテンツを処理（$section を 'kaitou' と指定）
                        $current_question['kaitou'] = $this->process_content($current_question['kaitou'], $current_question, 'ap', 'kaitou');
                    } else {
                        Session::put('kaitou_image_count', 0);
                    }
                } else {
                    \Log::error("handleAPExam: Next question ID {$current_question['question_id']} is not in the correct format for applied info.", [
                        'current_question' => $current_question
                    ]);
                    return view('questions.exam', [
                        'error_message' => '次の質問の形式が正しくありません。',
                        'end_time' => $end_time,
                        'examType' => $examType // 追加
                    ]);
                }

                $displayOptions = $this->get_display_options($current_question, 'ap');

                return view('questions.exam', compact('questions', 'current_question', 'curt_idx', 'displayOptions', 'end_time', 'examType'));
            } else {
                // 全ての質問が終了した場合の処理
                return redirect()->route('questions.ap.result')->with('success', '全ての問題が終了しました。');
            }
        }
    }

    /**
     * 画像の数をカウントする
     */
    private function count_images($content)
    {
        preg_match_all('/<img[^>]*data-image-index\s*=\s*["\'](\d+)["\'][^>]*\/?>/i', $content, $matches);
        return count($matches[1]);
    }

    /**
     * クイズのコンテンツを処理する
     */
    private function process_content($content, $current_question, $type = 'basic', $section = 'mondai')
    {
        // \n を <br> に変換
        $content = nl2br(str_replace('\\n', "\n", $content));

        // 画像タグの置換
        if (strpos($content, 'img data-image-index') !== false) {
            $content = preg_replace_callback(
                '/<img[^>]*data-image-index\s*=\s*["\'](\d+)["\'][^>]*\/?>/i',
                function ($matches) use ($current_question, $type, $section) {
                    $img_idx = intval($matches[1]);

                    // 画像インデックスの調整
                    if ($section === 'kaitou') {
                        $mondai_image_count = Session::get('mondai_image_count', 0);
                        $img_idx += $mondai_image_count;
                    }
                    // 'mondai' の場合は調整不要

                    $img_src = $this->create_img_src($current_question['question_id'], $img_idx, $type);
                    return '<img src="' . $img_src . '" alt="画像" class="mb-4 w-64 h-64 object-contain">';
                },
                $content
            );
        }

        return $content;
    }

    /**
     * クイズの選択肢を表示用に処理する
     */
    private function get_display_options($current_question, $type = 'basic')
    {
        $displayOptions = [];

        if (isset($current_question['options']) && count($current_question['options']) > 0) {
            // 選択肢内の data-image-index の最小値を取得
            $sentaku_image_indices = [];
            foreach ($current_question['options'] as $option) {
                if (preg_match('/data-image-index\s*=\s*["\'](\d+)["\']/', $option['option_text'], $matches)) {
                    $sentaku_image_indices[] = intval($matches[1]);
                }
            }
            $min_sentaku_img_idx = count($sentaku_image_indices) > 0 ? min($sentaku_image_indices) : 0;

            foreach ($current_question['options'] as $index => $option) {
                $optionText = $option['option_text'];
                if (strpos($optionText, 'img data-image-index') !== false) {
                    // 画像オプションの場合
                    $displayOptions[$index] = preg_replace_callback(
                        '/<img[^>]*data-image-index\s*=\s*["\'](\d+)["\'][^>]*\/?>/i',
                        function ($matches) use ($current_question, $type, $min_sentaku_img_idx) {
                            $img_idx = intval($matches[1]);

                            // 画像インデックスの調整
                            $mondai_image_count = Session::get('mondai_image_count', 0);
                            $kaitou_image_count = Session::get('kaitou_image_count', 0);

                            // 選択肢の画像インデックス調整
                            $img_idx = $img_idx - $min_sentaku_img_idx + $mondai_image_count + $kaitou_image_count;

                            $img_src = $this->create_img_src($current_question['question_id'], $img_idx, $type);
                            return '<img src="' . $img_src . '" alt="選択肢画像" class="w-48 h-48 object-contain">';
                        },
                        $optionText
                    );
                } else {
                    // テキストオプションの場合
                    $displayOptions[$index] = nl2br(e($optionText));
                }
            }
        } else {
            // 選択肢がない場合（ラベルのみ表示）
            for ($i = 0; $i < 4; $i++) {
                $displayOptions[$i] = '';
            }
        }
        return $displayOptions;
    }

    /**
     * 画像のソースURLを生成する
     */
    private function create_img_src($question_id, $img_idx, $type = 'basic')
    {
        // タイプに応じた画像パスを設定
        if ($type === 'basic') {
            $basePath = 'FE/image/';
        } elseif ($type === 'ap') {
            $basePath = 'AP/image/';
        } else {
            $basePath = 'FE/image/'; // デフォルト
        }

        $img_filename = "{$question_id}-{$img_idx}.gif";
        return asset("{$basePath}{$img_filename}");
    }

    /**
     * 結果表示
     */
    public function showResult()
    {
        $totalQuestions = count(Session::get('questions', []));
        $correctAnswers = Session::get('correct_answers', 0);
        $timeTaken = time() - Session::get('total_start_time', time());
    
        $perQuestionScore = floor(10000 / $totalQuestions);
        $timeDivider = 5;
    
        $timePenalty = floor($timeTaken / $timeDivider);
        $score = ($correctAnswers * $perQuestionScore) - $timePenalty;
        $score = max($score, 0); // 下限0
    
        // === ランキング用スコアをDB保存 ===
        if (auth()->check()) {
            \App\Models\Score::create([
                'user_id'         => auth()->id(),
                'exam_type'       => 'FE', // 基本情報
                'correct_answers' => $correctAnswers,
                'total_questions' => $totalQuestions,
                'score'           => $score,
            ]);
        }
    
        return view('questions.result', compact('totalQuestions', 'correctAnswers', 'timeTaken', 'score', 'perQuestionScore'));
    }    

    /**
     * 応用情報の結果表示
     */
    public function showAPResult()
    {
        $totalQuestions = count(Session::get('questions_ap', []));
        $correctAnswers = Session::get('correct_answers_ap', 0);
        $timeTaken = time() - Session::get('total_start_time_ap', time());
    
        $perQuestionScore = floor(10000 / $totalQuestions);
        $timeDivider = 5;
    
        $timePenalty = floor($timeTaken / $timeDivider);
        $score = ($correctAnswers * $perQuestionScore) - $timePenalty;
        $score = max($score, 0);
    
        // === ランキング用スコアをDB保存 ===
        if (auth()->check()) {
            \App\Models\Score::create([
                'user_id'         => auth()->id(),
                'exam_type'       => 'AP', // 応用情報
                'correct_answers' => $correctAnswers,
                'total_questions' => $totalQuestions,
                'score'           => $score,
            ]);
        }    
        return view('questions.result', compact('totalQuestions', 'correctAnswers', 'timeTaken', 'score'));
    }
}
