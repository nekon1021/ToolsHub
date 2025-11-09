<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class MaxPixels implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    // 例: 4,000万px
    public function __construct(private int $max = 40_000_000)
    {
        
    }
    
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $path = $value->getPathname();
        $info = @getimagesize($path);

        if (!$info || !isset($info[0], $info[1])) {
            $fail(__('validation.image_unreadable'));
            return;
        }

        [$w, $h] = [(int)$info[0], (int)$info[1]];
        if ($w * $h > $this->max) {
            $fail(__('validation.max_pixels', [
                'max' => number_format($this->max),
            ]));
        }
    }
}
