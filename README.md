## Тестовое задание A2 для Serpstat

Запускать рекомендуется в Docker.

Билдим контейнер: `docker build -t serpstat/parser .`

Запускаем: `docker run -it serpstat/parser bash`

Смотрим помощь по скрипту: `php parser help`

### Скачивание отчетов
Для скачивания отчетов необходимо запустить nginx сервер и прописать хост `parser.test` в локальный файл `hosts`.

Например для Docker под Ubuntu запись будет иметь вид:
```
172.17.0.2      parser.test
```

### Добавление новой команды
1. Создаем класс команды имплементирующий `api/Console/CommandInterface.php`
2. Добавляем команду в конфиг `config/main.php`
3. Проверяем работу команды

### Добавление нового типа парсинга
1.Создаем класс типа имплементирующий интерфейс `api/Parser/TypeInterface.php`

2.Добавляем новый тип к парсеру в команде `app/Console/Commands/ParseCommand.php`
```
$parser = new Parser($httpClient, $stringHelper);
$parser->addType(new ImageParser($reporter, $stringHelper));
```
3.Добавляем вывод отчета по новому типу в команде `app/Console/Commands/ReportCommand.php`
```
$this->outputReportForType('images', $domain);
```

### Недостатки
1. Отсутствие DI контейнера могло бы упростить манипуляции зависимостями

2. Необходимость вручную добавлять тип прсинга в команде парсера. Использование DI и глобального объекта конфигурации 
позволило бы вынести объявление типов парсинга в конфиг файл и подключать их автоматически

3. Не гибкая работа с отчетами. Сейчас вывод отчетов заточен под сохранение в файловую систему. 
Хотелось бы иметь возможность хранить их в базе данных и т.п.   
