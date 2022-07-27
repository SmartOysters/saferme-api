<?php

namespace SmartOysters\SaferMe\Resources;

use SmartOysters\SaferMe\Resources\Base\Resource;
use SmartOysters\SaferMe\Http\Response;

class Reports extends Resource
{
    /**
     * Fetch the entity details by ID.
     *
     * @param  int $report_id Entity ID to find.
     * @return Response
     */
    public function fetch($report_id)
    {
        return $this->request->get(':report_id', compact('report_id'));
    }

    /**
     * Fetch the entity details by ID.
     * @note: https://saferme.github.io/saferme-api-docs/reports.html#available-report-fields
     *
     * @param  int  $report_id Entity ID to find.
     * @param array $fields    Fields to append to the query
     * @return Response
     */
    public function fetchFields($report_id, $fields = [])
    {
        $options = array_merge(compact('report_id'), [
            'fields' => $fields
        ]);

        return $this->request->get(':report_id', $options);
    }

    /**
     * Fetch the report state changes when finding by ID.
     *
     * @param  int  $report_id Entity ID to find.
     * @param array $headers   Set the headers for the request
     * @return Response
     */
    public function report_state_changes($report_id, $headers = array())
    {
        return $this->request->get(':report_id/report_state_changes', compact('report_id', 'headers'));
    }

    /**
     * Fetch the HEAD data for report state changes when finding by ID.
     *
     * @param  int $report_id Entity ID to find head data for.
     * @return Response
     */
    public function report_state_changes_head($report_id)
    {
        return $this->request->head(':report_id/report_state_changes', compact('report_id'));
    }

    /**
     * Search the reports API
     *
     * @note: https://github.com/SaferMe/saferme-api-docs/blob/teams/030_reports.md#search-for-reports
     *
     * @param  array $filter
     * @param  array $options
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

    /**
     * Filter reports based on data provided in get query
     *
     * @param  array $filter
     * @return Response
     */
    public function filter($filter)
    {
        return $this->request->get('', $filter);
    }

    /**
     * Update the entity details by ID.
     *
     * @param  int $report_id Entity ID to find.
     * @param array $report
     * @param array $options
     * @return Response
     */
    public function update($report_id, $report, $options = [])
    {
        $options = array_merge(
            compact('report_id','report'),
            $options
        );

        return $this->request->patch(':report_id', $options);
    }

    /**
     * Update a FeatureUuid from the provided Report
     *
     * @param  int    $report_id    Report Entity to update
     * @param  string $feature_uuid Mapbox FeatureUuid to be included with Report
     * @return Response
     */
    public function updateFeature($report_id, $feature_uuid)
    {
        $options = array_merge(compact('report_id'), [
            'data' => [
                'feature' => [
                    'feature_uuid' => $feature_uuid
                ]
            ]
        ]);

        return $this->request->post(':report_id/update_feature', $options);
    }

}
