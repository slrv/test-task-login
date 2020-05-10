<?php


namespace Core\Validation\Validators;


use Core\DB\DB;
use Core\Validation\AbstractValidator;
use Exception;
use Traits\SettableTrait;

class EntityExistsValidator extends AbstractValidator
{
    use SettableTrait;

    protected $optionsList = ['table', 'field', 'where', 'reverse'];
    protected $name = 'exists';

    /**
     * @param $value
     * @return bool
     * @throws Exception
     */
    function validate($value): bool
    {
        if (!$this->hasOption('table') || !$this->hasOption('field')) {
            throw new Exception('Incorrect validator setup', 422);
        }

        $query = DB::select(
                $this->getOptionValue('table'),
                [$this->getOptionValue('field')
            ])
            ->limit(1);

        $where = [
            [$this->getOptionValue('field'), $value]
        ];

        if ($this->hasOption('where')) {
            $fullWhere = $this->getOptionValue('where');
            $fullWhere[] = $where;
            $where = $fullWhere;
        }

        $query->setWhere($where);

        return ($this->hasOption('reverse') && $this->getOptionValue('reverse')) ?
            !count($query->execute()) :
            !!count($query->execute());
    }
}