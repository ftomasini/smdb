<?php

class Config {
    
    public static function getConf($nomeConf)
    {
        $lang = Yii::app()->language;        

        $sql = "SELECT valor FROM config WHERE "
                . "nome = '{$nomeConf}' AND "
                . "(idioma = '{$lang}' OR idioma IS NULL)";
        
        $dbConnection = Yii::app()->db;
        $command = $dbConnection->createCommand($sql);
        
        $result = $command->queryRow();
        
        return $result["valor"];
        
    }
    
    
}

?>
