<?php

namespace App\Controller;

class ArticlesController extends AppController
{
    public function index()
    {
        $this->loadComponent('Paginator');
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
}
