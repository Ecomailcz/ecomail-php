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


    /**
     * Konstruktor
     *
     * @param string $key Klíč API
     * @param string $response Návratový typ
     * @param string $server Server API
     */
    public function __construct($key, $response = self::JSONArray, $server = 'https://api2.ecomailapp.cz')
    {
        $this->key = $key;
        $this->server = $server;
        $this->response = $response;
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


    // === Campaigns ===

    /**
     * @param string $filters Filtr
     * @return array|stdClass|string
     */
    public function listCampaigns($filters = null)
    {
        $url = $this->joinString('campaigns');
        if (!is_null($filters)) {
            $url = $this->joinString($url, '?filters=', $filters);
        }
        return $this->get($url);
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


    // === Automation ===

    /**
     * @return array|stdClass|string
     */
    public function listAutomations()
    {
        $url = $this->joinString('automation');
        return $this->get($url);
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
     * @return  array|stdClass|string
     */
    public function sendTransactionalEmail(array $data)
    {
        $url = $this->joinString('transactional/send-message');
        return $this->post($url, $data);
    }


    /**
     * @param array $data Data
     * @return  array|stdClass|string
     */
    public function sendTransactionalTemplate(array $data)
    {
        $url = $this->joinString('transactional/send-template');
        return $this->post($url, $data);
    }


    // === Tracker ===

    /**
     * @param array $data Data
     * @return  array|stdClass|string
     */
    public function createNewTransaction(array $data)
    {
        $url = $this->joinString('tracker/transaction');
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
	 * @param array $data Data
	 *
	 * @return array|stdClass|string
	 */
	public function addEvent(array $data)
	{
		$url = $this->joinString('tracker/events');
		return $this->post($url, $data);
	}

	// === Automations ===

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
     * @return  array|stdClass|string
     */
    private function get($request)
    {
        return $this->send($request, null, 'get');
    }


    /**
     * Pomocná metoda pro POST
     *
     * @param string $request Požadavek
     * @param array $data Zaslaná data
     * @return  array|stdClass|string
     */
    private function post($request, array $data)
    {
        return $this->send($request, $data, 'post');
    }


    /**
     * Pomocná metoda pro PUT
     *
     * @param string $request Požadavek
     * @param array $data Zaslaná data
     * @return  array|stdClass|string
     */
    private function put($request, array $data = array())
    {
        return $this->send($request, $data, 'put');
    }


    /**
     * Pomocná metoda pro DELETE
     *
     * @param string $request Požadavek
     * @param array $data
     * @return  array|stdClass|string
     */
    private function delete($request, array $data = array())
    {
        return $this->send($request, $data, 'delete');
    }

    /**
     * Odeslání požadavku
     *
     * @param string $request Požadavek
     * @param null|array $data Zaslaná data
     * @param null|string $method Metoda (GET, POST, DELETE, PUT)
     * @return  array|stdClass|string
     */
    private function send($request, $data = null, $method = null)
    {
        $urlRequest = $this->server . '/' . $request;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $urlRequest);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        if (!is_null($method)) {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));
        }

        if (is_array($data)) {
            $options = 0 | (PHP_VERSION_ID >= 70300 ? JSON_THROW_ON_ERROR : 0);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data, $options));
        }

        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'key: ' . $this->key,
                'Content-Type: application/json'
            )
        );

        $output = curl_exec($ch);

        // Check HTTP status code
        if (!curl_errno($ch)) {
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $content_type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
            $error_message_is_json = $content_type === 'application/json';
            if ($error_message_is_json) {
                $output_decoded = json_decode($output, null, $options);
            }
            if ($http_code < 200 || $http_code > 299) {
                return array(
                    'error' => $http_code,
                    'message' => $output_decoded,
                );
            }
        }

        curl_close($ch);

        switch ($this->response) {
            case self::JSONArray:
            case self::JSONObject:
                if (is_array(json_decode($output, true))) {
                    $output = json_decode($output, $this->response !== self::JSONObject);
                }
                break;
        }

        return $output;
    }
}
