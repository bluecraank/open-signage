<?php

namespace App\Ldap\Rules;

use Illuminate\Database\Eloquent\Model as Eloquent;
use LdapRecord\Laravel\Auth\Rule;
use LdapRecord\Models\Model as LdapRecord;

class HasPermission implements Rule
{
    /**
     * Check if the rule passes validation.
     */
    public function passes(LdapRecord $user, Eloquent $model = null): bool
    {
        // Pass user if in correct ldap group
        // return $user->inGroup('CN=Admins,OU=Groups,DC=example,DC=com');
        return $user->groups()->exists(config('ldap.allowed_group'));
    }
}
