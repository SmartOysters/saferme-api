<?php

namespace SmartOysters\SaferMe\Resources\Base;

use SmartOysters\SaferMe\Helpers\ArrayHelpers;
use SmartOysters\SaferMe\Helpers\StringHelpers;
use SmartOysters\SaferMe\Http\Response;
use ReflectionClass;
use SmartOysters\SaferMe\Http\Request;
use SmartOysters\SaferMe\Exception\SaferMeException;

abstract class Resource
{
    use StringHelpers;
    use ArrayHelpers;

    /**
     * The API caller object.
     *
     * @var Request
     */
    protected $request;

    /**
     * List of abstract methods available.
     *
     * @var array
     */
    protected $enabled = ['*'];

    /**
     * List of abstract methods disabled.
     *
     * @var array
     */
    protected $disabled = [];

    /**
     * Endpoint constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $request->setResource($this->getName());

        $this->request = $request;
    }

    /**
     * List all the entities.
     *
     * @param array $options Endpoint accepted options
     * @return Response
     */
    public function list($options = [])
    {
        return $this->request->get('', $options);
    }

    /**
     * Fetch the entity details by ID.
     *
     * @param int $id Entity ID to find.
     * @return Response
     */
    public function fetch($id)
    {
        return $this->request->get(':id', compact('id'));
    }

    /**
     * Create new entity.
     *
     * @param array $values
     * @return Response
     */
    public function create(array $values)
    {
        return $this->request->post('', ['data' => $values]);
    }

    /**
     * Update an entity by ID.
     *
     * @param       $id
     * @param array $values
     * @return Response
     */
    public function update($id, array $values)
    {
        $this->arraySet($values, 'id', $id);

        return $this->request->patch(':id', $values);
    }

    /**
     * Delete an entity by ID.
     *
     * @param $id
     * @return Response
     */
    public function delete($id)
    {
        return $this->request->delete(':id', compact('id'));
    }

    /**
     * Get the endpoint name based on the name class.
     *
     * @return string
     */
    public function getName()
    {
        $reflection = new ReflectionClass($this);

        return $this->snakeCase($reflection->getShortName());
    }

    /**
     * Check if the method is enabled for use.
     *
     * @param $method
     * @return bool
     */
    public function isEnabled($method)
    {
        if ($this->isDisabled($method)) {
            return false;
        }

        if (! in_array($method, get_class_methods(get_class()))) {
            return true;
        }

        return in_array($method, $this->enabled) || $this->enabled == ['*'];
    }

    /**
     * Check if the method is disabled for use.
     *
     * @param $method
     * @return bool
     */
    public function isDisabled($method)
    {
        return in_array($method, $this->disabled);
    }

    /**
     * Get enabled methods.
     *
     * @return array
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * Set enabled methods.
     *
     * @param array $enabled
     */
    public function setEnabled($enabled)
    {
        if (! is_array($enabled)) {
            $enabled = func_get_args();
        }

        $this->enabled = $enabled;
    }

    /**
     * Set disabled methods.
     *
     * @param array $disabled
     */
    public function setDisabled($disabled)
    {
        if (! is_array($disabled)) {
            $disabled = func_get_args();
        }

        $this->disabled = $disabled;
    }

    /**
     * Magic method call.
     *
     * @param       $method
     * @param array $args
     * @return void
     * @throws SaferMeException
     */
    public function __call($method, $args = [])
    {
        if (! $this->isEnabled($method)) {
            throw new SaferMeException("The method {$method}() is not available for the resource {$this->getName()}");
        }
    }
}
