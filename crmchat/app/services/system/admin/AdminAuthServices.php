<?php





namespace app\services\system\admin;


use app\dao\system\admin\AdminAuthDao;
use crmeb\basic\BaseServices;
use app\services\other\CacheServices;
use crmeb\exceptions\AuthException;
use crmeb\services\CacheService;
use crmeb\utils\ApiErrorCode;
use crmeb\utils\JwtAuth;
use Firebase\JWT\ExpiredException;

/**
 * admin授权service
 * Class AdminAuthServices
 * @package app\services\system\admin
 */
class AdminAuthServices extends BaseServices
{
    /**
     * 构造方法
     * AdminAuthServices constructor.
     * @param AdminAuthDao $dao
     */
    public function __construct(AdminAuthDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * 获取Admin授权信息
     * @param string $token
     * @return array
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function parseToken(string $token): array
    {

        if (!$token || $token === 'undefined') {
            throw new AuthException(ApiErrorCode::ERR_LOGIN);
        }
        /** @var JwtAuth $jwtAuth */
        $jwtAuth = app()->make(JwtAuth::class);
        //设置解析token
        [$id, $type] = $jwtAuth->parseToken($token);

        //验证token
        try {
            $jwtAuth->verifyToken();

        } catch (ExpiredException $e) {
            throw new AuthException(ApiErrorCode::ERR_LOGIN_INVALID);
        } catch (\Throwable $e) {
            $this->authFailAfter($id, $type);
            throw new AuthException(ApiErrorCode::ERR_LOGIN_INVALID);
        }

        //获取管理员信息
        $adminInfo = $this->dao->get($id);
        if (!$adminInfo || !$adminInfo->id) {
            $this->authFailAfter($id, $type);
            throw new AuthException(ApiErrorCode::ERR_LOGIN_STATUS);
        }

        $adminInfo->type = $type;
        return $adminInfo->hidden(['pwd', 'is_del', 'status'])->toArray();
    }

    /**
     * token验证失败后事件
     */
    protected function authFailAfter($id, $type)
    {
        // 已移除原对 product/product/<id> POST 请求的商品数据缓存逻辑（不再保留与商品业务耦合的残留）。
    }

    // saveProduct 方法已删除
}
