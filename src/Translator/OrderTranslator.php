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

namespace Gpupo\SubmarinoSdk\Translator;

use Gpupo\CommonSchema\AbstractTranslator;
use Gpupo\CommonSchema\ArrayCollection\People;
use Gpupo\CommonSchema\ArrayCollection\Trading;

class OrderTranslator extends AbstractTranslator
{
    public function import()
    {
        $item = $this->getForeign()->toArray();

        $shipping = new Trading\Order\Shipping\Shipping();
        $shipping->set('currency_id', 'BRL');
        $shipping->set('date_created', $item['placed_at']);
        $shipping->set('total_payments_amount', $item['total_ordered']);
        $shipping->set('shipping_number', (int) $item['import_info']['remote_code']);
        //$shipping->setTotalPaymentsFeeAmount();
        $shipping->set('total_payments_fee_amount', $item['shipping_cost']);
        $shipping->set('total_payments_net_amount', $item['total_ordered'] - $item['shipping_cost']);

        $paymentCollection = new Trading\Order\Shipping\Payment\Collection();
        foreach ($item['payments'] as $pay) {
            $payment = new Trading\Order\Shipping\Payment\Payment();
            //$payment->setAuthorizationUri();
            $payment->set('authorization_code', $pay['autorization_id']);
            //$payment->setCardId();
            $payment->set('collector', $item['channel']);
            $payment->set('currency_id', 'BRL');
            //$payment->setInstallmentAmount();
            $payment->set('installments', $pay['parcels']);
            //$payment->setMarketplaceFee();
            $payment->set('operation_type', $pay['description']);
            //$payment->setOverpaidAmount();
            $payment->set('payment_method_id', $pay['card_issuer']);
            $payment->set('payment_number', (int)$item['import_info']['remote_code']);
            $payment->set('payment_type', $pay['sefaz']['payment_indicator']);
            $payment->set('shipping_cost', 0);
            $payment->set('status', $pay['status']);
            $payment->set('date_approved', $pay['transaction_date']);
            $payment->set('date_created', $pay['transaction_date']);
            $payment->set('date_last_modified', $pay['transaction_date']);
            //$payment->setStatusCode();
            //$payment->setStatusDetail();
            $payment->set('total_paid_amount', $pay['value']);
            $payment->set('transaction_amount', $pay['value']);
            $payment->set('transaction_net_amount', $pay['value']);
            //$payment->setShipping($shipping);

            $paymentCollection->add($payment);
        }
        $shipping->set('payment', $paymentCollection);

        $quantity = 0;
        foreach ($item['items'] as $i) {
            $quantity += $i['qty'];
        }

        $shipping->set('total_quantity', $quantity);
        $shipping->set('status', $this->validateState($item['status']['type']));
        // $shipping->setSeller($this->getSeller());

        $productCollection = new Trading\Order\Shipping\Product\Collection();
        foreach ($item['items'] as $i) {
            $product = new Trading\Order\Shipping\Product\Product();
            //$product->setGtin();
            $product->set('quantity', $i['qty']);
            //$product->setSaleFee();
            $product->set('seller_product_id', $i['id']);
            $product->set('title', $i['name']);
            $product->set('unit_price', $i['special_price']);
            $productCollection->add($product);
        }
        $shipping->set('product', $productCollection);

        $customer = new Trading\Order\Customer\Customer();
        $customer->set('email', $item['customer']['email']);
        list($fname, $lname) = preg_split('/ /', $item['customer']['name'], 2);
        $customer->set('first_name', $fname);
        $customer->set('last_name', $lname);

        $document = new People\Document();
        $document->set('doc_number', $item['customer']['vat_number']);
        $document->set('doc_type', 'CPF');
        $customer->set('document', $document);

        if (!empty($item['customer']['phones'])) {
            $phone = new People\Phone();
            $phone->set('number', $item['customer']['phones'][0]);
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
        //$delivery->setStatus();
        $customer->set('address_delivery', $delivery);

        $order = new Trading\Order\Order();
        $order->set('currency_id', 'BRL');
        $order->set('order_type', 'skyhub');
        $order->set('order_number', $item['code']);
        $order->set('date_created', $item['placed_at']);
        $order->set('date_last_modified', $item['updated_at']);

        $shippingCollection = new Trading\Order\Shipping\Collection();
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
    }

    public function validateState($string)
    {
        switch ($string) {
            case 'NEW':
                return 'created';
            case 'APPROVED':
                return 'approved';
            case 'INVOICE':
                return 'invoiced';
            case 'SHIPPED':
                return 'shipped';
            case 'DELIVERED':
                return 'delivered';
            case 'CANCELED':
                return 'canceled';
        }

        throw new \Exception('Order state not supported', 1);
    }

    public function export()
    {
    }
}
