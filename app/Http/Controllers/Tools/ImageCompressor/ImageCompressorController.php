<?php

namespace App\Http\Controllers\Tools\ImageCompressor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tool\ImageCompressRequest;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Drivers\Imagick\Driver as ImagickDriver;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;

class ImageCompressorController extends Controller
{
    public function index()
    {
        return view('tools.image-compressor.image');
    }

    public function store(ImageCompressRequest $request)
    {
        // 1) バリデート済みデータ
        $data = $request->validated();

        // 2) ファイル取得
        $file = $request->file('image');

        try {
            // 3) 画像ドライバ選択（Imagick優先）
            $manager = $this->makeImageManager();

            // 4) 画像読込
            $image = $manager->read($file->getRealPath());

            // 5) EXIFの向き自動補正（v3は orient()）
            //   ※ ドライバ環境により未実装の場合があるので try で安全に
            try {
                if (method_exists($image, 'orient')) {
                    $image->orient();
                }
            } catch (\Throwable $t) {
                // 無視（対応していない/EXIFなし等）
            }

            // 6) 任意リサイズ（縦横の最大値に内接・アップサイズ禁止）
            $image = $this->maybeResize(
                $image,
                $data['resize_width']  ?? null,
                $data['resize_height'] ?? null
            );

            // 7) エンコード（形式＆品質）
            [$binary, $mime] = $this->encodeImage(
                $image,
                $data['format'],
                (int) $data['quality']
            );

            // 8) ダウンロード名
            $downloadFilename = $this->buildDownloadFilename(
                $file->getClientOriginalName(),
                $data['format']
            );

            // 9) ストリームダウンロード
            return response()->streamDownload(
                function () use ($binary) {
                    echo $binary;
                },
                $downloadFilename,
                [
                    'Content-Type'  => $mime,
                    'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
                    'Pragma'        => 'no-cache',
                    'Expires'       => '0',
                ]
            );

        } catch (\Throwable $e) {
            return back()
                ->withInput()
                ->withErrors(['image' => '画像の処理に失敗しました。再度お試しください。']);
        }
    }

    /** Imagick が使えれば Imagick、なければ GD */
    private function makeImageManager(): ImageManager
    {
        return extension_loaded('imagick')
            ? new ImageManager(new ImagickDriver())
            : new ImageManager(new GdDriver());
    }

    /** 指定があればリサイズ（アスペクト比維持 & アップサイズ防止） */
    private function maybeResize(ImageInterface $image, ?int $targetW, ?int $targetH): ImageInterface
    {
        if (empty($targetW) && empty($targetH)) {
            return $image;
        }

        $origW = $image->width();
        $origH = $image->height();

        $scaleW = $targetW ? ($targetW / $origW) : INF;
        $scaleH = $targetH ? ($targetH / $origH) : INF;
        $scale  = min($scaleW, $scaleH);

        if ($scale >= 1) {
            // アップサイズしない
            return $image;
        }

        $newW = max(1, (int) floor($origW * $scale));
        $newH = max(1, (int) floor($origH * $scale));

        $image->scale(width: $newW, height: $newH);

        return $image;
    }

    /** 形式＆品質でエンコードして [binary, mime] を返す */
    private function encodeImage(ImageInterface $image, string $format, int $quality): array
    {
        $format = strtolower($format);

        switch ($format) {
            case 'jpeg':
                $binary = $image->toJpeg($quality)->toString();
                $mime   = 'image/jpeg';
                break;

            case 'webp':
                // v3 の toWebp($quality) で可。高圧縮は AVIF がおすすめ。
                $binary = $image->toWebp($quality)->toString();
                $mime   = 'image/webp';
                break;

            case 'avif':
                // toAvif($quality) で良い（ドライバにより非対応環境あり）
                $binary = $image->toAvif($quality)->toString();
                $mime   = 'image/avif';
                break;

            case 'png':
                // PNG は引数なしでOK（品質は無関係）
                $binary = $image->toPng()->toString();
                $mime   = 'image/png';
                break;

            default:
                throw new \RuntimeException('Unsupported format: '.$format);
        }

        return [$binary, $mime];
    }

    /** ダウンロード名を元名ベースで生成 */
    private function buildDownloadFilename(string $originalName, string $ext): string
    {
        $base = pathinfo($originalName, PATHINFO_FILENAME);
        $safe = Str::slug($base) ?: 'image';
        return $safe.'_compressed.'.$ext;
    }
}
