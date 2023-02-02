<?php
/*
 * Pipedrive
 *
 * This file was automatically generated by APIMATIC v2.0 ( https://apimatic.io ).
 */

namespace Pipedrive\Controllers;

use Pipedrive\APIException;
use Pipedrive\APIHelper;
use Pipedrive\Configuration;
use Pipedrive\Models;
use Pipedrive\Exceptions;
use Pipedrive\Utils\DateTimeHelper;
use Pipedrive\Http\HttpRequest;
use Pipedrive\Http\HttpResponse;
use Pipedrive\Http\HttpMethod;
use Pipedrive\Http\HttpContext;
use Pipedrive\OAuthManager;
use Pipedrive\Servers;
use Pipedrive\Utils\CamelCaseHelper;
use Unirest\Request;

/**
 * @todo Add a general description for this controller.
 */
class NotesController extends BaseController
{
    /**
     * @var NotesController The reference to *Singleton* instance of this class
     */
    private static $instance;

    /**
     * Returns the *Singleton* instance of this class.
     * @return NotesController The *Singleton* instance.
     */
    public static function getInstance()
    {
        if (null === static::$instance) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * Returns all notes.
     *
     * @param  array  $options    Array with all options for search
     * @param integer  $options['userId']                      (optional) ID of the user whose notes to fetch. If
     *                                                         omitted, notes by all users will be returned.
     * @param string  $options['leadId']                       (optional) ID of the lead which notes to fetch. If
     *                                                         omitted, notes about all leads will be returned.
     * @param integer  $options['dealId']                      (optional) ID of the deal which notes to fetch. If
     *                                                         omitted, notes about all deals with be returned.
     * @param integer  $options['personId']                    (optional) ID of the person whose notes to fetch. If
     *                                                         omitted, notes about all persons with be returned.
     * @param integer  $options['orgId']                       (optional) ID of the organization which notes to fetch.
     *                                                         If omitted, notes about all organizations with be
     *                                                         returned.
     * @param integer  $options['start']                       (optional) Pagination start
     * @param integer  $options['limit']                       (optional) Items shown per page
     * @param string   $options['sort']                        (optional) Field names and sorting mode separated by a
     *                                                         comma (field_name_1 ASC, field_name_2 DESC). Only first-
     *                                                         level field keys are supported (no nested keys).
     *                                                         Supported fields: id, user_id, deal_id, person_id,
     *                                                         org_id, content, add_time, update_time.
     * @param DateTime $options['startDate']                   (optional) Date in format of YYYY-MM-DD from which notes
     *                                                         to fetch from.
     * @param DateTime $options['endDate']                     (optional) Date in format of YYYY-MM-DD until which
     *                                                         notes to fetch to.
     * @param int      $options['pinnedToLeadFlag']            (optional) If set, then results are filtered by note to
     *                                                         lead pinning state.
     * @param int      $options['pinnedToDealFlag']            (optional) If set, then results are filtered by note to
     *                                                         deal pinning state.
     * @param int      $options['pinnedToOrganizationFlag']    (optional) If set, then results are filtered by note to
     *                                                         organization pinning state.
     * @param int      $options['pinnedToPersonFlag']          (optional) If set, then results are filtered by note to
     *                                                         person pinning state.
     * @return mixed response from the API call
     * @throws APIException Thrown if API call fails
     */
    public function getAllNotes(
        $options
    ) {
        //check or get oauth token
        OAuthManager::getInstance()->checkAuthorization();

        //prepare query string for API call
        $_queryBuilder = '/notes';

        //process optional query parameters
        APIHelper::appendUrlWithQueryParameters($_queryBuilder, array (
            'user_id'                     => $this->val($options, 'userId'),
            'lead_id'                     => $this->val($options, 'leadId'),
            'deal_id'                     => $this->val($options, 'dealId'),
            'person_id'                   => $this->val($options, 'personId'),
            'org_id'                      => $this->val($options, 'orgId'),
            'start'                       => $this->val($options, 'start', 0),
            'limit'                       => $this->val($options, 'limit'),
            'sort'                        => $this->val($options, 'sort'),
            'start_date'                  => DateTimeHelper::toSimpleDate($this->val($options, 'startDate')),
            'end_date'                    => DateTimeHelper::toSimpleDate($this->val($options, 'endDate')),
            'pinned_to_lead_flag'         => $this->val($options, 'pinnedToLeadFlag'),
            'pinned_to_deal_flag'         => $this->val($options, 'pinnedToDealFlag'),
            'pinned_to_organization_flag' => $this->val($options, 'pinnedToOrganizationFlag'),
            'pinned_to_person_flag'       => $this->val($options, 'pinnedToPersonFlag'),
        ));

        //validate and preprocess url
        $_queryUrl = APIHelper::cleanUrl(Configuration::getBaseUri() . $_queryBuilder);

        //prepare headers
        $_headers = array (
            'user-agent'                => BaseController::USER_AGENT,
            'Accept'                    => 'application/json',
            'Authorization' => sprintf('Bearer %1$s', Configuration::$oAuthToken->accessToken)
        );

        //call on-before Http callback
        $_httpRequest = new HttpRequest(HttpMethod::GET, $_headers, $_queryUrl);
        if ($this->getHttpCallBack() != null) {
            $this->getHttpCallBack()->callOnBeforeRequest($_httpRequest);
        }

        //and invoke the API call request to fetch the response
        $response = Request::get($_queryUrl, $_headers);

        $_httpResponse = new HttpResponse($response->code, $response->headers, $response->raw_body);
        $_httpContext = new HttpContext($_httpRequest, $_httpResponse);

        //call on-after Http callback
        if ($this->getHttpCallBack() != null) {
            $this->getHttpCallBack()->callOnAfterRequest($_httpContext);
        }

        //handle errors defined at the API level
        $this->validateResponse($_httpResponse, $_httpContext);

        $mapper = $this->getJsonMapper();

        return $mapper->mapClass($response->body, 'Pipedrive\\Models\\GetNotes');
    }

    /**
     * Adds a new note.
     *
     * @param  array  $options    Array with all options for search
     * @param string  $options['content']                     Content of the note in HTML format. Subject to
     *                                                        sanitization on the back-end.
     * @param integer $options['userId']                      (optional) ID of the user who will be marked as the
     *                                                        author of this note. Only an admin can change the author.
     * @param string $options['leadId']                       (optional) ID of the lead the note will be attached to.
     * @param integer $options['dealId']                      (optional) ID of the deal the note will be attached to.
     * @param integer $options['personId']                    (optional) ID of the person this note will be attached to.
     * @param integer $options['orgId']                       (optional) ID of the organization this note will be
     *                                                        attached to.
     * @param string  $options['addTime']                     (optional) Optional creation date & time of the Note in
     *                                                        UTC. Can be set in the past or in the future. Requires
     *                                                        admin user API token. Format: YYYY-MM-DD HH:MM:SS
     * @param int     $options['pinnedToLeadFlag']            (optional) If set, then results are filtered by note to
     *                                                        lead pinning state (lead_id is also required).
     * @param int     $options['pinnedToDealFlag']            (optional) If set, then results are filtered by note to
     *                                                        deal pinning state (deal_id is also required).
     * @param int     $options['pinnedToOrganizationFlag']    (optional) If set, then results are filtered by note to
     *                                                        organization pinning state (org_id is also required).
     * @param int     $options['pinnedToPersonFlag']          (optional) If set, then results are filtered by note to
     *                                                        person pinning state (person_id is also required).
     * @return mixed response from the API call
     * @throws APIException Thrown if API call fails
     */
    public function addANote(
        $options
    ) {
        //check or get oauth token
        OAuthManager::getInstance()->checkAuthorization();

        //prepare query string for API call
        $_queryBuilder = '/notes';

        //validate and preprocess url
        $_queryUrl = APIHelper::cleanUrl(Configuration::getBaseUri() . $_queryBuilder);

        //prepare headers
        $_headers = array (
            'user-agent'                => BaseController::USER_AGENT,
            'Accept'                    => 'application/json',
            'Authorization' => sprintf('Bearer %1$s', Configuration::$oAuthToken->accessToken)
        );

        //prepare parameters
        $_parameters = array (
            'content'                     => $this->val($options, 'content'),
            'user_id'                     => $this->val($options, 'userId'),
            'lead_id'                     => $this->val($options, 'leadId'),
            'deal_id'                     => $this->val($options, 'dealId'),
            'person_id'                   => $this->val($options, 'personId'),
            'org_id'                      => $this->val($options, 'orgId'),
            'add_time'                    => $this->val($options, 'addTime'),
            'pinned_to_lead_flag'       => APIHelper::prepareFormFields($this->val($options, 'pinnedToLeadFlag')),
            'pinned_to_deal_flag'       => APIHelper::prepareFormFields($this->val($options, 'pinnedToDealFlag')),
            'pinned_to_organization_flag' => APIHelper::prepareFormFields($this->val($options, 'pinnedToOrganizationFlag')),
            'pinned_to_person_flag'     => APIHelper::prepareFormFields($this->val($options, 'pinnedToPersonFlag'))
        );

        //call on-before Http callback
        $_httpRequest = new HttpRequest(HttpMethod::POST, $_headers, $_queryUrl, $_parameters);
        if ($this->getHttpCallBack() != null) {
            $this->getHttpCallBack()->callOnBeforeRequest($_httpRequest);
        }

        //and invoke the API call request to fetch the response
        $response = Request::post($_queryUrl, $_headers, Request\Body::Form($_parameters));

        $_httpResponse = new HttpResponse($response->code, $response->headers, $response->raw_body);
        $_httpContext = new HttpContext($_httpRequest, $_httpResponse);

        //call on-after Http callback
        if ($this->getHttpCallBack() != null) {
            $this->getHttpCallBack()->callOnAfterRequest($_httpContext);
        }

        //handle errors defined at the API level
        $this->validateResponse($_httpResponse, $_httpContext);

        $mapper = $this->getJsonMapper();

        return $mapper->mapClass($response->body, 'Pipedrive\\Models\\PostNote');
    }

    /**
     * Deletes a specific note.
     *
     * @param integer $id ID of the note
     * @return mixed response from the API call
     * @throws APIException Thrown if API call fails
     */
    public function deleteANote(
        $id
    ) {
        //check or get oauth token
        OAuthManager::getInstance()->checkAuthorization();

        //prepare query string for API call
        $_queryBuilder = '/notes/{id}';

        //process optional query parameters
        $_queryBuilder = APIHelper::appendUrlWithTemplateParameters($_queryBuilder, array (
            'id' => $id,
            ));

        //validate and preprocess url
        $_queryUrl = APIHelper::cleanUrl(Configuration::getBaseUri() . $_queryBuilder);

        //prepare headers
        $_headers = array (
            'user-agent'    => BaseController::USER_AGENT,
            'Accept'        => 'application/json',
            'Authorization' => sprintf('Bearer %1$s', Configuration::$oAuthToken->accessToken)
        );

        //call on-before Http callback
        $_httpRequest = new HttpRequest(HttpMethod::DELETE, $_headers, $_queryUrl);
        if ($this->getHttpCallBack() != null) {
            $this->getHttpCallBack()->callOnBeforeRequest($_httpRequest);
        }

        //and invoke the API call request to fetch the response
        $response = Request::delete($_queryUrl, $_headers);

        $_httpResponse = new HttpResponse($response->code, $response->headers, $response->raw_body);
        $_httpContext = new HttpContext($_httpRequest, $_httpResponse);

        //call on-after Http callback
        if ($this->getHttpCallBack() != null) {
            $this->getHttpCallBack()->callOnAfterRequest($_httpContext);
        }

        //handle errors defined at the API level
        $this->validateResponse($_httpResponse, $_httpContext);

        $mapper = $this->getJsonMapper();

        return $mapper->mapClass($response->body, 'Pipedrive\\Models\\DeleteNote');
    }

    /**
     * Returns details about a specific note.
     *
     * @param integer $id ID of the note
     * @return mixed response from the API call
     * @throws APIException Thrown if API call fails
     */
    public function getOneNote(
        $id
    ) {
        //check or get oauth token
        OAuthManager::getInstance()->checkAuthorization();

        //prepare query string for API call
        $_queryBuilder = '/notes/{id}';

        //process optional query parameters
        $_queryBuilder = APIHelper::appendUrlWithTemplateParameters($_queryBuilder, array (
            'id' => $id,
            ));

        //validate and preprocess url
        $_queryUrl = APIHelper::cleanUrl(Configuration::getBaseUri() . $_queryBuilder);

        //prepare headers
        $_headers = array (
            'user-agent'    => BaseController::USER_AGENT,
            'Accept'        => 'application/json',
            'Authorization' => sprintf('Bearer %1$s', Configuration::$oAuthToken->accessToken)
        );

        //call on-before Http callback
        $_httpRequest = new HttpRequest(HttpMethod::GET, $_headers, $_queryUrl);
        if ($this->getHttpCallBack() != null) {
            $this->getHttpCallBack()->callOnBeforeRequest($_httpRequest);
        }

        //and invoke the API call request to fetch the response
        $response = Request::get($_queryUrl, $_headers);

        $_httpResponse = new HttpResponse($response->code, $response->headers, $response->raw_body);
        $_httpContext = new HttpContext($_httpRequest, $_httpResponse);

        //call on-after Http callback
        if ($this->getHttpCallBack() != null) {
            $this->getHttpCallBack()->callOnAfterRequest($_httpContext);
        }

        //handle errors defined at the API level
        $this->validateResponse($_httpResponse, $_httpContext);

        $mapper = $this->getJsonMapper();

        return $mapper->mapClass($response->body, 'Pipedrive\\Models\\PostNote');
    }

    /**
     * Updates a note.
     *
     * @param  array  $options    Array with all options for search
     * @param integer $options['id']                          ID of the note
     * @param string  $options['content']                     Content of the note in HTML format. Subject to
     *                                                        sanitization on the back-end.
     * @param integer $options['userId']                      (optional) ID of the user who will be marked as the
     *                                                        author of this note. Only an admin can change the author.
     * @param string $options['leadId']                       (optional) ID of the lead the note will be attached to.
     * @param integer $options['dealId']                      (optional) ID of the deal the note will be attached to.
     * @param integer $options['personId']                    (optional) ID of the person this note will be attached to.
     * @param integer $options['orgId']                       (optional) ID of the organization this note will be
     *                                                        attached to.
     * @param string  $options['addTime']                     (optional) Optional creation date & time of the Note in
     *                                                        UTC. Can be set in the past or in the future. Requires
     *                                                        admin user API token. Format: YYYY-MM-DD HH:MM:SS
     * @param int     $options['pinnedToLeadFlag']            (optional) If set, then results are filtered by note to
     *                                                        lead pinning state (lead_id is also required).
     * @param int     $options['pinnedToDealFlag']            (optional) If set, then results are filtered by note to
     *                                                        deal pinning state (deal_id is also required).
     * @param int     $options['pinnedToOrganizationFlag']    (optional) If set, then results are filtered by note to
     *                                                        organization pinning state (org_id is also required).
     * @param int     $options['pinnedToPersonFlag']          (optional) If set, then results are filtered by note to
     *                                                        person pinning state (person_id is also required).
     * @return mixed response from the API call
     * @throws APIException Thrown if API call fails
     */
    public function updateANote(
        $options
    ) {
        //check or get oauth token
        OAuthManager::getInstance()->checkAuthorization();

        //prepare query string for API call
        $_queryBuilder = '/notes/{id}';

        //process optional query parameters
        $_queryBuilder = APIHelper::appendUrlWithTemplateParameters($_queryBuilder, array (
            'id'                          => $this->val($options, 'id'),
            ));

        //validate and preprocess url
        $_queryUrl = APIHelper::cleanUrl(Configuration::getBaseUri() . $_queryBuilder);

        //prepare headers
        $_headers = array (
            'user-agent'                => BaseController::USER_AGENT,
            'Accept'                    => 'application/json',
            'Authorization' => sprintf('Bearer %1$s', Configuration::$oAuthToken->accessToken)
        );

        //prepare parameters
        $_parameters = array (
            'content'                     => $this->val($options, 'content'),
            'user_id'                     => $this->val($options, 'userId'),
            'lead_id'                     => $this->val($options, 'leadId'),
            'deal_id'                     => $this->val($options, 'dealId'),
            'person_id'                   => $this->val($options, 'personId'),
            'org_id'                      => $this->val($options, 'orgId'),
            'add_time'                    => $this->val($options, 'addTime'),
            'pinned_to_lead_flag'       => APIHelper::prepareFormFields($this->val($options, 'pinnedToLeadFlag')),
            'pinned_to_deal_flag'       => APIHelper::prepareFormFields($this->val($options, 'pinnedToDealFlag')),
            'pinned_to_organization_flag' => APIHelper::prepareFormFields($this->val($options, 'pinnedToOrganizationFlag')),
            'pinned_to_person_flag'     => APIHelper::prepareFormFields($this->val($options, 'pinnedToPersonFlag'))
        );

        //call on-before Http callback
        $_httpRequest = new HttpRequest(HttpMethod::PUT, $_headers, $_queryUrl, $_parameters);
        if ($this->getHttpCallBack() != null) {
            $this->getHttpCallBack()->callOnBeforeRequest($_httpRequest);
        }

        //and invoke the API call request to fetch the response
        $response = Request::put($_queryUrl, $_headers, Request\Body::Form($_parameters));

        $_httpResponse = new HttpResponse($response->code, $response->headers, $response->raw_body);
        $_httpContext = new HttpContext($_httpRequest, $_httpResponse);

        //call on-after Http callback
        if ($this->getHttpCallBack() != null) {
            $this->getHttpCallBack()->callOnAfterRequest($_httpContext);
        }

        //handle errors defined at the API level
        $this->validateResponse($_httpResponse, $_httpContext);

        $mapper = $this->getJsonMapper();

        return $mapper->mapClass($response->body, 'Pipedrive\\Models\\PostNote');
    }


    /**
    * Array access utility method
     * @param  array          $arr         Array of values to read from
     * @param  string         $key         Key to get the value from the array
     * @param  mixed|null     $default     Default value to use if the key was not found
     * @return mixed
     */
    private function val($arr, $key, $default = null)
    {
        if (isset($arr[$key])) {
            return is_bool($arr[$key]) ? var_export($arr[$key], true) : $arr[$key];
        }
        return $default;
    }
}
