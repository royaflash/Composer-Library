<?php

namespace Zarinpal\Laravel;

use Illuminate\Support\ServiceProvider;
use Zarinpal\Drivers\NuSoap;
use Zarinpal\Drivers\NuSoapDriver;
use Zarinpal\Drivers\RestDriver;
use Zarinpal\Drivers\Soap;
use Zarinpal\Drivers\SoapDriver;
use Zarinpal\Zarinpal;

class ZarinpalServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return \Zarinpal\Zarinpal
     */
    public function register()
    {
        $this->app->singleton('Zarinpal', function () {
            $merchantID = config('Zarinpal.merchantID', 'XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX');
            $driver = config('Zarinpal.driver', 'Rest');
            $startPayAddress = config('Zarinpal.start_pay_address', 'https://zarinpal.com/pg/StartPay/');
            switch ($driver) {
                case 'Soap':
                    $wsdlAddress = config('Zarinpal.wsdl_address', 'https://www.zarinpal.com/pg/services/WebGate/wsdl');
                    $driver = new SoapDriver($wsdlAddress);
                    break;
                case 'NuSoap':
                    $wsdlAddress = config('Zarinpal.wsdl_address', 'https://www.zarinpal.com/pg/services/WebGate/wsdl');
                    $driver = new NuSoapDriver();
                    break;
                default:
                    $baseUrl = config('Zarinpal.rest_base_url', 'https://www.zarinpal.com/pg/rest/WebGate/');
                    $driver = new RestDriver($baseUrl);
                    break;
            }

            return new Zarinpal($merchantID, $driver, $startPayAddress);
        });
    }

    /**
     * Publish the plugin configuration.
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/Zarinpal.php' => config_path('Zarinpal.php'),
        ]);
    }
}
