#!/usr/bin/env bash
set -euo pipefail

##
## 1) ディレクトリ定義
##   SRC_DIR : GitHub Actions が更新しているコード（/var/www/toolshub）
##   DEST_DIR: 実際に Docker 本番が使っているコード（/home/deploy/app）
##
SRC_DIR="/var/www/toolshub"
DEST_DIR="/home/deploy/app"

echo "==> Sync from ${SRC_DIR} to ${DEST_DIR}"

##
## 2) コード同期（rsync）
##   ※ .env / storage / vendor / node_modules / public/uploads は本番の状態を保持したいので除外
##
rsync -av --delete \
  --exclude '.git' \
  --exclude '.github' \
  --exclude 'storage' \
  --exclude 'vendor' \
  --exclude 'node_modules' \
  --exclude '.env' \
  --exclude 'public/uploads' \
  "${SRC_DIR}/" "${DEST_DIR}/"

echo "==> Sync finished"

##
## 3) Docker 本番ディレクトリに移動してから、docker compose を実行
##
cd "${DEST_DIR}"

C="docker compose -f docker-compose.prod.yml"

echo "==> Put Laravel into maintenance mode"
# メンテモード開始（失敗しても続行）
$C exec -T app php artisan down || true

echo "==> Pull latest images & restart containers"
# 新イメージ取得＆起動
$C pull app web
$C up -d app web

echo "==> Composer install (inside app container)"
# 必要に応じて依存関係を同期（composer.lock が変わっている場合など）
$C exec -T app composer install --no-dev --prefer-dist -o || true

echo "==> Run migrations"
# DBマイグレーション
$C exec -T app php artisan migrate --force

echo "==> Rebuild caches"
# キャッシュ再生成
$C exec -T app php artisan optimize:clear
$C exec -T app php artisan config:cache
$C exec -T app php artisan route:cache
$C exec -T app php artisan view:cache

echo "==> Restart queues"
# Queue再起動（Horizonなら horizon:terminate に置き換え可）
$C exec -T app php artisan queue:restart || true

echo "==> Bring app up"
# メンテ解除
$C exec -T app php artisan up

echo "==> Health check via localhost (Caddy → Docker → Laravel)"
# ヘルスチェック（Caddy 経由で / にアクセス）
curl -fsS http://127.0.0.1/ > /dev/null || (echo "Health check failed" && exit 1)

echo "✅ Deploy OK"
