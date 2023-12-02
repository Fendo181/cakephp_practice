<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Utility\Text;
use Cake\Event\EventInterface;
use Cake\Validation\Validator;

class ArticlesTable extends Table
{
    public function initialize(array $config): void
    {
        $this->addBEhavior('Timestamp');
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

}
