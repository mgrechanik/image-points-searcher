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
 * This strategy is based on searching for exactly specific color on image
 *
 * @author Mikhail Grechanik <mike.grechanik@gmail.com>
 * @since 1.0.0 
 */
class ChoosenColorStrategy extends BaseStrategy /*implements SearchStrategyInterface*/ {

    use ColorGuard;

    /**
     * @var array The color we are looking for
     */
    private array $searchColor;

    public function __construct(int $red, int $green, int $blue) {
        $this->guardColor($red, $green, $blue);
        $this->searchColor = [
            'red' => $red,
            'green' => $green,
            'blue' => $blue
        ];
    }

    /**
     * @inheritdoc
     */ 	
    public function matches(array $color) : bool {
        return $this->isLikeSearchColor($color);
    }

    /**
     * Check whether point color is like the one we are looking for
         *
         * @param array $color 
         * @return bool 
     */ 	
    protected function isLikeSearchColor(array $color) : bool{
        return 
            ($color['red'] == $this->searchColor['red'])
            && ($color['green'] == $this->searchColor['green'])
            && ($color['blue'] == $this->searchColor['blue'])
            ? true : false;
    }

}