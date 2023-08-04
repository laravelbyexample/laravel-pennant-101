<?php

namespace App\Tracing;

use Illuminate\Support\Facades\Facade;
use OpenTracing\Tracer as OpenTracingTracer;

class Tracer extends Facade
{
    protected static function getFacadeAccessor()
    {
        return OpenTracingTracer::class;
    }
}
