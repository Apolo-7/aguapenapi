
<?php

class M_RegistroDetalle extends \DB\SQL\Mapper
{
    public function __construct()
    {
        parent::__construct(\Base::instance()->get('DB'), 'registro_detalle');
    }    
}