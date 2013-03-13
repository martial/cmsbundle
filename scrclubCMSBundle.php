<?php

namespace scrclub\CMSBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;


class scrclubCMSBundle extends Bundle
{


    public function getParent()
    {
        return 'FOSUserBundle';
    }

}
