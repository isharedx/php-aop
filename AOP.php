<?php
/**
 * Created by PhpStorm.
 * User: cjiali
 * Date: 2019/1/30
 * Time: 23:11
 */

namespace library;

header("Content-type:text/html;charset=utf-8");

class AOP
{
    private static $pointcuts = array();

    private final static function indexOf($joint_point)
    {
        $index = -1;
        foreach (self::$pointcuts as $key => $value) {
            if ($value['joint_point'] === $joint_point)
                $index = $key;
        }

        return $index;
    }

    /**
     * @param $operate 目标方法，也即AOP连接点(joint point)
     * @param null $params 目标方法参数
     */
    public final function execute($operate, $params = null)
    {
        $before = null;
        $after = null;

        if (!method_exists($this, $operate))
            return;

        $index = self::indexOf($operate);
        if ($index !== -1) {
            $before = self::$pointcuts[$index]['before'];
            $after = self::$pointcuts[$index]['after'];
        }

        // 1、前置回调函数
        if (isset($before) && !!$before) {
            $func = '';
            $args = null;

            if (isset($before['callback'])&& function_exists($before['callback']))
                $func = $before['callback'];
            if (isset($before['arguments']))
                $args = $before['arguments'];

            if (!!$func)
                $func($args);
        }

        // 2、目标方法
        $result = $this->$operate($params);


        // 3、后置回调函数
        if (isset($after) && !!$after) {
            $func = '';
            $args = null;

            if (isset($after['callback']) && function_exists($after['callback']))
                $func = $after['callback'];
            if (isset($after['arguments']))
                $args = $after['arguments'];

            if (!!$func)
                $func($args);
        }
    }

    /**
     * 设置对象属性
     * @param $property 属性
     * @param null $value 属性值
     */
    public function set($property, $value = null)
    {
        if (isset($this->$property))
            $this->$property = $value;
    }

    /**
     * 添加AOP切面
     * @param $pointcut AOP切面
     */
    public final static function add($pointcut)
    {
        $joint_point = isset($pointcut['joint_point']) ? $pointcut['joint_point']:'';
        $index = self::indexOf($joint_point);
        //if ($index === -1)
        //    self::$pointcuts[$joint_point] = $pointcut;
        //else
        //    throw new \Error("The joint point (" + $joint_point + ") has been inserted!");
        if ($index !== -1){
            // do something to notice that: Hook.insertMethod:The hook has inserted.
        }
        self::$pointcuts[$joint_point] = $pointcut;
    }

    /**
     * 删除AOP切面
     * @param $joint_point AOP连接点
     */
    public final static function del($joint_point)
    {
        $joint_point = !!$joint_point ? $joint_point:'';
        $index = self::indexOf($joint_point);
        if ($index !== -1)
            unset(self::$pointcuts[$index]);
        else
            throw new \Error('The joint point (" + $joint_point + ") hasn\'t been inserted!');
    }
}

