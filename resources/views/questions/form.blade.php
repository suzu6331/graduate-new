@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">基本情報年度別データ</h1>
    <form method="post" action="{{ route('questions.start') }}">
    @csrf <!-- CSRFトークンの生成 -->
    <input type="hidden" name="exam" id="exam" value="">
    <table class="table-auto w-full border-collapse border border-gray-300">
        <thead>
            <tr>
                <th class="border border-gray-300 px-4 py-2">年度</th>
                <th class="border border-gray-300 px-4 py-2">春</th>
                <th class="border border-gray-300 px-4 py-2">秋</th>
                <th class="border border-gray-300 px-4 py-2">免除</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($yearlyData as $item)
                <tr class="border border-gray-300">
                    <td class="border border-gray-300 px-4 py-2">{{ $item['year'] }}</td>
                    <td class="border border-gray-300 px-4 py-2 text-center">
                        @if (isset($item['spring']))
                            <input type="radio" name="year" value="{{ substr($item['spring']['link'], strpos($item['spring']['link'], 'year=') + 5, 4) }}" data-exam="spring" id="spring-{{ $item['year'] }}" class="mr-2">
                            <label for="spring-{{ $item['year'] }}">春</label>
                        @else
                            -
                        @endif
                    </td>
                    <td class="border border-gray-300 px-4 py-2 text-center">
                        @if (isset($item['fall']))
                            <input type="radio" name="year" value="{{ substr($item['fall']['link'], strpos($item['fall']['link'], 'year=') + 5, 4) }}" data-exam="fall" id="fall-{{ $item['year'] }}" class="mr-2">
                            <label for="fall-{{ $item['year'] }}">秋</label>
                        @else
                            -
                        @endif
                    </td>
                    <td class="border border-gray-300 px-4 py-2 text-center">
                        @if (isset($item['menjo']))
                            <input type="radio" name="year" value="{{ substr($item['menjo']['link'], strpos($item['menjo']['link'], 'year=') + 5, 4) }}" data-exam="menjo" id="menjo-{{ $item['year'] }}" class="mr-2">
                            <label for="menjo-{{ $item['year'] }}">免除</label>
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <button type="submit" class="btn btn-primary mt-4 px-4 py-2">選択した年度の問題を表示</button>
    </form>
</div>

<!-- JavaScript を追加 -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const radios = document.querySelectorAll('input[name="year"]');
        const examInput = document.getElementById('exam');
        radios.forEach(radio => {
            radio.addEventListener('change', function() {
                examInput.value = this.dataset.exam;
            });
        });
    });
</script>
@endsection
