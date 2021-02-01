<?php

namespace SmartOysters\SaferMe\Resources;

use SmartOysters\SaferMe\Resources\Base\Resource;
use SmartOysters\SaferMe\Http\Response;

class Teams extends Resource
{
    /**
     * Get the TeamUsers from team.
     *
     * @link https://github.com/SaferMe/saferme-api-docs/blob/teams/120_team.md#list-team-users
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

    /**
     * Show a User from the TeamUsers.
     *
     * @link https://github.com/SaferMe/saferme-api-docs/blob/doc-tweaks/125_team_users.md#fetch-a-team-user
     *
     * @param int   $team_id Entity ID interact with.
     * @param int   $user_id User ID.
     * @param array $options
     * @return Response
     */
    public function show_team_user($team_id, $user_id, $query = '', $options = [])
    {
        $options = array_merge(
            compact('team_id', 'user_id', 'query'),
            $options
        );

        return $this->request->get(':team_id/team_users/:user_id', $options);
    }

    /**
     * Create TeamUsers for a team.
     *
     * @link https://github.com/SaferMe/saferme-api-docs/blob/teams/120_team.md#bulk-add-users-to-a-team
     *
     * @param int   $team_id
     * @param array $payload
     * @param array $options
     * @return Response
     */
    public function bulk_create($team_id, $data, $options = [])
    {
        $options = array_merge(
            compact('team_id', 'data'),
            $options
        );

        return $this->request->post(':team_id/team_users/bulk_create', $options);
    }
}
