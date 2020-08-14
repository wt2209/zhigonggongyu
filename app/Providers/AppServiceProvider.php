<?php

namespace App\Providers;

use App\Models\Bill;
use App\Models\Record;
use App\Models\Room;
use App\Models\Type;
use App\Observers\BillObserver;
use App\Observers\RoomObserver;
use App\Observers\TypeObserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->observers();
        $this->tags();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment() !== 'production') {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }
    }

    private function tags()
    {
        Blade::if('hasRentContract', function (Record $record) {
            return $record->type->has_contract && $record->start_at;
        });

        Blade::if('hasContract', function (Record $record) {
            return $record->person->contract_start && $record->person->contract_end;
        });

        Blade::if('showContractDetail', function (Record $record) {
            return $record->type->has_contract
                || ($record->person->contract_start && $record->person->contract_end);
        });
    }

    private function observers()
    {
        Room::observe(RoomObserver::class);
        Type::observe(TypeObserver::class);
        Bill::observe(BillObserver::class);
    }
}
