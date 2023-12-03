<?php

namespace App\Controller;

class ArticlesController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('Flash'); // Include the FlashComponent
    }

    public function index()
    {
        $articles = $this->Articles->find('all');
        $this->set(compact('articles'));
    }

    public function view($id = null)
    {
        $article = $this->Articles->get($id);
        $this->set(compact('article'));
    }

    public function add()
    {
        // 記事を追加するためのインスタンスを作成する
        $article =  $this->Articles->newEmptyEntity();
        if ($this->request->is('post')) {
            $article = $this->Articles->patchEntity($article, $this->request->getData());

            if ($this->Articles->save($article)) {
                $this->Flash->success(__('記事の追加に成功しました'));
                // 記事一覧にリダイレクト
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('記事の追加に失敗しました'));
        }
        $this->set('article', $article);
    }

    public function edit($id = null)
    {
        $article = $this->Articles->get($id);
        if ($this->request->is(['post','put'])) {
           $this->Articles->patchEntity($article, $this->request->getData());
            if ($this->Articles->save($article)) {
               $this->Flash->success(__('記事の更新に成功しました'));
               return $this->redirect(['action' => 'index']);
           }
              $this->Flash->error(__('記事の更新に失敗しました'));
        }
        $this->set('article', $article);
    }
}
