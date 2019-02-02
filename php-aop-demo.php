<?php
/**
 * Created by PhpStorm.
 * User: cjiali
 * Date: 2019/2/2
 * Time: 12:59
 */
namespace library;

header("Content-type:text/html;charset=utf-8");

include "AOP.php";
include "Aspect.php";

// 数据定义
class Test extends AOP
{
    public function hi()
    {
        echo 'Function library\Test\hi invoked.</br>';
    }

    public function hello(){
        echo 'Function library\Test\hello invoked.</br>';
    }
}

function before()
{
    echo " Before function (".__FUNCTION__.") invoked. </br>";
}

function after()
{
    echo " After function (".__FUNCTION__.") invoked. </br>";
}

$advice = array('before' => array('callback' => 'library\before', 'arguments' => null), 'after' => array('callback' => 'library\after', 'arguments' => null));

// 类测试
echo "</br>=============== 类测试 ==============</br>";
Aspect::install('library\Test', 'hello', $advice);
$test0 = new Test();
$test0->execute('hello');

// 对象测试
echo "</br>============== 对象测试 =============</br>";
$test1 = new Test();
Aspect::install($test1, 'hi', $advice);
$test1->execute('hi');

