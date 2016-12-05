<?php

namespace Indb\Spreader\Support\Contracts;

interface ParameterContract
{
    /**
     * Get the possible/usable parameters
     *
     * @return array
     */
    public function getDefinedParameters();

    /**
     * Get the parameters that has default values
     *
     * @return array
     */
    public function getDefaultParameters();

    /**
     * Get the parameters that has default values
     *
     * @return array
     */
    public function getRequiredParameters();
}
