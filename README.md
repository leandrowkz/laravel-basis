# Laravel Basis
[![Build Status](https://travis-ci.com/leandrowkz/laravel-basis.svg?branch=master)](https://travis-ci.com/leandrowkz/laravel-basis)
[![Latest Stable Version](https://poser.pugx.org/leandrowkz/laravel-basis/v/stable)](https://packagist.org/packages/leandrowkz/laravel-basis)
[![License](https://poser.pugx.org/leandrowkz/laravel-basis/license)](https://packagist.org/packages/leandrowkz/laravel-basis)

Laravel Basis is a package that provides a base layer to your application, containing CRUD operations and events.

![Laravel Basis](laravel-basis.png?raw=true "Laravel Basis")

* `Leandrowkz\Basis\Controllers\BaseController`
* `Leandrowkz\Basis\Repositories\BaseRepository`
* `Leandrowkz\Basis\Services\BaseService`
* `Leandrowkz\Basis\Traits\Filterable`

These classes provides an easy way to CRUD operations inside Laravel apps. All you have to do is to extend and configure your classes from those available here.

#### `Leandrowkz\Basis\Controllers\BaseController`
Every class extended from BaseController must set the `$service` and `$request` (for validation) names.
- `protected $service`
- `protected $request`
- `public function all()`
- `public function find(string $id)`
- `public function create(array $data)`
- `public function update(string $id, array $data)`
- `public function delete(string $id)`
- `public function exists($id)`
- `public function validate()`

#### `Leandrowkz\Basis\Service\BaseService`
The service extended class from this one must set `$repo` name and `$events` that will be fired (optional) on CRUD operations.
- `protected $repo`
- `protected $events` // 'created', 'updated', 'deleted'
- `public function all()`
- `public function find(string $id)`
- `public function query($where)`
- `public function create(array $data)`
- `public function update(string $id, array $data)`
- `public function delete(string $id)`

#### `Leandrowkz\Basis\Repositories\BaseRepository`
Classes extended from BaseRepository must has the `$model` name. The create/update operations are done by using de `$fillable` array presented on `$model` name.
- `protected $model`
- `public function all()`
- `public function find(string $id)`
- `public function query($where)`
- `public function create(array $data)`
- `public function update(string $id, array $data)`
- `public function delete(string $id)`

#### `Leandrowkz\Basis\Traits\Filterable`
This trait just adds to the target class an additional `$filters` attribute and a fluent getter/setter `filter()`.
- `protected $filters`
- `public function filter()`

## License
This code is published under the MIT License. This means you can do almost anything with it, as long as the copyright notice and the accompanying license file is left intact.

## Contributing
Feel free to send pull requests or create issues if you come across problems or have great ideas. Any input is appreciated!

