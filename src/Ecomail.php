<?php

/**
 * PHP knihovna pro přístup k API
 *
 * @author Filip Šedivý <mail@filipsedivy.cz>
 * @version 1.1
 */
class Ecomail
{


    const   JSONObject = 'jsono',
        JSONArray = 'jsona',
        PlainText = 'plaintext';

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
     * @return Ecomail
     */
    public function __construct($key, $response = self::JSONArray, $server = 'http://api2.ecomailapp.cz')
    {
        $this->key = $key;
        $this->server = $server;
        $this->response = $response;
        return $this;
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
    public function getSubscribers($list_id){
        $url = $this->joinString('lists/', $list_id, '/subscribers');
        return $this->get($url);
    }


    /**
     * @param string $list_id ID listu
     * @param string $email Email
     * @return array|stdClass|string
    */
    public function getSubscriber($list_id, $email){
        $url = $this->joinString('lists/', $list_id, '/subscriber/', $email);
        return $this->get($url);
    }


    /**
     * @param string $list_id ID listu
     * @param array $data Data
     * @return array|stdClass|string
     */
    public function addSubscriber($list_id, array $data){
        $url = $this->joinString('lists/', $list_id, '/subscribe');
        return $this->post($url, $data);
    }


    /**
     * @param string $list_id ID listu
     * @return array|stdClass|string
     */
    public function removeSubscriber($list_id, array $data){
        $url = $this->joinString('lists/', $list_id, '/unsubscribe');
        return $this->delete($url, $data);
    }


    /**
     * @param string $list_id ID listu
     * @param array $data Data
     * @return array|stdClass|string
     */
    public function updateSubscriber($list_id, array $data){
        $url = $this->joinString('lists/', $list_id, '/update-subscriber');
        return $this->put($url, $data);
    }


    /**
     * @param string $list_id ID listu
     * @param array $data Data
     * @return array|stdClass|string
     */
    public function addSubscriberBulk($list_id, array $data){
        $url = $this->joinString('lists/', $list_id, '/subscribe-bulk');
        return $this->post($url, $data);
    }


    // === Campaigns ===


    /**
     * @param string $filters Filtr
     * @return array|stdClass|string
     */
    public function listCampaigns($filters = NULL){
        $url = $this->joinString('campaigns');
        if(!is_null($filters)){
            $url = $this->joinString($url, '?filters=', $filters);
        }
        return $this->get($url);
    }


    /**
     * @param array $data Data
     * @return array|stdClass|string
     */
    public function addCampaign(array $data){
        $url = $this->joinString('campaigns');
        return $this->post($url, $data);
    }


    /**
     * @param int $campaign_id ID kampaně
     * @param array $data Data
     * @return array|stdClass|string
     */
    public function updateCampaign($campaign_id, array $data){
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
    public function sendCampaign($campaign_id){
        $url = $this->joinString('campaign/', $campaign_id, '/send');
        return $this->get($url);
    }


    // === Reports ===


    // === Automation ===

    /**
     * @return array|stdClass|string
     */
    public function listAutomations(){
        $url = $this->joinString('automation');
        return $this->get($url);
    }


    // === Templates ===

    /**
     * @param array $data Data
     * @return array|stdClass|string
     */
    public function createTemplate(array $data){
        $url = $this->joinString('template');
        return $this->post($url, $data);
    }


    // === Domains ===

    /**
     * @return array|stdClass|string
     */
    public function listDomains(){
        $url = $this->joinString('domains');
        return $this->get($url);
    }


    /**
     * @param array $data Data
     * @return array|stdClass|string
     */
    public function createDomain(array $data){
        $url = $this->joinString('domains');
        return $this->post($url);
    }


    /**
     * @param int     $id     ID domény
     * @return array|stdClass|string
     */
    public function deleteDomain($id){
        $url = $this->joinString('domains/', $id);
        return $this->delete($url);
    }


    // ===  Transakční e-maily ===

    /**
     * @param   array   $data   Data
     * @return  array|stdClass|string
     */
    public function sendTransactionalEmail(array $data){
        $url = $this->joinString('transactional/send-message');
        return $this->post($url, $data);
    }


    /**
     * @param   array   $data   Data
     * @return  array|stdClass|string
     */
    public function sendTransactionalTemplate(array $data){
        $url = $this->joinString('transactional/send-template');
        return $this->post($url, $data);
    }


    // === Tracker ===


    /**
     * @param   array   $data   Data
     * @return  array|stdClass|string
     */
    public function createNewTransaction(array $data){
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


    // === Automations ===


    /**
     * @param string $automation_id ID automatizace
     * @param array $data Data
     * @return array|stdClass|string
     */
    public function triggerAutomation($automation_id, array $data){
        $url = $this->joinString('pipelines/', $automation_id, '/trigger');
        return $this->post($url, $data);
    }



    /**
     * Spojování textu
     *
     * @return string
    */
    private function joinString(){
        $join = "";
        for($i = 0; $i < func_num_args(); $i++){
            $join .= func_get_arg($i);
        }
        return $join;
    }


    // === cURL ===


    /**
     * Pomocná metoda pro GET
     *
     * @param   string  $request    Požadavek
     * @return  array|stdClass|string
     */
    private function get($request)
    {
        return $this->send($request);
    }


    /**
     * Pomocná metoda pro POST
     *
     * @param   string      $request    Požadavek
     * @param   null|array  $data       Zaslaná data
     * @return  array|stdClass|string
     */
    private function post($request, array $data)
    {
        return $this->send($request, $data);
    }


    /**
     * Pomocná metoda pro PUT
     *
     * @param   string      $request    Požadavek
     * @param   null|array  $data       Zaslaná data
     * @return  array|stdClass|string
     */
    private function put($request, array $data = []){
        return $this->send($request, $data, 'put');
    }


    /**
     * Pomocná metoda pro DELETE
     *
     * @param   string  $request Požadavek
     * @return  array|stdClass|string
     */
    private function delete($request, array $data = []){
        return $this->send($request, $data, 'delete');
    }

    /**
     * Odeslání požadavku
     *
     * @param   string          $request Požadavek
     * @param   null|array      $data Zaslaná data
     * @param   null|string     $method Metoda (GET, POST, DELETE, PUT)
     * @return  array|stdClass|string
     */
    private function send($request, $data = NULL, $method = NULL)
    {
        $urlRequest = $this->server . '/' . $request;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $urlRequest);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        if(!is_null($method)){
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));
        }

        if (is_array($data)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        }

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'key: ' . $this->key
        ));

        $output = curl_exec($ch);

        curl_close($ch);

        switch ($this->response) {
            case self::JSONArray:
            case self::JSONObject:
                if (is_object(json_decode($output))) {
                    $output = json_decode($output, $this->response == self::JSONObject ? false : true);
                }
                break;
        }

        return $output;
    }

}
