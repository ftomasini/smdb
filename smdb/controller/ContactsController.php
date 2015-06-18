<?php


//require_once 'model/ContactsModel.php';
//require_once 'core/Controller.php';

class ContactsController extends Controller
{

    private $contactsModel = NULL;

    public function __construct()
    {
        $this->contactsModel = new ContactsModel();
    }


    public function save()
    {

        $title = 'Add new contact';

        $name = '';
        $phone = '';
        $email = '';
        $address = '';

        $errors = array();

        if ( isset($_POST['form-submitted']) )
        {

            $name       = isset($_POST['name']) ?   $_POST['name']  :NULL;
            $phone      = isset($_POST['phone'])?   $_POST['phone'] :NULL;
            $email      = isset($_POST['email'])?   $_POST['email'] :NULL;
            $address    = isset($_POST['address'])? $_POST['address']:NULL;

            try
            {
                $this->contactsModel->createNewContact($name, $phone, $email, $address);
                $this->redirect('index.php');
                return;
            }
            catch (ValidationException $e)
            {
                $errors = $e->getErrors();
            }
        }

        include 'view/contact-form.php';
    }

    public function listar()
    {
        $orderby = isset($_GET['orderby'])?$_GET['orderby']:NULL;
        $contacts = $this->contactsModel->getAllContacts($orderby);

        include 'view/contacts.php';
    }


    public function delete()
    {
        $id = isset($_GET['id'])?$_GET['id']:NULL;
        if ( !$id )
        {
            throw new Exception('Internal error.');
        }

        $this->contactsModel->deleteContact($id);

        $this->redirect('index.php');
    }

    public function show()
    {
        $id = isset($_GET['id'])?$_GET['id']:NULL;
        if ( !$id )
        {
            throw new Exception('Internal error.');
        }
        $contact = $this->contactsModel->getContact($id);

        include 'view/contact.php';
    }


}
?>
