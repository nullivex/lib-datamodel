openlss/lib-datamodel
=============

Implements a callable object from an array of data that can be extended to implement inline formatting.

Real Name
===
A real name users all lower case with underscores in place of spaces
Example
```php
$arr['my_real_name_here'] = 'foo';
```

All data array members must use this naming.

Camel Name
===
A camel name is generally used when calling functions
Example
```php
echo $this->getMyRealNameHere();
```

Both are used and generated in this class, so make sure they are properly provided and formatted.

There are helper functions that can be used below to convert names.

Usage
===

```php
use \LSS\DataModel;

class MyDataModel extends DataModel {

	public function getFoo(){
		return ucwords($this->data['foo']);
	}

}

$row = array('foo'=>'test','bar'=>'foo');

$obj = MyDataModel::_setup($row);
var_dump($obj->getFoo()); //outputs 'Test'
var_dump($obj->getBar()); //outputs 'foo'

$obj->setFoo('test2');
var_dump($obj->getFoo()); //outputs 'Test2'
```

Methods
===

### $this DataModel::_setup($arr)
Sets the data array to use and instantiates the object
NOTE: should be single dimensional associative array, 
	multidimensional arrays will be treated as a
	single dimensional array with array values

### $this DataModel::_setData($arr)
Used to set data same as above but on an existing object

### (array) DataModel::_getColumns($cols=array(),$flags=DataModel::KEYS_ASSOC)
Used to retrieve a specific set of columns
$cols should be an array of column names (real_name)
Accepts the same flags as _getAll()

### (array) DataModel::_getAll($flags=DataModel::KEYS_ASSOC)
Returns an array similar to that used in _setData except all the values are passed through getters.
Flags can be one of the following
 * DataModel::KEYS_ASSOC	return will be an associative array
 * DataModel::KEYS_NUMERIC	return will be a numeric array
Example
```php
$row = $obj->getAll();
```

### (string) DataModel::_camelName($name,$prefix=null)
When passed a real_name it returns the camel name
A prefix can be passed to get a usable function name
Example
```php
var_dump(DataModel::_camelName('my_name','get')); //outputs 'getMyName'
var_dump(DataModel::_camelName('my_name'); //outputs 'myName';
```

### (string) DataModel::_realName($name,$prefix=null)
Used to obtain a real_name from a function name.
Example
```php
var_dump(DataModel::_realName('getMyName','get')); //outputs 'my_name'
var_dump(DataModel::_realName('myName')); //outputs 'my_name'
```
