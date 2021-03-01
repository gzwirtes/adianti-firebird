<?php
/**
 * Cliente Active Record
 * @author  Gustavo Zwirtes
 */
class Cliente extends TRecord
{
    const TABLENAME = 'CLIENTE';
    const PRIMARYKEY= 'ID';
    const IDPOLICY =  'max'; // {max, serial}
    
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('RAZAO_SOCIAL');
        parent::addAttribute('CPF');
    }


}
