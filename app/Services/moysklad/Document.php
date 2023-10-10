<?php

namespace App\moysklad;

use App\App;

class Document extends Client
{
  public function getOrganisations()
  {
    return $this->execute('entity/organization');
  }

  public function createIncomingPayment($name, $agent, $organization, $sum)
  {
    $data = [
      'name' => $name,
      'agent' => (new MetaData([
        'id' => $agent,
        'scope' => $this->baseUrl . 'entity/counterparty',
        'type' => 'counterparty'
      ]))->getAsArray(),
      'organization' => (new MetaData([
        'id' => $organization,
        'scope' => $this->baseUrl . 'entity/organization',
        'type' => 'organization'
      ]))->getAsArray(),
      'sum' => $sum
    ];
    return $this->download('entity/paymentin', 'POST', $data);
  }
}