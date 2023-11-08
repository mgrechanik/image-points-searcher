# Searching for points on image

[Русская версия](docs/README_ru.md)

## Содержание

* [Goal](#goal)
* [Demo](#demo)
* [Installing](#installing)
* [Search for points](#search)
* [Display search result](#display-result)


---

## Goal <span id="goal"></span>

This  library allows to find the coordinates of the points you are looking for on an image (```jpg``` format).

The point is the set of nearby pixels of choosen color.

For example, when you draw a graph on the image, we will find it's vertices.

Out of the box we have two strategies to determite our points.

1. ```DifferentColorsStrategy``` - when the point is different from background color. Default behavior. See Demo, picture 1.
2. ```ChoosenColorStrategy``` - when point is defined by it's exact color.  See Demo, picture 2.

You can create your own strategy and use it.

Points could be of different size, so they could be easily seen, not just 1px size. We use parameter ```Searcher::$margin``` to determine all those pixels as one point.

The result of the search work is an array of points, represented by their X and Y coordinates.

We also give you ```ImageResult```, who creates the image of the search result:
- We can add labels to points we found
- The borders of the points we found could be shown to better understand search process
- We could draw a path between points


---

## Demo <span id="demo"></span>

Determine points with ```DifferentColorsStrategy``` strategy , **Picture 1**:
![Determine points on image](https://raw.githubusercontent.com/mgrechanik/image-points-searcher/main/docs/first.jpg "Determine points on image")


Determine points with ```ChoosenColorStrategy``` strategy, **Picture 2**:
![Determine points on image](https://raw.githubusercontent.com/mgrechanik/image-points-searcher/main/docs/second.jpg "Determine points on USA map image")

	
---
    
## Installing <span id="installing"></span>

#### Installing through composer:

The preferred way to install this library is through composer.

Either run
```
composer require --prefer-dist mgrechanik/image-points-searcher
```

or add
```
"mgrechanik/image-points-searcher" : "~1.0.0"
```
to the require section of your `composer.json`.



---

## Search for points  <span id="search"></span> 

#### Search for points with ```DifferentColorsStrategy``` strategy
```php
try {
  $searcher = new \mgrechanik\imagepointssearcher\Searcher(
    './images/graph.jpg'
  );
  $count = $searcher->run();
  print 'Found - ' . $count;
  $points = $searcher->getPoints();
  var_dump($points);
} catch (Exception $e) {
	
}
```
The result will be like this:
```
Found - 2
[
	['x' => 10, 'y' => 10],
	['x' => 80, 'y' => 80],
]
```

#### Search for points with ```ChoosenColorStrategy``` strategy
```php
try {
  $searcher = new \mgrechanik\imagepointssearcher\Searcher(
    './images/usa.jpg',
    new \mgrechanik\imagepointssearcher\ChoosenColorStrategy(60, 132, 253)
  );
  $count = $searcher->run();
  print 'Found - ' . $count;
  $points = $searcher->getPoints();
  var_dump($points);
} catch (Exception $e) {
	
}
```

---

## Display search result  <span id="display-result"></span>   

```php
$imageResult = new \mgrechanik\imagepointssearcher\ImageResult($searcher);

// We can set colors
$imageResult->setLabelsColor(1, 14, 230);
$imageResult->setLinesColor(255, 33, 73);
$imageResult->setMarginsColor(255, 106, 0);


// Draw default labels
$imageResult->drawLabels();
// Draw labels with the text we want
$imageResult->drawLabels(function($key, $point) { return "{$point['x']},{$point['y']}";});

// Draw borders of the points we found
$imageResult->drawMargins();

// Draw the path between points
$imageResult->drawPath([2,9,20,28,41,48,44]);

// Save the result as the image. See Demo.
$imageResult->save('./images/result.jpg');
```