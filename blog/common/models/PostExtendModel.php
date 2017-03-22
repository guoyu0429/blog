<?php

namespace common\models;

use Yii;
use common\models\base\BaseModel;

/**
 * This is the model class for table "post_extends".
 *
 * @property integer $id
 * @property integer $post_id
 * @property integer $browser
 * @property integer $collect
 * @property integer $praise
 * @property integer $comment
 */
class PostExtendModel extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'post_extends';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['post_id', 'browser', 'collect', 'praise', 'comment'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'post_id' => 'Post ID',
            'browser' => 'Browser',
            'collect' => 'Collect',
            'praise' => 'Praise',
            'comment' => 'Comment',
        ];
    }
    
    /**
     * 更新文章统计
     * @param unknown $cond
     * @param unknown $attribute
     * @param unknown $num
     */
    public function upCounter($cond,$attibute,$num){
        $counter=$this->findOne($cond);
        if(!$counter){
            //原先没有这条数据则创建这条数据，访问量设置为1
            $this->setAttributes($cond);
            $this->$attibute=$num;
            $this->save();
        }else{
            //若这条数据已经存在
            $countData[$attibute]=$num;
            $counter->updateCounters($countData);
            
        }
    }
}
