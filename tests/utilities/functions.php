<?php

function create($class, $attributes = [], $times = null)
{
    return factory($class, $times)->create($attributes);
}

function make($class, $attributes = [], $times = null)
{
    return factory($class, $times)->make($attributes);
}

function createStates($class, $state, $attributes = [], $times = null)
{
    return factory($class, $times)->states($state)->create($attributes);
}

function createStatesRaw($class, $state, $attributes = [], $times = null)
{
    $factory = factory($class, $times)->states($state)->create($attributes);
    return $factory->toArray();
}

function makeStates($class, $state, $attributes = [], $times = null)
{
    return factory($class, $times)->states($state)->make($attributes);
}

function makeStatesRaw($class, $state, $attributes = [], $times = null)
{
    return factory($class, $times)->states($state)->raw($attributes);
}

function createRaw($class, $attributes = [], $times = null)
{
    $factory = factory($class, $times)->create($attributes);

    return $factory->toArray();
}

function makeRaw($class, $attributes = [], $times = null)
{
    return factory($class, $times)->raw($attributes);
}
