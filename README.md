# Parts Manager

### 開発用アカウント
> ユーザー名：`admin`
> パスワード：`secret`

### phpMyAdminログイン
> サーバー名：`mysql`
> ユーザー名：`root`
> パスワード：`root`

### RedisWebUIログイン
> ユーザー名：`laradock`
> パスワード：`laradock`

### Landiskのマウント（php-fpmコンテナ内）
cifs-utilsでLandiskをpublic/landiskディレクトリにマウントする
```
mount.cifs -o guest,iocharset=utf8 //192.168.0.124/disk /var/www/webapp/public/landisk
```

### workspaceコンテナに入る
```bash
make user
```

### JavaScriptをコンパイル（workspaceコンテナ内）
```bash
# 開発環境用
npm run dev
# 本番環境用
npm run prod
```

## ローカル環境構築（最初だけ）

以下は旧 Laradock 環境の手順です。更新後のアプリには PHP 8.5.3 以降の 8.5 系の実行環境が必要です。

### .envファイルの作成
```bash
cp -n laradock/.env.example laradock/.env
cp -n webapp/.env.example webapp/.env
make build
```

### composerからモジュールをダウンロード（workspaceコンテナ内）
```bash
composer install
```

### phpMyAdminからベースを作成
http://localhost:8081/index.php?route=/server/sql を開いて以下を実行
```sql
CREATE DATABASE parts_manager CHARACTER SET utf8mb4 COLLATE utf8mb4_bin;
```

### 以下を実行（workspaceコンテナ内）
```bash
# Application encryption keyの作成
php artisan key:generate
# マイグレーション実行
php artisan migrate:fresh --seed
# appディレクトリにstorageディレクトリをリンク
php artisan storage:link
# Node.jsの必要モジュールをインストール
npm install && npm run dev
```

## Ubuntu 26.04 / PHP-FPM 8.5 への反映

PHP 8.5.3 で依存関係を解決しています。CLI と PHP-FPM の両方で PHP 8.5.3 以降の 8.5 系を使用してください。必要な拡張は Composer で確認し、MySQL 用の `pdo_mysql` と、CSV 処理に使う `mbstring` も有効にします。

1. DB と永続ファイルをバックアップし、別のリリースディレクトリに配置します。既存の `webapp/.env`（特に `APP_KEY`）、`storage` の永続データ、公開ファイルへのリンクや Landisk のマウント設定を引き継ぎます。本番では `APP_ENV=production`、`APP_DEBUG=false` を設定します。
2. `webapp` で以下を実行します。Nginx のドキュメントルートは `webapp/public` とし、`fastcgi_pass` は稼働中の PHP 8.5 FPM プールに合わせます。FPM の実行ユーザーに `storage` と `bootstrap/cache` の書き込み権限を付与します。

   ```bash
   php -v
   composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction
   composer check-platform-reqs --no-dev
   php artisan config:clear
   php artisan route:clear
   php artisan view:clear
   php artisan migrate:status
   ```

3. 既存のフロントエンドビルドで生成した CSS・JS と、対応する `public/mix-manifest.json` を配置します。切り替え前にステージングでログイン、MySQL・Redis、CSV 入出力、日本語の発注書 PDF、利用中の外部連携を確認します。
4. この更新に伴う DB スキーマ変更はありません。未適用マイグレーションが表示された場合は内容を個別に確認します。アプリ内で `.env` を直接参照する機能があるため、設定キャッシュは解除した状態で運用します。
