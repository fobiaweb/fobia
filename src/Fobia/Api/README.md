# API
=====

php App Api


$method = new Api_Object_Name($params, $options);


При создании объекта в конструкторе запускаеться ``$this->configure()``, где и определяються свойства параметров метода.


Для выполнения метода и получения результата, выполните след.:

    $method->invoke();
    if ($error = $api->errorInfo) {
        $result = $error;
    } else {
        $result = $api->getResponse();
    }


Эти действия можно выполнить короче

    $result = $method();

Обращение к объекту как к фунции вызовит последовательность вышеперечисленый действий

## Core

Меотд вызываеться путем
    
    $method->invoke();

функция инициализирует ``$this->initialize();`` параметры метода и запускает ``$method->execute()``, где и происходит вcя логика метотода. Все действия обернуты обработкой исключений. Т.Е. в крации можно представить:

    function invoke() {
        try {
            $this->initialize();
            $this->execute();
        } catch (\Exception $exc) {}
    }



## map

```php
// <methodName:String> => array(<type:String>, <target:Mixed>, [<classOptions:Array>], [<targetArgs:Array>])
$map = array(
    'users.create' => array('file',     '/path/to/file.php', array(... )),
    'users.search' => array('callable', {closure}, array(... ) ),
    'users.delete' => array('object',   'ClassName:method', array(... )
);
```

__class/object__

Создает объект сласса _ClassName_ с передаными в конструктор ``$params`` и ``$classOptions``. 
Делее вызываеться метод _method_ (по умолчанию _invoke_) с передаными мараметрами ``$targetArgs``.

    $api = new ClassName($params, $classOptions);
    $api->$method($targetArgs);



__file__

Подключаеться файл ``target`` в котором доступна переменая ``$p`` - переданные параметры

    $p = $params;
    include $target;

__callable__

Вызаваеться функция ``target`` с параметрами ``$params`` и ``$targetArgs``. 

    $target($params, $targetArgs);


