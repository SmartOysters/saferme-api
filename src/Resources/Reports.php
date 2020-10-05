<?php

namespace SmartOysters\SaferMe\Resources;

use SmartOysters\SaferMe\Resources\Base\Resource;
use SmartOysters\SaferMe\Http\Response;

class Reports extends Resource
{
    /**
     * Fetch the entity details by ID.
     *
     * @param int $channel_id Entity ID to find.
     * @return Response
     */
    public function fetch($report_id)
    {
        return $this->request->get(':report_id', compact('report_id'));
    }

    /**
     * Search the reports API
     *
     * @note: https://github.com/SaferMe/saferme-api-docs/blob/teams/030_reports.md#search-for-reports
     *
     * @param array $filter
     * @param array $options
     * @return Response
     */
    public function search($filter = [], $options = [])
    {
        $options = array_merge(
            compact('filter'),
            $options
        );

        return $this->request->get('search', $options);
    }

}
