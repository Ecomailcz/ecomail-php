<?php

/**
 * PHP knihovna pro přístup k API
 *
 * @author Filip Šedivý <mail@filipsedivy.cz>
 * @version 1.1
 */
class Ecomail
{
    const JSONObject = 'jsono';
    const JSONArray = 'jsona';
    const PlainText = 'plaintext';


    /** @var string $key Klíč API */
    private $key;

    /** @var string $server Server API */
    private $server;

    /** @var string $response Návratový typ */
    private $response;

    /** @var array $query Query parametry pro všechna API volání */
    private $query;


    /**
     * Konstruktor
     *
     * @param string $key Klíč API
     * @param string $response Návratový typ
     * @param string $server Server API
     * @param array $query Query parametry pro všechna API volání
     */
    public function __construct(
        $key,
        $response = self::JSONArray,
        $server = 'https://api2.ecomailapp.cz',
        array $query = array()
    ) {
        $this->key = $key;
        $this->server = $server;
        $this->response = $response;
        $this->query = $query;
    }


    // === Modifiers ===

    /**
     * Vytvoří novou instanci služby pro volání API se zadaným query parametrem.
     *
     * @param string $key Název query parametru
     * @param string|int|null $value Hodnota quoery parametru, pro smazání parametru zadejte `null`
     * @return $this
     */
    public function withQuery($key, $value)
    {
        $params = $this->query;

        if ($value === null) {
            unset($params[$key]);
        } else {
            $params[$key] = $value;
        }
        return new static($this->key, $this->response, $this->server, $params);
    }


    /**
     * Vytvoří novou instanci služby pro čtení příslušné stránky z API
     *
     * @param int $page Číslo stránky
     * @return $this
     */
    public function page($page)
    {
        return $this->withQuery('page', $page);
    }


    // === Lists ===

    /**
     * Práce se seznamy kontaktů a s přihlášenými odběrateli
     * @return array|stdClass|string
     */
    public function getListsCollection()
    {
        return $this->get('lists');
    }


    /**
     * Vložení nového seznamu kontaktů
     * @param array $data Data
     * @return array|stdClass|string
     */
    public function addListCollection(array $data)
    {
        return $this->post('lists', $data);
    }


    /**
     * @param string $list_id ID listu
     * @return array|stdClass|string
     */
    public function showList($list_id)
    {
        $url = $this->joinString('lists/', $list_id);
        return $this->get($url);
    }


    /**
     * @param string $list_id ID listu
     * @param array $data Data
     * @return array|stdClass|string
     */
    public function updateList($list_id, array $data)
    {
        $url = $this->joinString('lists/', $list_id);
        return $this->put($url, $data);
    }


    /**
     * @param string $list_id ID listu
     * @return array|stdClass|string
     */
    public function getSubscribers($list_id)
    {
        $url = $this->joinString('lists/', $list_id, '/subscribers');
        return $this->get($url);
    }


    /**
     * @param string $list_id ID listu
     * @param string $email Email
     * @return array|stdClass|string
     */
    public function getSubscriber($list_id, $email)
    {
        $url = $this->joinString('lists/', $list_id, '/subscriber/', $email);
        return $this->get($url);
    }


    /**
     * @param string $email Email
     * @return array|stdClass|string
     */
    public function getSubscriberList($email)
    {
        $url = $this->joinString('subscribers/', $email);
        return $this->get($url);
    }


    /**
     * @param string $list_id ID listu
     * @param array $data Data
     * @return array|stdClass|string
     */
    public function addSubscriber($list_id, array $data)
    {
        $url = $this->joinString('lists/', $list_id, '/subscribe');
        return $this->post($url, $data);
    }


    /**
     * @param string $list_id ID listu
     * @param array $data
     * @return array|stdClass|string
     */
    public function removeSubscriber($list_id, array $data)
    {
        $url = $this->joinString('lists/', $list_id, '/unsubscribe');
        return $this->delete($url, $data);
    }


    /**
     * @param string $list_id ID listu
     * @param array $data Data
     * @return array|stdClass|string
     */
    public function updateSubscriber($list_id, array $data)
    {
        $url = $this->joinString('lists/', $list_id, '/update-subscriber');
        return $this->put($url, $data);
    }


    /**
     * @param string $list_id ID listu
     * @param array $data Data
     * @return array|stdClass|string
     */
    public function addSubscriberBulk($list_id, array $data)
    {
        $url = $this->joinString('lists/', $list_id, '/subscribe-bulk');
        return $this->post($url, $data);
    }


    // === Subscribers ===

    /**
     * Remove subscriber from DB (all lists).
     *
     * @param string $email Email
     * @return array|stdClass|string
     */
    public function deleteSubscriber($email)
    {
        $url = $this->joinString('subscribers/', $email, '/delete');
        return $this->delete($url);
    }


    /**
     * Get subscriber information by email (global)
     *
     * @param string $email Email of the subscriber
     * @return array|stdClass|string API response
     */
    public function getSubscriberByEmail($email)
    {
        $url = $this->joinString('subscribers/', $email);
        return $this->get($url);
    }

    // === Campaigns ===

    /**
     * @param string|null $filters Filtr
     * @return array|stdClass|string
     */
    public function listCampaigns($filters = null)
    {
        $url = $this->joinString('campaigns');

        $query = array();
        if (!is_null($filters)) {
            $query = array('filters' => $filters);
        }

        return $this->get($url, $query);
    }


    /**
     * @param array $data Data
     * @return array|stdClass|string
     */
    public function addCampaign(array $data)
    {
        $url = $this->joinString('campaigns');
        return $this->post($url, $data);
    }


    /**
     * @param int $campaign_id ID kampaně
     * @param array $data Data
     * @return array|stdClass|string
     */
    public function updateCampaign($campaign_id, array $data)
    {
        $url = $this->joinString('campaigns/', $campaign_id);
        return $this->put($url, $data);
    }


    /**
     * Toto volání okamžitě zařadí danou kampaň do fronty k odeslání.
     * Tuto akci již nelze vrátit zpět.
     *
     * @param int $campaign_id ID kampaně
     * @return array|stdClass|string
     */
    public function sendCampaign($campaign_id)
    {
        $url = $this->joinString('campaign/', $campaign_id, '/send');
        return $this->get($url);
    }


    /**
     * Získejte statistiku odeslané kampaně.
     *
     * @param int $campaign_id ID kampaně
     * @return array|stdClass|string
     */
    public function getCampaignStats($campaign_id)
    {
        $url = $this->joinString('campaigns/', $campaign_id, '/stats');
        return $this->get($url);
    }

    /**
     * Get detailed campaign statistics by campaign ID and optional query parameters.
     *
     * @param int $campaignId Campaign ID number
     * @param array $queryParams Optional query parameters
     * @return array|stdClass|string API response
     */
    public function getCampaignStatsDetail($campaignId, $queryParams = array())
    {
        // Construct the URL for the API endpoint
        $url = $this->joinString('campaigns/', $campaignId, '/stats-detail');

        // Construct the query parameters
        $query = array();
        if (!empty($queryParams)) {
            $query = $queryParams;
        }

        // Make the API request using the 'get' method
        return $this->get($url, $query);
    }


    // === Automation ===

    /**
     * @return array|stdClass|string
     */
    public function listAutomations()
    {
        $url = $this->joinString('pipelines');
        return $this->get($url);
    }


    /**
     * @param string $automation_id ID automatizace
     * @param array $data Data
     * @return array|stdClass|string
     */
    public function triggerAutomation($automation_id, array $data)
    {
        $url = $this->joinString('pipelines/', $automation_id, '/trigger');
        return $this->post($url, $data);
    }


    /**
     * Get statistics for an automation pipeline by its ID.
     *
     * @param string $pipelineId ID of the automation pipeline
     * @return array|stdClass|string API response
     */
    public function getPipelineStats($pipelineId)
    {
        // Construct the URL for the API endpoint
        $url = $this->joinString('pipelines/', $pipelineId, '/stats');
        
        // Make the API request using the 'get' method
        return $this->get($url);
    }


    /**
     * Get detailed statistics for an automation pipeline by its ID and optional query parameters.
     *
     * @param string $pipelineId ID of the automation pipeline
     * @param array $queryParams Optional query parameters
     * @return array|stdClass|string API response
     */
    public function getPipelineStatsDetail($pipelineId, $queryParams = array())
    {
        // Construct the URL for the API endpoint
        $url = $this->joinString('pipelines/', $pipelineId, '/stats-detail');
        
        // Construct the query parameters
        $query = array();
        if (!empty($queryParams)) {
            $query = $queryParams;
        }

        // Make the API request using the 'get' method
        return $this->get($url, $query);
    }


    // === Templates ===

    /**
     * @param array $data Data
     * @return array|stdClass|string
     */
    public function createTemplate(array $data)
    {
        $url = $this->joinString('template');
        return $this->post($url, $data);
    }


    // === Domains ===

    /**
     * @return array|stdClass|string
     */
    public function listDomains()
    {
        $url = $this->joinString('domains');
        return $this->get($url);
    }


    /**
     * @param array $data Data
     * @return array|stdClass|string
     */
    public function createDomain(array $data)
    {
        $url = $this->joinString('domains');
        return $this->post($url, $data);
    }


    /**
     * @param int $id ID domény
     * @return array|stdClass|string
     */
    public function deleteDomain($id)
    {
        $url = $this->joinString('domains/', $id);
        return $this->delete($url);
    }


    // ===  Transakční e-maily ===

    /**
     * @param array $data Data
     * @return array|stdClass|string
     */
    public function sendTransactionalEmail(array $data)
    {
        $url = $this->joinString('transactional/send-message');
        return $this->post($url, $data);
    }


    /**
     * @param array $data Data
     * @return array|stdClass|string
     */
    public function sendTransactionalTemplate(array $data)
    {
        $url = $this->joinString('transactional/send-template');
        return $this->post($url, $data);
    }


    /**
     * Get statistics for transactional emails.
     *
     * @return array|stdClass|string API response
     */
    public function getTransactionalStats()
    {
        $url = $this->joinString('transactional/stats');
        return $this->get($url);
    }


    /**
     * Get statistics for double opt-in (DOI) transactional emails.
     *
     * @return array|stdClass|string API response
     */
    public function getTransactionalStatsDOI()
    {
        $url = $this->joinString('transactional/stats/doi');
        return $this->get($url);
    }


    // === Transactions ===

    /**
     * @param array $data Data
     * @return array|stdClass|string
     */
    public function createNewTransaction(array $data)
    {
        $url = $this->joinString('tracker/transaction');
        return $this->post($url, $data);
    }


    /**
     * @param array $data Data
     * @return array|stdClass|string
     */
    public function createBulkTransactions(array $data)
    {
        $url = $this->joinString('tracker/transaction-bulk');
        return $this->post($url, $data);
    }


    /**
     * @param string $transaction_id ID transakce
     * @param array $data Data
     * @return array|stdClass|string
     */
    public function updateTransaction($transaction_id, array $data)
    {
        $url = $this->joinString('tracker/transaction/', $transaction_id);
        return $this->put($url, $data);
    }


    /**
     * @param string $transaction_id ID transakce
     * @return array
     */
    public function deleteTransaction($transaction_id)
    {
        $url = $this->joinString('tracker/transaction/', $transaction_id, '/delete');
        return $this->delete($url);
    }


    // === Feeds ===

    /**
     * Refresh a product feed by its ID.
     *
     * @param string $feedId ID of the product feed
     * @return array|stdClass|string API response
     */
    public function refreshProductFeed($feedId)
    {
        $url = $this->joinString('feeds/', $feedId, '/refresh');
        return $this->get($url);
    }

    /**
     * Refresh a data feed by its ID.
     *
     * @param string $feedId ID of the data feed
     * @return array|stdClass|string API response
     */
    public function refreshDataFeed($feedId)
    {
        $url = $this->joinString('data-feeds/', $feedId, '/refresh');
        return $this->get($url);
    }


    // === Tracker ===

    /**
     * @param array $data Data
     *
     * @return array|stdClass|string
     */
    public function addEvent(array $data)
    {
        $url = $this->joinString('tracker/events');
        return $this->post($url, $data);
    }

    // === Search ===


    /**
     * Perform a search using the specified query.
     *
     * @param string $query Search query
     * @return array|stdClass|string API response
     */
    public function search($query)
    {
        $url = $this->joinString('search');
        $data = array('query' => $query);
        return $this->post($url, $data);
    }


    // === Discount coupons ===


    /**
     * Import coupons using the provided data.
     *
     * @param array $data Data for coupon import
     * @return array|stdClass|string API response
     */
    public function importCoupons(array $data)
    {
        $url = $this->joinString('coupons/import');
        return $this->post($url, $data);
    }


    /**
     * Delete coupons using the provided data.
     *
     * @param array $data Data for coupon deletion
     * @return array|stdClass|string API response
     */
    public function deleteCoupons(array $data)
    {
        $url = $this->joinString('coupons/delete');
        return $this->delete($url, $data);
    }


    /**
     * Spojování textu
     *
     * @return string
     */
    private function joinString()
    {
        $join = "";
        foreach (func_get_args() as $arg) {
            $join .= $arg;
        }
        return $join;
    }


    // === cURL ===

    /**
     * Pomocná metoda pro GET
     *
     * @param string $request Požadavek
     * @param array $query Query data dotazu
     * @return array|stdClass|string
     */
    private function get($request, array $query = array())
    {
        return $this->send($request, null, 'get', $query);
    }


    /**
     * Pomocná metoda pro POST
     *
     * @param string $request Požadavek
     * @param array $data Zaslaná data
     * @param array $query Query data dotazu
     * @return array|stdClass|string
     */
    private function post($request, array $data, array $query = array())
    {
        return $this->send($request, $data, 'post', $query);
    }


    /**
     * Pomocná metoda pro PUT
     *
     * @param string $request Požadavek
     * @param array $data Zaslaná data
     * @param array $query Query data dotazu
     * @return array|stdClass|string
     */
    private function put($request, array $data = array(), array $query = array())
    {
        return $this->send($request, $data, 'put', $query);
    }


    /**
     * Pomocná metoda pro DELETE
     *
     * @param string $request Požadavek
     * @param array $data
     * @param array $query Query data dotazu
     * @return array|stdClass|string
     */
    private function delete($request, array $data = array(), array $query = array())
    {
        return $this->send($request, $data, 'delete', $query);
    }

    /**
     * Odeslání požadavku
     *
     * @param string $request Požadavek
     * @param null|array $data Zaslaná data
     * @param null|string $method Metoda (GET, POST, DELETE, PUT)
     * @param array $query Query data dotazu
     * @return array|stdClass|string
     */
    private function send($request, $data, $method, array $query)
    {
        /** @noinspection AdditionOperationOnArraysInspection */
        $query += $this->query;
        $urlRequest = $this->server . '/' . $request . '?' . http_build_query($query);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $urlRequest);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        if (!is_null($method)) {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));
        }

        $json_options = 0 | (PHP_VERSION_ID >= 70300 ? JSON_THROW_ON_ERROR : 0);

        if (is_array($data)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data, $json_options));
        }

        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'key: ' . $this->key,
                'Content-Type: application/json'
            )
        );

        $raw_output = $output = curl_exec($ch);

        // Check HTTP status code
        if (!curl_errno($ch)) {
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $content_type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
            $error_message_is_json = $content_type === 'application/json';
            if ($error_message_is_json) {
                $output = json_decode($raw_output, null, 512, $json_options);
            }
            if ($http_code < 200 || $http_code > 299) {
                return array(
                    'error' => $http_code,
                    'message' => $output,
                );
            }
        }

        curl_close($ch);

        switch ($this->response) {
            case self::JSONArray:
            case self::JSONObject:
                if (is_array(json_decode($raw_output, true))) {
                    $raw_output = json_decode($raw_output, $this->response !== self::JSONObject);
                }
                break;
        }

        return $raw_output;
    }
}
