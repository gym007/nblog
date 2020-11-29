<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

/**
 * 使用方法：
 * Class AbstractRequest
 * @package App\Http\Requests
 */
class BaseCategoryPost extends FormRequest
{
    public $scenes = [];
    public $currentScene;               //当前场景
    public $autoValidate = false;       //是否注入之后自动验证
    public $extendRules;

    public function authorize()
    {
        return true;
    }

    /**
     * 设置场景
     * @param $scene
     * @return $this
     */
    public function scene($scene)
    {
        $this->currentScene = $scene;
        dd('scene');
        return $this;
    }

    /**
     * 使用扩展rule
     * @param string $name
     * @return AbstractRequest
     */
    public function with($name = '')
    {
        if (is_array($name)) {
            $this->extendRules = array_merge($this->extendRules[], array_map(function ($v) {
                return Str::camel($v);
            }, $name));
        } else if (is_string($name)) {
            $this->extendRules[] = Str::camel($name);
        }

        return $this;
    }

    /**
     * 覆盖自动验证方法
     */
    public function validateResolved()
    {
        if ($this->autoValidate) {
            dd(444);
            $this->handleValidate();
        }
    }

    /**
     * 验证方法
     * @param string $scene
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function validate($scene = '')
    {
        var_dump(322222);
        var_dump($scene);
        if ($scene) {
            $this->currentScene = $scene;
            dd('scene2');
        }
        $this->handleValidate();
    }

    /**
     * 根据场景获取规则
     * @return array|mixed
     */
    public function getRules()
    {
        echo '<pre>';

        $rules = $this->container->call([$this, 'rules']);
        var_dump(1);
        $newRules = [];
        if ($this->extendRules) {
            var_dump(2);
            $extendRules = array_reverse($this->extendRules);
            foreach ($extendRules as $extendRule) {
                if (method_exists($this, "{$extendRule}Rules")) {   //合并场景规则
                    $rules = array_merge($rules, $this->container->call(
                        [$this, "{$extendRule}Rules"]
                    ));
                }
            }
        }
        var_dump($this->currentScene);
        var_dump($this->scenes);
        if ($this->currentScene && isset($this->scenes[$this->currentScene])) {
            $sceneFields = is_array($this->scenes[$this->currentScene])
                ? $this->scenes[$this->currentScene] : explode(',', $this->scenes[$this->currentScene]);
            foreach ($sceneFields as $field) {
                if (array_key_exists($field, $rules)) {
                    $newRules[$field] = $rules[$field];
                }
            }
            return $newRules;
        }
        var_dump($rules);
        return $rules;
    }

    /**
     * 覆盖设置 自定义验证器
     * @param $factory
     * @return mixed
     */
    public function validator($factory)
    {
        return $factory->make(
            $this->validationData(), $this->getRules(),
            $this->messages(), $this->attributes()
        );
    }

    /**
     * 最终验证方法
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function handleValidate()
    {
        if (!$this->passesAuthorization()) {
            $this->failedAuthorization();
        }
        $instance = $this->getValidatorInstance();
        if ($instance->fails()) {
            $this->failedValidation($instance);
        }
    }

}
