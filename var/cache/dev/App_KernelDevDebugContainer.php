<?php

// This file has been auto-generated by the Symfony Dependency Injection Component for internal use.

if (\class_exists(\ContainerALA43x4\App_KernelDevDebugContainer::class, false)) {
    // no-op
} elseif (!include __DIR__.'/ContainerALA43x4/App_KernelDevDebugContainer.php') {
    touch(__DIR__.'/ContainerALA43x4.legacy');

    return;
}

if (!\class_exists(App_KernelDevDebugContainer::class, false)) {
    \class_alias(\ContainerALA43x4\App_KernelDevDebugContainer::class, App_KernelDevDebugContainer::class, false);
}

return new \ContainerALA43x4\App_KernelDevDebugContainer([
    'container.build_hash' => 'ALA43x4',
    'container.build_id' => '6c8ae443',
    'container.build_time' => 1725438754,
    'container.runtime_mode' => \in_array(\PHP_SAPI, ['cli', 'phpdbg', 'embed'], true) ? 'web=0' : 'web=1',
], __DIR__.\DIRECTORY_SEPARATOR.'ContainerALA43x4');
