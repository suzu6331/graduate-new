<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use HTMLPurifier;
use HTMLPurifier_Config;

class QuestionController extends Controller
{
    public function showForm()
    {
        $yearlyData = [
            ['year' => "令和5年", 'menjo' => ['link' => 'start.php?year=2023&exam=menjo']],
            ['year' => "令和4年", 'menjo' => ['link' => 'start.php?year=2022&exam=menjo']],
            ['year' => "令和3年", 'menjo' => ['link' => 'start.php?year=2021&exam=menjo']],
            ['year' => "令和2年", 'menjo' => ['link' => 'start.php?year=2020&exam=menjo']],
            ['year' => "令和元年", 'fall' => ['link' => 'start.php?year=2019&exam=spring']],
            ['year' => "平成31年", 'spring' => ['link' => 'start.php?year=2019&exam=fall']],
            ['year' => "平成30年", 'spring' => ['link' => 'start.php?year=2018&exam=spring'], 'fall' => ['link' => 'start.php?year=2018&exam=fall']],
            ['year' => "平成29年", 'spring' => ['link' => 'start.php?year=2017&exam=spring'], 'fall' => ['link' => 'start.php?year=2017&exam=fall']],
            ['year' => "平成28年", 'spring' => ['link' => 'start.php?year=2016&exam=spring'], 'fall' => ['link' => 'start.php?year=2016&exam=fall']],
            ['year' => "平成27年", 'spring' => ['link' => 'start.php?year=2015&exam=spring'], 'fall' => ['link' => 'start.php?year=2015&exam=fall']],
            ['year' => "平成26年", 'spring' => ['link' => 'start.php?year=2014&exam=spring'], 'fall' => ['link' => 'start.php?year=2014&exam=fall']],
            ['year' => "平成25年", 'spring' => ['link' => 'start.php?year=2013&exam=spring'], 'fall' => ['link' => 'start.php?year=2013&exam=fall']],
            ['year' => "平成24年", 'spring' => ['link' => 'start.php?year=2012&exam=spring'], 'fall' => ['link' => 'start.php?year=2012&exam=fall']],
            ['year' => "平成23年", 'spring' => ['link' => 'start.php?year=2011&exam=spring'], 'fall' => ['link' => 'start.php?year=2011&exam=fall']],
            ['year' => "平成22年", 'spring' => ['link' => 'start.php?year=2010&exam=spring'], 'fall' => ['link' => 'start.php?year=2010&exam=fall']],
            ['year' => "平成21年", 'spring' => ['link' => 'start.php?year=2009&exam=spring'], 'fall' => ['link' => 'start.php?year=2009&exam=fall']],
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
                'spring' => ['exam' => 'spring_ap'],
                'fall' => ['exam' => 'fall_ap']
            ],
            [
                'year' => "令和5年",
                'year_numeric' => 2023,
                'spring' => ['exam' => 'spring_ap'],
                'fall' => ['exam' => 'fall_ap']
            ],
            [
                'year' => "令和4年",
                'year_numeric' => 2022,
                'spring' => ['exam' => 'spring_ap'],
                'fall' => ['exam' => 'fall_ap']
            ],
            [
                'year' => "令和3年",
                'year_numeric' => 2021,
                'spring' => ['exam' => 'spring_ap'],
                'fall' => ['exam' => 'fall_ap']
            ],
            [
                'year' => "令和2年",
                'year_numeric' => 2020,
                'fall' => ['exam' => 'fall_ap']
            ],
            [
                'year' => "令和元年",
                'year_numeric' => 2019,
                'fall' => ['exam' => 'fall_ap']
            ],
            [
                'year' => "平成31年",
                'year_numeric' => 2019,
                'spring' => ['exam' => 'spring_ap']
            ],
            [
                'year' => "平成30年",
                'year_numeric' => 2018,
                'spring' => ['exam' => 'spring_ap'],
                'fall' => ['exam' => 'fall_ap']
            ],
            [
                'year' => "平成29年",
                'year_numeric' => 2017,
                'spring' => ['exam' => 'spring_ap'],
                'fall' => ['exam' => 'fall_ap']
            ],
            [
                'year' => "平成28年",
                'year_numeric' => 2016,
                'spring' => ['exam' => 'spring_ap'],
                'fall' => ['exam' => 'fall_ap']
            ],
            [
                'year' => "平成27年",
                'year_numeric' => 2015,
                'spring' => ['exam' => 'spring_ap'],
                'fall' => ['exam' => 'fall_ap']
            ],
            [
                'year' => "平成26年",
                'year_numeric' => 2014,
                'spring' => ['exam' => 'spring_ap'],
                'fall' => ['exam' => 'fall_ap']
            ],
            [
                'year' => "平成25年",
                'year_numeric' => 2013,
                'spring' => ['exam' => 'spring_ap'],
                'fall' => ['exam' => 'fall_ap']
            ],
            [
                'year' => "平成24年",
                'year_numeric' => 2012,
                'spring' => ['exam' => 'spring_ap'],
                'fall' => ['exam' => 'fall_ap']
            ],
            [
                'year' => "平成23年",
                'year_numeric' => 2011,
                'spring' => ['exam' => 'spring_ap'],
                'fall' => ['exam' => 'fall_ap']
            ],
            [
                'year' => "平成22年",
                'year_numeric' => 2010,
                'spring' => ['exam' => 'spring_ap'],
                'fall' => ['exam' => 'fall_ap']
            ],
            [
                'year' => "平成21年",
                'year_numeric' => 2009,
                'spring' => ['exam' => 'spring_ap'],
                'fall' => ['exam' => 'fall_ap']
            ],
        ];

        return view('questions.ap.form', compact('yearlyData'));
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
        Session::put('siken_ap', $exam_ap); // 'spring_ap', 'fall_ap'
        Session::put('total_start_time_ap', time());
        Session::put('end_time_ap', time() + 3600); // 1時間後
        Session::put('curt_question_ap', 0);
        Session::put('correct_answers_ap', 0);

        // スタート画面に遷移
        return view('questions.ap.start', compact('year', 'exam_ap')); // 応用情報用スタートビューを返す
    }

    

    // 応用情報の試験初期化処理
    public function initializeAPExam(Request $request)
    {
        $request->validate([
            'year' => 'required|string',
            'exam' => 'required|string',
        ]);

        $year = $request->input('year');
        $exam = $request->input('exam'); // 'spring_ap', 'fall_ap', 'menjo_ap'

        // 応用情報用のセッションキーをクリア
        Session::forget(['years_ap', 'siken_ap', 'total_start_time_ap', 'end_time_ap', 'curt_question_ap', 'correct_answers_ap', 'question_order_ap']);

        // 基本情報用のセッションキーもクリア
        Session::forget(['years', 'siken', 'total_start_time', 'end_time', 'curt_question', 'correct_answers', 'question_order']);

        // 必要なセッションデータを設定
        Session::put('years_ap', [$year]); // 単一年度の場合、配列にする
        Session::put('siken_ap', $exam); // 'spring_ap', 'fall_ap', 'menjo_ap'
        Session::put('total_start_time_ap', time());
        Session::put('end_time_ap', time() + 3600); // 1時間後
        Session::put('curt_question_ap', 0);
        Session::put('correct_answers_ap', 0);

        return redirect()->route('questions.ap.exam');
    }


    // 応用情報の試験表示
    //80問目設定
    // public function showAPExam()
    // {
    //     $years = Session::get('years_ap', []);
    //     $siken = Session::get('siken_ap', null); // 'spring_ap', 'fall_ap'
    //     $end_time = Session::get('end_time_ap');

    //     $questions = [];
    //     foreach ($years as $year) {
    //         $questions = array_merge($questions, $this->Local_JSON($year, $siken, 'ap'));
    //     }

    //     if (empty($questions)) {
    //         \Log::error("showAPExam: No valid questions loaded for applied info.");
    //         return view('questions.exam', [
    //             'error_message' => '質問が読み込めませんでした。',
    //             'end_time' => $end_time,
    //             'examType' => 'ap' // 追加
    //         ]);
    //     }

    //     Session::put('questions_ap', $questions);

    //     // 初期化処理
    //     Session::put('curt_question_ap', Session::get('curt_question_ap', 0));
    //     Session::put('correct_answers_ap', Session::get('correct_answers_ap', 0));
    //     if (!Session::has('question_order_ap')) {
    //         $order = range(0, count($questions) - 1);
    //         shuffle($order);
    //         Session::put('question_order_ap', $order);
    //     }

    //     $curt_idx = Session::get('curt_question_ap');
    //     $question_order = Session::get('question_order_ap', []);
    //     $curt_id = $questions[$question_order[$curt_idx]]['id'] ?? null;
    //     $current_question = $questions[$question_order[$curt_idx]] ?? null;

    //     if ($current_question && (isset($current_question['sentaku']) || isset($current_question['kaitou']))) {

    //         // 1. 'mondai' の画像数をカウントしてセッションに保存
    //         $mondai_image_count = $this->count_images($current_question['mondai']);
    //         Session::put('mondai_image_count', $mondai_image_count);
    
    //         // 'mondai' のコンテンツを処理
    //         $current_question['mondai'] = $this->process_content($current_question['mondai'], $current_question, 'ap', 'mondai');
    
    //         // 2. 'kaitou' の画像数をカウントしてセッションに保存
    //         if (isset($current_question['kaitou'])) {
    //             $kaitou_image_count = $this->count_images($current_question['kaitou']); // 修正点
    //             Session::put('kaitou_image_count', $kaitou_image_count);
    
    //             // 'kaitou' のコンテンツを処理（$section を 'kaitou' と指定）
    //             $current_question['kaitou'] = $this->process_content($current_question['kaitou'], $current_question, 'ap', 'kaitou');
    //         } else {
    //             Session::put('kaitou_image_count', 0);
    //         }
    //     } else {
    //         \Log::error("showAPExam: Question ID {$curt_id} is not in the correct format for applied info.", [
    //             'current_question' => $current_question
    //         ]);
    //         return view('questions.exam', [
    //             'error_message' => '質問の形式が正しくありません。',
    //             'end_time' => $end_time,
    //             'examType' => 'ap'
    //         ]);
    //     }

    //     $displayOptions = $this->get_display_options($current_question, 'ap');

    //     $examType = 'ap'; // 追加

    //     return view('questions.exam', compact('questions', 'current_question', 'curt_idx', 'displayOptions', 'end_time', 'examType'));
    // }

    //デバック用
    public function showAPExam()
    {
        $years = Session::get('years_ap', []);
        $siken = Session::get('siken_ap', null);
        $end_time = Session::get('end_time_ap');

        $questions = [];
        foreach ($years as $year) {
            $questions = array_merge($questions, $this->Local_JSON($year, $siken, 'ap'));
        }

        // デバッグ用: 問題数を10問に制限
        $questions = array_slice($questions, 0, 10);

        if (empty($questions)) {
            \Log::error("showAPExam: No valid questions loaded for applied info.");
            return view('questions.exam', [
                'error_message' => '質問が読み込めませんでした。',
                'end_time' => $end_time,
                'examType' => 'ap'
            ]);
        }

        Session::put('questions_ap', $questions);

        // 初期化処理
        Session::put('curt_question_ap', Session::get('curt_question_ap', 0));
        Session::put('correct_answers_ap', Session::get('correct_answers_ap', 0));
        if (!Session::has('question_order_ap')) {
            $order = range(0, count($questions) - 1);
            shuffle($order);
            Session::put('question_order_ap', $order);
        }

        $curt_idx = Session::get('curt_question_ap');
        $question_order = Session::get('question_order_ap', []);
        $curt_id = $questions[$question_order[$curt_idx]]['id'] ?? null;
        $current_question = $questions[$question_order[$curt_idx]] ?? null;

        if ($current_question && (isset($current_question['sentaku']) || isset($current_question['kaitou']))) {

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
            \Log::error("showAPExam: Question ID {$curt_id} is not in the correct format for applied info.", [
                'current_question' => $current_question
            ]);
            return view('questions.exam', [
                'error_message' => '質問の形式が正しくありません。',
                'end_time' => $end_time,
                'examType' => 'ap'
            ]);
        }

        $displayOptions = $this->get_display_options($current_question, 'ap');

        $examType = 'ap'; // 追加

        return view('questions.exam', compact('questions', 'current_question', 'curt_idx', 'displayOptions', 'end_time', 'examType'));
    }

    private function count_images($content)
    {
        preg_match_all('/<img[^>]*data-image-index\s*=\s*["\'](\d+)["\'][^>]*\/?>/i', $content, $matches);
        return count($matches[1]);
    }

    // 応用情報の試験処理
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

        $curt_id = $questions[$question_order[$curt_idx]]['id'] ?? null;
        $current_question = $questions[$question_order[$curt_idx]] ?? null;

        // 質問の処理
        if ($current_question && (isset($current_question['sentaku']) || isset($current_question['kaitou']))) {

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
                if (isset($current_question['sentaku'])) {
                    $user_choice = $current_question['sentaku'][intval($select_idx)] ?? null;
                    $correct_choice = $current_question['sentaku'][$ans_idx] ?? null;
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
                if ($current_question && (isset($current_question['sentaku']) || isset($current_question['kaitou']))) {
    
                    // 1. 'mondai' の画像数をカウントしてセッションに保存
                    $mondai_image_count = $this->count_images($current_question['mondai']);
                    Session::put('mondai_image_count', $mondai_image_count);
    
                    // 'mondai' のコンテンツを処理
                    $current_question['mondai'] = $this->process_content($current_question['mondai'], $current_question, 'ap', 'mondai');
    
                    // 2. 'kaitou' の画像数をカウントしてセッションに保存
                    if (isset($current_question['kaitou'])) {
                        $kaitou_image_count = 1; // kaitou は常に画像1つ
                        Session::put('kaitou_image_count', $kaitou_image_count);
    
                        // 'kaitou' のコンテンツを処理（$section を 'kaitou' と指定）
                        $current_question['kaitou'] = $this->process_content($current_question['kaitou'], $current_question, 'ap', 'kaitou');
                    } else {
                        Session::put('kaitou_image_count', 0);
                    }
                } else {
                    \Log::error("handleAPExam: Next question ID {$current_question['id']} is not in the correct format for applied info.", [
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


    public function startExam(Request $request)
    {
        $year = $request->input('year');
        $exam = $request->input('exam');

        if (!$year || !$exam) {
            return redirect()->back()->with('error', '年と試験区分を指定してください。');
        }

        return view('questions.start', compact('year', 'exam'));
    }

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
    
                    $img_src = $this->create_img_src($current_question['id'], $img_idx, $type);
                    return '<img src="' . $img_src . '" alt="画像" class="mb-4 w-64 h-64 object-contain">';
                },
                $content
            );
        }
    
        return $content;
    }    
    
    //80問設定
    // public function showExam()
    // {
    //     $years = Session::get('years', []);
    //     $siken = Session::get('siken', null); // 'spring', 'fall', 'menjo'
    //     $end_time = Session::get('end_time');

    //     $questions = [];
    //     foreach ($years as $year) {
    //         $questions = array_merge($questions, $this->Local_JSON($year, $siken));
    //     }

    //     if (empty($questions)) {
    //         \Log::error("showExam: No valid questions loaded.");
    //         return view('questions.exam', [
    //             'error_message' => '質問が読み込めませんでした。',
    //             'end_time' => $end_time,
    //             'examType' => 'basic' // 追加
    //         ]);
    //     }

    //     Session::put('questions', $questions);

    //     // 初期化処理
    //     Session::put('curt_question', Session::get('curt_question', 0));
    //     Session::put('correct_answers', Session::get('correct_answers', 0));
    //     if (!Session::has('question_order')) {
    //         $order = range(0, count($questions) - 1);
    //         shuffle($order);
    //         Session::put('question_order', $order);
    //     }

    //     $curt_idx = Session::get('curt_question');
    //     $question_order = Session::get('question_order', []);
    //     $curt_id = $questions[$question_order[$curt_idx]]['id'] ?? null;
    //     $current_question = $questions[$question_order[$curt_idx]] ?? null;

    //     // 質問の処理
    //     if ($current_question && (isset($current_question['sentaku']) || isset($current_question['kaitou']))) {

    //         // 1. 'mondai' の画像数をカウントしてセッションに保存
    //         $mondai_image_count = $this->count_images($current_question['mondai']);
    //         Session::put('mondai_image_count', $mondai_image_count);

    //         // 'mondai' のコンテンツを処理
    //         $current_question['mondai'] = $this->process_content($current_question['mondai'], $current_question, 'basic', 'mondai');

    //         // 2. 'kaitou' の画像数をカウントしてセッションに保存
    //         if (isset($current_question['kaitou'])) {
    //             $kaitou_image_count = $this->count_images($current_question['kaitou']); // 修正点
    //             Session::put('kaitou_image_count', $kaitou_image_count);

    //             // 'kaitou' のコンテンツを処理（$section を 'kaitou' と指定）
    //             $current_question['kaitou'] = $this->process_content($current_question['kaitou'], $current_question, 'basic', 'kaitou');
    //         } else {
    //             Session::put('kaitou_image_count', 0);
    //         }
    //     } else {
    //         \Log::error("showExam: Question ID {$curt_id} is not in the correct format.", [
    //             'current_question' => $current_question
    //         ]);
    //         return view('questions.exam', [
    //             'error_message' => '質問の形式が正しくありません。',
    //             'end_time' => $end_time,
    //             'examType' => 'basic'
    //         ]);
    //     }

    //     $displayOptions = $this->get_display_options($current_question, 'basic');

    //     $examType = 'basic'; // 追加

    //     return view('questions.exam', compact('questions', 'current_question', 'curt_idx', 'displayOptions', 'end_time', 'examType'));
    // }

    //デバック用
    public function showExam()
    {
        $years = Session::get('years', []);
        $siken = Session::get('siken', null);
        $end_time = Session::get('end_time');
    
        $questions = [];
        foreach ($years as $year) {
            $questions = array_merge($questions, $this->Local_JSON($year, $siken));
        }
    
        // デバッグ用: 問題数を10問に制限
        $questions = array_slice($questions, 0, 10);
    
        if (empty($questions)) {
            \Log::error("showExam: No valid questions loaded.");
            return view('questions.exam', [
                'error_message' => '質問が読み込めませんでした。',
                'end_time' => $end_time,
                'examType' => 'basic' 
            ]);
        }
    
        Session::put('questions', $questions);

        // 初期化処理
        Session::put('curt_question', Session::get('curt_question', 0));
        Session::put('correct_answers', Session::get('correct_answers', 0));
        if (!Session::has('question_order')) {
            $order = range(0, count($questions) - 1);
            shuffle($order);
            Session::put('question_order', $order);
        }

        $curt_idx = Session::get('curt_question');
        $question_order = Session::get('question_order', []);
        $curt_id = $questions[$question_order[$curt_idx]]['id'] ?? null;
        $current_question = $questions[$question_order[$curt_idx]] ?? null;

        // 質問の処理
        if ($current_question && (isset($current_question['sentaku']) || isset($current_question['kaitou']))) {

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
            \Log::error("showExam: Question ID {$curt_id} is not in the correct format.", [
                'current_question' => $current_question
            ]);
            return view('questions.exam', [
                'error_message' => '質問の形式が正しくありません。',
                'end_time' => $end_time,
                'examType' => 'basic'
            ]);
        }

        $displayOptions = $this->get_display_options($current_question, 'basic');

        $examType = 'basic'; // 追加

        return view('questions.exam', compact('questions', 'current_question', 'curt_idx', 'displayOptions', 'end_time', 'examType'));
    }


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

        $curt_id = $questions[$question_order[$curt_idx]]['id'] ?? null;
        $current_question = $questions[$question_order[$curt_idx]] ?? null;

        // 質問の処理
        if ($current_question && (isset($current_question['sentaku']) || isset($current_question['kaitou']))) {

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
            \Log::error("handleExam: Question ID {$curt_id} is not in the correct format.", [
                'current_question' => $current_question
            ]);
            return view('questions.exam', [
                'error_message' => '質問の形式が正しくありません。',
                'end_time' => $end_time,
                'examType' => 'basic'
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
                if (isset($current_question['sentaku'])) {
                    $user_choice = $current_question['sentaku'][intval($select_idx)] ?? null;
                    $correct_choice = $current_question['sentaku'][$ans_idx] ?? null;
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
                if ($current_question && (isset($current_question['sentaku']) || isset($current_question['kaitou']))) {
    
                    // 1. 'mondai' の画像数をカウントしてセッションに保存
                    $mondai_image_count = $this->count_images($current_question['mondai']);
                    Session::put('mondai_image_count', $mondai_image_count);
    
                    // 'mondai' のコンテンツを処理
                    $current_question['mondai'] = $this->process_content($current_question['mondai'], $current_question, 'basic', 'mondai');
    
                    // 2. 'kaitou' の画像数をカウントしてセッションに保存
                    if (isset($current_question['kaitou'])) {
                        $kaitou_image_count = 1; // kaitou は常に画像1つ
                        Session::put('kaitou_image_count', $kaitou_image_count);
    
                        // 'kaitou' のコンテンツを処理（$section を 'kaitou' と指定）
                        $current_question['kaitou'] = $this->process_content($current_question['kaitou'], $current_question, 'basic', 'kaitou');
                    } else {
                        Session::put('kaitou_image_count', 0);
                    }
                } else {
                    \Log::error("handleExam: Next question ID {$current_question['id']} is not in the correct format.", [
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


    private function process_mondai($current_question)
    {
        $mondai = $current_question['mondai'];

        // \n を <br> に変換
        $mondai = nl2br(str_replace('\\n', "\n", $mondai));

        // 画像タグの置換
        if (strpos($mondai, 'img data-image-index') !== false) {
            $mondai = preg_replace_callback(
                '/<img data-image-index="(\d+)">/',
                function ($matches) use ($current_question) {
                    $img_idx = $matches[1];
                    $img_src = $this->create_img_src($current_question['id'], $img_idx);
                    return '<img src="' . $img_src . '" alt="問題画像" class="mb-4 w-64 h-64 object-contain">';
                },
                $mondai
            );
        }

        return $mondai;
    }

    private function get_image_src($content, $question_id)
    {
        // 画像インデックスを抽出
        if (preg_match('/<img data-image-index="(\d+)">/', $content, $matches)) {
            $img_idx = $matches[1];
            return $this->create_img_src($question_id, $img_idx);
        }
        return null;
    }

    private function get_display_options($current_question, $type = 'basic')
    {
        $displayOptions = [];
    
        if (isset($current_question['sentaku'])) {
            // 選択肢内の data-image-index の最小値を取得
            $sentaku_image_indices = [];
            foreach ($current_question['sentaku'] as $option) {
                if (preg_match('/data-image-index\s*=\s*["\'](\d+)["\']/', $option, $matches)) {
                    $sentaku_image_indices[] = intval($matches[1]);
                }
            }
            $min_sentaku_img_idx = count($sentaku_image_indices) > 0 ? min($sentaku_image_indices) : 0;
    
            foreach ($current_question['sentaku'] as $index => $option) {
                if (strpos($option, 'img data-image-index') !== false) {
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
    
                            $img_src = $this->create_img_src($current_question['id'], $img_idx, $type);
                            return '<img src="' . $img_src . '" alt="選択肢画像" class="w-48 h-48 object-contain">';
                        },
                        $option
                    );
                } else {
                    // テキストオプションの場合
                    $displayOptions[$index] = nl2br(htmlspecialchars($option));
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

    private function Local_JSON($year, $siken, $type = 'basic')
    {
        // タイプに応じたパスを設定
        if ($type === 'basic') {
            $path = public_path("FE/$year");
        } elseif ($type === 'ap') {
            $path = public_path("AP/$year");
        } else {
            \Log::warning("Local_JSON: 未対応のタイプ '{$type}' が指定されました。");
            return [];
        }

        \Log::info("Local_JSON: 調査対象パス: $path for type {$type}");

        if (!is_dir($path)) {
            \Log::error("Local_JSON: パスが存在しません: $path for type {$type}");
            return [];
        }

        // 'siken' に基づいて季節コードを設定（spring_ap: 0, fall_ap: 1）
        if ($siken === 'spring_ap') {
            $season_code = '0';
        } elseif ($siken === 'fall_ap') {
            $season_code = '1';
        } else {
            \Log::warning("Local_JSON: 未対応の試験区分 '{$siken}' が指定されました。デフォルトで fall_ap とみなします。");
            $season_code = '1';
        }

        \Log::info("Local_JSON: 使用する季節コード: $season_code");

        $files = scandir($path);
        $questions = [];
        foreach ($files as $file) {
            if (strpos($file, '.json') !== false) {
                // ファイル名から季節コードを抽出し、選択された季節と一致するか確認
                // 例: 2024001.json -> '0', 2024101.json -> '1'
                if (strlen($file) >= 5 && substr($file, 4, 1) == $season_code) {
                    \Log::info("Local_JSON: Processing file: $file for type {$type}");
                    $filePath = "$path/$file";

                    if (!file_exists($filePath)) {
                        \Log::error("Local_JSON: ファイルが存在しません: $filePath for type {$type}");
                        continue;
                    }

                    $jsonContent = file_get_contents($filePath);
                    if ($jsonContent === false) {
                        \Log::error("Local_JSON: ファイルを読み込めませんでした: $filePath for type {$type}");
                        continue;
                    }

                    $decoded = json_decode($jsonContent, true);
                    if (json_last_error() !== JSON_ERROR_NONE) {
                        \Log::error("Local_JSON: JSONのデコードに失敗しました: $filePath, エラー: " . json_last_error_msg());
                        continue;
                    }

                    if (isset($decoded['quizzes']) && is_array($decoded['quizzes'])) {
                        foreach ($decoded['quizzes'] as $quiz) {
                            if (isset($quiz['id']) && (isset($quiz['sentaku']) || isset($quiz['kaitou']))) {
                                // sentakuが存在する場合は4つであることを確認
                                if (isset($quiz['sentaku']) && (!is_array($quiz['sentaku']) || count($quiz['sentaku']) !== 4)) {
                                    \Log::error("Local_JSON: 質問ID {$quiz['id']} に 'sentaku' が存在しないか、4つではありません。ファイル: $filePath for type {$type}");
                                    continue;
                                }

                                // kaitouが存在する場合は文字列であることを確認
                                if (isset($quiz['kaitou']) && !is_string($quiz['kaitou'])) {
                                    \Log::error("Local_JSON: 質問ID {$quiz['id']} に 'kaitou' が存在しないか、形式が正しくありません。ファイル: $filePath for type {$type}");
                                    continue;
                                }

                                \Log::info("Local_JSON: Loaded question ID {$quiz['id']} from file: $filePath for type {$type}");
                                $questions[] = $quiz;
                            } else {
                                \Log::error("Local_JSON: 質問にIDがないか、'sentaku'/'kaitou'が存在しません。ファイル: $filePath for type {$type}");
                            }
                        }
                    } else {
                        \Log::error("Local_JSON: quizzesが設定されていません。ファイル: $filePath for type {$type}");
                    }
                }
            }
        }
        \Log::info("Local_JSON: Loaded questions count: " . count($questions) . " for type {$type}");
        return $questions;
    }



    public function showResult()
    {
        $totalQuestions = count(Session::get('questions', []));
        $correctAnswers = Session::get('correct_answers', 0);
        $timeTaken = time() - Session::get('total_start_time', time());
    
        // 問題ごとの得点を計算
        $perQuestionScore = floor(10000 / $totalQuestions); // スコアが大きすぎるため後述の計算方法を提案
    
        // 時間ペナルティの分母を設定（例: 5秒ごとに1ポイントのペナルティ）
        $timeDivider = 5;
    
        // 時間ペナルティを計算
        $timePenalty = floor($timeTaken / $timeDivider);
    
        // スコアを計算
        $score = ($correctAnswers * $perQuestionScore) - $timePenalty;
    
        // スコアが0未満にならないように調整
        $score = max($score, 0);
    
        return view('questions.result', compact('totalQuestions', 'correctAnswers', 'timeTaken', 'score', 'perQuestionScore'));
    }

    public function showAPResult()
    {
        $totalQuestions = count(Session::get('questions_ap', []));
        $correctAnswers = Session::get('correct_answers_ap', 0);
        $timeTaken = time() - Session::get('total_start_time_ap', time());
    
        // スコアの計算方法を調整（例: 正解数 × 100 - 所要時間（分） × 10）
        $timeMinutes = floor($timeTaken / 60);
        $score = ($correctAnswers * 100) - ($timeMinutes * 10);
        $score = max($score, 0);
    
        return view('questions.result', compact('totalQuestions', 'correctAnswers', 'timeTaken', 'score'));
    }

}