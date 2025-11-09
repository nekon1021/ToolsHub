{{-- resources/views/tool/index.blade.php --}}
@extends('layouts.app')

@section('title', '画像圧縮｜' . config('app.name'))

@section('meta_description', 'JPEG PNG WebP AVIFの画像圧縮と変換に対応。品質調整・リサイズ可能。ドラッグ＆ドロップでアップロードし、軽量画像をすぐにダウンロード。')

@section('content')
  <div class="mx-auto max-w-5xl px-4">
    <div class="max-w-2xl mx-auto w-full space-y-6">
      {{-- ▼ パンくず --}}
      @include('partials.breadcrumbs', [
        'items' => [
          ['name' => 'ホーム', 'url' => url('/')],
          // ツール一覧ページがあれば↓を有効化（なければ消してOK）
          // ['name' => 'ツール', 'url' => route('tools.index')],
          ['name' => '画像圧縮'] // ← currentは自動付与
        ]
      ])

      <h1 class="text-2xl font-bold">画像圧縮</h1>

      {{-- 上部広告（?dummy=1 でダミー出し） --}}
      {{-- <x-ad.slot id="tool-top" class="my-4" /> --}}
      {{-- @includeIf('partials.ads.tool-top') --}}

      @php($r = session('count_result'))
      @php($hasResult = $r !== null)

      {{-- 全体エラー --}}
      <x-form.errors-summary class="mb-6" />

      {{-- コンテンツ部分 --}}
      <form method="POST" action="{{ route('tools.image.compressor.store')}}" enctype="multipart/form-data">
        @csrf

        {{-- アップロードUI（クリック & DnD） --}}
      <div>
        <label class="block font-semibold">画像ファイル</label>

        {{-- 実ファイル入力（送信用・非表示） --}}
        <input id="file-input" type="file" name="image" accept="image/*" class="hidden">

        {{-- 大きいボタン（画像投入後はこの中身がプレビューに変わる） --}}
         <button
            id="pick-button"
            type="button"
            class="mt-2 w-full rounded-xl border-2 border-dashed border-gray-300 bg-white px-6 py-12 text-center hover:border-blue-400 hover:bg-blue-50 transition focus:outline-none focus:ring"
            aria-label="ここをクリックまたは画像をドロップして選択"
          >
            <div class="flex flex-col items-center justify-center">
              {{-- アイコン（任意） --}}
              <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-500" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 15a4 4 0 004 4h10a4 4 0 004-4V8a4 4 0 00-4-4H7a4 4 0 00-4 4v7m8-8v9m0 0l-3-3m3 3l3-3"/>
              </svg>
              <p class="mt-3 text-sm text-gray-700">
                クリックして選択 / ドラッグ＆ドロップ
              </p>
              <p class="mt-1 text-xs text-gray-500">対応：jpeg/jpg/png/webp/avif・5MBまで</p>
            </div>
          </button>

          {{-- プレビュー（画像が入ったらこちらに置き換える） --}}
          <div id="preview-wrap" class="relative mt-2 hidden">
            <img id="img-preview" class="w-full h-auto rounded-xl border bg-white object-contain">
            {{-- ファイル名帯（下辺に重ねる・上にしたい場合は bottom-0 → top-0） --}}
            <div class="absolute bottom-0 left-0 right-0 bg-black/60 text-white text-xs sm:text-sm px-3 py-2 flex flex-wrap items-center gap-x-2">
              <span id="file-name" class="font-medium truncate max-w-[70%]"></span>
              <span id="file-size" class="opacity-90"></span>
              <span id="img-dim"  class="opacity-90"></span>
              <button type="button" id="change-btn" class="ml-auto underline decoration-white/70 hover:decoration-white">変更する</button>
            </div>
          </div>

          <x-form.field-error name="image" />
      </div>

        {{-- 出力形式 --}}
        <div class="mt-6">
          <label class="block font-semibold">出力形式</label>
          <select name="format" class="border p-2 w-full">
            <option value="jpeg">JPEG</option>
            <option value="webp">WEBP</option>
            <option value="avif">AVIF</option>
            <option value="png">PNG</option>
          </select>
          <x-form.field-error name="format" />
        </div>

        {{-- 品質 --}}
        <div class="mt-6">
          <label class="block font-semibold">品質（1〜100 / PNGは無視）</label>
          <input type="number" name="quality" min="1" max="100" value="85" class="border p-2 w-full">
          <x-form.field-error name="quality" />
        </div>

        <div class="grid-cols-1 gap-4 mt-6 md:grid grid-cols-2">
          <div>
            <label class="block font-semibold">最大幅（任意）</label>
            <input type="number" name="resize_width" min="1" max="8000" class="border p-2 w-full">
            <x-form.field-error name="resize_width" />
          </div>
          <div>
            <label class="block font-semibold">最大高（任意）</label>
            <input type="number" name="resize_height" min="1" max="8000" class="border p-2 w-full">
            <x-form.field-error name="resize_height" />
          </div>
        </div>

        <div class="mt-6 md:mt-6 flex justify-end">
          <button class="bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded md:center">
            圧縮してダウンロード
          </button>
        </div>
      </form>

      {{-- ▼ 追加：ツール中段広告（?dummy=1 でダミー可） --}}
      {{-- <x-ad.slot id="tool-mid" class="my-8" /> --}}
      {{-- パーシャル派: @includeIf('partials.ads.tool-mid') --}}

      <section class="mt-6 p-4 bg-gray-50 border border-gray-200 rounded-md text-sm text-gray-700 space-y-3">
        <h2 class="font-semibold text-gray-800">このツールについて</h2>
        <p>
          この画像圧縮ツールは、JPEG / PNG / WebP / AVIF への変換と、品質の調整・リサイズを
          まとめて行えるシンプルな画像最適化サービスです。投稿画像の容量を減らしたいときや、
          Webサイト・ブログ・SNS 用に軽量な画像を作りたいときにご利用いただけます。
        </p>

        <dl class="space-y-3 ml-5">

          <div>
            <dt class="font-semibold">画像形式ごとのおすすめ用途</dt>
            <dd class="ml-6">
              <strong>JPEG：</strong>写真・スクリーンショットなど階調が多い画像向け。圧縮率が高く、WebブログやSNSで最も使われます。<br>
              <strong>PNG：</strong>ロゴ・イラスト・UIパーツなど、輪郭がはっきりした画像に最適。透過背景に対応し、画質を落とさず保存できます。<br>
              <strong>WebP：</strong>JPEGより軽く、高画質を保ちやすい次世代形式。写真・イラストどちらでも軽量化効果が高く、サイト高速化に有効です。<br>
              <strong>AVIF：</strong>最も高い圧縮率を持つ次世代画像。高画質・超軽量で、Webパフォーマンスを追求する用途に最適（ただし一部環境で非対応の場合あり）。<br>
            </dd>
          </div>
          
          <div>
            <dt class="font-semibold">主な機能</dt>
            <dd class="ml-6">
              ・画像形式の変換（JPEG / PNG / WebP / AVIF）<br>
              ・品質（1〜100）の調整によるファイルサイズ削減<br>
              ・任意の最大幅・最大高を指定してリサイズ（縦横比は自動調整）<br>
              ・透明背景の画像を JPEG に変換する際は白背景に自動補正<br>
            </dd>
          </div>

          <div>
            <dt class="font-semibold">対応形式と制限</dt>
            <dd class="ml-6">
              ・アップロード可能な画像形式：JPEG / JPG / PNG / WebP / AVIF<br>
              ・最大サイズ：5MB、最大画素数：4,000万ピクセル<br>
              ・WebP・AVIF はサーバー環境によりエンコードできない場合があります<br>
            </dd>
          </div>

          <div>
            <dt class="font-semibold">安全性について</dt>
            <dd class="ml-6">
              アップロードされた画像はサーバー上に保存されず、圧縮処理後すぐに削除されます。
              ダウンロード以外の用途に利用されることはありませんので、安心してご利用いただけます。
            </dd>
          </div>

          <div>
            <dt class="font-semibold">ご利用上の注意</dt>
            <dd class="ml-6">
              ・品質を下げすぎると画質が大きく劣化します。70〜85 付近がバランスの良い目安です。<br>
              ・PNG は可逆圧縮のため、品質値は「減色の強さ」として扱われます。<br>
              ・縦横どちらか一方だけ入力すると、もう一方は自動的に比率を維持して調整されます。
            </dd>
          </div>
        </dl>
      </section>

      {{-- 下部広告 --}}
      {{-- <x-ad.slot id="tool-bottom" class="my-10" /> --}}
      {{-- @includeIf('partials.ads.tool-bottom') --}}
    </div>
  </div>

  <script>
    const fileInput   = document.getElementById('file-input');
    const pickButton  = document.getElementById('pick-button');
    const previewWrap = document.getElementById('preview-wrap');
    const imgPreview  = document.getElementById('img-preview');
    const fileNameEl  = document.getElementById('file-name');
    const fileSizeEl  = document.getElementById('file-size');
    const imgDimEl    = document.getElementById('img-dim');
    const changeBtn   = document.getElementById('change-btn');

    // --- ボタンをクリック → ファイル選択
    pickButton.addEventListener('click', () => fileInput.click());
    pickButton.addEventListener('keydown', (e) => {
      if (e.key === 'Enter' || e.key === ' ') {
        e.preventDefault(); fileInput.click();
      }
    });

    // --- Drag & Drop（ボタン自体をドロップゾーン化）
    ['dragenter','dragover'].forEach(type => {
      pickButton.addEventListener(type, (e) => {
        e.preventDefault(); e.stopPropagation();
        pickButton.classList.add('border-blue-500','bg-blue-50');
      });
    });
    ['dragleave','dragend','drop'].forEach(type => {
      pickButton.addEventListener(type, (e) => {
        e.preventDefault(); e.stopPropagation();
        pickButton.classList.remove('border-blue-500','bg-blue-50');
      });
    });
    pickButton.addEventListener('drop', (e) => {
      const files = e.dataTransfer.files;
      if (!files || !files.length) return;
      fileInput.files = files;   // 送信用 input に反映
      handleFile(files[0]);      // プレビュー
    });

    // --- 通常のファイル選択
    fileInput.addEventListener('change', (e) => {
      const f = e.target.files?.[0];
      if (f) handleFile(f);
    });

    // --- 画像を差し替えてプレビュー表示
    function handleFile(file) {
      fileNameEl.textContent = file.name;
      fileSizeEl.textContent = `(${(file.size/1024/1024).toFixed(2)} MB)`;

      const reader = new FileReader();
      reader.onload = (ev) => {
        imgPreview.src = ev.target.result;
        imgPreview.onload = () => {
          imgDimEl.textContent = `- ${imgPreview.naturalWidth}×${imgPreview.naturalHeight}px`;
          // ピッカーボタン → 非表示、プレビュー → 表示
          pickButton.classList.add('hidden');
          previewWrap.classList.remove('hidden');
        };
      };
      reader.readAsDataURL(file);
    }

    // --- 「変更する」：再度ボタンに戻す
    changeBtn.addEventListener('click', () => {
      fileInput.value = '';
      imgPreview.src = '';
      previewWrap.classList.add('hidden');
      pickButton.classList.remove('hidden');
    });
  </script>

@endsection
