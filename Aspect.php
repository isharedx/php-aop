<?php
/**
 * Created by PhpStorm.
 * User: cjiali
 * Date: 2019/2/2
 * Time: 14:39
 */
namespace library;

header("Content-type:text/html;charset=utf-8");

class Aspect
{
    private static $pointcuts = array();

    /**
     * 获取切面索引
     * @param $joint_point AOP连接点
     * @return int|string 切面索引
     */
    public static function indexOf($joint_point)
    {
        $index = -1;
        foreach (self::$pointcuts as $key => $value) {
            if ($value['joint_point'] === $joint_point)
                $index = $key;
        }

        return $index;
    }

    /**
     * 插入（添加）AOP切面
     * @param $joint_point AOP连接点
     * @param array $advice AOP通知
     */
    public static function insert($joint_point, $advice = array())
    {
        $index = self::indexOf($joint_point);
        //if ($index === -1)
        //    self::$pointcuts[$joint_point] = array('joint_point' => $joint_point, 'before' => isset($advice['before']) ? $advice['before'] : null, 'after' => isset($advice['after']) ? $advice['after'] : null);
        //else
        //    throw new \Error("The joint point (" + $joint_point + ") has been inserted!");

        if ($index !== -1){
            // do something to notice that: Hook.insertMethod:The hook has inserted.
        }
        self::$pointcuts[$joint_point] = array('joint_point' => $joint_point, 'before' => isset($advice['before']) ? $advice['before'] : null, 'after' => isset($advice['after']) ? $advice['after'] : null);
    }

    /**
     * 移除（删除）AOP切面
     * @param $joint_point AOP连接点
     */
    public static function remove($joint_point)
    {
        $index = self::indexOf($joint_point);
        if ($index === -1)
            throw new \Error("The joint point (" + $joint_point + ") hasn't been inserted!");
        else
            unset(self::$pointcuts[$index]);

    }

    /**
     * 装载切面
     * @param $host 宿主
     * @param $joint_point AOP连接点
     * @param array $advice AOP通知
     */
    public final static function install($host, $joint_point, $advice = array())
    {
        if (isset($advice) && !!$advice)
            $pointcut = array('joint_point' => $joint_point, 'before' => isset($advice['before']) ? $advice['before'] : null, 'after' => isset($advice['after']) ? $advice['after'] : null);
        else {
            $index = self::indexOf($joint_point);
            $pointcut = ($index !== -1) ? self::$pointcuts[$index] : null;
        }

        // 类调用
        if (is_string($host) && class_exists($host)) {
            $host::add($pointcut);
        }
        // 对象调用
        else if (is_object($host) && ($host instanceof AOP)) {
            $host->add($pointcut);
        }
        // 函数调用
        //else if (is_string($host) && function_exists($host)) {
        //   $origin = $host;
        //   override_function($host,"\$pointcut = {$pointcut}","\$before = null; \$after = null; \$params = func_get_args(); if (!function_exists({$origin})) return; \$index = self::indexOf({$origin}); if (\$index !== -1) { \$before = \$pointcut['before']; \$after = \$pointcut['after']; } if (isset(\$before) && !!\$before) { \$func = \'\'; \$args = null; if (isset(\$before['callback']) && function_exists(\$before['callback'])) \$func = \$before['callback']; if (isset(\$before['arguments'])) \$args = \$before['arguments']; if (\$func) \$func(\$args); } \$result = {$origin}(\$params); if (isset(\$after) && !!\$after) { \$func = \'\'; \$args = null; if (isset(\$after['callback']) && function_exists(\$after['callback'])) \$func = \$after['callback']; if (isset(\$after['arguments'])) \$args = \$after['arguments']; if (\$func) \$func(\$args, \$result); } ");
        //   p('Aspect->install:eval($host)', eval($host));
        //}
        // 错误调用
        else
            throw new \Error('Aspect::install: params is invalid!');
    }

    /**
     * 卸载切面
     * @param $host 宿主
     * @param $joint_point AOP连接点
     */
    public final static function uninstall($host, $joint_point)
    {
        // 类调用
        if (is_string($host) && class_exists($host)) {
            $host::del($joint_point);
        }
        // 对象调用
        else if (is_object($host) && ($host instanceof Subject)) {
            $host->del($joint_point);
        }
        // 函数调用
        //else if (is_string($host) && function_exists($host)) {
        //   $origin = $host;
        //   override_function($host,"\$pointcut = {$pointcut}","\$before = null; \$after = null; \$params = func_get_args(); if (!function_exists({$origin})) return; \$index = self::indexOf({$origin}); if (\$index !== -1) { \$before = \$pointcut['before']; \$after = \$pointcut['after']; } if (isset(\$before) && !!\$before) { \$func = \'\'; \$args = null; if (isset(\$before['callback']) && function_exists(\$before['callback'])) \$func = \$before['callback']; if (isset(\$before['arguments'])) \$args = \$before['arguments']; if (\$func) \$func(\$args); } \$result = {$origin}(\$params); if (isset(\$after) && !!\$after) { \$func = \'\'; \$args = null; if (isset(\$after['callback']) && function_exists(\$after['callback'])) \$func = \$after['callback']; if (isset(\$after['arguments'])) \$args = \$after['arguments']; if (\$func) \$func(\$args, \$result); } ");
        //   p('Aspect->install:eval($host)', eval($host));
        //}
        // 错误调用
        else
            throw new \Error('Aspect::uninstall: params is invalid!');

    }
}
