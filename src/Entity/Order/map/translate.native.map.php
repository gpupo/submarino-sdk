<?php

declare(strict_types=1);

/*
 * This file is part of gpupo/submarino-sdk
 * Created by Gilmar Pupo <contact@gpupo.com>
 * For the information of copyright and license you should read the file
 * LICENSE which is distributed with this source code.
 * Para a informação dos direitos autorais e de licença você deve ler o arquivo
 * LICENSE que é distribuído com este código-fonte.
 * Para obtener la información de los derechos de autor y la licencia debe leer
 * el archivo LICENSE que se distribuye con el código fuente.
 * For more information, see <https://opensource.gpupo.com/>.
 *
 */

 use Gpupo\CommonSchema\ArrayCollection\People;
 use Gpupo\CommonSchema\ArrayCollection\Trading;
 use Gpupo\CommonSchema\ArrayCollection\Trading\Order\Shipping as TOS;
 use Gpupo\CommonSchema\TranslatorException;


dump($native);

foreach([
    'channel',
] as $k) {
    $v = $native->get($k);
    if (empty($v)) {
        throw new TranslatorException(sprintf("Conversion failed. order::%s cannot be empty", $k));
    }
}


$quantity = 0;
$items = $native['order_items'];
$acceptedOffer = [];
foreach ($items as $item) {
    if (is_array($item)) {
        $quantity += $item['quantity'];
        $acceptedOffer[] = [
            'itemOffered' => [
                'sku' => $item['item']['id'],
            ],
            'quantity' => $item['quantity'],
            'price' => $item['unit_price'],
        ];
    }
}

$dateTime = new DateTime($native['date_created']);

$translateStatus = function ($status) {
    $string = strtoupper($status);
    $list = include __DIR__.'/status.map.php';
    $find = array_search($string, $list, true);

    return empty($find) ? $string : $find;
};

return [
    'merchant' => [
        'name' => 'B2W',
        'marketplace' => 'B2W',
        'originNumber' => '',
    ],
    'orderNumber' => $native->getId(),
    'acceptedOffer' => $acceptedOffer,
    'orderStatus' => $translateStatus($native->getStatus()),
    'orderDate' => $dateTime->format('Y-m-d H:i:s'),
    'customer' => [
        'document' => $native['buyer']['billing_info']['doc_number'],
        'name' => $native['buyer']['first_name'].' '.$native['buyer']['last_name'],
        'telephone' => '('.$native['buyer']['phone']['area_code'].') '.$native['buyer']['phone']['number'],
        'email' => $native['buyer']['email'],
    ],
    'billingAddress' => [
        'streetAddress' => $native->getShipping()['receiver_address']['street_name'],
        'addressComplement' => $native->getShipping()['receiver_address']['comment'],
        'addressReference' => '',
        'addressNumber' => $native->getShipping()['receiver_address']['street_number'],
        'addressLocality' => $native->getShipping()['receiver_address']['city']['name'],
        'addressRegion' => str_replace('BR-', '', $native->getShipping()['receiver_address']['state']['id']),
        'addressNeighborhood' => '',
        'postalCode' => $native->getShipping()['receiver_address']['zip_code'],
    ],
    'currency' => 'BRL',
    'price' => $native['total_amount'],
    'discount' => 0,
    'quantity' => $quantity,
    'freight' => '',
    'freightType' => (182 === (int) $native->getShipping()['shipping_option']['shipping_method_id']) ? 'EXPRESS' : 'NORMAL',
    'total' => $native['total_amount'],
];

//////////////////////////
$shipping = new TOS\Shipping();
$shipping->set('currency_id', 'BRL');
$shipping->set('date_created', $item['placed_at']);
$shipping->set('total_payments_amount', $item['total_ordered']);
$shipping->set('shipping_number', (int) $item['import_info']['remote_code']);
$shipping->set('total_payments_fee_amount', $item['shipping_cost']);
$shipping->set('total_payments_net_amount', $item['total_ordered'] - $item['shipping_cost']);

$paymentCollection = new TOS\Payment\Collection();
foreach ($item['expands']['payments'] as $pay) {
    $payment = new TOS\Payment\Payment();
    $payment->set('authorization_code', $pay['autorization_id']);
    $payment->set('collector', $item['channel']);
    $payment->set('currency_id', 'BRL');
    $payment->set('installments', $pay['parcels']);
    $payment->set('operation_type', $pay['description']);
    $payment->set('payment_method_id', $pay['card_issuer']);
    $payment->set('payment_number', (int) $item['import_info']['remote_code']);
    $payment->set('payment_type', $pay['sefaz']['payment_indicator']);
    $payment->set('shipping_cost', 0);
    $payment->set('status', $pay['status']);
    $payment->set('date_approved', $pay['transaction_date']);
    $payment->set('date_created', $pay['transaction_date']);
    $payment->set('date_last_modified', $pay['transaction_date']);
    $payment->set('total_paid_amount', $pay['value']);
    $payment->set('transaction_amount', $pay['value']);
    $payment->set('transaction_net_amount', $pay['value']);

    $paymentCollection->add($payment);
}
$shipping->set('payment', $paymentCollection);

$quantity = 0;
foreach ($item['expands']['items'] as $i) {
    $quantity += $i['qty'];
}

$shipping->set('total_quantity', $quantity);
$shipping->set('status', $this->validateState($item['status']['type']));

$productCollection = new TOS\Product\Collection();
foreach ($item['expands']['items'] as $i) {
    $product = new TOS\Product\Product();
    $product->set('quantity', $i['qty']);
    $product->set('seller_product_id', $i['id']);
    $product->set('title', $i['name']);
    $product->set('unit_price', $i['special_price']);
    $productCollection->add($product);
}
$shipping->set('product', $productCollection);

$customer = new Trading\Order\Customer\Customer();
$customer->set('email', $item['expands']['customer']['email']);
list($fname, $lname) = preg_split('/ /', $item['expands']['customer']['name'], 2);
$customer->set('first_name', $fname);
$customer->set('last_name', $lname);

$document = new People\Document();
$document->set('doc_number', $item['expands']['customer']['vat_number']);
$document->set('doc_type', 'CPF');
$customer->set('document', $document);

if (!empty($item['expands']['customer']['phones'])) {
    $phone = new People\Phone();
    $phone->set('number', $item['expands']['customer']['phones'][0]);
    $customer->set('phone', $phone);
}

$address = $item['shipping_address'];
$delivery = new Trading\Order\Customer\AddressDelivery();
$delivery->set('city', $address['city']);
$delivery->set('comments', $address['complement']);
$delivery->set('neighborhood', $address['neighborhood']);
$delivery->set('number', $address['number']);
$delivery->set('postalCode', $address['postcode']);
$delivery->set('reference', $address['reference']);
$delivery->set('state', $address['region']);
$delivery->set('street', $address['street']);
$customer->set('address_delivery', $delivery);

$order = new Trading\Order\Order();
$order->set('currency_id', 'BRL');
$order->set('order_type', 'skyhub');
$order->set('order_number', $item['code']);
$order->set('origin_number', $item['import_info']['remote_id']);
$order->set('date_created', $item['placed_at']);
$order->set('date_last_modified', $item['updated_at']);

$shippingCollection = new TOS\Collection();
$shippingCollection->add($shipping);
$order->set('shipping', $shippingCollection);

//$shipping->setOrder($order);
$order->set('customer', $customer);
//$customer->setOrder($order);

$trading = new Trading\Trading();
$trading->set('order', $order);
//$order->setTrading($trading);
$trading->set('expands', $item);

return $trading;
