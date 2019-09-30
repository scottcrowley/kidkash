<?php

function create($class, $attributes = [], $times = null)
{
    return factory($class, $times)->create($attributes);
}

function make($class, $attributes = [], $times = null)
{
    return factory($class, $times)->make($attributes);
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
