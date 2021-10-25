<?php
namespace App\Util;

class SqlLog
{
    // SQL语句
    private static $sql = '';

    // UPDATE 正则条件
    private static $updateExpression = '/UPDATE[\\s`]+?(\\w+)[\\s`]+?/is';

    // INSERT 正则条件
    private static $insertExpression = '/INSERT\\s+?INTO[\\s`]+?(\\w+)[\\s`]+?/is';

    // DELETE 正则条件
    private static $deleteExpression = '/DELETE\\s+?FROM[\\s`]+?(\\w+)[\\s`]+?/is';

    // SELECT 正则条件
    private static $selectExpression = '/((SELECT.+?FROM)|(LEFT\\s+JOIN|JOIN|LEFT))[\\s`]+?(\\w+)[\\s`]+?/is';

    /**
     * @return array 返回查询操作的所有表名
     */
    public static function selectTableNames()
    {
        return self::getTableNames(self::$selectExpression);
    }

    /**
     * @return array 返回更新操作的所有表名
     */
    public static function updateTableNames()
    {
        return self::getTableNames(self::$updateExpression);
    }

    /**
     * @return array 返回插入操作的所有表名
     */
    public static function insertTableNames()
    {
        return self::getTableNames(self::$insertExpression);
    }

    /**
     * @return array 返回删除操作的所有表名
     */
    public static function deleteTableNames()
    {
        return self::getTableNames(self::$deleteExpression);
    }

    /**
     * 根据正则表达式获取所有操作的表名
     * @param $expression
     * @return array
     */
    public static function getTableNames($expression = '')
    {
        $sqlString =  self::$sql;
        if($expression == ''){
            $expression = self::getExpressionType();
        }
        preg_match_all($expression, $sqlString, $matches);
        return array_unique(array_pop($matches));
    }


    public static function getExpressionType()
    {
           $sqlType = explode(' ',self::$sql)[0];

           switch($sqlType){
               case 'insert':
                   $expression = self::$insertExpression;
                   break;
               case 'update':
                   $expression = self::$updateExpression;
                   break;
               case 'select':
                   $expression = self::$selectExpression;
                   break;
               case 'delete':
                   $expression = self::$deleteExpression;
                   break;
           }

           return $expression;


    }


    /**
     * 根据sql获取表名、操作
     * @param $sql string SQL语句
     */
    public static function setSql($sql)
    {
        self::$sql = $sql;
    }

    /**
     * 获取SQL语句
     * @return array SQL语句集合
     */
    public static function getSql()
    {
        return self::$sql;
    }
}