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
use SoureCode\Bundle\Common\SoureCodeCommonBundle;
use SoureCode\Bundle\Cqrs\SoureCodeCqrsBundle;
use SoureCode\Bundle\Token\SoureCodeTokenBundle;
use SoureCode\Bundle\User\SoureCodeUserBundle;
use Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\SecurityBundle\SecurityBundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\KernelInterface;

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
        $kernel->addTestBundle(SoureCodeCommonBundle::class);
        $kernel->addTestBundle(SoureCodeTokenBundle::class);
        $kernel->addTestBundle(SoureCodeUserBundle::class);
        $kernel->addTestBundle(SoureCodeCqrsBundle::class);
        $kernel->addTestBundle(SecurityBundle::class);
        $kernel->addTestBundle(DoctrineBundle::class);
        $kernel->addTestBundle(StofDoctrineExtensionsBundle::class);
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
