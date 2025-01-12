@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-3xl font-bold mb-4">応用情報ランキング TOP 10</h1>
    @if ($topScores->isEmpty())
        <p>まだスコアが登録されていません。</p>
    @else
        <table class="table-auto w-full text-left border-collapse">
            <thead>
                <tr class="border-b">
                    <th class="px-4 py-2">順位</th>
                    <th class="px-4 py-2">ユーザー名</th>
                    <th class="px-4 py-2">スコア</th>
                    <th class="px-4 py-2">正解数 / 問題数</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($topScores as $index => $score)
                <tr class="border-b">
                    <td class="px-4 py-2">{{ $index + 1 }}</td>
                    <td class="px-4 py-2">{{ $score->user->name }}</td>
                    <td class="px-4 py-2">{{ $score->score }}</td>
                    <td class="px-4 py-2">{{ $score->correct_answers }} / {{ $score->total_questions }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
