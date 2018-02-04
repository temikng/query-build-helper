# query-build-helper

SQL query build helper by request data

## SimpleSort

```php
<?php
// import SimpleSort class
use QueryBuildHelper\Sort\SimpleSort;

// initialize data and default values
$simpleSort = new SimpleSort([
	'id'=>'a.id',
	'fio'=>['a.lastname','a.firstname','a.patronymic'],
	'role'=>'b.title',
	'created'=>'a.created'
], ['-created']);

// example params
$_REQUEST = ['sort'=>['role','-fio']];

// execute with params to fill sort array
$simpleSort->exec($_REQUEST);

// get result string of sql query
$simpleSort->getQuery(); // return "b.title ASC, a.lastname DESC, a.firstname DESC, a.patronymic DESC"
```