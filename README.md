# Parts Manager

## 開発用アカウント

> ユーザー名：`admin`
> パスワード：`secret`

## JavaScriptをビルド（workspaceコンテナ内）

```bash
# 開発環境用
npm run dev
# 本番環境用
npm run prod
```

## クイックスタートガイド

### workspaceコンテナに入る

```bash
cp -n .env.example .env
sudo chmod 744 workspace.sh
./workspace.sh
```

### 以下を実行（workspaceコンテナ内）

```bash
composer install
# Application encryption keyの作成
php artisan key:generate
# appディレクトリにstorageディレクトリをリンク
php artisan storage:link
# マイグレーション実行
php artisan migrate:fresh --seed
# Node.jsの必要モジュールをインストール
npm install && npm run dev
```
