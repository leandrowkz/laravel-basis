# Laravel Basis
[![Build Status](https://travis-ci.com/leandrowkz/laravel-basis.svg?branch=master)](https://travis-ci.com/leandrowkz/laravel-basis)
[![Latest Stable Version](https://poser.pugx.org/leandrowkz/laravel-basis/v/stable)](https://packagist.org/packages/leandrowkz/laravel-basis)
[![License](https://poser.pugx.org/leandrowkz/laravel-basis/license)](https://packagist.org/packages/leandrowkz/laravel-basis)

Laravel Basis is a package that provides a base CRUD layer for your application.

![Laravel Basis](laravel-basis.png?raw=true "Laravel Basis")

* `Leandrowkz\Basis\Controllers\BaseController`
* `Leandrowkz\Basis\Services\BaseService`
* `Leandrowkz\Basis\Traits\AccessibleProps`
* `Leandrowkz\Basis\Traits\FiltersCollections`
* `Leandrowkz\Basis\Traits\MutatesProps`

These classes provides an easy way to CRUD operations inside Laravel apps. All you have to do is to extend and configure your classes from those available here.

#### `Leandrowkz\Basis\Controllers\BaseController`
Every class extended from BaseController must set the `$service` and `$request` (for validation) class names strings (Ex: FooService::class).
```php
protected $service;
protected $request;
public function all();
public function find(string $id);
public function create(array $data);
public function update(string $id, array $data);
public function delete(string $id);
public function exists($id);
public function validate();
public function service(BaseServiceInterface $service = null);
```

#### `Leandrowkz\Basis\Service\BaseService`
Every class exteded from BaseService must set `$model` class name string (Ex: FooModel::class). The create/update operations are done by using de `$fillable` array presented on `$model` class.
```php
protected $model;
public function all();
public function find(string $id);
public function query($where);
public function create(array $data);
public function update(string $id, array $data);
public function delete(string $id);
public function model(string $model = null);
```

#### `Leandrowkz\Basis\Traits\FiltersCollections`
This trait adds to the target class fluent getters/setters to access any property through a method with same name. As a caveat it breaks any property visibility.
```php
protected $foo = 1;
protected $bar = 2;
$this->foo(); // returns 1;
$this->bar(); // returns 2;
$this->foo(3); // sets $this->foo as 3;
$this->bar(3); // sets $this->bar as 3;
```

#### `Leandrowkz\Basis\Traits\FiltersCollections`
This trait just adds to the target class an additional `$filters` attribute and a `filter` method. This method filters a Collection based on current `$this->filters` value. Ex:
```php
protected $filters = ['status' => 'foo', 'category' => 'bar'];
$this->filters(array $filters); // Getter/setter
$this->filter(Collection $items); // Returns all items with status = foo and category = bar
```

#### `Leandrowkz\Basis\Traits\MutatesProps`
This trait adds a method that fetch all class properties and transforms all props with valid class names into objects of same class. Ex:
```php
protected $customService = MyService::class; // string
$this->customService; // string
$this->mutateProps();
$this->customService; // MyService object
```

## License
This code is published under the MIT License. This means you can do almost anything with it, as long as the copyright notice and the accompanying license file is left intact.

## Contributing
Feel free to send pull requests or create issues if you come across problems or have great ideas. Any input is appreciated!

