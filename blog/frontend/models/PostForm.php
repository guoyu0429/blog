<?php
namespace frontend\models;
/*
 *创建文章
 */
use yii\base\Model;
use Yii;
use common\models\PostsModel;
use common\models\RelationPostTagModel;
use yii\db\Query;
use yii\web\NotFoundHttpException;


class PostForm extends Model{
    public $id;
    public $title;
    public $content;
    public $label_img;
    public $cat_id;
    public $tags;
    
    public $_lastError="";
    
    /*
     * 定义场景(限制可以更改的字段属性）
     *SCENARIOS_CREATE： 创建
     *SCENARIOS_CREATE：更新
     */
    const SCENARIOS_CREATE ='create';
    const SCENARIOS_UPDATE ='update';
    /**
     * EVENT_AFTER_CREATE：创建之后的事件
     * EVENT_AFTER_UPDATE：更新之后的事件
     * @var unknown
     */
    const EVENT_AFTER_CREATE="eventAfterCreate";
    const EVENT_AFTER_UPDATE="eventAfterUpdate";
    /**
     * 
     * 场景设置
     * @see \yii\base\Model::scenarios()
     */
    public function scenarios(){
        $scenarios=[
            self::SCENARIOS_CREATE=>['title','content','label_img','cat_id','tags'],
            self::SCENARIOS_UPDATE=>['title','content','label_img','cat_id','tags']
        ];
        return array_merge(parent::scenarios(),$scenarios);
    }
    
    public function rules(){
        return[
            [['id','title','content','cat_id'],'required'],
            [['id','cat_id'],'integer'],
            ['title','string','min'=>4,'max'=>50],
        ];
    }
    public function attributeLabels(){
        return[
            'id'=>'编号',
            'title'=>'标题',
            'content'=>'内容',
            'label_img'=>'标签图',
            'tags'=>'标签',
            'cat_id'=>'分类'
        ];
    }
    
    public static function getList($cond,$curPage=1,$pageSize=5,$orderBy=['id'=>SORT_DESC]){
        $model=new PostsModel();
        //查询语句
        $select=['id','title','summary','label_img','cat_id','user_id','user_name','is_valid','created_at','updated_at'];
        $query=$model->find()
            ->select($select)
            ->where($cond)
            ->with('relate.tag','extend')
            ->orderBy($orderBy);
        //获取分页数据
        $res=$model->getPages($query,$curPage,$pageSize);
        //格式化数据
        $res['data']=self::_formatList($res['data']);
        return $res;
    }
    
    
    /**
     * 数据格式化
     * @param unknown $data
     * @return unknown
     */
    public static function _formatList($data){
        foreach($data as &$list){
            $list['tags']=[];
            if(isset($list['relate']) && !empty($list['relate'])){
                foreach ($list['relate'] as $lt){
                    $list['tags'][]=$lt['tag']['tag_name'];
                }
            }
            unset($list['relate']);
        }
        return $data;
    }
    
    /**
     * 文章创建
     * @throws \Exception
     */
    public function create(){
        //事务
        $transaction=Yii::$app->db->beginTransaction();
        try{
            $model=new PostsModel();
            $model->setAttributes($this->attributes);
            $model->summary=$this->_getSummary();
            $model->user_id=Yii::$app->user->identity->id;
            $model->user_name=Yii::$app->user->identity->username;
            $model->is_valid=PostsModel::IS_VALID;
            $model->created_at=time();
            $model->updated_at=time();
            if(!$model->save()){
                throw new \Exception('文章保存失败！');
            }
            
            $this->id=$model->id;
            //调用事件
            $data=array_merge($this->getAttributes(),$model->getAttributes());
            $this->_eventAfterCreate($data);
            
            
            $transaction->commit();
            return true;
        }catch(\Exception $e){
            $transaction->rollBack();
            $this->_lastError=$e->getMessage();
            return false;
        }
    }
    
    
    /**
     * 通过ID获取文章
     */
    public function getViewById($id){
        $res=PostsModel::find()->with('relate.tag','extend')->where(['id'=>$id])->asArray()->one();
        if(!$res){
            throw new NotFoundHttpException('文章不存在！');
        }
        //处理标签格式
        $res['tags']=[];
        if(isset($res['relate'])&&!empty($res['relate'])){
            foreach ($res['relate']as $list){
                $res['tags'][]=$list['tag']['tag_name'];
            }
        }
        unset($res['relate']);
        return $res;
    }
    
    /**
     * 截取文章摘要
     * @param number $s
     * @param number $e
     * @param string $char
     * @return NULL|string
     */
    private function _getSummary($s=0,$e=90,$char="utf-8"){
        if(empty($this->content)){
            return null;
        }
       return (mb_substr(str_replace('&nbsp;', '',strip_tags($this->content)),$s,$e,$char));
        
    }
    /**
     * 文章创建之后的事件定义
     * 创建完成后调用的事件方法(比如说添加积分等）
     */
    public function _eventAfterCreate($data){
        //添加事件
        $this->on(self::EVENT_AFTER_CREATE,[$this,'_eventAddTag'],$data);
        //触发事件
        $this->trigger(self::EVENT_AFTER_CREATE);
    
    }
    /**
     * 添加标签的方法实现
     */
    public function _eventAddTag($event){
        //保存标签
        $tag=new TagForm();
        $tag->tags=$event->data['tags'];
        $tagids=$tag->saveTags();
        
        //删除原先的关联关系
        RelationPostTagModel::deleteAll(['post_id' => $event->data['id']]);
        
        //批量保存文章标签的关联关系
        if(!empty($tagids)){
            foreach($tagids as $k=>$id){
                $row[$k]['post_id']=$this->id;
                $row[$k]['tag_id']=$id;
                
            }
            //批量插入
            $res=(new Query())->createCommand()
              ->batchInsert(RelationPostTagModel::tableName(), ['post_id','tag_id'],$row )
              ->execute();
            //返回结果
            if(!$res)
                throw new \Exception("关联关系保存失败");
        }
        
    }
}