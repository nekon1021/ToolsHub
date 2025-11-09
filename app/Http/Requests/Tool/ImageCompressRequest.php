<?php

namespace App\Http\Requests\Tool;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\MaxPixels;
class ImageCompressRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // アップロード画像本体
            'image'         => [
                'bail', 'required', 'file', 'image',
                // SVGはXSS観点で除外推奨
                'mimes:jpeg,jpg,png,webp,avif',
                'mimetypes:image/jpeg,image/png,image/webp,image/avif',
            // 例: 5MB
                'max:5120',
                new MaxPixels(40_000_000),
            ],
            // オプション
            'quality'       => ['nullable', 'integer', 'min:1', 'max:100'],
            'format'        => ['nullable', 'in:jpeg,png,webp,avif'],
            'resize_width'  => ['nullable', 'integer', 'min:1', 'max:8000'],
            'resize_height' => ['nullable', 'integer', 'min:1', 'max:8000'],
        ];
    }

     public function validated($key = null, $default = null)
    {
        $data = parent::validated($key, $default);

        // デフォルト値をここで補完
        $data['quality'] = (int)($data['quality'] ?? 85);
        $data['format']  = $data['format'] ?? 'jpeg';

        return $data;
    }
}
