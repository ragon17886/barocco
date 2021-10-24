<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class InstagramService
{
    /**
     * Base url for request
     *
     * @var string
     */
    protected $baseUrl = 'https://graph.instagram.com';

    /**
     * API access token
     *
     * @var string
     */
    protected $accessToken;

    /**
     * Token lifetime
     *
     * @var integer
     */
    protected $tokenLifetime = 4320000; // 50 days

    /**
     * Cache keys
     */
    const CACHE_POSTS_KEY = 'instagram_posts';
    const CACHE_TITLE_KEY = 'instagram_title';

    public function __construct()
    {
        $this->accessToken = $this->getAccessToken();;
    }

    /**
     * Get access token
     *
     * @return string
     */
    protected function getAccessToken(): string
    {
        $file = database_path('files/instagram_api_token.php');

        if (!file_exists($file)) {
            throw new \Exception('Instagram token not exists');
        }
        $this->updateIfNeeded($file);

        return require $file;
    }

    /**
     * Update access token if needed
     *
     * @param string $file
     * @return void
     */
    protected function updateIfNeeded(string $file): void
    {
        if (time() > (filectime($file) + $this->tokenLifetime)) {
            $token = $this->updateToken(require $file);
            file_put_contents($file, "<?php return '$token';");
        }
    }

    /**
     * Update access token
     *
     * @param string $oldToken
     * @return string
     */
    protected function updateToken(string $oldToken): string
    {
        $response = Http::get($this->baseUrl . '/refresh_access_token', [
            'grant_type' => 'ig_refresh_token',
            'access_token' => $oldToken
        ]);

        return $response->json('access_token');
    }

    /**
     * Get last 25 instagram posts
     *
     * @return array
     */
    public function getPosts(): array
    {
        $response = Http::get($this->baseUrl . '/me/media', [
            'fields' => implode(',', [
                'id',
                'media_type',
                'media_url',
                'caption',
                'timestamp',
                'thumbnail_url',
                'permalink'
            ]),
            'access_token' => $this->accessToken
        ]);

        return $response->json('data');
    }

    /**
     * Get last 25 instagram posts use cache
     *
     * @return array
     */
    public function getCachedPosts(): array
    {
        return Cache::remember(self::CACHE_POSTS_KEY, 3600, function () { // 1h
            return $this->getPosts();
        });
    }

    /**
     * Get title from admin panel
     *
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return Cache::get(self::CACHE_TITLE_KEY);
    }

    /**
     * Set new title for instagram
     *
     * @param string|null $title
     * @return void
     */
    public function setTitle(?string $title): void
    {
        if (empty($title)) {
            Cache::forget(self::CACHE_TITLE_KEY);
        } else {
            Cache::forever(self::CACHE_TITLE_KEY, $title);
        }
    }
}