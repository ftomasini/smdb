<?php

/**
 * Model base para os models de formulários da aplicação
 * 
 */
class ModelForm extends CFormModel
{
    private $_isPopulated;
    
    public function __construct($scenario = '')
    {
        $this->_isPopulated = false;
        
        parent::__construct($scenario);

    }
    
    public function setIsPopulated($populated)
    {
        $this->_isPopulated = $populated;
        
    }

    public function getIsPopulated($populated)
    {
        return $this->_isPopulated;
        
    }
    
}
