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
 * This strategy based on searching for points which color is different from background color
 *
 * @author Mikhail Grechanik <mike.grechanik@gmail.com>
 * @since 1.0.0  
 */
class DifferentColorsStrategy extends BaseStrategy /*implements SearchStrategyInterface*/ {
	
    /**
     * @var array The background color of the image. It is set by first top-left pixel of the image
     */	
    private array $backgroundColor;
	
    /**
     * @inheritdoc
     */ 	
    public function init() : void {
        $colorrat = imagecolorat($this->img, 0, 0);
        $this->backgroundColor = imagecolorsforindex($this->img, $colorrat);	
    }
	
    /**
     * @inheritdoc
     */ 	
    public function matches(array $color) : bool {
        return !$this->isLikeBackColor($color);
    }
	
    /**
     * Check whether the color is like background color
     * We use percents since is is usual that the background color is not smooth  
     *
     * @param array $color 
     * @return bool 
     */ 	
    protected function isLikeBackColor(array $color) : bool{
        return 
            ($this->percent($color['red'], $this->backgroundColor['red']) < 90)
            || ($this->percent($color['green'], $this->backgroundColor['green']) < 90)
            || ($this->percent($color['blue'], $this->backgroundColor['blue']) < 90)
            ? false : true;
    }

    /**
     * Percent
     *
     * @param int $who 
     * @param int $what
     * @return float percent of one value against the other 
     */ 
    protected function percent(int $who, int $what) : float {
        $percent = (float) ($who * 100 / $what);
        return $percent;
    }	
}