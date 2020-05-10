<?php


namespace Models\User;


use Core\DTO\AbstractDTO;
use Core\DTO\PropertySchema;
use Core\Sanitization\Sanitizers\EmailSanitizer;
use Core\Sanitization\Sanitizers\FileSanitizer;
use Core\Sanitization\Sanitizers\StringSanitizer;
use Core\Validation\Validators\EmailValidator;
use Core\Validation\Validators\EntityExistsValidator;
use Core\Validation\Validators\FileValidator;
use Core\Validation\Validators\StringValidator;
use Exception;
use Exceptions\DTO\WrongPropertySchemaException;
use Exceptions\Validation\IncorrectValidationInstanceException;
use Exceptions\Validation\ValidationException;
use Exceptions\Validation\ValidationNameException;

/**
 * Class UserDTO
 * @package Models\User
 *
 * @property-read string $email
 * @property-read string $password
 * @property-read string first_name
 * @property-read string last_name
 * @property-read string description
 */
class UserDTO extends AbstractDTO
{
    /**
     * SignIn / SignUp flag
     * true = SignIn, false = SignUp
     *
     * @var bool
     */
    private $signIn;

    /**
     * Get validated and sanitized DTO
     *
     * @param array $data
     * @param bool $signIn
     * @return UserDTO
     * @throws IncorrectValidationInstanceException
     * @throws ValidationException
     * @throws ValidationNameException
     * @throws WrongPropertySchemaException
     * @throws Exception
     */
    public static function getValidDTO(array $data, bool $signIn = true) {
        $userDto = new UserDTO($data, $signIn);
        $userDto->validate();
        $userDto->sanitize();

        return $userDto;
    }

    /**
     * UserDTO constructor.
     * @param array $data
     * @param bool $signIn
     */
    public function __construct(array $data, bool $signIn = true)
    {
        parent::__construct($data);

        $this->signIn = $signIn;
    }

    function getSchema(): array
    {
        $schema = [
            $this->getEmailSchema(),
            $this->getPasswordSchema()
        ];

        if (!$this->signIn) {
            $schema[] = $this->getStringSchema('first_name');
            $schema[] = $this->getStringSchema('last_name');
            $schema[] = $this->getStringSchema('description', 500, false);
            $schema[] = $this->getImgSchema();
        }

        return $schema;
    }

    function getEmailSchema(): PropertySchema {
        $validators = [
            new EmailValidator(['maxLength' => 255])
        ];

        if (!$this->signIn) {
            $validators[] = new EntityExistsValidator([
                'table'     => 'user',
                'field'     => 'email',
                'reverse'   => true
            ]);
        }

        return new PropertySchema('email', true, $validators, new EmailSanitizer());
    }

    function getPasswordSchema(): PropertySchema {
        return new PropertySchema('password', true, [
            new StringValidator([
                'minLength' => 6,
                'maxLength' => 20
            ])
        ], $this->signIn ? null : function ($value) {
            return password_hash($value, PASSWORD_BCRYPT);
        });
    }

    function getStringSchema(string $name, int $length = 50, bool $required = true) {
        return new PropertySchema($name, $required, [
            new StringValidator([
                'maxLength' => $length
            ])
        ], new StringSanitizer());
    }

    function getImgSchema() {
        return new PropertySchema('img', false, [
            new FileValidator([
                'maxSize'   => 2 * 1024 * 1024,
                'type'      => ['image/gif', 'image/jpeg', 'image/png']
            ])
        ],
            new FileSanitizer(function ($file) {
                $ext = explode('/', $file['type'])[1];
                $name = 'image-'.time().'-'.rand(1, 100);

                return "$name.$ext";
            }, 'public/images')
        );
    }
}