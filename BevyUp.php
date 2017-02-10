<?php
class BevyUpFactory {

  private $order;
  private $url;

  public function __construct($order) {

    $this->order = $order;

    $this->processOrder();
  }

  private function processOrder() {

    $bevyInfo = [
      'email' => $this->order->getCustomerEmail(),
      'transactionId' => $this->order->getRealOrderId(),
      'items' => [],
      'affiliation' => 'online',
      'total' => $this->order->getGrandTotal(),
      'tax' => $this->order->getBaseTaxAmount()
    ];

    foreach ($this->order->getAllItems() as $item) {

      $bevyProduct = [
        'productId' => $item->getId(),
        'sku' => $item->getSku(),
        'quantity' => $item->getQtyOrdered(),
        'price' => $item->getPrice()
      ];

      $bevyInfo['items'][] = $bevyProduct;
    }

    $this->url = urlencode(json_encode($bevyInfo));


  }

  public function getUrl() {

    return 'https://b.bevyup.com/m/rvs?C=7&N=7&d=PARTNER_ID&_ss1data=' . $this->url;
  }

}

Class BevyUp {

  public function __construct() {

  }

  /**
   * @param $order
   * @return mixed
   */
  public static function process($order) {
    $bevyUp = new BevyUpFactory($order);

    return $bevyUp->getUrl();

  }

}