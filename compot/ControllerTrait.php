<?php
/**
 * Created by JetBrains PhpStorm.
 * User: pavel_000
 * Date: 11/28/13
 * Time: 11:01 PM
 * To change this template use File | Settings | File Templates.
 */

namespace compot;

use compot\Responses\ControllerRedirectToActionResponse;
use compot\Responses\ControllerViewResponse;
use compot\Responses\IControllerResponse;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validation;

trait ControllerTrait
{
    protected $bag = [ ];
    /**
     * @var ConstraintViolationList
     */
    private $validationErrors;

    /**
     * @param        $model
     * @param  array $groups
     *
     * @return bool
     */
    public function validate ($model, $groups = [ ])
    {
        $builder = Validation::createValidatorBuilder ();
        $builder->enableAnnotationMapping ();
        $validator            = $builder->getValidator ();
        $this->bag['_errors'] = $validator->validate ($model, $groups);
        if ( count ($this->bag['_errors']) > 0 ) {
            return false;
        }

        return true;
    }

    /**
     * @param        $name
     * @param  null  $action
     * @param  null  $controller
     * @param  array $args
     *
     * @return IControllerResponse
     */
    public function redirectToAction ($name, $action = null, $controller = null, $args = [ ])
    {

        $controllerResponse = new ControllerRedirectToActionResponse();
        $controllerResponse->setName ($name);
        $controllerResponse->setAction ($action);
        $controllerResponse->setController ($controller);
        $controllerResponse->setArgs ($args);

        return $controllerResponse;
    }

    /**
     * @param  null $template
     * @param  null $data
     *
     * @return IControllerResponse
     */
    public function view ($template = null, $data = null)
    {

        $controllerResponse = new ControllerViewResponse();
        $controllerResponse->setTemplatePath ($template);
        $controllerResponse->setData ($data
            ? : $this->bag);

        return $controllerResponse;
    }
}
