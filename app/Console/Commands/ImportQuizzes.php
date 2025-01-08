<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Quiz;
use App\Models\QuizOption;
use Illuminate\Support\Facades\File;

class ImportQuizzes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:quizzes {type}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import quizzes from JSON files into the database. Type: FE or AP';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $type = strtoupper($this->argument('type'));

        if (!in_array($type, ['FE', 'AP'])) {
            $this->error("Invalid type specified. Use 'FE' or 'AP'.");
            return 1;
        }

        $basePath = $type === 'FE' ? public_path("FE") : public_path("AP");
        if (!File::exists($basePath)) {
            $this->error("Directory does not exist: {$basePath}");
            return 1;
        }

        // 年度ごとのディレクトリを取得
        $yearDirs = File::directories($basePath);
        foreach ($yearDirs as $yearDir) {
            $year = basename($yearDir);

            // 季節ごとのファイルを取得
            $files = File::files($yearDir);
            foreach ($files as $file) {
                if ($file->getExtension() !== 'json') {
                    continue;
                }

                $fileName = $file->getFilename();
                $questionId = pathinfo($fileName, PATHINFO_FILENAME);

                // ファイル名から季節を判定
                $seasonCode = substr($questionId, 4, 1);
                if ($type === 'FE' && in_array($year, ['2020', '2021', '2022'])) {
                    // FEの2020～2022年は'menjo'として扱う
                    $season = 'menjo';
                } else {
                    $season = $seasonCode === '0' ? 'spring' : 'fall';
                }

                // JSONファイルの内容を読み込む
                $jsonContent = File::get($file->getRealPath());
                $decoded = json_decode($jsonContent, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    $this->error("JSON decode error in file: {$file->getRealPath()}");
                    continue;
                }

                if (!isset($decoded['quizzes']) || !is_array($decoded['quizzes'])) {
                    $this->error("Invalid JSON structure in file: {$file->getRealPath()}");
                    continue;
                }

                foreach ($decoded['quizzes'] as $quizData) {
                    // 必要なフィールドの存在を確認
                    if (!isset($quizData['id'], $quizData['mondai'], $quizData['answer'])) {
                        $this->error("Missing required fields in quiz data: " . json_encode($quizData));
                        continue;
                    }

                    // 'kaitou' が存在するか確認
                    $hasKaitou = isset($quizData['kaitou']);

                    // クイズを作成または更新（exam_typeも条件に追加）
                    // クイズを作成または更新（exam_typeも条件に追加）
                    $quiz = Quiz::updateOrCreate(
                        [
                            'question_id' => $quizData['id'],
                            'exam_type' => $type
                        ],
                        [
                            'year' => intval(substr($quizData['id'], 0, 4)),
                            'season' => $season,
                            'mondai' => $quizData['mondai'],
                            'kaitou' => $hasKaitou ? $quizData['kaitou'] : null,
                            'answer' => intval($quizData['answer']),
                        ]
                    );


                    // 選択肢が存在する場合
                    if (isset($quizData['sentaku']) && is_array($quizData['sentaku'])) {
                        foreach ($quizData['sentaku'] as $index => $optionText) {
                            QuizOption::updateOrCreate(
                                [
                                    'quiz_id' => $quiz->id,
                                    'option_index' => $index,
                                ],
                                [
                                    'option_text' => $optionText,
                                ]
                            );
                        }
                    }

                    $this->info("Imported Quiz ID: {$quizData['id']} ({$type})");
                }
            }
        }

        $this->info("Quiz import completed successfully for type: {$type}.");
        return 0;
    }
}
