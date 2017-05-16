<?php

namespace MaxHumme\ChatApi\Framework\BehatExtension\Context;

use Behat\Behat\Context\Context;
use Symfony\Component\HttpKernel\HttpKernelInterface;

interface KernelAwareContext extends Context
{

    /**
     * Set the kernel instance on the context.
     *
     * @param HttpKernelInterface $kernel
     * @return mixed
     */
    public function setApp(\Laravel\Lumen\Application $kernel);

}