<?php

namespace App\Transformers;

use App\Models\SchoolPerson;
use App\Models\OtherPersonInfo;
use App\Models\School;

use App\Models\User;
use League\Fractal\TransformerAbstract;

class SchoolInfoTransformer extends TransformerAbstract
{
    public function transform($school)
    {
        return [
            'description' => $school->description,
            'contactPersons' => $this->contactPersons($school),
            'otherPersons' => $this->otherPersons($school),
        ];
    }

    public function contactPersons(School $school)
    {
        $users = [];
        $persons = SchoolPerson::where(['school_id' => $school->id])->orderBy('type')->get();
        if (!empty($persons)) {
            foreach ($persons as $person) {
                $roleLabel = ($person->type == 'primary') ? 'I Administrative Contact' : 'II Administrative Contact';
                $users[] = [
                    'role' => $roleLabel,
                    'name' => $person->user->first_name.' '.$person->user->last_name,
                    'userPhotoUrl' => $person->user->getPhoto(),
                    'email' => $person->user->email,
                    'cellPhone' => $person->cell_phone,
                    'cellPhoneExt' => $person->cell_phone_ext,
                    'schoolPhone' => $person->school_phone,
                    'schoolPhoneExt' => $person->school_phone_ext,
                ];
            }
        }

        // Website Admin

        return $users;
    }

    public function otherPersons(School $school)
    {
        $users = [];
        $otherUsers = OtherPersonInfo::where(['school_id' => $school->id])->where('other_type_id', '<=', '19')
                                     ->orderBy('other_type_id', 'DESC')->get();
        if (!empty($otherUsers)) {
            foreach ($otherUsers as $userInfo) {
                $users[] = [
                    'role' => $userInfo->type->title,
                    'name' => $userInfo->user->first_name.' '.$userInfo->user->last_name,
                    'userPhotoUrl' => $userInfo->user->getPhoto(),
                    'bio' => (!empty($otherPerson)) ? $userInfo->bio : '',
                ];
            }
        }

        return $users;
    }

}