<?php

namespace App\Services;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Ufee\Amo\ApiClient;
use Ufee\Amo\Base\Storage\Oauth\FileStorage;
use Ufee\Amo\Oauthapi;

class AmoCRMService extends Controller
{
    /**
     * @var Oauthapi Объект для работы с API AmoCRM
     */
    private Oauthapi $api;

    /**
     * Создает новый экземпляр сервиса
     */
    public function __construct()
    {
        $this->api = app('AmoCRM');
    }

    /**
     * Авторизация в АМО CRM через OAuth 2.0.
     *
     * @param Request $request Объект запроса.
     * @return RedirectResponse Ответ с редиректом на главную страницу.
     */
    public function auth(Request $request): RedirectResponse
    {
        if ($request->input('code')) {
            try {

                $directory = storage_path('app/amocrm/' . $this->api->getAuth('domain'));
                if (!is_dir($directory)) {
                    File::makeDirectory($directory, 0755, true, true);
                }

                $oauth = $this->api->fetchAccessToken($request->input('code'));
                (new FileStorage(['path' => storage_path('app/amocrm')]))->setOauthData($this->api, $oauth);
                return redirect()->route('home')->with('status', 'Token saved');
            } catch (Exception $e) {
                Log::error('Error fetching token: ' . $e->getMessage());
                return redirect()->back()->with('error', 'Error fetching access token');
            }
        }
        $first_auth_url = $this->api->getOauthUrl(['mode' => 'popup', 'state' => 'amoapi']);

        return redirect($first_auth_url);
    }

    /**
     * Инициализация API клиента АМО CRM с обновлением токена.
     *
     * @return ApiClient Объект API клиента.
     * @throws Exception
     */
    public function init(): ApiClient
    {
        $oauth = $this->api->refreshAccessToken();
        (new FileStorage(['path' => storage_path('app/amocrm')]))->setOauthData($this->api, $oauth);

        return $this->api;
    }
}
