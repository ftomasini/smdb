<?php

/**
 * Classe modelo para a tabela do banco de dados "configuracao"
 *
 * Colunas disponíveis da tabela 'log_sistema':
 * @property Integer $id_configuracao Identificador da configuração
 * @property String $nome Nome da configuração
 * @property String $valor Valor da configuração
 * @property String $idioma Idioma da configuração
 * @property String $descricao Descrição da configuração
 */
class Configuracao extends CActiveRecord
{

    /**
     * Método que contém o nome da tabela que essa classe lida
     * 
     * @return String Nome da tabela
     */
    public function tableName()
    {
        return 'configuracao';

    }

    /**
     * Regras de validação do modelo
     * 
     * @return Array Array com a lista de regras
     */
    public function rules()
    {
        return array(
            array('nome, valor', 'required'),
            array('idioma, descricao', 'safe'),
            // Aqui são definidos os atributos que devem ser considerados no método search
            array('id_configuracao, nome, valor, idioma, descricao', 'safe', 'on' => 'search'),
        );

    }

    /**
     * Regras de relacionamento com outras tabelas
     * 
     * @return Array Array com as regras relacionais
     */
    public function relations()
    {
        return array();

    }

    /**
     * Define o label dos atributos
     * 
     * @return Array Array com os apelidos (nomeDaPropriedade => label)
     */
    public function attributeLabels()
    {
        return array(
            'id_configuracao' => 'Id Configuracao',
            'nome' => 'Nome',
            'valor' => 'Valor',
            'idioma' => 'Idioma',
            'descricao' => 'Descricao',
        );

    }

    /**
     * Recupera uma lista de instâncias deste modelo na base de dados conforme os
     * filtros informados previamente
     *
     * @return CActiveDataProvider Dados da pesquisa
     */
    public function search()
    {
        $criteria = new CDbCriteria;

        $criteria->compare('id_configuracao', $this->id_configuracao);
        $criteria->compare('nome', $this->nome, true);
        $criteria->compare('valor', $this->valor, true);
        $criteria->compare('idioma', $this->idioma, true);
        $criteria->compare('descricao', $this->descricao, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));

    }

    /**
     * Procura pelo valor de uma dada configuração
     * 
     * @param String $nome Nome da configuração
     * 
     * @return Mixed Valor da configuração, NULL se esta não for encontrada
     */
    public static function getValorDaConfiguracao($nome)
    {
        $configuracao = new Configuracao();
        $configuracao->nome = $nome;
        $configuracao->idioma = Yii::app()->language; // Idioma sendo utilizado
                
        // Faz a pesquisa
        $resultadoPesquisa = $configuracao->search()->getData();
        
        return $resultadoPesquisa[0]->valor;

    }

    /**
     * Retorna o modelo estático da classe especificada
     * 
     * Esse método é obrigatório para todas as classes descententes da classe CActiveRecord!
     * 
     * @param String $className Nome da classe
     * 
     * @return Classe estática do modelo
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);

    }

}
