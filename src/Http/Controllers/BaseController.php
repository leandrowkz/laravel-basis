<?php

namespace Leandrowkz\Basis\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as LaravelController;
use Illuminate\Support\Facades\Validator;
use Leandrowkz\Basis\Interfaces\Http\Controllers\BaseControllerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use ReflectionClass;

abstract class BaseController extends LaravelController implements BaseControllerInterface
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @var \Leandrowkz\Basis\Interfaces\Services\BaseServiceInterface
     */
    protected $service;

    /**
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * @var string
     */
    protected $notFoundMessage = 'not-found';

    /**
     * Controller constructor.
     */
    function __construct()
    {
        $reflect = new ReflectionClass($this);
        $props = $reflect->getProperties();
        foreach ($props as $attribute)
            if (class_exists($attribute))
                $this->$attribute = app($attribute);
    }

    /**
     * Validates controller request. If errors are found then redirect to
     * error messages with page status 422.
     */
    public function validate()
    {
        Validator::make(request()->all(), $this->request->rules())->validate();
    }

    /**
     * Check if given id exists and if not, throws a NotFoundHttpException exception.
     *
     * @param string $id
     */
    public function exists(string $id)
    {
        if (!$this->service->find($id))
            throw new NotFoundHttpException($this->notFoundMessage);
    }

    /**
     * Returns all data.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all()
    {
        return $this->service->all();
    }

    /**
     * Single record.
     *
     * @param string $id
     * @return \Illuminate\Database\Eloquent\Model|NotFoundHttpException
     */
    public function find(string $id)
    {
        $this->exists($id);
        return $this->service->find($id);
    }

    /**
     * Create resource.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create()
    {
        $this->validate();
        return $this->service->create(request()->all());
    }

    /**
     * Update resource.
     *
     * @param string $id
     * @return \Illuminate\Database\Eloquent\Model|NotFoundHttpException
     */
    public function update(string $id)
    {
        $this->exists($id);
        $this->validate();
        return $this->service->update($id, request()->all());
    }

    /**
     * Delete resource.
     *
     * @param string $id
     * @return \Illuminate\Database\Eloquent\Model|NotFoundHttpException
     */
    public function delete(string $id)
    {
        $this->exists($id);
        return $this->service->delete($id);
    }
}
