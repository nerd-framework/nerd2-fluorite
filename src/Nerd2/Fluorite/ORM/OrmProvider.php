<?php

namespace Nerd2\Fluorite\ORM;


use Slang\Converters\ResponseConverter;
use Slang\Http\ResponseFactory;
use Slang\Providers\ServiceProvider;

class OrmProvider extends ServiceProvider
{
    public function boot()
    {
        $this->registerEntityManager();
        $this->setEntityManagerToModels();
        $this->registerResponseConverter();
    }

    public function shutdown()
    {
        //
    }

    private function registerEntityManager()
    {
        $this->app->singleton(EntityManager::class);
    }

    private function setEntityManagerToModels()
    {
        $entityManager = $this->app->get(EntityManager::class);

        Model::setEntityManager($entityManager);
    }

    private function registerResponseConverter()
    {
//        $this->app->get(ResponseConverter::class)->on(Model::class, function (Model $model) {
//            return app(ResponseFactory::class)->json($model);
//        });
    }
}