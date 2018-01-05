<?php

class Application_Form_Dangky extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
        $firstname = $this->createElement('text','firstname');
        $firstname->setLabel('First Name:')
                    ->setRequired(false);
                    
        $lastname = $this->createElement('text','lastname');
        $lastname->setLabel('Last Name:')
                    ->setRequired(false);
                    
        $email = $this->createElement('text','email');
        $email->setLabel('Email:')
                ->setRequired(false);
                
        $username = $this->createElement('text','username');
        $username->setLabel('Username: *')
                ->setRequired(true)
                ->addValidator('Alnum');
                
        $password = $this->createElement('password','password');
        $password->setLabel('Password: *')
                ->setRequired(true)
                ->addValidator(new Zend_Validate_Between(array('min' => 5, 'max' => 10)))
                ->addValidator('Alnum');
                
        $confirmPassword = $this->createElement('password','confirmPassword');
        $confirmPassword->setLabel('Confirm Password: *')
                ->setRequired(true);
                
        $register = $this->createElement('submit','register');
        $register->setLabel('Sign up')
                ->setIgnore(true);
                
        $this->addElements([
                        $firstname,
                        $lastname,
                        $email,
                        $username,
                        $password,
                        $confirmPassword,
                        $register
        ]);
    }

}

