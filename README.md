# query-build-helper

SQL query build helper by request data

## SimpleSort

```php
<?php
// import Sort classes
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

## SimpleFilter

```php
<?php
// import Filter classes
use QueryBuildHelper\Filter\SimpleFilter;
use QueryBuildHelper\Filter\ArgumentFunctions as QBHFAF;
use QueryBuildHelper\Filter\QueryFunctions as QBHFQF;
use QueryBuildHelper\Filter\DefaultValueFunctions as QBHFDVF;

// initialize data and default values
$simpleFilter = new SimpleFilter([
	'id' =>"CAST(id AS text) LIKE CONCAT(?,'%')",
	'name'=>['q'=>"UPPER(b.name) LIKE CONCAT('%',UPPER(?),'%')"],
	'del' =>['q'=>'b.is_del = ?','f'=>QBHFAF::BOOL()],
	'date'=>[
		't'   =>'switch',
		'case'=>[
			'created'=>['p'=>'created','q'=>'CAST(created AS date) >= CAST(? AS date)','f'=>QBHFAF::DATE()],
			'between'=>['p'=>['df','dt'],'q'=>QBHFQF::BETWEEN_DATE('created'),'f'=>QBHFAF::DATE()]
		]
	]
], [
	'f_id'  =>QBHFDVF::EQ_REGEX('/\d+/'),
	'f_name'=>QBHFDVF::EQ_REGEX('/[A-Za-zА-Яа-яЁё]+/'),
	'f_del' =>QBHFDVF::EQ(['1','0'],'0'),
	'f_date'=>QBHFDVF::EQ(['created','between'],'between'),
	'df'    =>QBHFDVF::DATE('2017-01-01'),
	'dt'    =>QBHFDVF::DATE('2018-01-01')
]);

// example params
$_REQUEST = ['f_name'=>'test','f_del'=>'1'];

// execute with params to fill sort array
$simpleFilter->exec($_REQUEST);

$simpleFilter->getQuery(); // "UPPER(b.name) LIKE CONCAT('%',UPPER(?),'%') AND b.is_del = ? AND (CAST(created AS date) >= CAST(? AS date) AND CAST(created AS date) <= CAST(? AS date))"
$simpleFilter->getArgs(); // ['test','TRUE']
```