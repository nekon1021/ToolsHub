@props([
    // 必須：バリデーション対象のフィールド名（例: 'image', 'quality'）
    'name',
    // エラーバッグ（通常 'default'）
    'bag' => 'default',
    // 追加クラス
    'class' => '',
])

@php
    /** @var \Illuminate\Support\ViewErrorBag $errors */
    $errorBag = $errors->getBag($bag);
    $errorMsg = $errorBag->first($name);
    // 入力と関連付ける error id（aria-describedby 用）
    $errorId = "error-".strtr($name, ['[' => '-', ']' => '', '.' => '-']);
@endphp

@if ($errorMsg)
    <p id="{{ $errorId }}"
       class="mt-2 text-sm text-red-600 {{ $class }}">
        {!! e($errorMsg) !!}
    </p>
@endif
