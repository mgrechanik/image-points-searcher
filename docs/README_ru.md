# Поисковик точек на изображении

[English version](../README.md)

## Содержание

* [Цель](#goal)
* [Демо](#demo)
* [Установка](#installing)
* [Поиск точек](#search)
* [Отображение результата поиска](#display-result)


---

## Цель <span id="goal"></span>

Данная библиотека позволяет на изображении (картинке ```jpg```) узнать координаты точек, которые вы ищете.

Точка это набор рядом стоящих пикселов выбранного цвета.

Для примера если вы на изображении нарисовали граф, мы узнаем его вершины.

Для определения, что является точкой, а что нет, из коробки идут две стратегии.

1. ```DifferentColorsStrategy``` - когда точка определяется тем, что отличается от цвета фона. Работает по умолчанию. См Демо. рис1.
2. ```ChoosenColorStrategy``` - когда точка определяется конкретно ее цветом.  См Демо. рис2.

Вы можете создать свою стратегию поиска и использовать ее.

Точки могут быть разного размера, чтобы были видны, а не 1px, и чтобы их нашло как одну, используется параметр ```Searcher::$margin```.

Результатом работы поисковика будет массив точек, представленных их X и Y координатами.

В поставке идет также ```ImageResult```, который позволяет создать изображение результата поиска:
- Найденным точкам можно проставить надписи
- Границы найденных точек можно обвести, чтобы понятней было как происходил поиск
- Между точками можно проложить путь из линий


---

## Демо <span id="demo"></span>

Определение точек по стратегии ```DifferentColorsStrategy``` , **Рисунок 1**:
![Определение точек на изображении](https://raw.githubusercontent.com/mgrechanik/image-points-searcher/main/docs/first.jpg "Определение точек на изображении")


Определение точек по стратегии ```ChoosenColorStrategy``` , **Рисунок 2**:
![Определение точек на изображении](https://raw.githubusercontent.com/mgrechanik/image-points-searcher/main/docs/second.jpg "Определение точек на изображении карты США")

	
---
    
## Установка <span id="installing"></span>

#### Установка через composer:

Выполните
```
composer require --prefer-dist mgrechanik/image-points-searcher
```

или добавьте
```
"mgrechanik/image-points-searcher" : "~1.0.0"
```
в  `require` секцию вашего `composer.json` файла.



---

## Поиск точек  <span id="search"></span> 

#### Поиск по стратегии ```DifferentColorsStrategy```
```php
try {
  $searcher = new \mgrechanik\imagepointssearcher\Searcher(
    './images/graph.jpg'
  );
  $count = $searcher->run();
  print 'Найдено - ' . $count;
  $points = $searcher->getPoints();
  var_dump($points);
} catch (Exception $e) {
	
}
```
Результат будет, например:
```
Найдено - 2
[
	['x' => 10, 'y' => 10],
	['x' => 80, 'y' => 80],
]
```

#### Поиск по стратегии ```ChoosenColorStrategy```
```php
try {
  $searcher = new \mgrechanik\imagepointssearcher\Searcher(
    './images/usa.jpg',
    new \mgrechanik\imagepointssearcher\ChoosenColorStrategy(60, 132, 253)
  );
  $count = $searcher->run();
  print 'Найдено - ' . $count;
  $points = $searcher->getPoints();
  var_dump($points);
} catch (Exception $e) {
	
}
```

---

## Отображение результата поиска  <span id="display-result"></span>   

```php
$imageResult = new \mgrechanik\imagepointssearcher\ImageResult($searcher);

// Можно самому задать цвета
$imageResult->setLabelsColor(1, 14, 230);
$imageResult->setLinesColor(255, 33, 73);
$imageResult->setMarginsColor(255, 106, 0);


// Рисуем надписи по умолчанию
$imageResult->drawLabels();
//Рисуем надписи, сами определяя текст
$imageResult->drawLabels(function($key, $point) { return "{$point['x']},{$point['y']}";});

// Рисуем границы найденных точек
$imageResult->drawMargins();

// Рисуем путь между точками
$imageResult->drawPath([2,9,20,28,41,48,44]);

// Сохраняем результат в виде изображения. См. Демо.
$imageResult->save('./images/result.jpg');
```