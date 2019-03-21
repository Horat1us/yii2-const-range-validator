# Yii2 ConstRange Validator
[![Build Status](https://travis-ci.org/Horat1us/yii2-const-range-validator.svg?branch=master)](https://travis-ci.org/Horat1us/yii2-const-range-validator)
[![codecov](https://codecov.io/gh/Horat1us/yii2-const-range-validator/branch/master/graph/badge.svg)](https://codecov.io/gh/Horat1us/yii2-const-range-validator)

This package provides validator that allows use class constants as `\yii\validators\RangeValidator::range`.  
Main purpose is to prevent errors after adding new constants to classes 
(forgot to update validation rule).  

Previous implementation was available in [horat1us/yii2-base](https://github.com/Horat1us/yii2-base) package
as [ConstRangeValidator](https://github.com/Horat1us/yii2-base/blob/1.16.0/src/Validators/ConstRangeValidator.php). 

## Installation
Using [packagist.org](https://packagist.org/packages/horat1us/yii2-const-range-validator):
```bash
composer require horat1us/yii2-const-range-validator:^1.0
```

## Usage

### Validator
```php
<?php

namespace App;

use Horat1us\Yii\ConstRange;
use yii\base;

class Model extends base\Model
{
    public const TYPE_A = 'A';
    public const TYPE_B = 'B';
    
    public $type;
    
    public function rules(): array {
        return [
            [['type',], ConstRange\Validator::class,],    
        ];
    }
}

$model = new Model;

$model->type = 'C';
$model->validate(); // false

$model->type = Model::TYPE_A;
$model->validate(); // true
```

### ArrayObject
You can use Yii2 RangeValidator:
```php
<?php

namespace App;

use Horat1us\Yii\ConstRange;
use yii\base;

class Model extends base\Model
{
    public const TYPE_A = 'A';
    public const TYPE_B = 'B';
    
    public $type;
    
    public function rules(): array {
        return [
            [['type',], 'range', 'range' => (new ConstRange\ArrayObject(Model::class, 'TYPE_'))],    
        ];
    }
}

$model = new Model;

$model->type = 'C';
$model->validate(); // false

$model->type = Model::TYPE_A;
$model->validate(); // true
```

## License
[MIT](./LICENSE)
