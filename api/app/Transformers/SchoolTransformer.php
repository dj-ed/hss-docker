<?php

namespace App\Transformers;

use App\Http\Controllers\Api\FavoriteController;
use App\Models\Favorite;
use App\Models\SchoolPerson;
use App\Models\OtherPersonInfo;
use App\Models\Globals\States;
use App\Models\School;

use League\Fractal\TransformerAbstract;

class SchoolTransformer extends TransformerAbstract
{
    protected $defaultIncludes = [
        'socials'
    ];

    public function transform(School $school)
    {
        return [
            'id' => $school->id,
            'name' => $school->name,
            'shortName' => $school->getShortName(),
            'logoUrl' => $school->getSchoolLogo(),
            'stateName' => States::getNameById($school->state_id),
            'city' => $school->city->name,
            'address' => $school->address,
            'zip' => $school->zip,
            'mainColor' => $school->main_color,
            'secondColor' => $school->second_color,
            'persons' => $this->getPersons($school),
            'phone' => $school->phone,
            'phoneExt' => $school->phone_ext,
            'fax' => $school->fax,
            'faxExt' => $school->fax_ext,
            'mascot' => $school->mascot_text,
        ];
    }

    public function includeSocials(School $school)
    {
        return $this->item($school->socials, new SchoolSocialTransformer);
    }

    public function getPersons(School $school)
    {
        $existsUsers = $users = [];

        $persons = SchoolPerson::with('user')->where(['school_id' => $school->id])->get();
        if (!empty($persons)) {
            foreach ($persons as $person) {
                $roleLabel = ($person->type == 'primary') ? 'Primary Contact' : 'Secondary Contact';
                $users[] = [
                    'role' => $roleLabel,
                    'name' => $person->user->first_name . ' ' . $person->user->last_name,
                ];
                $existsUsers[] = $person->user_id;
            }
        }

        $otherUsers = OtherPersonInfo::with('type')->with('user')->where(['school_id' => $school->id])->get();
        if (!empty($otherUsers)) {
            foreach ($otherUsers as $userInfo) {
                if ($this->canAddPerson($userInfo, $existsUsers, $users)) {
                    $users[] = [
                        'role' => $userInfo->type->title,
                        'name' => $userInfo->user->first_name . ' ' . $userInfo->user->last_name,
                    ];
                }
            }
        }

        return $users;
    }

    public function canAddPerson($userInfo, $existsUsers, $users)
    {
        return !in_array($userInfo->user_id, $existsUsers) && in_array($userInfo->other_type_id, [
                17,
                19,
                20
            ]) && count($users) < 5;
    }

}