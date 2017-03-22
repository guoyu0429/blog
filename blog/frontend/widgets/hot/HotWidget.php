<?php
namespace frontend\widgets\hot;
/*
 * 热门浏览组件
 */
use Yii;
use yii\bootstrap\Widget;
use common\models\PostExtendModel;
use common\models\PostsModel;
use yii\db\Query;

class HotWidget extends Widget{
    public $title='';
    /**
     * 显示条数
     * @var unknown
     */
    public $limit = 8;
    public function run() {
        $res=(new Query())
        ->select('a.browser,b.id,b.title')
        ->from(['a'=>PostExtendModel::tableName()])
        ->join('LEFT JOIN',['b'=>PostsModel::tableName()],'a.post_id=b.id')//b是文章表的简称
        ->where('b.is_valid='.PostsModel::IS_VALID)
        ->orderBy(['browser'=>SORT_DESC,'id'=>SORT_DESC])//当浏览数一样的时候按照ID排序
        ->limit($this->limit)
        ->all();
        //数据查询完后进行页面渲染
        $result['title']=$this->title?:'热门浏览';//如果有的话设置，没有的话设置为热门浏览
        $result['body']=$res?:[];
        return $this->render('index',['data'=>$result]);
    }
}