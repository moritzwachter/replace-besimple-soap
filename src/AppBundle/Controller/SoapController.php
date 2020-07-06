<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SoapController extends Controller
{
    /**
     * @Route("/LivestreamApi", name="_soap_livestream")
     *
     * @return Response
     */
    public function handleLivestreamAction(): Response
    {
        return $this->handleSoapServer('livestream', 'soap.livestream');
    }

    /**
     * @Route("/TranscodingApi", name="_soap_transcoding")
     *
     * @return Response
     */
    public function handleTranscodingAction(): Response
    {
        return $this->handleSoapServer('transcoding', 'soap.transcoding');
    }

    /**
     * @Route("/{identifier}.wsdl", name="_soap_wsdl")
     *
     * @param string $identifier
     * @return Response
     */
    public function getWsdlFileContent(string $identifier): Response
    {
        $template = sprintf('AppBundle:SOAP:%s.wsdl.twig', $identifier);

        $body = $this->renderView($template, array(
            'host' => $this->generateUrl('_soap_' . $identifier, [], UrlGeneratorInterface::ABSOLUTE_URL)
        ));

        $response = new Response();
        $response->setContent($body);
        $response->headers->set('Content-Type', 'application/wsdl+xml');

        return $response;
    }

    /**
     * @param string $identifier
     * @param string $serviceId
     * @return Response
     */
    protected function handleSoapServer(string $identifier, string $serviceId): Response
    {
        $response = new Response();
        $response->headers->set('Content-Type', 'text/xml; charset=ISO-8859-1');

        $wsdlUrl = $this->generateUrl('_soap_wsdl', ['identifier' => $identifier], UrlGeneratorInterface::ABSOLUTE_URL);

        $soapServer = new \SoapServer($wsdlUrl, ['encoding' => SOAP_ENCODED]);
        $soapServer->setObject($this->get($serviceId));

        ob_start();
        $soapServer->handle();
        $response->setContent(ob_get_clean());

        return $response;
    }
}
