<?php

/*
 * This file is part of gpupo/submarino-sdk
 * Created by Gilmar Pupo <contact@gpupo.com>
 * For the information of copyright and license you should read the file
 * LICENSE which is distributed with this source code.
 * Para a informação dos direitos autorais e de licença você deve ler o arquivo
 * LICENSE que é distribuído com este código-fonte.
 * Para obtener la información de los derechos de autor y la licencia debe leer
 * el archivo LICENSE que se distribuye con el código fuente.
 * For more information, see <https://www.gpupo.com/>.
 */

namespace Gpupo\SubmarinoSdk\Entity\Order;

use Gpupo\CommonSdk\Entity\EntityAbstract;
use Gpupo\CommonSdk\Entity\EntityInterface;

/**
 * @method string getId()
 * @method setId(string $id)
 * @method string getSiteId()
 * @method setSiteId(string $siteId)
 * @method string getStore()
 * @method setStore(string $store)
 * @method string getPurchaseDate()
 * @method setPurchaseDate(string $purchaseDate)
 * @method string getLastUpdate()
 * @method setLastUpdate(string $lastUpdate)
 * @method string getPurchaseTimestamp()
 * @method setPurchaseTimestamp(string $purchaseTimestamp)
 * @method string getLastUpdateTimestamp()
 * @method setLastUpdateTimestamp(string $lastUpdateTimestamp)
 * @method Gpupo\SubmarinoSdk\Entity\Order\Status\Status getStatus()
 * @method setStatus(Gpupo\SubmarinoSdk\Entity\Order\Status\Status $status)
 * @method Gpupo\SubmarinoSdk\Entity\Order\Invoiced getInvoiced()
 * @method setInvoiced(Gpupo\SubmarinoSdk\Entity\Order\Invoiced $invoiced)
 * @method string getEstimatedDeliveryDate()
 * @method setEstimatedDeliveryDate(string $estimatedDeliveryDate)
 * @method Gpupo\SubmarinoSdk\Entity\Order\Customer\Customer getCustomer()
 * @method setCustomer(Gpupo\SubmarinoSdk\Entity\Order\Customer\Customer $customer)
 * @method Gpupo\SubmarinoSdk\Entity\Order\Payer\Payer getPayer()
 * @method setPayer(Gpupo\SubmarinoSdk\Entity\Order\Payer\Payer $payer)
 * @method float getTotalAmount()
 * @method setTotalAmount(float $totalAmount)
 * @method float getTotalFreight()
 * @method setTotalFreight(float $totalFreight)
 * @method float getTotalDiscount()
 * @method setTotalDiscount(float $totalDiscount)
 * @method float getTotalInterest()
 * @method setTotalInterest(float $totalInterest)
 * @method Gpupo\SubmarinoSdk\Entity\Order\Products\Products getProducts()
 * @method setProducts(Gpupo\SubmarinoSdk\Entity\Order\Products\Products $products)
 * @method Gpupo\SubmarinoSdk\Entity\Order\Shipping getShipping()
 * @method setShipping(Gpupo\SubmarinoSdk\Entity\Order\Shipping $shipping)
 * @method Gpupo\SubmarinoSdk\Entity\Order\PaymentMethods\PaymentMethods getPaymentMethods()
 * @method setPaymentMethods(Gpupo\SubmarinoSdk\Entity\Order\PaymentMethods\PaymentMethods $paymentMethods)
 */
class Order extends EntityAbstract implements EntityInterface
{
    public function getSchema()
    {
        return  [
            'id'                    => 'string',
            'siteId'                => 'string',
            'store'                 => 'string',
            'purchaseDate'          => 'string',
            'lastUpdate'            => 'string',
            'purchaseTimestamp'     => 'string',
            'lastUpdateTimestamp'   => 'string',
            'status'                => 'object',
            'invoiced'              => 'object',
            'estimatedDeliveryDate' => 'string',
            'customer'              => 'object',
            'payer'                 => 'object',
            'totalAmount'           => 'number',
            'totalFreight'          => 'number',
            'totalDiscount'         => 'number',
            'totalInterest'         => 'number',
            'products'              => 'object',
            'shipping'              => 'object',
            'paymentMethods'        => 'object',
        ];
    }

    public function toStatus()
    {
        return $this->piece('status');
    }

    public function getTotalReal()
    {
        return bcsub($this->getTotalAmount(), $this->getTotalInterest(), 2);
    }
}
