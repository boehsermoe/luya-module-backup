# Backup Module

## Installation

In order to add the modules to your project go into the modules section of your config:

```php
return [
    'modules' => [
        // ...
        'backup' => [
            'class' => 'luya\backup\Module',
        ],
        // ...
    ],
];
```

## Create a new job



## Execute job

```
./luya scheduler/run/now {job id}
```