<?php

class Application_Form_Dangnhap extends Zend_Form
{

    public function init()
    {
       	/*$this->setName("login");
		$this->setMethod('post');

		$this->addElement('text', 'username', array(
		'filters' => array('StringTrim', 'StringToLower'),
		'validators' => array(
		array('StringLength', false, array(0, 50)),
		),
		'required' => true,
		'label' => 'Username:',
		));

		$this->addElement('password', 'password', array(
		'filters' => array('StringTrim'),
		'validators' => array(
		array('StringLength', false, array(0, 50)),
		),
		'required' => true,
		'label' => 'Password:',
		));

		$this->addElement('submit', 'login', array(
		'required' => false,
		'ignore' => true,
		'label' => 'Login',
		));*/
		$this->setMethod('Post');
		$username = $this->createElement('text','username');
        $username->setLabel('Username: *')
                ->setRequired(true)
                ->addFilter('StripTags')
				->addFilter('StringTrim')
				->addValidator('NotEmpty');
                
        $password = $this->createElement('password','password');
        $password->setLabel('Password: *')
                ->setRequired(true)
                ->addFilter('StripTags')
				->addFilter('StringTrim')
				->addValidator('NotEmpty');
                
        $signin = $this->createElement('submit','signin');
        $signin->setLabel('Sign in')
                ->setIgnore(true);
                
        $this->addElements(array(
                        $username,
                        $password,
                        $signin,
        ));
    }

}

