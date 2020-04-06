# PHP wrapper pro práci s Ecomail.cz API

[![Build Status](https://travis-ci.org/Ecomailcz/ecomail-php.svg?branch=master)](https://travis-ci.org/Ecomailcz/ecomail-php)
[![Downloads this Month](https://img.shields.io/packagist/dm/ecomailcz/ecomail.svg)](https://packagist.org/packages/ecomailcz/ecomail)

# Instalace

```
composer require ecomailcz/ecomail
```

# Použití

```
$ecomail = new Ecomail('API_KEY');
$ecomail->getListsCollection();
```

API klíč naleznete v nastavení vašeho účtu v sekci integrace.

# Seznam dostupných metod

Všechny metody mají návratový typ: `array stdClass string`

Pro více informací prosím navštivte dokumentaci naší API: https://ecomailczv2.docs.apiary.io/

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

### triggerAutomation
