<?php

namespace App\Controller;

use App\Entity\Device;
use App\Representation\Devices;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Controller\Annotations\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class DeviceController extends AbstractFOSRestController
{
    /**
     * @View()
     * @Get(
     *     path = "/devices",
     *     name = "app_device_list"
     * )
     * @QueryParam(
     *     name="keyword",
     *     requirements="[a-zA-Z0-9]",
     *     nullable=true,
     *     description="The keyword to search for."
     * )
     * @QueryParam(
     *     name="order",
     *     requirements="asc|desc",
     *     default="asc",
     *     description="Sort order (asc or desc)"
     * )
     * @QueryParam(
     *     name="limit",
     *     requirements="\d+",
     *     default="15",
     *     description="Max number of devices per page."
     * )
     * @QueryParam(
     *     name="offset",
     *     requirements="\d+",
     *     default="0",
     *     description="The pagination offset"
     * )
     */
    public function listAction($keyword, $order, $limit, $offset)
    {
        $pager = $this->getDoctrine()
            ->getRepository(Device::class)
            ->search(
                $keyword,
                $order,
                $limit,
                $offset
            );
        return new Devices($pager);
    }

    /**
     * @View()
     * @Get(
     *     path = "/devices/{id}",
     *     name = "app_device_show",
     *     requirements = {"id"="\d+"}
     * )
     */
    public function showAction(Device $device)
    {
        return $device;
    }

    /**
     * @Post("/devices")
     * @View(StatusCode = 201)
     * @ParamConverter("device", converter="fos_rest.request_body")
     */
    public function createAction(Device $device)
    {
        $em = $this->getDoctrine()->getManager();

        $em->persist($device);
        $em->flush();

        return $this->view($device, Response::HTTP_CREATED, ['Location' => $this->generateUrl('app_device_show', ['id' => $device->getId(), UrlGeneratorInterface::ABSOLUTE_URL])]);
    }
}