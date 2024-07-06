
<?php

class M_UsuariosTrabajadores extends \DB\SQL\Mapper
{
    public function __construct()
    {
        parent::__construct(\Base::instance()->get('DB'), 'usuarios_trabajadores');
    }    
}