<?php

/*
 * This file is part of Ftp.
 *
 * (c) Blue Bay Travel <developers@bluebaytravel.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BlueBayTravel\Tests\Ftp\Facades;

use BlueBayTravel\Ftp\Facades\Ftp;
use BlueBayTravel\Ftp\FtpManager;
use BlueBayTravel\Tests\Ftp\AbstractTestCase;
use GrahamCampbell\TestBenchCore\FacadeTrait;

class FtpTest extends AbstractTestCase
{
    use FacadeTrait;

    /**
     * Get the facade accessor.
     *
     * @return string
     */
    protected function getFacadeAccessor()
    {
        return 'ftp';
    }

    /**
     * Get the facade class.
     *
     * @return string
     */
    protected function getFacadeClass()
    {
        return Ftp::class;
    }

    /**
     * Get the facade route.
     *
     * @return string
     */
    protected function getFacadeRoot()
    {
        return FtpManager::class;
    }
}
