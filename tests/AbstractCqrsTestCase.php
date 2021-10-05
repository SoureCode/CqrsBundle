<?php
/*
 * This file is part of the SoureCode package.
 *
 * (c) Jason Schilling <jason@sourecode.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SoureCode\Bundle\Cqrs\Tests;

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Nyholm\BundleTest\TestKernel;
use SoureCode\Bundle\Cqrs\SoureCodeCqrsBundle;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpKernel\KernelInterface;
use Zenstruck\Messenger\Test\ZenstruckMessengerTestBundle;

/**
 * @author Jason Schilling <jason@sourecode.dev>
 */
abstract class AbstractCqrsTestCase extends KernelTestCase
{
    protected static function createKernel(array $options = []): KernelInterface
    {
        /**
         * @var TestKernel $kernel
         */
        $kernel = parent::createKernel($options);
        $kernel->addTestBundle(SoureCodeCqrsBundle::class);
        $kernel->addTestBundle(ZenstruckMessengerTestBundle::class);
        $kernel->addTestBundle(DoctrineBundle::class);
        $kernel->setTestProjectDir(__DIR__.'/App');
        $kernel->addTestConfig(__DIR__.'/config.yml');
        $kernel->handleOptions($options);

        return $kernel;
    }

    protected static function getKernelClass(): string
    {
        return TestKernel::class;
    }
}
