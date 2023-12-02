<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\Table;
use Cake\Utility\Text;
use Cake\Event\EventInterface;
use Cake\Validation\Validator;

class ArticlesTable extends Table
{
    public function initialize(array $config): void
    {
        $this->addBEhavior('Timestamp');
        $this->belongsToMany('Tags');
    }

    public function beforeSave(EventInterface $event, $entity, $options)
    {
        // スラグの作成
        // 新規作成時はスラグを作成します。
        // 既存の記事の編集時はスラグを変更しません。
        // これにより、タイトルを変更した場合にスラグが変更されないようになります。
        if ($entity->isNew() && !$entity->slug) {
            // タイトルからスラグを作成します。
            $sluggedTitle = Text::slug($entity->title);
            // スラグをスキーマで定義された最大長に調整します。
            $entity->slug = substr($sluggedTitle, 0, 191);
        }
    }

    public function validationDefault(Validator $validator): Validator
    {
        // title と body のバリデーションルールを作成します。
        $validator->notEmptyString('title')
                    ->minLength('title', 3)
                    ->maxLength('title', 255)
                    ->notEmptyString('body')
                    ->minLength('body', 10);

        return $validator;
    }

    // $query 引数はクエリービルダーのインスタンスです。
    // $options 配列には、コントローラーのアクションで find('tagged') に渡した
    // "tags" オプションが含まれています。
    public function findTagged(Query $query, array $options)
    {
        $coulumns = [
            'Articles.id', 'Articles.user_id', 'Articles.title',
            'Articles.body', 'Articles.published', 'Articles.created',
            'Articles.slug',
        ];

        // distinctは、関連する記事が重複しないようにするために必要です。
        $query = $query
                    ->select($coulumns)
                    ->distinct($coulumns);

        if (empty($options['tags'])) {
            // タグが指定していない場合は、タグのない記事を検索します。
            $query->leftJoinWith('Tags')
                    ->where(['Tags.title IS' => null]);
        } else {
            // 提供されたタグが1つ以上ある記事を検索します。
            $query->innerJoinWith('Tags')
                            ->where(['Tags.title IN' => $options['tags']]);
        }

        // 結果をタグでグループ化します。
        return $query->group('Articles.id');
    }

}
