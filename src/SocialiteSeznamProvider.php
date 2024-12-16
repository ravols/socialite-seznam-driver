<?php

namespace Ravols\SocialiteSeznamDriver;

use GuzzleHttp\RequestOptions;
use Illuminate\Support\Arr;
use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\InvalidStateException;
use Laravel\Socialite\Two\Token;
use Laravel\Socialite\Two\User;

class SocialiteSeznamProvider extends AbstractProvider
{
    /**
     * Unique Provider Identifier.
     */
    const IDENTIFIER = 'seznam';

    /**
     * @var string[]
     */
    protected $scopes = [
        'identity',
    ];

    /**
     * Get the authentication URL for the provider.
     *
     * @param  string  $state
     * @return string
     */
    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase('https://login.szn.cz/api/v1/oauth/auth', $state);
    }

    /**
     * {@inheritdoc}
     */
    protected function getTokenUrl()
    {
        return 'https://login.szn.cz/api/v1/oauth/token';
    }

    /**
     * {@inheritdoc}
     */
    public function user()
    {
        if ($this->hasInvalidState()) {
            throw new InvalidStateException;
        }

        $response = $this->getAccessTokenResponse($this->getCode());

        $user = $this->mapUserToObject(
            $this->getUserByToken(
                $token = $response['access_token']
            )
        );

        session(['socialite_' . self::IDENTIFIER . '_idtoken' => $token]);
        $user->setRefreshToken($response['refresh_token']);
        $user->setExpiresIn($response['expires_in']);
        $user->setApprovedScopes($this->scopes);

        return $user->setToken($token);
    }

    /**
     * Get the access token response for the given code.
     *
     * @param  string  $code
     * @return array
     */
    public function getAccessTokenResponse($code)
    {
        $response = $this->getHttpClient()->post(
            'https://login.szn.cz/api/v1/oauth/token',
            [
                'form_params' => [
                    'grant_type' => 'authorization_code',
                    'code' => request()->code,
                    'redirect_uri' => config('services.seznam.redirect'),
                    'client_secret' => config('services.seznam.client_secret'),
                    'client_id' => config('services.seznam.client_id')],
            ],
        );

        return json_decode($response->getBody(), true);
    }

    /**
     * Get the raw user for the given id token.
     *
     * @param  string  $token
     * @return array
     */
    protected function getUserByToken($token)
    {
        $response = $this->getHttpClient()->get('https://login.szn.cz/api/v1/user', [
            RequestOptions::QUERY => [
                'prettyPrint' => 'false',
            ],
            RequestOptions::HEADERS => [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $token,
            ],
        ]);

        return json_decode((string) $response->getBody(), true);
    }

    /**
     * {@inheritdoc}
     */
    protected function mapUserToObject(array $user)
    {
        $user['id'] = Arr::get($user, 'oauth_user_id');
        $user['verified_email'] = Arr::get($user, 'email_verified');

        return (new User)->setRaw($user)->map([
            'id' => Arr::get($user, 'oauth_user_id'),
            'nickname' => Arr::get($user, 'username'),
            'name' => Arr::get($user, 'firstname') . ' ' . Arr::get($user, 'lastname'),
            'email' => Arr::get($user, 'email'),
        ]);
    }
}
