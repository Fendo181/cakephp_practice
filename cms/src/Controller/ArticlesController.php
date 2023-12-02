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
                return $this->redirect(['action' => 'index']);
            }
            // 保存に失敗した場合にエラーメッセージを表示します。
            $this->Flash->error(__('Unable to add your article.'));
        }
        // タグのリストを取得して表示します。
        $tags = $this->Articles->Tags->find('list')->all();

        // ビューコンテキストに tags をセットします
        $this->set('tags', $tags);
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
        $tags = $this->Articles->Tags->find('list')->all();

        $this->set('tags', $tags);
        $this->set('article', $article);
    }

    public function delete($slug)
    {
        $this->request->allowMethod(['post','delete']);
        $article = $this->Articles->findBySlug($slug)->firstOrFail();
        if($this->Articles->delete($article)) {
            $this->Flash->success(__('The {0} article has been deleted.', $article->title));
            return $this->redirect(['action' => 'index']);
        }
    }

    public function tags()
    {
        // passキーは、CakePHPが自動的に提供する配列です。
        // 例えば、URL /tags/search/3 は、
        // $this->request->getParam('pass.0') に 3 を設定します。
        // よってpassを指定すれば全ての URL パスセグメントを含みます。
        $tags = $this->request->getParam('pass');
        $articles = $this->Articles->find('tagged',[
            'tags' => $tags
        ])
        ->all();

        $this->set([
            'articles' => $articles,
            'tags' => $tags
        ]);
    }
}
