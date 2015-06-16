<?php

/**
 * Classe modelo para a tabela do banco de dados "usuario"
 *
 * Colunas disponíveis da tabela 'usuario':
 * @property Integer $id_usuario Identificador do usuário
 * @property String $nome Nome do usuário
 * @property String $senha Senha do usuário (gerada pelo sistema)
 * @property String $email Email do usuário
 * @property String $documento Documento do usuário
 * @property String $momento Momento do cadastro do usuário
 * @property String $numero_celular Número do celular do usuário
 * @property Boolean $ativado Flag se o usuário está ativado ou não
 * @property Boolean $bloqueado Flag se o usuário está bloqueado ou não
 *
 * Relações com esse modelo:
 * @property LogSistema[] $logSistemas
 */
class Usuario extends CActiveRecord
{

    /**
     * Método que contém o nome da tabela que essa classe lida
     * 
     * @return String Nome da tabela
     */
    public function tableName()
    {
        return 'usuario';

    }

    /**
     * Regras de validação do modelo
     * 
     * @return Array Array com a lista de regras
     */
    public function rules()
    {
        return array(
            array('nome, senha, email, documento, numero_celular', 'required'),
            array('momento, ativado, bloqueado', 'safe'),
            // Aqui são definidos os atributos que devem ser considerados no método search
            array('id_usuario, nome, senha, email, documento, momento, numero_celular, ativado, bloqueado', 'safe', 'on' => 'search'),
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
            'logSistemas' => array(self::HAS_MANY, 'LogSistema', 'id_usuario'),
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
            'id_usuario' => 'Id Usuário',
            'nome' => 'Nome',
            'senha' => 'Senha',
            'email' => 'Email',
            'documento' => 'Documento',
            'momento' => 'Momento',
            'numero_celular' => 'Número Celular',
            'ativado' => 'Ativo',
            'bloqueado' => 'Bloqueado',
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

        $criteria->compare('id_usuario', $this->id_usuario);
        $criteria->compare('nome', $this->nome, true);
        $criteria->compare('senha', $this->senha, true);
        $criteria->compare('email', $this->email, true);
        $criteria->compare('documento', $this->documento, true);
        $criteria->compare('momento', $this->momento, true);
        $criteria->compare('numero_celular', $this->numero_celular, true);
        $criteria->compare('ativado', $this->ativado);
        $criteria->compare('bloqueado', $this->bloqueado);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));

    }
    
    /**
     * Busca as informações específicas do usuário com email e senha informados
     * 
     * @param String $email Nome do usuário
     * @param String $senha Senha do usuário
     * 
     * @return Usuario Ocorrência da tabela 'usuario' conforme os dados informados
     * 
     * @throws EUsuarioInvalido Caso não haja usuário com as credenciais informadas
     */
    public static function procuraUsuarioComDadosDeAcesso($email, $senha)
    {
        $usuario = new Usuario();
        $usuario->email = $email;
        $usuario->senha = $senha;
        
        $resultadoPesquisa = $usuario->search()->getData();
        
        if( count($resultadoPesquisa) > 0 )
        {
            return $resultadoPesquisa[0];
        }
        
        throw new EUsuarioInvalido(Yii::t('app', 'Não foi encontrado nenhum usuário com as credênciais informadas'));

    }
    
    /**
     * Resgata as informações do usuário com o dado login
     * 
     * @param String $email Login utilizado pelo usuário
     * 
     * @return Usuario Resultado da pesquisa
     */
    public static function procuraInformacoesUsuarioPorLogin($email)
    {
        $usuario = new Usuario();
        $usuario->email = $email;
        
        $resultadoPesquisa = $usuario->search()->getData();
        
        if( count($resultadoPesquisa) > 0 )
        {
            return $resultadoPesquisa[0];
        }
        
        throw new EUsuarioInvalido("Não foi encontrado nenhum usuário com dado nome de usuário");

    }
    
    /**
     * Obtém a data de vencimento do usuário atual
     * 
     * @return DateTime Data de vencimento do cadastro
     */
    public function obtemDataVencimentoCadastro()
    {
        $diasValidosCadastro = Configuracao::getValorDaConfiguracao('diasValidadeCadastro');
        
        $data = strtotime("+{$diasValidosCadastro} days", strtotime($this->momento));
        
        return date('Y-m-d H:i:s', $data);
        
    }

    /**
     * Verifica se o momento do cadastro não venceu conforme a configuração
     * de limite de dias válidos para um cadastro
     * 
     * @return Boolean Se a data de hoje é menor que a data em que o cadastro expira
     */
    public function cadastroEstaNoPeriodoValido()
    {
        $hoje = new DateTime(date('Y-m-d H:i:s'));
        
        $expira = new DateTime($this->obtemDataVencimentoCadastro());
        
        return $hoje < $expira;

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
