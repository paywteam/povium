<?php
/**
 * This factory is responsible for creating "RegistrationFormMiddleware" instance.
 *
 * @author		H.Chihoon
 * @copyright	2018 DesignAndDevelop
 */

namespace Povium\Middleware\Authentication\Factory;

use Povium\Base\Factory\AbstractChildFactory;
use Povium\Base\Factory\MasterFactory;

class RegistrationFormMiddlewareFactory extends AbstractChildFactory
{
    /**
     * {@inheritdoc}
     */
    protected function prepareArgs()
    {
        $master_factory = new MasterFactory();

        $readable_id_validator = $master_factory->createInstance('\Povium\Security\Validator\UserInfo\ReadableIDValidator');
        $name_validator = $master_factory->createInstance('\Povium\Security\Validator\UserInfo\NameValidator');
        $password_validator = $master_factory->createInstance('\Povium\Security\Validator\UserInfo\PasswordValidator');

        $this->args = array(
            $readable_id_validator,
            $name_validator,
            $password_validator
        );
    }
}
