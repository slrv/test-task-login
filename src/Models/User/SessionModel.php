<?php


namespace Models\User;


use Core\DB\DB;
use Exception;
use Exceptions\Database\ConnectionException;
use Exceptions\Database\ExecutionException;
use Traits\SettableTrait;

/**
 * Class SessionModel
 * @package Models\User
 *
 * @property string $token
 * @property int $user_id
 * @property string $created_at
 * @property string $deleted_at
 *
 */
class SessionModel
{
    use SettableTrait;

    const TABLE_NAME = 'session';
    const FIELDS = [
        'user_id', 'token', 'created_at', 'deleted_at'
    ];

    /**
     * @param string $token
     * @return SessionModel|null
     * @throws ConnectionException
     * @throws ExecutionException
     * @throws Exception
     */
    public static function findOneByToken(string $token) {
        $result = DB::select(self::TABLE_NAME, SessionModel::FIELDS)
            ->setWhere([
                ['token', $token],
                ['deleted_at', 'is', null]
            ])
            ->limit(1)
            ->execute(function ($row) {
                return new SessionModel($row);
            });

        return !!count($result) ? $result[0] : null;
    }

    /**
     * Create new session
     *
     * @param int $user_id
     * @return SessionModel
     * @throws Exception
     */
    public static function create(int $user_id) {
        $token = md5($user_id.time());
        $session = new SessionModel([
            'user_id'   => $user_id,
            'token'     => $token
        ]);

        return $session->save();
    }

    /**
     * Destroy session
     *
     * @param string $token
     * @return bool
     * @throws ConnectionException
     * @throws ExecutionException
     */
    public static function destroy(string $token) {
        $session = new SessionModel(['token' => $token]);

        return $session->saveDelete();
    }

    function __construct($data = [], bool $fillMissedNull = false)
    {
        $this->optionsList = self::FIELDS;
        $this->setOptions($data, $fillMissedNull);
    }

    /**
     * @throws Exception
     */
    function save(): SessionModel {
        $result = DB::insert(self::TABLE_NAME, [$this->getOptions()])
            ->execute();

        if ($result['affected_rows'] === 1) {
            return SessionModel::findOneByToken($this->token);
        } else {
            throw new Exception('Error during insert token', 422);
        }
    }

    /**
     * @return bool
     * @throws ConnectionException
     * @throws ExecutionException
     */
    function saveDelete() {
        $result = DB::update(self::TABLE_NAME, [
            'deleted_at' => date('Y-m-d H:i:s')
        ], [
            ['token',  $this->token]
        ])->execute();

        return $result['affected_rows'] > 0;
    }
}