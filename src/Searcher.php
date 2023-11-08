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
 * Searcher for points on image.
 * Now we use only JPEG images.
 *
 * @author Mikhail Grechanik <mike.grechanik@gmail.com>
 * @since 1.0.0 
 */
class Searcher
{
    /**
     * @var \GdImage Gd image object
     */	
	private \GdImage $img;
	
    /**
     * @var array Points we found on image
     * Array like 
     * [
     *    ['x' => 10,'y' => 10],
     *    ['x' => 20,'y' => 20],
     *	   ...
     * ]
     */		
    private array $points = [];
	
    /**
     * @var array All pixels of image with marks what we found at that pixel
     */		
    private array $pixels = [];
	
    /**
     * @var BaseStrategy Strategy to look for points
     */		
    private ?BaseStrategy $strategy;

    /**
     * @var int The width of the image
     */	
    private int $width = 0;
	
    /**
     * @var int The height of the image
     */		
    private int $height = 0;
	
    /**
     * @var int The margin
     * When we found the pixel we are looking for we mark all margin around like the one we shou;d ignore in our next search
     */	
    private int $margin;
	
    /**
     * Constructor
     * 
     * @param string $filePath The path to the image file
     * @param BaseStrategy $strategy Strategy to look for points
     * @param int $margin
     */	
	public function __construct(string $filePath, BaseStrategy $strategy = null, int $margin = 15)  {
            if (!file_exists($filePath)) {
                throw new \Exception('File not found');
            }
            if ($img = @imagecreatefromjpeg($filePath)) {
                $this->img = $img;
            } else {
                throw new \Exception('Image format is incorrect, we expect jpg image');
            }	
            $this->margin = $margin;
            $this->width = imagesx($this->img);
            $this->height = imagesy($this->img);
            $this->pixels = array_fill(0, $this->height, array_fill(0, $this->width, ''));
            $this->strategy = $strategy;
            if (!$this->strategy) {
                $this->strategy = new DifferentColorsStrategy();
            }
            $this->strategy->setImage($this->img);
	}
	
    /**
     * Returns all points we found
     * 
     * @return array
     */	
    public function getPoints(): array {
        return $this->points;
    }
	
    /**
     * Returns GD image object
     * 
     * @return \GdImage|null
     */		
    public function getImg(): ?\GdImage {
        return $this->img;
    }	
	
    /**
     * Set points.
     * Say we found points but need to process them some way. With this we can set new points set back.
     * 
     * @param array $points
     */		
    public function setPoints(array $points) : void {
        $this->points = $points;
    }

    /**
     * Returns width of the image
     * 
     * @return int
     */	
    public function getWidth(): int {
        return $this->width;
    }
	
    /**
     * Returns height of the image
     * 
     * @return int
     */		
    public function getHeight(): int {
        return $this->height;
    }	
	
    /**
     * Check whether pixel is marked like border one.
     * Borders are used to display a frame of the found point
     * 
     * @param int $x X coordinate
     * @param int $y Y coordinate
     * @return bool
     */	
    public function isBorderPixel(int $x, int $y) : bool {
        return $this->pixels[$y][$x] == 'border';
    }
	
    /**
     * Run the searching process
     * 
     * @return int Amount of Points we found
     */		
    public function run() : int {
        $this->strategy->init();
        $widthY = $this->height;
        $widthX = $this->width;
        for ($y = 0; $y < $widthY - 1; $y++) {
            for ($x = 0; $x < $widthX - 1; $x++) {
                $colorrat = imagecolorat($this->img, $x, $y);
                $color = imagecolorsforindex($this->img, $colorrat);
                if ($this->pixels[$y][$x] || !$this->strategy->matches($color)) {
                    // pixel are already marked or does not match our strategy
                    continue;
                }
                $this->points[] = [
                    'y' => $y,
                    'x' => $x,
                ];
                $margin = $this->margin;
                for ($y2 = $y; $y2 <= $y + $margin; $y2++) {
                    for ($x2 = $x - $margin; $x2 <= $x + $margin; $x2++) {
                        if (isset($this->pixels[$y2][$x2])) {
                            if (!$this->pixels[$y2][$x2]) {
                                $this->pixels[$y2][$x2] = 'found_within_margin';
                            }
                            if (($y2 == $y) || ($y2 == $y + $margin) || ($x2 == $x - $margin) || ($x2 == $x + $margin)) {
                                $this->pixels[$y2][$x2] = 'border';
                            }
                        }						
                    }

                }
                $this->pixels[$y][$x] = 'found';
            }
        }
        return count($this->points);

    }	
	

}