<?php

namespace App\Transformers;

use App\Models\Team;
use App\Models\UserContentAlbum;
use League\Fractal\TransformerAbstract;

class TeamTransformer extends TransformerAbstract
{
    protected $defaultIncludes = [
        'school',
        'teams'
    ];

    protected $state;

    protected $city;

    public function __construct($stateId = null, $cityId = null)
    {
        $this->state = $stateId;
        $this->city = $cityId;
    }

    public function transform(Team $team)
    {
        $teamSocials = [
            'fb' => '???',
            'tw' => '???',
            'ins' => '???',
            'pin' => '???',
            'youtube' => '???',
            'vimeo' => '???'
        ];

        return [
            'id' => $team->id,
            'name' => $team->name,
            'nameShort' =>  Team::generateShortName($team->name),
            'logoUrl' => $team->getLogo(),
            'teamTypeName' => $team->getTeamTypeFull(' '),
            'mainColor' => '#' . $team->main_color,
            'teamSocials' => $teamSocials,
            'varsityId' => $team->teamType->title,
            'albumId' => UserContentAlbum::getAlbumTeam($team->id),

        ];
    }

    public function includeSchool($team)
    {
        return $this->item($team->school, new SchoolTransformer);
    }

    public function includeTeams($team)
    {
        $query = Team::select(['team.*'])->where([
            'team.active' => 1,
            'team.visible' => 1,
            'team.season_id' => $team->season_id
        ]);
        if ($this->state != null || $this->city != null) {
            $query = $query->join('school', 'school.id', 'team.school_id');
            if ($this->state != null) {
                $query = $query->where(['school.state_id' => $this->state]);
            }
            if ($this->city != null) {
                $query = $query->where(['school.city_id' => $this->city])->whereRaw('school.city_id IS NOT NULL');
            }
        }
        $teams = $query->get();

        return $this->collection($teams, new TeamShortTransformer);
    }
}