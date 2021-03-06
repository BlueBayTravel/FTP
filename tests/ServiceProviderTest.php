<?php

/*
 * This file is part of Ftp.
 *
 * (c) Blue Bay Travel <developers@bluebaytravel.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BlueBayTravel\Tests\Ftp;

use BlueBayTravel\Ftp\FtpManager;
use GrahamCampbell\TestBench\AbstractPackageTestCase;
use GrahamCampbell\TestBenchCore\ServiceProviderTrait;

class ServiceProviderTest extends AbstractPackageTestCase
{
    use ServiceProviderTrait;

    public function testFtpManagerIsInjectable()
    {
        $this->assertIsInjectable(FtpManager::class);
    }
}
