<?php

namespace App\Common\Traits;

trait GetUniqueUid
{
    function renderUid()
    {
        $id = mt_rand(100000, 999999);

        // call the same function if the id exists already
        // if ($this->registrationIdExists($id)) {
        //     return $this->generateRegistrationId();
        // }

        // otherwise, it's valid and can be used
        return $id;
    }

    function registrationIdExists($id)
    {
        // query the database and return a boolean
        // for instance, it might look like this in Laravel
        // return Student::where('student_registration_id', $id)->exists();
    }
}