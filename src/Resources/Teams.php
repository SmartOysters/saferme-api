<?php

namespace SmartOysters\SaferMe\Resources;

use SmartOysters\SaferMe\Resources\Base\Resource;
use SmartOysters\SaferMe\Http\Response;

class Teams extends Resource
{
    /**
     * Update a Team
     *
     * @link https://saferme.github.io/saferme-api-docs/teams.html#update-a-team
     *
     * @param int   $team_id Entity ID interact with.
     * @param array $team
     * @param array $options
     * @return Response
     */
    public function team_update($team_id, $team, $options = [])
    {
        $options = array_merge(
            compact('team_id','team'),
            $options
        );

        return $this->request->patch(':team_id', $options);
    }

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
     * @param int    $team_id Entity ID interact with.
     * @param int    $user_id User ID.
     * @param string $fields  Array of fields to return in result
     * @param array  $options
     * @return Response
     */
    public function show_team_user($team_id, $user_id, $fields = '', $options = [])
    {
        $options = array_merge(
            compact('team_id', 'user_id', 'fields'),
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

        /**
     * Remove TeamUsers for a team.
     * 
     * @link https://github.com/SaferMe/saferme-api-docs/blob/teams/120_team.md#bulk-remove-users-to-a-team
     *
     * @param int   $team_id
     * @param array $payload
     * @param array $options
     * @return Response
     */
    public function bulk_destroy($team_id, $data, $options = [])
    {
        $options = array_merge(
            compact('team_id','data'),
            $options
        );

        return $this->request->post(':team_id/team_users/bulk_destroy', $options);
    }

    /**
     * Added Users to a Channel via Team endpoints
     *
     * @param int   $team_id
     * @param array $data
     * @param array $options
     * @return Response
     */
    public function add_users_to_team_channels($team_id, $data= [], $options = [])
    {
        $options = array_merge(
            compact('team_id', 'data'),
            $options
        );

        return $this->request->post(':team_id/add_users_to_team_channels', compact('team_id', 'data'));
        //{"users": [{"user_id": <user_id>, "role": "admin", "send_email": False}], "channel_ids": [<channel_id1>,channel_id2]}
    }
}
