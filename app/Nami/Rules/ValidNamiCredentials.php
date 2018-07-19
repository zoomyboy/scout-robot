<?php

namespace App\Nami\Rules;

use App\Nami\Receiver\Group;
use App\Nami\Resolvers\InlineUser;
use App\Nami\Service;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\Request;

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
    public function __construct(Request $request)
    {
        $this->user = $request->namiUser;
        $this->password = $request->namiPassword;
        $this->group = $request->namiGroup;
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
        $service = app()->makeWith(Service::class, [
            'user' => new InlineUser($this->user, $this->password, $this->group)
        ]);
        $group = app()->makeWith(Group::class, [
            'service' => $service
        ]);

        return $service->checkCredentials()
            && !$group->all()->where('id', $this->group)->isEmpty();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'NaMi-Login fehlgeschlagen';
    }
}
