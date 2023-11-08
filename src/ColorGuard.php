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
 * Trait to add validation of color values
 *
 * @author Mikhail Grechanik <mike.grechanik@gmail.com>
 * @since 1.0.0 
 */
trait ColorGuard
{
    /**
     * Check all colors for correct values
     * @throws \InvalidArgumentException
     */
    protected function guardColor(int $red, int $green, int $blue) {
        foreach ([$red, $green, $blue] as $val) {
            if ($val < 0 || $val > 255) {
                throw new \InvalidArgumentException('Invalid values of color');
            }
        }
}		
}