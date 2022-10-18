<?php
namespace teik\Theme\Model;

class PricingCountry
{
  public $name = '';
  public $price = 0;
  public $counting = '';

  public function __construct($country, $price, $counting)
  {
    $this->setName($country);
    $this->setPrice($price);
    $this->setCounting($counting);
  }

  private function setName($country)
  {
    $this->name = $country;
  }

  private function setCounting($counting)
  {
    $this->counting = $counting;
  }

  private function setPrice($price)
  {
    if(!is_numeric($price)) {
      $price = 0;
      if(WP_DEBUG) {
        throw new \Exception('Price passed as string. Check your API.');
      }
    }
    $this->price = number_format($price, 3, ',', '.'). ' PLN';
  }
}