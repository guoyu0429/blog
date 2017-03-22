<?php
namespace common\models\base;
use yii\db\ActiveRecord;
/*
 * 基础控制器
 */

class BaseModel extends ActiveRecord{
    
    /**
     * 获取分页数据
     * @param unknown $query
     * @param number $curPage
     * @param number $pageSize
     * @param number $search
     */
    public function getPages($query,$curPage=1,$pageSize=10,$search=12){
       // if($search)
           // $query= $query->andFilerWhere($search);
      
        $data['count']=$query->count();
        if(!$data['count']){
            return ['count'=>0,'curPage'=>$curPage,'pageSize'=>$pageSize,'start'=>0,'end'=>0,'data=>[]'];
            
        }
        //超多实际页数，不去cuePage为当前页
        $curPage=(ceil($data['count']/$pageSize)<$curPage)?ceil($data['count']/$pageSize):$curPage;
       //当前页
        $data['curPage']=$curPage;
        //每页显示条数
        $data['pageSize']=$pageSize;
        //起始页
        $data['start']=($curPage-1)*$pageSize+1;
        //末页
        $data['end']=(ceil($data['count']/$pageSize)==$curPage)?$data['count']:($curPage-1)*$pageSize+$pageSize;
        //数据
        $data['data']=$query->offset(($curPage-1)*$pageSize)->limit($pageSize)->asArray()->all();
        return $data;
        
    }
}