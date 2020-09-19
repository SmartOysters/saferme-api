<?php

namespace SmartOysters\SaferMe\Resources;

use SmartOysters\SaferMe\Resources\Base\Resource;
use SmartOysters\SaferMe\Http\Response;

class Teams extends Resource
{
    /**
     * Get the TeamUsers from team.
     *
     * @param int   $team_id Entity ID interact with.
     * @param array $options
     * @return Response
     */
    public function team_users($team_id, $fields = '', $options = [])
    {
        $options = array_merge(
            compact('team_id', 'fields'),
            $options
        );

        return $this->request->get(':team_id/team_users', $options);
    }
}
