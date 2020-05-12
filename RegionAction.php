<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2020-05-12 14:46:36
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2020-05-12 14:47:20
 */
 
namespace diandi\region;
use yii\base\Action;
use Yii;
use yii\base\InvalidParamException;
use yii\helpers\Html;

class RegionAction extends Action
{
    /**
     * @var \yii\db\ActiveRecord Region Model
     */
    public $model=null;

    public function init()
    {
        parent::init();
        if(!$this->model)throw new InvalidParamException('model不能为null');
    }

    public function run()
    {
        $parent_id=Yii::$app->request->get('parent_id');
        $modelClass=$this->model;
        if($parent_id>0){
            return Html::renderSelectOptions('district',$modelClass::getRegion($parent_id));
        }else{
            return [];
        }
    }
}