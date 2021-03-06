# Описание проекта
### Игра "Виселица"

Компьютер загадывает слово из шести букв и рисует на странице отдельные пустые клетки для каждой буквы. 

* Игрок пытается угадать буквы, а затем и все слово целиком. 
* Если игрок правильно угадывает букву, компьютер вписывает ее в клетку.
* Если ошибается, то рисует одну из частей тела повешенного человека. 
* Чтобы победить, игрок должен угадать все буквы в слове до того, как повешенный человечек будет полностью нарисован.

* Информация о датах и исходах всех партий, а также о всех попытках, сделанных во время игры, должна сохраняться в базе данных SQLite.
* Для каждой игры в базе должна храниться следующая информация:
    * Дата игры
    * Имя игрока
    * Загаданное компьютером слово
    * Исход игры (угадал/не угадал)
    * Запись попыток в формате: 
      `номер попытки | предложенная буква | результат`
* В программе должны быть реализованы три режима, которым соответствуют ключи:
    * `--new`. Новая игра.
    * `--list`. Вывод списка всех сохраненных игр.
    * `--replay id`. Повтор игры с идентификатором id.

# Требования к программному обеспечению

* PHP версии 7.2 +
* Composer версии 1.9.3 +

# Инструкция по установке и запуску

С помощью GitHub:
1. Загрузите проект на персональный компьютер
2. Перейдите в корневой каталог
3. Выполните в консоли команду composer update
4. Перейдите в каталог bin и запустите файл hangman.bat

Из Packagist:
1. Перейдите в каталог, в который необходимо загрузить проект
2. Выполните в консоли команду composer create-project yosha_exe/hangman
3. Перейдите в каталог bin и запустите файл hangman.bat

# Ссылки

* Packagist: https://packagist.org/packages/andrfk/hangman