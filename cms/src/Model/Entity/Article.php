<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\Collection\Collection;

class Article extends Entity
{
    protected $_accessible = [
        '*' => true,
        'id' => false,
        'slug' => false,
        // その他のフィールドも追加可能
        'tag_string' => true,
    ];

    protected function _getTagString()
    {
        if(isset($this->_fields['tag_string'])) {
            return $this->_fields['tag_string'];
        }
        if(empty($this->tags)) {
            return '';
        }

        $tags = new Collection($this->tags);
        // タグのコレクションを、タグ名のカンマ区切りのリストに変換します。
        $str = $tags->reduce(function($string, $tag) {
            return $string . $tag->title . ', ';
        }, '');

        return trim($str, ', ');
    }
}
