<?php

namespace app\index\controller;

use app\index\model\Location;
use app\index\model\Users;
use think\Db;
use think\exception\HttpException;
use think\helper\Hash;

class SocialAuth extends Home
{
    private $providers = ['google', 'apple', 'microsoft', 'facebook'];

    public function start($provider = '')
    {
        $config = $this->providerConfig($provider);
        $state = bin2hex(random_bytes(24));
        session('social_oauth_state', $state);
        session('social_oauth_provider', $provider);
        //标记是否来自 App 内嵌网页，回调成功后把登录态回传给 App
        session('social_oauth_inapp', input('inapp') ? 1 : 0);

        $params = [
            'client_id' => $config['client_id'],
            'redirect_uri' => $this->callbackUrl($provider),
            'response_type' => 'code',
            'scope' => $this->scope($provider),
            'state' => $state,
        ];
        if ($provider === 'apple') {
            $params['response_mode'] = 'form_post';
        } elseif ($provider !== 'facebook') {
            // Facebook's dialog endpoint does not accept response_mode.
            $params['response_mode'] = 'query';
        }
        $this->redirect($this->authorizationUrl($provider, $config) . '?' . http_build_query($params));
    }

    public function callback($provider = '')
    {
        $config = $this->providerConfig($provider);
        if (!hash_equals((string) session('social_oauth_state'), (string) input('state'))) {
            throw new HttpException(400, 'Your sign-in request has expired. Please try again.');
        }
        session('social_oauth_state', null);
        if (input('error')) {
            throw new HttpException(400, 'Sign-in was cancelled or not authorized.');
        }
        $code = input('code');
        if (!$code) {
            throw new HttpException(400, 'The identity provider did not return an authorization code.');
        }

        $token = $this->postForm($this->tokenUrl($provider, $config), [
            'client_id' => $config['client_id'],
            'client_secret' => $config['client_secret'],
            'code' => $code,
            'grant_type' => 'authorization_code',
            'redirect_uri' => $this->callbackUrl($provider),
        ]);
        $profile = $this->profile($provider, $token, $config);
        if (empty($profile['id']) || empty($profile['email'])) {
            throw new HttpException(400, 'Your account did not provide a verified email address.');
        }
        $userId = $this->signIn($provider, $profile);
        if (session('social_oauth_inapp')) {
            //App 内嵌网页：签发 App token，通过成功页回传给 App
            session('social_oauth_inapp', null);
            $appUser = (new Users())->issueAppToken($userId);
            if (!$appUser) {
                throw new HttpException(500, 'Could not start your app session. Please try again.');
            }
            session('social_inapp_user', $appUser);
            $this->redirect(url('social_auth/inapp_success'));
        }
        $this->redirect('member/index');
    }

    //App 内嵌网页登录成功页：把用户信息 postMessage 给 App 的 web-view
    public function inappSuccess()
    {
        $user = session('social_inapp_user');
        session('social_inapp_user', null);
        if (!$user) {
            $this->redirect('sign/learner');
        }
        $this->assign('app_user_json', json_encode($user));
        return $this->fetch();
    }

    private function signIn($provider, array $profile)
    {
        $identity = Db::name('cms_social_identity')->where([
            'provider' => $provider, 'provider_user_id' => $profile['id'],
        ])->find();
        $user = $identity ? Users::find($identity['user_id']) : null;
        if (!$user) {
            // A verified email is safe to link to an existing learner account.
            $user = Users::where(['email' => $profile['email'], 'user_type' => 1, 'is_partner' => 0])->find();
        }
        if (!$user) {
            $user = new Users();
            $user->save([
                'username' => mb_substr($profile['name'] ?: strstr($profile['email'], '@', true), 0, 40),
                'email' => $profile['email'],
                'email_verify' => 1,
                'password' => Hash::make(bin2hex(random_bytes(24))),
                'user_type' => 1,
                'is_partner' => 0,
                'moneys' => 6,
                'invitation_code' => get_invitation_code(),
                'create_ip' => get_client_ip(),
                'create_time' => time(),
            ]);
        }
        if (!$identity) {
            Db::name('cms_social_identity')->insert([
                'user_id' => $user['id'], 'provider' => $provider,
                'provider_user_id' => $profile['id'], 'email' => $profile['email'],
                'create_time' => time(), 'update_time' => time(),
            ]);
        }
        $location = Location::find($user['location']);
        Users::where('id', $user['id'])->update([
            'login_ip' => get_client_ip(), 'login_time' => time(),
            'last_login_time' => $user['login_time'], 'last_login_ip' => $user['login_ip'],
        ]);
        session('user_id', $user['id']);
        session('username', $user['username']);
        session('user_type', $user['user_type']);
        session('location', $user['location']);
        session('is_partner', $user['is_partner']);
        cookie('user_type', $user['user_type']);
        cookie('is_partner', $user['is_partner']);
        if ($location) {
            session('member_timezone', $location['timezone']);
        }
        return $user['id'];
    }

    private function profile($provider, array $token, array $config)
    {
        if ($provider === 'facebook') {
            $data = $this->getJson(
                'https://graph.facebook.com/' . $this->graphVersion($config) . '/me?fields=id,name,email',
                $token['access_token']
            );
            $email = $data['email'] ?? '';
            if (empty($data['id']) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new HttpException(400, 'Facebook did not provide a verified email address.');
            }
            return ['id' => $data['id'], 'email' => $email, 'name' => $data['name'] ?? ''];
        }
        if ($provider === 'google') {
            $data = $this->getJson('https://openidconnect.googleapis.com/v1/userinfo', $token['access_token']);
            if (empty($data['email_verified'])) throw new HttpException(400, 'Google did not verify this email address.');
            return ['id' => $data['sub'], 'email' => $data['email'], 'name' => $data['name'] ?? ''];
        }
        $claims = $this->jwtPayload($token['id_token'] ?? '');
        $email = $claims['email'] ?? ($claims['preferred_username'] ?? '');
        $issuer = $claims['iss'] ?? '';
        $validIssuer = $provider === 'apple'
            ? $issuer === 'https://appleid.apple.com'
            : strpos($issuer, 'https://login.microsoftonline.com/') === 0 && substr($issuer, -5) === '/v2.0';
        if (!$validIssuer || ($claims['aud'] ?? '') !== $config['client_id'] || empty($claims['sub']) || !filter_var($email, FILTER_VALIDATE_EMAIL) || ($claims['exp'] ?? 0) < time()) {
            throw new HttpException(400, 'The identity provider returned an incomplete identity token.');
        }
        return ['id' => $claims['sub'], 'email' => $email, 'name' => $claims['name'] ?? ''];
    }

    private function providerConfig($provider)
    {
        if (!in_array($provider, $this->providers, true)) throw new HttpException(404, 'Unknown sign-in provider.');
        $config = config('social_auth.' . $provider);
        if (empty($config['enabled']) || empty($config['client_id']) || empty($config['client_secret'])) {
            throw new HttpException(503, ucfirst($provider) . ' sign-in has not been configured yet.');
        }
        return $config;
    }

    private function authorizationUrl($provider, array $config)
    {
        if ($provider === 'google') return 'https://accounts.google.com/o/oauth2/v2/auth';
        if ($provider === 'apple') return 'https://appleid.apple.com/auth/authorize';
        if ($provider === 'facebook') return 'https://www.facebook.com/' . $this->graphVersion($config) . '/dialog/oauth';
        return 'https://login.microsoftonline.com/' . rawurlencode($config['tenant']) . '/oauth2/v2.0/authorize';
    }
    private function tokenUrl($provider, array $config)
    {
        if ($provider === 'google') return 'https://oauth2.googleapis.com/token';
        if ($provider === 'apple') return 'https://appleid.apple.com/auth/token';
        if ($provider === 'facebook') return 'https://graph.facebook.com/' . $this->graphVersion($config) . '/oauth/access_token';
        return 'https://login.microsoftonline.com/' . rawurlencode($config['tenant']) . '/oauth2/v2.0/token';
    }
    private function scope($provider)
    {
        if ($provider === 'facebook') return 'email';
        if ($provider === 'microsoft') return 'openid profile email User.Read';
        return 'openid email profile';
    }
    private function graphVersion(array $config) { return $config['graph_version'] ?? 'v21.0'; }
    private function callbackUrl($provider) { return url('social_auth/callback', ['provider' => $provider], '', true); }
    private function postForm($url, array $fields)
    {
        $result = $this->request($url, http_build_query($fields), ['Content-Type: application/x-www-form-urlencoded']);
        $data = json_decode($result, true);
        if (!is_array($data) || isset($data['error'])) throw new HttpException(400, 'The identity provider could not complete sign-in.');
        return $data;
    }
    private function getJson($url, $accessToken) { return json_decode($this->request($url, null, ['Authorization: Bearer ' . $accessToken]), true) ?: []; }
    private function request($url, $body = null, array $headers = [])
    {
        $ch = curl_init($url); curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        if ($body !== null) { curl_setopt($ch, CURLOPT_POST, true); curl_setopt($ch, CURLOPT_POSTFIELDS, $body); }
        $result = curl_exec($ch); $status = curl_getinfo($ch, CURLINFO_HTTP_CODE); curl_close($ch);
        if ($result === false || $status < 200 || $status >= 300) throw new HttpException(502, 'The identity provider is currently unavailable.');
        return $result;
    }
    private function jwtPayload($token)
    {
        $parts = explode('.', $token); if (count($parts) !== 3) return [];
        return json_decode(base64_decode(strtr($parts[1], '-_', '+/')), true) ?: [];
    }
}
