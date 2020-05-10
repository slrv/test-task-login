<?php


namespace Models\User;


use Core\DB\DB;
use Core\Interfaces\Arrayable;
use Exception;
use Exceptions\Database\ConnectionException;
use Exceptions\Database\ExecutionException;
use Traits\SettableTrait;

/**
 * Class User
 * @package Models\User
 *
 * @property int $id
 * @property string $email
 * @property string $password
 * @property string $first_name
 * @property string $last_name
 * @property string $description
 * @property string $img
 * @property string $created_at
 */
class User implements Arrayable
{
    use SettableTrait;

    const TABLE_NAME = 'user';

    const FIELDS = [
        'id', 'first_name', 'last_name', 'email', 'password', 'description', 'img', 'created_at'
    ];

    /**
     * Find user by email
     *
     * @param string $email
     * @return User
     * @throws ConnectionException
     * @throws Exception
     */
    public static function findByEmail(string $email) {
        return self::findOneBy([
            ['email', $email]
        ]);
    }

    /**
     * Find user by email
     *
     * @param int $id
     * @return User
     * @throws ConnectionException
     * @throws ExecutionException
     */
    public static function findById(int $id) {
        return self::findOneBy([
            ['id', $id]
        ]);
    }

    /**
     * @param string $token
     * @return User|null
     * @throws ConnectionException
     * @throws ExecutionException
     */
    public static function findByToken(string $token)
    {
        $result = DB::select(
            SessionModel::TABLE_NAME,
            array_map(function ($field) {
                return self::TABLE_NAME.'.'.$field;
            }, self::FIELDS)
        )
            ->join(self::TABLE_NAME, 'user_id', 'id')
            ->setWhere([
                ['token', $token],
                ['deleted_at', 'is', null]
            ])
            ->limit(1)
            ->execute(function ($row) {
                return new User($row);
            });

        return count($result) ? $result[0] : null;
    }

    /**
     * Create new User
     *
     * @param array $data
     * @return User
     * @throws Exception
     */
    public static function create(array $data): User {
        $user = new User($data, false);
        return $user->save();
    }

    /**
     * @param array $where
     * @return User|null
     * @throws ConnectionException
     * @throws ExecutionException
     * @throws Exception
     */
    private static function findOneBy(array $where) {
        $result = DB::select(self::TABLE_NAME, User::FIELDS)
            ->setWhere($where)
            ->limit(1)
            ->execute(function ($row) {
                return new User($row);
            });

        return !!count($result) ? $result[0] : null;
    }

    function __construct(array $data = [], bool $fillMissedNull = true)
    {
        $this->optionsList = self::FIELDS;
        $this->setOptions($data, $fillMissedNull);
    }

    /**
     * @throws Exception
     */
    function save(): User {
        if (!$this->id) {
            $result = DB::insert(self::TABLE_NAME, [$this->getOptions()])
                ->execute();

            return User::findById($result['insert_id']);
        } else {
            throw new Exception('DB update not implemented');
        }
    }

    function toArray(): array
    {
        $data = $this->getOptions();
        unset($data['password']);

        return $data;
    }
}