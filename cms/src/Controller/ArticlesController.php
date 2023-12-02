<?php

namespace App\Controller;

class ArticlesController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('Paginator');
        $this->loadComponent('Flash'); // FlashComponent をインクルード
    }

    public function index(): void
    {
        $articles = $this->Paginator->paginate($this->Articles->find());
        $this->set(compact('articles'));
    }

    // ユーザーが /articles/view/first-post をリクエストした際
    // 値 'first-post' が CakePHP のルーティングとディスパッチレイヤーで $slug に渡されます。
    public function view($slug = null)
    {
        $article = $this->Articles->findBySlug($slug)->firstOrFail();
        $this->set(compact('article'));
    }

    public function add()
    {
        // $article はエンティティーのインスタンスです。
        // これは、空のエンティティーを渡すことで、フォームの作成時に
        // デフォルト値を使用することを CakePHP に伝えます。
        $article = $this->Articles->newEmptyEntity();

        // POST 送信時の処理
        if ($this->request->is('post')) {
            // 新しいエンティティーを ArticlesTable オブジェクトにパッチします。
            // これにより、フォームから送信されたデータと
            // デフォルト値が $article エンティティーにマージされます。
            $article = $this->Articles->patchEntity($article, $this->request->getData());
            // user_id の決め打ち
            $article->user_id = 1;

            // エンティティーを保存して成功したらメッセージを表示してリダイレクトします。
            if ($this->Articles->save($article)) {
                $this->Flash->success(__('Your article has been saved.'));
                return $this->redirect(['action ' => 'index']);
            }
            // 保存に失敗した場合にエラーメッセージを表示します。
            $this->Flash->error(__('Unable to add your article.'));
        }
        $this->set('article', $article);
    }

    public function edit($slug)
    {
        $article = $this->Articles->findBySlug($slug)->firstOrFail();
        if ($this->request->is(['post','put'])) {
            $this->Articles->patchEntity($article, $this->request->getData());
            if ($this->Articles->save($article)) {
                $this->Flash->success(__('Your article has been updated.'));
                return $this->redirect(['action' => 'index']);
            }
            // 保存に失敗した場合にエラーメッセージを表示します。
            $this->Flash->error(__('Unable to edit your article.'));
        }
        $this->set('article', $article);
    }
}
