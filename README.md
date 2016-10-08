PHP wrapper pro práci s Ecomail.cz API

# Instalace

# Použití

```
$ecomail = new Ecomail('API_KEY');
$ecomail->getListsCollection();
```

# Seznam dostupných metod

`Všechny metody mají návratový typ: array stdClass string`

### getListsCollection

### addListCollection

### showList

### updateList

### getSubscribers

### getSubscriber

### addSubscriber

### removeSubscriber

### updateSubscriber

### addSubscriberBulk

### listCampaigns

### addCampaign

### updateCampaign

### sendCampaign

### listAutomations

### createTemplate

### listDomains

### createDomain

### deleteDomain

### sendTransactionalEmail

### sendTransactionalTemplate

### createNewTransaction

