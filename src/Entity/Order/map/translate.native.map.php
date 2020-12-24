<?php

declare(strict_types=1);

/*
 * This file is part of gpupo/submarino-sdk created by Gilmar Pupo <contact@gpupo.com>
 * For the information of copyright and license you should read the file LICENSE which is
 * distributed with this source code. For more information, see <https://opensource.gpupo.com/>
 */

 use Gpupo\CommonSchema\ArrayCollection\People;
 use Gpupo\CommonSchema\ArrayCollection\Trading;
 use Gpupo\CommonSchema\ArrayCollection\Trading\Order\Shipping as TOS;
 use Gpupo\CommonSchema\TranslatorException;

 // dump('NATIVE', $native);

foreach ([
    'channel',
] as $k) {
    $v = $native->get($k);
    if (empty($v)) {
        throw new TranslatorException(sprintf('Conversion failed. order::%s cannot be empty', $k));
    }
}

//////////////////////////
$shipping = new TOS\Shipping();
$shipping->set('currency_id', 'BRL');
$shipping->set('date_created', $native['placed_at']);
$shipping->set('total_payments_amount', $native['total_ordered']);
$shipping->set('shipping_number', (int) $native['import_info']['remote_code']);
$shipping->set('total_payments_fee_amount', $native['shipping_cost']);
$shipping->set('total_payments_net_amount', $native['total_ordered'] - $native['shipping_cost']);

$paymentCollection = new TOS\Payment\Collection();
foreach ($native['payments'] as $pay) {
    $payment = new TOS\Payment\Payment();
    $payment->set('authorization_code', $pay['autorization_id']);
    $payment->set('collector', $native['channel']);
    $payment->set('currency_id', 'BRL');
    $payment->set('installments', $pay['parcels']);
    $payment->set('operation_type', $pay['description']);
    $payment->set('payment_method_id', $pay['card_issuer']);
    $payment->set('payment_number', (int) $native['import_info']['remote_code']);
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
foreach ($native['items'] as $i) {
    $quantity += $i['qty'];
}

$shipping->set('total_quantity', $quantity);
$shipping->set('status', $this->validateState($native['status']['type']));

$productCollection = new TOS\Product\Collection();
foreach ($native['items'] as $i) {
    $product = new TOS\Product\Product();
    $product->set('quantity', $i['qty']);
    $product->set('seller_product_id', $i['id']);
    $product->set('title', $i['name']);
    $product->set('unit_price', $i['special_price']);
    $productCollection->add($product);
}
$shipping->set('product', $productCollection);

$customer = new Trading\Order\Customer\Customer();
$customer->set('email', $native['customer']['email']);
list($fname, $lname) = preg_split('/ /', $native['customer']['name'], 2);
$customer->set('first_name', $fname);
$customer->set('last_name', $lname);

$document = new People\Document();
$document->set('doc_number', $native['customer']['vat_number']);
$document->set('doc_type', 'CPF');
$customer->set('document', $document);

if (!empty($native['customer']['phones'])) {
    $phone = new People\Phone();
    $phone->set('number', $native['customer']['phones'][0]);
    $customer->set('phone', $phone);
}

$address = $native['shipping_address'];
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
$order->set('order_number', $native['code']);
$order->set('origin_number', $native['import_info']['remote_id']);
$order->set('date_created', $native['placed_at']);
$order->set('date_last_modified', $native['updated_at']);

$shippingCollection = new TOS\Collection();
$shippingCollection->add($shipping);
$order->set('shipping', $shippingCollection);

//$shipping->setOrder($order);
$order->set('customer', $customer);
//$customer->setOrder($order);

$trading = new Trading\Trading();
$trading->set('order', $order);
//$order->setTrading($trading);
$trading->set('expands', $native);

return $trading->toArray();
