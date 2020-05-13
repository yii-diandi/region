<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2020-05-12 14:46:30
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2020-05-12 19:17:18
 */
 
namespace diandi\region;

use yii\base\InvalidParamException;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class Region extends Widget
{
    public $model = null;

    /**
     * @var string 此属性不用处理
     */
    public $attribute;

    /**
     * @var array 省份配置
     */
    public $province = [];

    /**
     * @var array 城市配置
     */
    public $city = [];

    /**
     * @var array 县/区配置
     */
    public $district = [];

    /**
     * @var mixed 数据源
     */
    public $url;

    public function init()
    {
        if (!$this->model) {
            throw new InvalidParamException('model不能为null!');
        }

        if (empty($this->province) || empty($this->city)) {
            throw new InvalidParamException('province和city不能为空！');
        }

        $cityId = Html::getInputId($this->model, $this->city['attribute']);
        if (empty($this->city['options']['prompt'])) {
            $this->city['options']['prompt'] = '选择城市';
        }

        if (empty($this->province['options']['class'])) {
            $this->province['options']['class'] = 'form-control-inline';
        }

        if (empty($this->city['options']['class'])) {
            $this->city['options']['class'] = 'form-control-inline';
        }
        $cityDefault = Html::renderSelectOptions('city', ['' => $this->city['options']['prompt']]);

        $joinChar = strripos($this->url, '?') ? '&' : '?';
        $url = $this->url . $joinChar;

        if (!empty($this->district)) {
            if (empty($this->district['options']['prompt'])) {
                $this->district['options']['prompt'] = '选择县/区';
            }
            if (empty($this->district['options']['class'])) {
                $this->district['options']['class'] = 'form-control-inline';
            }
            $districtId = Html::getInputId($this->model, $this->district['attribute']);
            $districtDefault = Html::renderSelectOptions('district', ['' => $this->district['options']['prompt']]);
            $this->city['options'] = ArrayHelper::merge($this->city['options'], [
                'onchange' => "
                    if($(this).val() != ''){
                        $.get('{$url}parent_id='+$(this).val(), function(data) {
                            $('#{$districtId}').html('{$districtDefault}'+data);
                        })
                    }else{
                        $('#{$districtId}').html('{$districtDefault}');
                    }
                "
            ]);
        }

        $this->province['options'] = ArrayHelper::merge($this->province['options'], [
            'onchange' => "
                if($(this).val()!=''){
                    $.get('{$url}parent_id='+$(this).val(), function(data) {
                        $('#{$cityId}').html('{$cityDefault}'+data);
                    })
                }else{
                    $('#{$cityId}').html('{$cityDefault}');
                }
                $('#{$districtId}').html('{$districtDefault}');
            "
        ]);

    }

    public function run()
    {
        $output[] = Html::activeDropDownList($this->model, $this->province['attribute'], $this->province['items'],
            $this->province['options']);
        $output[] = Html::activeDropDownList($this->model, $this->city['attribute'], $this->city['items'],
            $this->city['options']);
        if (!empty($this->district)) {
            $output[] = Html::activeDropDownList($this->model, $this->district['attribute'], $this->district['items'],
                $this->district['options']);
        }

        return html::tag('div',@implode("\n", $output),[
            'class'=>'col-xs-12 col-sm-12 col-md-12 col-lg-12',
            'style'=>'padding-left:0px;padding-right:0px;margin-bottom: 10px;'
        ]);
        
        // p($output);
        // return @implode("\n", $output);
    }

}
