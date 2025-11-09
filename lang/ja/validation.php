<?php

return [
    'accepted'             => ':attributeを承認してください。',
    'active_url'           => ':attributeは有効なURLではありません。',
    'after'                => ':attributeには :date 以降の日付を指定してください。',
    'after_or_equal'       => ':attributeには :date 以降の日付を指定してください。',
    'alpha'                => ':attributeには英字のみ使用できます。',
    'alpha_dash'           => ':attributeには英数字・ダッシュ(-)・アンダースコア(_)が使用できます。',
    'alpha_num'            => ':attributeには英数字のみ使用できます。',
    'array'                => ':attributeは配列でなければなりません。',
    'before'               => ':attributeには :date 以前の日付を指定してください。',
    'before_or_equal'      => ':attributeには :date 以前の日付を指定してください。',
    'between'              => [
        'numeric' => ':attributeは :min から :max の間で指定してください。',
        'file'    => ':attributeは :min KBから :max KBの間で指定してください。',
        'string'  => ':attributeは :min 文字から :max 文字の間で指定してください。',
        'array'   => ':attributeは :min 個から :max 個の間で指定してください。',
    ],
    'boolean'              => ':attributeには true か false を指定してください。',
    'confirmed'            => ':attributeが確認用と一致していません。',
    'current_password'     => 'パスワードが正しくありません。',
    'date'                 => ':attributeは有効な日付ではありません。',
    'date_equals'          => ':attributeには :date と同じ日付を指定してください。',
    'date_format'          => ':attributeの形式が :format と一致しません。',
    'different'            => ':attributeと :other には異なる値を指定してください。',
    'digits'               => ':attributeは :digits 桁で指定してください。',
    'digits_between'       => ':attributeは :min 桁から :max 桁の間で指定してください。',
    'email'                => ':attributeには有効なメールアドレスを指定してください。',
    'ends_with'            => ':attributeは次のいずれかで終わらなければなりません: :values',
    'exists'               => '選択された :attributeは正しくありません。',
    'file'                 => ':attributeはファイルでなければなりません。',
    'filled'               => ':attributeは必須です。',
    'gt'                   => [
        'numeric' => ':attributeは :value より大きい値にしてください。',
        'file'    => ':attributeは :value KBより大きいファイルにしてください。',
        'string'  => ':attributeは :value 文字より多く入力してください。',
        'array'   => ':attributeは :value 個より多く指定してください。',
    ],
    'gte'                  => [
        'numeric' => ':attributeは :value 以上にしてください。',
        'file'    => ':attributeは :value KB以上にしてください。',
        'string'  => ':attributeは :value 文字以上で入力してください。',
        'array'   => ':attributeは :value 個以上指定してください。',
    ],
    'in'                   => '選択された :attributeは正しくありません。',
    'in_array'             => ':attributeは :other に存在しません。',
    'integer'              => ':attributeは整数で指定してください。',
    'ip'                   => ':attributeは有効なIPアドレスを指定してください。',
    'ipv4'                 => ':attributeは有効なIPv4アドレスを指定してください。',
    'ipv6'                 => ':attributeは有効なIPv6アドレスを指定してください。',
    'json'                 => ':attributeは有効なJSON文字列でなければなりません。',
    'lt'                   => [
        'numeric' => ':attributeは :value より小さい値にしてください。',
        'file'    => ':attributeは :value KBより小さいファイルにしてください。',
        'string'  => ':attributeは :value 文字より少なく入力してください。',
        'array'   => ':attributeは :value 個より少なく指定してください。',
    ],
    'lte'                  => [
        'numeric' => ':attributeは :value 以下にしてください。',
        'file'    => ':attributeは :value KB以下にしてください。',
        'string'  => ':attributeは :value 文字以下で入力してください。',
        'array'   => ':attributeは :value 個以下で指定してください。',
    ],
    'max'                  => [
        'numeric' => ':attributeは :max 以下にしてください。',
        'file'    => ':attributeは :max KB以下にしてください。',
        'string'  => ':attributeは :max 文字以下で入力してください。',
        'array'   => ':attributeは :max 個以下で指定してください。',
    ],
    'mimes'                => ':attributeは :values タイプのファイルでなければなりません。',
    'mimetypes'            => ':attributeは :values タイプのファイルでなければなりません。',
    'min'                  => [
        'numeric' => ':attributeは :min 以上にしてください。',
        'file'    => ':attributeは :min KB以上にしてください。',
        'string'  => ':attributeは :min 文字以上で入力してください。',
        'array'   => ':attributeは :min 個以上で指定してください。',
    ],
    'not_in'               => '選択された :attributeは正しくありません。',
    'not_regex'            => ':attributeの形式が正しくありません。',
    'numeric'              => ':attributeは数値で指定してください。',
    'present'              => ':attributeは必ず入力してください。',
    'regex'                => ':attributeの形式が正しくありません。',
    'required'             => ':attributeは必須です。',
    'required_if'          => ':other が :value のとき、:attributeは必須です。',
    'required_unless'      => ':other が :values でない限り、:attributeは必須です。',
    'required_with'        => ':values のいずれかが入力されているとき、:attributeは必須です。',
    'required_with_all'    => ':values がすべて入力されているとき、:attributeは必須です。',
    'required_without'     => ':values のいずれかが未入力のとき、:attributeは必須です。',
    'required_without_all' => ':values がすべて未入力のとき、:attributeは必須です。',
    'same'                 => ':attributeと :other が一致しません。',
    'size'                 => [
        'numeric' => ':attributeは :size にしてください。',
        'file'    => ':attributeは :size KBにしてください。',
        'string'  => ':attributeは :size 文字で入力してください。',
        'array'   => ':attributeは :size 個にしてください。',
    ],
    'starts_with'          => ':attributeは次のいずれかで始まらなければなりません: :values',
    'string'               => ':attributeを入力してください。',
    'timezone'             => ':attributeは有効なタイムゾーンを指定してください。',
    'unique'               => ':attributeはすでに使用されています。',
    'uploaded'             => ':attributeのアップロードに失敗しました。',
    'url'                  => ':attributeは有効なURLを指定してください。',
    'uuid'                 => ':attributeは有効なUUIDを指定してください。',

    'image_unreadable' => '有効な画像を指定してください。',
    'max_pixels'       => '画像が大きすぎます（合計ピクセルが :max を超えています）。',

    'custom' => [
        // 画像圧縮ツール（フィールド別カスタム）
        'image' => [
            'required' => '画像ファイルを選択してください。',
            'image'    => '画像ファイルのみアップロードできます。',
            'mimes'    => '対応形式: jpeg, png, webp, avif',
            // max（file）は :max がKBで入ります（Laravel既定）
            'max'      => 'ファイルサイズが大きすぎます（最大: :max KB）。',
        ],

        // 文字カウントの共通メッセージ
        'text' => [
            'present' => '文字列を入力してください。',
            'max' => '文字が長すぎます。(最大20,000文字)。',
        ],

        'quality' => [
            // フォームルールが integer|between:1,100 の想定
            'integer' => 'quality は整数で指定してください。',
            'min'     => 'quality は :min 以上で指定してください。',
            'max'     => 'quality は :max 以下で指定してください。',
        ],

        'format' => [
            // ルールが in:jpeg,png,webp,avif の想定
            'in' => 'format は jpeg/png/webp/avif のみ対応です。',
        ],

        'resize_width' => [
            // ルールが integer|between:1,8000 の想定
            'integer' => 'resize_width は整数で指定してください。',
            'min'     => 'resize_width は :min 以上で指定してください。',
            'max'     => 'resize_width は :max 以下で指定してください。',
        ],

        'resize_height' => [
            // ルールが integer|between:1,8000 の想定
            'integer' => 'resize_height は整数で指定してください。',
            'min'     => 'resize_height は :min 以上で指定してください。',
            'max'     => 'resize_height は :max 以下で指定してください。',
        ],
    ],

    'attributes' => [
        // フォーム項目名の日本語ラベル（任意）
        'email' => 'メールアドレス',
        'name'  => '名前',
        'password' => 'パスワード',
        'text' => '文字',

        // 画像圧縮ツール
        'image'         => '画像ファイル',
        'quality'       => '画質',
        'format'        => '出力形式',
        'resize_width'  => 'リサイズ幅',
        'resize_height' => 'リサイズ高さ',
    ],
];
