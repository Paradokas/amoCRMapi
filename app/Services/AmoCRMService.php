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
     * @var Oauthapi Экземпляр API клиента АМО CRM.
     */
    private Oauthapi $api;

    /**
     * @var FileStorage Экземпляр хранилища данных OAuth.
     */
    private FileStorage $fileStorage;

    /**
     * Создает новый экземпляр сервиса.
     * @param Oauthapi $api Экземпляр API клиента АМО CRM.
     * @param FileStorage $fileStorage Экземпляр хранилища данных OAuth.
     */
    public function __construct(Oauthapi $api, FileStorage $fileStorage)
    {
        $this->api = $api;
        $this->fileStorage = $fileStorage;
    }

    /**
     * Авторизация в АМО CRM через OAuth 2.0.
     *
     * @param Request $request Объект запроса.
     * @return RedirectResponse Ответ с редиректом на главную страницу.
     */
    public function auth(Request $request): RedirectResponse
    {
        try {
            if ($request->has('code')) {
                return $this->handleOauthCallback($request);
            }

            return $this->redirectToOauthProvider();
        } catch (Exception $e) {
            Log::error('Ошибка при извлечении токена: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Ошибка при извлечении токена');
        }
    }

    /**
     * Обработка OAuth-колбэка.
     *
     * @param Request $request Объект запроса.
     * @return RedirectResponse Ответ с редиректом на главную страницу.
     * @throws Exception
     */
    private function handleOauthCallback(Request $request): RedirectResponse
    {
        $oauth = $this->api->fetchAccessToken($request->input('code'));
        $directory = $this->prepareStorageDirectory();
        $this->storeOauthData($oauth, $directory);

        return redirect()->route('home')->with('status', 'Токен сохранен');
    }

    /**
     * Редирект на страницу авторизации.
     *
     * @return RedirectResponse Ответ с редиректом на страницу авторизации.
     */
    private function redirectToOauthProvider(): RedirectResponse
    {
        $firstAuthUrl = $this->api->getOauthUrl(['mode' => 'popup', 'state' => 'amoapi']);

        return redirect($firstAuthUrl);
    }

    /**
     * Подготовка директории для хранения данных OAuth.
     *
     * @return string Путь к директории.
     */
    public function prepareStorageDirectory(): string
    {
        $directory = storage_path(config('amocrm.path') . $this->api->getAuth('domain'));
        if (!is_dir($directory)) {
            File::makeDirectory($directory, 0755, true, true);
        }

        return $directory;
    }

    /**
     * Сохранение данных OAuth.
     *
     * @param array $oauth Данные OAuth.
     * @param string $directory Путь к директории для сохранения данных.
     * @throws Exception
     */
    private function storeOauthData(array $oauth, string $directory): void
    {
        $this->fileStorage->setOauthData($this->api, $oauth);
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
        $this->fileStorage->setOauthData($this->api, $oauth);

        return $this->api;
    }
}
