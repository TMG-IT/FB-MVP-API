<?php

/**
 * todo see if we still need this mess after upgrading to Symfony 4.2
 */

namespace App\Validator;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class ValidatorCompilerPass implements CompilerPassInterface
{
    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container): void
    {
        $builderDefinition = $container->getDefinition('validator.builder');

        $builderDefinition->addMethodCall('addLoader', [new Reference(SerializerAnnotationLoader::class)]);
    }
}
