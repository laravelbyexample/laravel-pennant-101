<?php

namespace App\Tracing;

use Illuminate\Foundation\Http\Events\RequestHandled;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\ServiceProvider;
use Jaeger\Config;
use Jaeger\Tracer as JaegerTracer;
use OpenTracing\Tracer;
use Psr\Log\LoggerInterface;

use const Jaeger\SAMPLER_TYPE_CONST;

class TracingServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(JaegerTracer::class, function () {
            $config = new Config([
                'sampler' => [
                    'type' => SAMPLER_TYPE_CONST,
                    'param' => true,
                ],

                'logging' => true,

                'local_agent' => [
                    'reporting_host' => 'localhost',
                    // You can override port by setting local_agent.reporting_port value
                    'reporting_port' => 6832,
                ],

                // Different ways to send data to Jaeger. Config::ZIPKIN_OVER_COMPACT - default):
                'dispatch_mode' => Config::JAEGER_OVER_BINARY_UDP,
            ], 'Mart Web Server', $this->app->make(LoggerInterface::class));

            return $config->initializeTracer();
        });

        $this->app->bind(Tracer::class, JaegerTracer::class);
    }

    public function boot(Tracer $tracer): void
    {
        $scope = $tracer->startActiveSpan(
            Request::getMethod() . ' ' . Request::getUri(),
        );

        Event::listen(RequestHandled::class, function () use ($scope, $tracer) {
            $scope->close();
            $tracer->flush();
        });
    }
}
