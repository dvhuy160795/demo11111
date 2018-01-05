<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
       /* $albums = new Application_Model_DbTable_Albums();
       $this->view->albums = $albums->fetchAll();*/
       $storage = new Zend_Auth_Storage_Session();
       $data = $storage->read();
       if(!$data)
       {
        $this->_redirect('index/dangnhap');
    }
    $this->view->username = $data->username; 
        // phan trang 
    $albums = new Application_Model_DbTable_Albums();
    $paginator  = Zend_Paginator::factory($albums->fetchAll('status ='.'1'));
    $perPage = 3;
    $paginator->setDefaultItemCountPerPage($perPage);
    $allItems = $paginator->getTotalItemCount();
    $countPages = $paginator->count();

    $p = $this->getRequest()->getParam('p');
    if(isset($p)) {
        $paginator->setCurrentPageNumber($p); 
    } else {
        $paginator->setCurrentPageNumber(1);
    }
    $currentPage = $paginator->getCurrentPageNumber();
    $this->view->albums = $paginator;
    $this->view->countItems = $allItems;
    $this->view->countPages = $countPages;
    $this->view->currentPage = $currentPage;
    if($currentPage != $countPages)
    {
        $this->view->previousPage = $currentPage-1;
        $this->view->nextPage = $currentPage+1;
        $this->view->endPage = $countPages; 
    }
    else if($currentPage == 1)
    {
        $this->view->nextPage = $currentPage+1;
        $this->view->previousPage = 1; 
    }
    else if($currentPage == 0)
    {
        $this->view->firstPage = $currentPage;
    }

    else {
        $this->view->nextPage = $currentPage+1;
        $this->view->previousPage = $currentPage-1;
    }

    $this->view->hasNext = $currentPage < $countPages ? true : false;
    $this->view->hasPrev = $currentPage > 1 ? true : false;
    $this->view->hasFirst = $currentPage > 1 ? true : false;
    $this->view->hasEnd = $currentPage < $countPages ? true : false;
}

public function addAction()
{
        // action body
    $form = new Application_Form_Album();
    $form->submit->setLabel('Add');
    $this->view->form = $form;
    if ($this->getRequest()->isPost()) {
        $formData = $this->getRequest()->getPost();
        if ($form->isValid($formData)) {
            $artist = $form->getValue('artist');
            $title = $form->getValue('title');
            $albums = new Application_Model_DbTable_Albums();
            $albums->addAlbum($artist, $title);

            $this->_helper->redirector('index');
        } else {
            $form->populate($formData);
        }

    }
}
    public function editAction()
    {
        // action body
        $form = new Application_Form_Album();
        $form->submit->setLabel('Save');
        $this->view->form = $form;

        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)) {
                $id = (int)$form->getValue('id');
                $artist = $form->getValue('artist');
                $title = $form->getValue('title');
                $albums = new Application_Model_DbTable_Albums();
                $albums->updateAlbum($id, $artist, $title);

                $this->_helper->redirector('index');
            } else {
                $form->populate($formData);
            }
        } else {
            $id = $this->_getParam('id', 0);
            if ($id > 0) {
                $albums = new Application_Model_DbTable_Albums();
                $form->populate($albums->getAlbum($id));
            }

        }
    }
        public function deleteAction()
        {
        // action body
        /*if ($this->getRequest()->isPost()) {
            $del = $this->getRequest()->getPost('del');
            if ($del == 'Yes') {
                $id = $this->getRequest()->getPost('id');
                $albums = new Application_Model_DbTable_Albums();
                $albums->deleteAlbum($id);
        }
            $this->_helper->redirector('index');
        } else {
            $id = $this->_getParam('id', 0);
            $albums = new Application_Model_DbTable_Albums();
            $this->view->album = $albums->getAlbum($id);
        }*/

        $request = $this->getRequest();     
        $albums = new Application_Model_DbTable_Albums();;
        $albums->deleteAlbum($this->getRequest()->id); 

    }

    public function ajaxAction()
    {

        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->getHelper('layout')->disableLayout();

        $request = $this->getRequest()->getParams();
        $artist = $request['artist'];
        $title = $request['title'];
        $status = $request['status'];
        $albums = new Application_Model_DbTable_Albums();
        $albums->addAlbum($artist, $title,$status);
        $message = "add album success!";
        $aryRespon = [
            "message" => $message
        ];
        echo json_encode($aryRespon);
    }

    public function showAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();

        $param = $this->getRequest()->getParams(); 
        $albums = new Application_Model_DbTable_Albums();

        $albums = $albums->getAlbum($param['edit_id']);


        $this->view->albums = $albums;
        $html = $this->view->render('index/show.phtml');

        echo json_encode(['html' => $html]);

    }

    public function savedataAction()
    {

      $request = $this->getRequest()->getPost();
      $id = isset($request['id']) ? $request['id'] :"";
      $title = isset($request['title']) ? $request['title'] :"";
      $artist = isset($request['artist']) ? $request['artist'] :"";

      $albums = new Application_Model_DbTable_Albums();
      $aryAfterUpdate = $albums->updateAlbum($request);
      $aryRespon = $aryAfterUpdate;
      $this->_helper->viewRenderer->setNoRender();
      $this->_helper->getHelper('layout')->disableLayout();
      echo json_encode($aryAfterUpdate);
  }

  public function removeAction()
  {
    $this->_helper->viewRenderer->setNoRender();
    $this->_helper->getHelper('layout')->disableLayout();
    $request = $this->getRequest()->getParams();
    $id = $request['id'];
    $status = $request['status'];   
    $albums = new Application_Model_DbTable_Albums();
    $albums->addStatus($status,$id); 
}

public function dangnhapAction()
{
    $users = new Application_Model_DbTable_Users();
    $loginForm = new Application_Form_Dangnhap();
    $this->view->loginForm = $loginForm;
    if($this->getRequest()->isPost()){
        if($loginForm->isValid($_POST)){
          $data = $loginForm->getValues();
          $auth = Zend_Auth::getInstance();
          $authAdapter = new Zend_Auth_Adapter_DbTable($users->getAdapter(),'users');
          $authAdapter->setIdentityColumn('username')
          ->setCredentialColumn('password');
          $authAdapter->setIdentity($data['username'])
          ->setCredential($data['password']);
          $result = $auth->authenticate($authAdapter);
          if($result->isValid()){
            $storage = new Zend_Auth_Storage_Session();
            $storage->write($authAdapter->getResultRowObject());
            $this->_helper->redirector('index');
        } else {
            $this->view->errorMessage = "Error. Please try again!!!";
        }         
    }

    
}
}

public function dangxuatAction()
{
    $storage = new Zend_Auth_Storage_Session();
    $storage->clear();
    $this->_redirect('index/dangnhap'); 
}

public function dangky1Action()
{
        //
    $users = new Application_Model_DbTable_Users();
    $form = new Application_Form_Dangky();
    $this->view->form=$form;
    if($this->getRequest()->isPost()){
        if($form->isValid($_POST)){
            $data = $form->getValues();
            if($data['password'] != $data['confirmPassword']){
                $this->view->errorMessage = "Password and confirm password don't match.";
                return;
            }
            if($users->checkUnique($data['username'])){
                $this->view->errorMessage = "Name already taken. Please choose  another one.";
                return;
            }
            unset($data['confirmPassword']);
            $users->insert($data);
            $this->_redirect('index/dangnhap');
        }
    }
}


}

