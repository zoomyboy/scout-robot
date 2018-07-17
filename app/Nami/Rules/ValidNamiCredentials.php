<?php

namespace App\Nami\Rules;

use App\Nami\Receiver\Group;
use App\Nami\Service;
use Illuminate\Contracts\Validation\Rule;

class ValidNamiCredentials implements Rule
{
    public $user;
    public $password;
    public $group;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($user, $password, $group)
    {
        $this->user = $user;
        $this->password = $password;
        $this->group = $group;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $service = app(Service::class);
        $group = app(Group::class);

        // @todo Add test for checkCredentials on service class
        return $service->checkCredentials($this->user, $this->password)
            && !$group->all()->where('id', $this->group)->isEmpty();

    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The validation error message.';
    }
}
