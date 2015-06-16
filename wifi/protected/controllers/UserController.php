<?php

class UserController extends Controller
{

    /**
     * OVERRIDE do método beforeAction
     * 
     * Faz o tratamento de habilitar ou não as ações referentes ao auto-cadastro
     * conforme o parâmetro definido nas configurações
     * 
     * @param CAction $action Ação corrente
     * 
     * @return Boolean Se a ação pode ser executada
     * 
     * @see CController::beforeAction();
     */
    public function beforeAction($action)
    {
        switch ( $action->id )
        {
            case 'registrar':
            case 'confirmar':
            case 'recuperar':
                // Se o auto-cadastro não está habilitado
                if ( !Yii::app()->params['preferencias']['autoCadastroHabilitado'] )
                {
                    $this->redirect(array('user/index'));
                }

                break;
        }

        return true;

    }

    /**
     * Ações personalizadas dos componentes
     * 
     * @return Array Array Com as ações dos componentes
     */
    public function actions()
    {
        return array(
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
            ),
            'page' => array(
                'class' => 'CViewAction',
            )
        );

    }

    /**
     * Ação responsável pelo tratamento de erros
     * 
     */
    public function actionError()
    {
        $error = Yii::app()->errorHandler->error;

        UserController::mudaIdiomaSeNecessario();

        if ( $error )
        {
            $this->render('error', $error);
        }

    }

    /**
     * Ação inicial da página
     * 
     */
    public function actionIndex()
    {
        $modeloPopulado = $this->getModeloPopuladoComDadosDaRequisicao(new LoginForm(), 'LoginForm');

        if ( $modeloPopulado->getIsPopulated() )
        {
            if ( $modeloPopulado->validate() && $modeloPopulado->login() )
            {
                Controladora::getControladora()->libera($modeloPopulado->nomeUsuario);
            }
        }

        UserController::mudaIdiomaSeNecessario();

        $this->render('index', array('model' => $modeloPopulado));

    }

    /**
     * Ação de registro
     * 
     */
    public function actionRegistrar()
    {
        $modeloPopulado = $this->getModeloPopuladoComDadosDaRequisicao(new RegistroForm(), 'RegistroForm');

        // Se o modelo é valido e os dados armazenados estão válidos, guarda na sessão
        if ( $modeloPopulado->getIsPopulated() )
        {
            if ( $modeloPopulado->validate() && $modeloPopulado->salvaUsuario() )
            {
                Yii::app()->session['userEmail'] = $modeloPopulado->email;

                $this->redirect(array('user/confirmar'));
            }
        }

        UserController::mudaIdiomaSeNecessario();

        $this->render('registro', array('model' => $modeloPopulado));

    }

    /**
     * Ação de confirmação de cadastro
     * 
     */
    public function actionConfirmar()
    {
        $modeloPopulado = $this->getModeloPopuladoComDadosDaRequisicao(new ConfirmacaoForm(), 'ConfirmacaoForm');

        // Se o modelo é valido e os dados armazenados estão válidos, guarda na sessão
        if ( $modeloPopulado->getIsPopulated() )
        {
            if ( $modeloPopulado->validate() && $modeloPopulado->confirmaUsuario() )
            {
                Controladora::getControladora()->libera($modeloPopulado->email);

                Yii::app()->session['userEmail'] = NULL;

                $this->redirect(array('user/index'));
            }
        }

        UserController::mudaIdiomaSeNecessario();

        $this->render('confirmacao', array('model' => $modeloPopulado));

    }

    /**
     * Ação recuperação dos dados cadastrais
     * 
     */
    public function actionRecuperar()
    {
        $modeloPopulado = $this->getModeloPopuladoComDadosDaRequisicao(new RecuperacaoForm(), 'RecuperacaoForm');

        // Se o modelo é valido e os dados armazenados estão válidos, guarda na sessão
        if ( $modeloPopulado->getIsPopulated() )
        {
            if ( $modeloPopulado->validate() && $modeloPopulado->recuperaUsuario() )
            {
                $this->redirect(array('user/page', 'view' => 'success', 'info' => 'recover'));
            }
        }

        UserController::mudaIdiomaSeNecessario();

        $this->render('recuperacao', array('model' => $modeloPopulado));

    }

    /**
     * Muda o idioma da aplicação conforme o idioma informado na URL
     * 
     */
    public static function mudaIdiomaSeNecessario()
    {
        $idioma = Yii::app()->request->getParam('idioma');
        
        // Se não for um dos idiomas suportados, não faz o processo
        if( !in_array($idioma, Yii::app()->params['idiomasSuportados']) )
        {
            return false;
        }
        
        // Testa se há algum pedido de mudança de língua
        if ( $idioma )
        {
            $cookie = new CHttpCookie('idioma', $idioma);
            $cookie->expire = time() + 60 * 60 * 24 * 30;
            Yii::app()->request->cookies['idioma'] = $cookie;

            Yii::app()->session['idioma'] = $idioma;
        }
        else // Se não há nenhuma requisição para isso, procura na sessão ou cookies
        {
            $idioma = Yii::app()->session['idioma'] || Yii::app()->request->cookies['idioma'];
        }

        Yii::app()->language = is_null($idioma) ? Yii::app()->sourceLanguage : $idioma;

    }

}
