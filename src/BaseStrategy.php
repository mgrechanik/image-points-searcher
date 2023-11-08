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
 * Base strategy to find points on image
 * 
 * @author Mikhail Grechanik <mike.grechanik@gmail.com>
 * @since 1.0.0
 */
abstract class BaseStrategy
{
    /**
     * @var \GdImage Gd image object
     */
    protected \GdImage $img;

    /**
     * Setter
     */
    public function setImage(\GdImage $img) : void {
        $this->img = $img;
    }

    /**
     * Initializer. Is called at the beginning of search
     */
    public function init() : void {

    }

    /**
     * Determine whether we found a Point
     *
     * @param array $color Color of the checked point. Array with keys 'red', 'green', 'blue', 'alpha'
     * @return bool  Mathes the point we search for or not
     */
    abstract public function matches(array $color) : bool;
}