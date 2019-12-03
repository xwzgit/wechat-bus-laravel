<?php

namespace WeChatBus\Laravel;



use Illuminate\Support\ServiceProvider;
use WeChatBus\MessageServer;
use WeChatBus\OpenApiServer;
use WeChatBus\WeChatApiServer;
use WeChatBus\WeChatAuthServer;
use WeChatBus\WeChatTokenServer;

class WeChatBusServiceProvider extends ServiceProvider
{
    /**
     * If is defer.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Boot the service.
     *
     * @author yansongda <me@yansongda.cn>
     */
    public function boot()
    {
        if ($this->app instanceof LaravelApplication && $this->app->runningInConsole()) {
            $this->publishes([
                dirname(__DIR__).'/config/weChatBus.php' => config_path('weChatBus.php'), ],
                'wechat-bus-laravel'
            );
        } elseif ($this->app instanceof LumenApplication) {
            $this->app->configure('weChatBus');
        }

        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
    }

    /**
     * Register the service.
     *
     * @author yansongda <me@yansongda.cn>
     *
     * @return void
     */
    public function register()
    {
    }

}
