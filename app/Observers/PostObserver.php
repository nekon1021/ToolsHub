<?php

namespace App\Observers;

use App\Models\Post;
use App\Models\SlugRedirect;
use Illuminate\Support\Str;


class PostObserver
{

    public function creating(Post $post): void
    {
        // 念のため小文字化（ルータ制約も [a-z0-9-]+）
        if (!blank($post->slug)) {
            $post->slug = Str::lower($post->slug);
        }
    }

    public function updating(Post $post): void
    {
        if ($post->isDirty('slug')) {
            $old = $post->getOriginal('slug');
            if ($old && $old !== $post->slug) {
                SlugRedirect::firstOrCreate(
                    ['old_slug' => $old],
                    ['post_id' => $post->id],
                );
            }
        }
    }

    public function saved(Post $post): void
    {
        $this->clearCaches($post);
    }

    public function deleted(Post $post): void
    {
        $this->saved($post); // 410 になる想定なのでキャッシュもクリア
    }

    public function restored(Post $post): void
    {
        $this->saved($post);
    }

    private function clearCaches(Post $post): void
    {
        // サイトマップ系キャッシュを破棄
        cache()->forget('sitemap:index');
        cache()->forget('sitemap:posts');
        cache()->forget('html_sitemap'); // HTMLサイトマップを使っている場合

        // サイドバーカテゴリ一覧（公開記事数つき）
        cache()->forget('sidebar:categories_with_counts');

        // 関連記事キャッシュ（現在と元カテゴリの投稿をまとめて破棄）
        $categories = collect([$post->category_id, $post->getOriginal('category_id')])
            ->map(static fn($category) => is_null($category) ? null : (int) $category)
            ->unique()
            ->filter(static fn($category) => is_null($category) || is_int($category));

        foreach ($categories as $categoryId) {
            $ids = Post::query()
                ->when(
                    is_null($categoryId),
                    fn($q) => $q->whereNull('category_id'),
                    fn($q) => $q->where('category_id', $categoryId)
                )
                ->pluck('id');

            foreach ($ids as $id) {
                cache()->forget("post:{$id}:related");
            }
        }
    }
}
