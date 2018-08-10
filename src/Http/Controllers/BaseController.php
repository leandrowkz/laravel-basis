<?php

namespace Leandrowkz\Basis\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as LaravelController;
use Illuminate\Support\Facades\Validator;
use Leandrowkz\Basis\Interfaces\Http\Controllers\BaseControllerInterface;
use Leandrowkz\Basis\Interfaces\Services\BaseServiceInterface;
use Leandrowkz\Basis\Traits\AccessibleProps;
use Leandrowkz\Basis\Traits\MutatesProps;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

abstract class BaseController extends LaravelController implements BaseControllerInterface
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, AccessibleProps, MutatesProps;

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
        $this->mutateProps();
    }

    /**
     * Get/Set service.
     *
     * @param \Leandrowkz\Basis\Interfaces\Services\BaseServiceInterface $service
     * @return mixed \Leandrowkz\Basis\Interfaces\Services\BaseServiceInterface|$this
     */
    public function service(BaseServiceInterface $service = null)
    {
        if ($service) {
            $this->service = $service;
            return $this;
        }

        return $this->service;
    }

    /**
     * Validates controller request. If errors are found then redirect to
     * error messages with page status 422.
     *
     * @param string $type
     */
    public function validate(string $type)
    {
        $request = is_array($this->request) && $this->request[$type]
            ? new $this->request[$type]()
            : $this->request;

        if ($request) Validator::make(request()->all(), $request->rules())->validate();
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
        $this->validate('create');
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
        $this->validate('update');
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
