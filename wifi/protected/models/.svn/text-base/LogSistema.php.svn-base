<?php

/**
 * Classe modelo para a tabela do banco de dados "log_sistema"
 *
 * Colunas disponíveis da tabela 'log_sistema':
 * @property String $acao Ação executada no sistema
 * @property String $ip Endereço IP do cliente
 * @property String $momento Momento do log
 * @property String $informacao_adicional Informações adicionais
 * @property Integer $id_usuario Referência do usuário
 *
 * Relações com esse modelo:
 * @property Usuario $idUsuario
 */
class LogSistema extends CActiveRecord
{

    const ACAO_LOGIN = "Login";
    const ACAO_LOGIN_INCORRETO = "Login incorreto";
    const ACAO_REENVIO_SENHA = "Reenvio de senha";
    const ACAO_CADASTRO_REALIZADO = "Cadastro realizado";
    const ACAO_CADASTRO_CONFIRMADO = "Cadastro confirmado";
    const ACAO_SMS_ENVIADO = "Envio de SMS";
    const ACAO_EMAIL_ENVIADO = "Envio de email";

    /**
     * Método que contém o nome da tabela que essa classe lida
     * 
     * @return String Nome da tabela
     */
    public function tableName()
    {
        return 'log_sistema';

    }

    /**
     * Regras de validação do modelo
     * 
     * @return Array Array com a lista de regras
     */
    public function rules()
    {
        return array(
            array('acao, ip', 'required'),
            array('id_usuario', 'numerical', 'integerOnly' => true),
            array('momento', 'safe'),
            // Aqui são definidos os atributos que devem ser considerados no método search
            array('acao, ip, momento, id_usuario', 'safe', 'on' => 'search'),
        );

    }

    /**
     * Regras de relacionamento com outras tabelas
     * 
     * @return Array Array com as regras relacionais
     */
    public function relations()
    {
        return array(
            'idUsuario' => array(self::BELONGS_TO, 'Usuario', 'id_usuario'),
        );

    }

    /**
     * Define o label dos atributos
     * 
     * @return Array Array com os apelidos (nomeDaPropriedade => label)
     */
    public function attributeLabels()
    {
        return array(
            'acao' => 'Ação',
            'ip' => 'IP',
            'momento' => 'Momento',
            'informacao_adicional' => 'Informação adicional',
            'id_usuario' => 'Id Usuário',
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

        $criteria->compare('acao', $this->acao, true);
        $criteria->compare('ip', $this->ip, true);
        $criteria->compare('momento', $this->momento, true);
        $criteria->compare('informacao_adicional', $this->informacao_adicional, true);
        $criteria->compare('id_usuario', $this->id_usuario);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));

    }

    /**
     * Registra uma ação vinculada a um usuário com determinado login. Vincula o
     * usuário ao registro de log.
     * 
     * @param String $acao Ação do usuário no sistema
     * @param String $loginUsuario Login do usuário
     */
    public static function registraOperacaoRealizadaPeloUsuarioCadastrado($acao, $loginUsuario)
    {
        try
        {
            $IPAcessoUsuario = Yii::app()->request->userHostAddress;

            $logSistema = new LogSistema();
            $logSistema->acao = $acao;
            $logSistema->ip = $IPAcessoUsuario;

            $usuario = Usuario::procuraInformacoesUsuarioPorLogin($loginUsuario);
            $logSistema->id_usuario = $usuario->id_usuario;

            $logSistema->save();
        }
        catch ( EUsuarioInvalido $e )
        {
            Yii::trace("O usuário realizando a ação '{$acao}' não está cadastrado. Arquivo: " . $e->getFile());
        }

    }

    /**
     * Registra uma operação genérica que não vincula o log a um usuário do sistema
     * Ideal para manter o registro das operações que são feitas com usuários que
     * não estão cadastrados na base utilizada pela aplicação (usuários de outra
     * base, por exemplo)
     * 
     * @param String $acao Ação no sistema
     * @param String $informacaoAdicional Informação adicional referente ao log.
     * Ex.: 'Autenticação realizada com sucesso utilizando base de dados do cliente'
     */
    public static function registraOperacaoRealizadaGenerica($acao, $informacaoAdicional)
    {
        $IPAcessoUsuario = Yii::app()->request->userHostAddress;

        $logSistema = new LogSistema();
        $logSistema->acao = $acao;
        $logSistema->ip = $IPAcessoUsuario;
        $logSistema->informacao_adicional = $informacaoAdicional;

        $logSistema->insert();

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
