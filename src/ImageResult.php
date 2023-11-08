<?php

/**
 * This file is part of the mgrechanik/image-points-searcher library
 *
 * @copyright Copyright (c) Mikhail Grechanik <mike.grechanik@gmail.com>
 * @license https://github.com/mgrechanik/image-points-searcher/blob/main/LICENSE.md
 * @link https://github.com/mgrechanik/image-points-searcher
 */

namespace mgrechanik\imagepointssearcher;

/**
 * Class to display result of our search on image file
 *
 * @author Mikhail Grechanik <mike.grechanik@gmail.com>
 * @since 1.0.0 
 */
class ImageResult
{
	use ColorGuard;
	
    /**
     * @var Searcher Searcher with who we did search we need to show results
     */	
	private Searcher $searcher;
	
    /**
     * @var \GdImage Gd image object
     */	
	private ?\GDImage $img;
	
    /**
     * @var array Color of the labels
     */	
	private array $labelsColor = [
		'red' => 1,
		'green' => 14,
		'blue' => 230
	];
	
    /**
     * @var array Color of the borders of margins of points found
     */		
	private array $marginsColor = [
		'red' => 255,
		'green' => 40,
		'blue' => 247
	];	
	
    /**
     * @var array Color of the lines of the path
     */		
	private array $linesColor = [
		'red' => 0,
		'green' => 255,
		'blue' => 0
	];		
	
	public function __construct(Searcher $searcher) {
		$this->searcher = $searcher;
		$this->img = $this->searcher->getImg();
	}
	
    /**
     * Saving the result to image file
	 * @param string $filePath
     */		
	public function save(string $filePath) : void {
		if ($this->img) {
			imagejpeg($this->img, $filePath);
		}
	}
	
    /**
     * Setter for labels color
     */	
	public function setLabelsColor(int $red, int $green, int $blue) {
		$this->setColorTo('labelsColor', $red, $green, $blue);
	}
	
    /**
     * Setter for margins color
     */		
	public function setMarginsColor(int $red, int $green, int $blue) {
		$this->setColorTo('marginsColor', $red, $green, $blue);
	}

    /**
     * Setter for lines color
     */	
	public function setLinesColor(int $red, int $green, int $blue) {
		$this->setColorTo('linesColor', $red, $green, $blue);
	}	
	
    /**
     * Setting color to destination
     */		
	protected function setColorTo(string $what, int $red, int $green, int $blue) {
		if (in_array($what, ['labelsColor', 'marginsColor', 'linesColor'])) {
			$this->guardColor($red, $green, $blue);
			$this->$what = [
				'red' => $red,
				'green' => $green,
				'blue' => $blue
			];
		}
	}
	
    /**
     * Getting color of destination
	 * 
	 * @param string $what A color of what
     */		
	protected function getColorOf(string $what) {
		if (in_array($what, ['labelsColor', 'marginsColor', 'linesColor'])) {
			return imagecolorallocate($this->img, $this->$what['red'], $this->$what['green'], $this->$what['blue']);
		}	
	}

    /**
     * Drawing labels of the Points we found
	 * With this we can clearly see what points we found.
	 *
	 * @param callable $nameFunc Function, if present, to create a name 
	 * Example: function($key, $point) { return "{$point['x']},{$point['y']}";}
	 * @param \GdFont|int $font Font with which we draw a label
     */	
	public function drawLabels(?callable $nameFunc = null,  \GdFont|int $font = 8) : void {
		$color = $this->getColorOf('labelsColor');
		$widthX = $this->searcher->getWidth();
		$points = $this->searcher->getPoints();
		foreach ($points as $key => $point) {
			$y = $point['y'] - 20;
			$x = $point['x'];
			$y = ($y < 0) ? 30 : $y;
			
			if ($x > $widthX - 50) {
				$x = $widthX - 50;
			}
			$name = $nameFunc ? call_user_func($nameFunc, $key, $point) : 't-' . $key;
			imagestring($this->img, $font, $x, $y, $name, $color);
		}	
	}
	
	/**
	 * Draw margins of Points.
	 * It is useful to see how the search process worked.
	 * If some points are too close and we don't want to recognize both of them we need to set Searcher::$margin parameter to larger value
	 */
	public function drawMargins() : void {
		$widthY = $this->searcher->getHeight();
		$widthX = $this->searcher->getWidth();
		$color = $this->getColorOf('marginsColor');
		for ($y = 0; $y < $widthY - 1; $y++) {
			for ($x = 0; $x < $widthX - 1; $x++) {
				if ($this->searcher->isBorderPixel($x, $y)) {
					imagesetpixel($this->img, $x, $y, $color);
				}
			}
		}
	}	
	
	/**
	 * Draw path on image.
	 *
	 * @param array $path Path. Represented like array of keys of the Points we found with Searcher
     * @param bool $double Whether we need two lines to draw a path line
     * @param bool $guard Whether to throw an exception if we receiwed wrong keys of the Points	 
	 * @throws \InvalidArgumentException
	 */	
	public function drawPath(array $path, bool $double = true, bool $guard = true) : void {
		$count = count($path);
		$color = $this->getColorOf('linesColor');
		$points = $this->searcher->getPoints();
		if ($count > 1) {
			for ($i = 0; $i < $count - 1 ; $i++) {
				$ind1 = $path[$i];
				$ind2 = $path[$i + 1];
				if (isset($points[$ind1], $points[$ind2] )) {
					imageline($this->img, $points[$ind1]['x'], $points[$ind1]['y'],
					$points[$ind2]['x'], $points[$ind2]['y'], $color);
					if ($double) {
						imageline($this->img, $points[$ind1]['x'], $points[$ind1]['y'] - 1,
						$points[$ind2]['x'], $points[$ind2]['y'] - 1, $color);					
					}
				} else {
					if ($guard) {
						throw new \InvalidArgumentException('Path helds wrong elements. They should be correct keys of Points found');
					}
				}	
			}
		}
	}	
}	