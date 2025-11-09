@props([
    // エラー表示対象のエラーバッグ名（通常は 'default'）
    'bag' => 'default',
    // 見出し文言（差し替え可能）
    'title' => __('入力内容にエラーがあります。ご確認ください。'),
    // まとめ表示かどうか（true: すべて、false: 先頭のみ）
    'showAll' => true,
])

@php
    /** @var \Illuminate\Support\ViewErrorBag $errors */
    $errorBag = $errors->getBag($bag);
@endphp

@if ($errorBag->any())
    <div role="alert" aria-live="assertive"
         class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4">
        <div class="mb-2 flex items-center gap-2">
            <svg viewBox="0 0 24 24" class="h-5 w-5" aria-hidden="true">
                <path d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"
                      fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <p class="font-medium text-red-800">
                {{ $title }}
            </p>
        </div>

        @php
            // 重複を避けたい場合は unique() してもOK
            $messages = $showAll ? $errorBag->all() : [$errorBag->first()];
        @endphp

        <ul class="list-inside list-disc text-sm text-red-700 space-y-1">
            @foreach($messages as $message)
                <li>{!! e($message) !!}</li>
            @endforeach
        </ul>
    </div>
@endif
