# CakePHP Practice
CakePHPの基本について学ぶリポジトリです

## はじめに

CakePHP4のアプリケーション用のアプリケーションテンプレートをインストールする

```
composer create-project --prefer-dist cakephp/app:4.x cms   
```

これだけでセットアップは完了です。
以下のコマンドを実行してローカルサーバーを起動して、アプリケーションが動いている事を確認してください。
```
cd cms
bin/cake server
```

`http://localhost:8765/`