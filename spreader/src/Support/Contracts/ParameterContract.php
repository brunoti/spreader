<?php

namespace Indb\Spreader\Support\Contracts;

interface ParameterContract
{
    public function getDefinedParameters();
    public function getDefaultParameters();
    public function getRequiredParameters();
}
