#!/usr/bin/env bash
set -euo pipefail
C="docker compose -f docker-compose.prod.yml"

# メンテモード開始（失敗しても続行）
$C exec -T app php artisan down || true

# 新イメージ取得＆起動
$C pull app web
$C up -d app web

# DBマイグレーション
$C exec -T app php artisan migrate --force

# キャッシュ再生成
$C exec -T app php artisan optimize:clear
$C exec -T app php artisan config:cache
$C exec -T app php artisan route:cache
$C exec -T app php artisan view:cache

# Queue再起動（Horizonなら horizon:terminate に置き換え可）
$C exec -T app php artisan queue:restart || true

# メンテ解除
$C exec -T app php artisan up

# ヘルスチェック
curl -fsS http://127.0.0.1/ > /dev/null || (echo "Health check failed" && exit 1)
echo "✅ Deploy OK"
