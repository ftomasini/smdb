<?php

class Logging extends CActiveRecord
{

    const TABLE_NAME = 'logging';
    
    public static function model($className = __CLASS__)
    {
        $model = new $className(null);
        return $model;

    }

    /**
     * Adiciona uma ocorrência a tabela de log
     * 
     * @param String $action Ação realizada
     * @param String $user Nome do usuário
     */
    public static function add($action, $user)
    {
        $userIP = Yii::app()->request->userHostAddress;

        $tbName = Logging::TABLE_NAME;

        $sqlInsert = "INSERT INTO {$tbName} "
                . "VALUES ('{$action}',"
                . "'{$userIP}',"
                . "'{$user}')";

        $dbConnection = Yii::app()->db;

        $command = $dbConnection->createCommand($sqlInsert);
        $command->execute();

        $dbConnection->active = false;

    }

}
