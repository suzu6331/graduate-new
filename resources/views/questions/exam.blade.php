<!-- resources/views/questions/exam.blade.php -->

@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    {{-- 残り時間の表示 --}}
    <div>残り時間: <span id="time" data-endtime="{{ $end_time }}"></span></div>
    
    <!-- エラーメッセージの表示 -->
    @if (isset($error_message))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
            <span class="block sm:inline">{{ $error_message }}</span>
        </div>
    @endif

    @if (isset($current_question))
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md mb-6 relative">
            <h2 class="text-2xl font-bold mb-2">問題 {{ $curt_idx + 1 }} / {{ isset($questions) ? count($questions) : (isset($quizzes) ? count($quizzes) : '不明') }}</h2>
            
            <!-- 質問文の表示 -->
            <div class="mb-4">{!! $current_question['mondai'] !!}</div>

            <!-- kaitou の表示 -->
            @if (isset($current_question['kaitou']))
                <div class="mb-4">{!! $current_question['kaitou'] !!}</div>
            @endif

            @php
                // セッションキーに基づいて年度を取得
                $year = Session::has('years') ? Session::get('years')[0] : (Session::has('years_ap') ? Session::get('years_ap')[0] : '不明');
                // question_number の取得方法を修正
                $question_number = intval(substr($current_question['question_id'], -2));
            @endphp
            <div class="absolute bottom-2 right-4 text-gray-500 text-sm">
                {{ $year }} 年の {{ $question_number }} 問目
            </div>

            <!-- 選択肢の表示：常にフォームを表示 -->
            <form method="post" action="{{ route(isset($examType) && $examType == 'ap' ? 'questions.ap.handle' : 'questions.handle') }}">
                @csrf
                <div>
                    @php
                        $labels = ['ア', 'イ', 'ウ', 'エ'];
                    @endphp
                    @foreach ($labels as $i => $label)
                        @php
                            // ユーザーが選択した選択肢かどうか
                            $isSelected = isset($user_choice) && intval($user_choice) === $i;
                            // 正解の選択肢かどうか
                            $isCorrectOption = isset($current_question['answer']) && intval($current_question['answer']) === $i;
                            // ユーザーが選択したが不正解な選択肢かどうか
                            $isIncorrectOption = isset($is_correct) && !$is_correct && $isSelected;
                        @endphp
                        <div class="mb-4">
                            <label class="flex items-center space-x-2">
                                <input type="radio" name="select" value="{{ $i }}" class="form-radio h-4 w-4 text-indigo-600" 
                                    {{ $isSelected ? 'checked' : '' }}
                                    {{ isset($user_choice) ? 'disabled' : '' }}>
                                <span class="font-medium">{{ $label }}:</span> 
                                @if (isset($displayOptions[$i]) && $displayOptions[$i] !== '')
                                    {!! $displayOptions[$i] !!}
                                @else
                                    <!-- optionsに画像がない場合はラベルを表示 -->
                                    <span>{{ $label }}</span>
                                @endif
                                {{-- 正解の場合は「正解」を表示 --}}
                                @if(isset($is_correct))
                                    @if($isCorrectOption)
                                        <span class="text-green-500 font-bold ml-2">正解</span>
                                    @endif
                                @endif
                            </label>
                        </div>
                    @endforeach
                </div>
                
                <!-- 回答するボタンを表示する条件 -->
                @if (!isset($user_choice) && !isset($correct_choice))
                    <button type="submit" name="submit_ans" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded w-full transition-colors duration-300">
                        回答する
                    </button>
                @endif
            </form>
        </div>
    @else
        <p class="text-center text-red-500">質問が見つかりませんでした。</p>
    @endif

    <!-- 結果表示セクション：回答後のみ表示 -->
    @if (isset($user_choice) && isset($correct_choice))
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md mb-6">
            <h2 class="text-2xl font-bold mb-4">結果</h2>
            <div class="mb-4">
                <strong class="text-gray-700 dark:text-gray-300">結果:</strong>
                @if ($is_correct)
                    <span class="text-green-600 font-semibold">正解</span>
                @else
                    <span class="text-red-600 font-semibold">不正解</span>
                @endif
            </div>
            <div class="mb-4">
                <strong class="text-gray-700 dark:text-gray-300">あなたの選択:</strong>
                @if ($user_choice !== '')
                    @if(array_key_exists(intval($user_choice), $current_question['options']))
                        <span class="ml-2">{{ $labels[intval($user_choice)] }}: {!! $current_question['options'][intval($user_choice)]['option_text'] !!}</span>
                    @else
                        <span class="ml-2">選択された選択肢は無効です。</span>
                    @endif
                @else
                    <span class="ml-2">{{ $labels[intval(request()->input('select'))] }}</span>
                @endif
            </div>
            <div class="mb-4">
                <strong class="text-gray-700 dark:text-gray-300">正解:</strong>
                @if ($correct_choice !== '')
                    <span class="ml-2">{{ $labels[intval($current_question['answer'])] }}: {!! $current_question['options'][intval($current_question['answer'])]['option_text'] !!}</span>
                @else
                    <span class="ml-2">{{ $labels[intval($current_question['answer'])] }}</span>
                @endif
            </div>
        </div>
    @endif

    <!-- 次の問題へのボタン：回答後のみ表示 -->
    @if (isset($user_choice) && isset($correct_choice))
        <form method="post" action="{{ route(isset($examType) && $examType == 'ap' ? 'questions.ap.handle' : 'questions.handle') }}">
            @csrf
            <button type="submit" name="next" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded w-full transition-colors duration-300">
                次の問題へ
            </button>
        </form>
    @endif
</div>

<!-- タイマーのスクリプト -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    var timeDisplay = document.getElementById('time');
    if (!timeDisplay) {
        console.error('timeDisplay element not found');
        return;
    }

    var endTime = parseInt(timeDisplay.getAttribute('data-endtime'));
    if (isNaN(endTime)) {
        console.error('Invalid endTime:', timeDisplay.getAttribute('data-endtime'));
        return;
    }

    function updateTime() {
        var now = Math.floor(Date.now() / 1000);
        var timeLeft = endTime - now;
        if (timeLeft <= 0) {
            timeDisplay.textContent = '時間切れ';
            clearInterval(timerInterval);
            // フォームを自動送信（オプション）
            var form = document.querySelector('form[name="auto_submit"]');
            if (form) {
                form.submit();
            }
        } else {
            var hours = Math.floor(timeLeft / 3600);
            var minutes = Math.floor((timeLeft % 3600) / 60);
            var seconds = timeLeft % 60;
            timeDisplay.textContent = hours + ':' + ('0' + minutes).slice(-2) + ':' + ('0' + seconds).slice(-2);
        }
    }

    var timerInterval = setInterval(updateTime, 1000);
    updateTime();
});
</script>
@endsection
