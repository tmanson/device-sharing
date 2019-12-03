<?php
namespace App\Controller;

use App\Entity\Device;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\FOSRestBundle;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
     */
    public function listAction()
    {
        $devices = $this->getDoctrine()
            ->getRepository(Device::class)
            ->findAll();
        return $devices;
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