<?php

declare(strict_types=1);

/*
 * This file is part of gpupo/submarino-sdk created by Gilmar Pupo <contact@gpupo.com>
 * For the information of copyright and license you should read the file LICENSE which is
 * distributed with this source code. For more information, see <https://opensource.gpupo.com/>
 */

namespace Gpupo\SubmarinoSdk\Tests\Entity\Order;

use Gpupo\Common\Entity\CollectionInterface;
use Gpupo\CommonSchema\ArrayCollection\Trading\Trading;
use Gpupo\CommonSchema\TranslatorDataCollection;
use Gpupo\CommonSdk\Entity\Metadata\MetadataContainer;
use Gpupo\SubmarinoSdk\Entity\Order\Transport\Plp;
use Gpupo\SubmarinoSdk\Tests\TestCaseAbstract;

/**
 * @coversNothing
 */
class ManagerTest extends TestCaseAbstract
{
    /**
     * @testdox Acessa lista de pedidos
     */
    public function testFetch(): CollectionInterface
    {
        $response = $this->factoryResponseFromFixture('mockup/orders/list.json');
        $list = $this->getManager($response)->fetch();
        $this->assertInstanceOf(CollectionInterface::class, $list, 'Assert 1');
        $this->assertInstanceOf(MetadataContainer::class, $list, 'Assert 2');
        foreach ($list as $orderList) {
            $this->assertInstanceOf(CollectionInterface::class, $orderList, 'Assert Order');
        }

        return $list;
    }

    /**
     * @testdox Acessa lista de pedidos recentemente aprovados
     */
    public function testFetchQueue()
    {
        $response = $this->factoryResponseFromFixture('mockup/orders/queue.json');
        $orderNative = $this->getManager($response)->fetchQueue();

        $this->assertInstanceOf(TranslatorDataCollection::class, $orderNative);
        $this->assertArrayHasKey('order', $orderNative);

        // $this->assertInstanceOf(Trading::class, $trading);
        // $order = $trading->getOrder();
        // $this->assertSame('skyhub', $order->getOrderType());
        // $this->assertSame('Bruno', $order->getCustomer()->getFirstName());
        // $this->assertSame('21 3722-3902', $order->getCustomer()->getPhone()->getNumber());
        // $this->assertSame('78732371683', $order->getCustomer()->getDocument()->getDocNumber());
        // $this->assertSame(1548766808293, $order->getShipping()->first()->getShippingNumber());
        // $this->assertSame('Teste-1548766808293', $order->getOrderNumber());

        // return $trading;
    }

    public function testRecuperaInformacoesDeUmPedidoEspecifico()
    {
        $response = $this->factoryResponseFromFixture('mockup/orders/detail.json');
        $order = $this->getManager($response)->findById(589);
        $this->assertInstanceOf(CollectionInterface::class, $order, 'Assert Order');
    }

    public function testGeraUmaListaDeEnvio()
    {
        $response = $this->factoryResponseFromFixture('mockup/orders/plp/factory.json');
        $manager = $this->getManager($response);
        $plp = $manager->factoryPlp('350755608801');
        $this->assertInstanceOf(Plp::class, $plp);
        $this->assertSame(98945320, $plp->getId());
        $this->assertSame('{"order_remote_codes":["350755608801"]}', $manager->getLastRequest()->getBody());
        $newResponse = $this->factoryResponseFromFixture('mockup/orders/plp/list.json');
        $filled = $this->getManager($newResponse)->fillPlp($plp);

        $this->assertInstanceOf(Plp::class, $filled);
        $this->assertSame('OH598342788BR', current(current($filled->getTrackingCodes())));
    }

    public function testFetchPlp()
    {
        $response = $this->factoryResponseFromFixture('mockup/orders/plp/list.json');
        $plp = $this->getManager($response)->fetchPlp(98945320);
        $this->assertInstanceOf(Plp::class, $plp);
        $this->assertSame('OH598342788BR', current(current($plp->getTrackingCodes())));
    }

    public function testDownloadPlp()
    {
        $response = $this->factoryResponseFromArray([]);
        $plp = new Plp(['id' => 98945320]);
        $pdfPath = $this->getManager($response)->downloadPlp($plp);
        $this->assertSame('/tmp/submarino_sdk_plp-98945320.pdf', $pdfPath);
    }

    protected function getManager($response = null)
    {
        return $this->getFactory()->factoryManager('order')->setDryRun($response);
    }
}
