<?php

namespace AppBundle\Controller;

use AppBundle\Entity\File;
use AppBundle\Form\FileType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Unirest;

/**
 * Class FileController
 * @Route("/file-manager")
 */
class FileController extends Controller
{
    const EXAMPLE_URL = 'http://proyezbc.herokuapp.com/rest-api/v1/consume/1?hash=%s';

    /**
     * @Template("@App/File/index.html.twig")
     *
     * @Route("/index", name="app_file_manager_index")
     *
     * @return array
     */
    public function indexAction()
    {
        $user = $this->getUser();
        $repository = $this->getDoctrine()->getRepository(File::class);
        $files = $repository->findBy(['user' => $user]);

        $parameters = [
            'files' => $files
        ];

        return $parameters;
    }

    /**
     * @Route("/new", name="app_file_manager_new")
     *
     * @Template("@App/File/new.html.twig")
     *
     * @param Request $request
     *
     * @return array|Response
     */
    public function newAction(Request $request)
    {
        $file = new File($this->getUser());
        $form = $this->createForm(FileType::class, $file);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var UploadedFile $binaryContent */
            $binaryContent = $file->getMedia()->getBinaryContent();
            $path = $binaryContent->getPathname();
            $hashValue = md5_file($path);
            $file->setFileHash($hashValue);

            //TODO dispatch event to save on BlockChain.
            $url = self::EXAMPLE_URL;
            $url = sprintf(
                $url,
                $file->getFileHash()
            );

            $resp = Unirest\Request::get($url);
            $info = json_decode(json_encode($resp->body), true);

            $code = (int) $resp->code;

            if ($code == 200) {
                $txid = $info['txid'];
                $file->setBcHash($txid);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($file);
            $entityManager->flush();

            $redirectUrl = $this->redirectToRoute('app_file_manager_index');

            return $redirectUrl;
        }

        $parameters = [
            'file' => $file,
            'form' => $form->createView()
        ];

        return $parameters;
    }

    /**
     * @Route("/delete")
     */
    public function deleteAction()
    {
        return $this->render('AppBundle:File:delete.html.twig', array(
            // ...
        ));
    }

    /**
     * @Route("/{id}/detail", name="app_file_manager_detail", requirements={"id" = "\d+"}, options={"expose" = true})
     *
     * @Template("@App/File/detail.html.twig")
     *
     * @param Request $request HTTP request.
     * @param int     $id      Identifier
     *
     * @return array
     */
    public function detailAction(Request $request, $id)
    {
        $repository = $this->getDoctrine()->getRepository(File::class);
        $file = $repository->find($id);

        $parameters = [
            'file' => $file
        ];

        return $parameters;
    }

    /**
     * @Route("/{id}/save-on-bc", name="app_file_manager_save_on_bc", requirements={"id" = "\d+"}, options={"expose" = true})
     *
     * @param Request $request HTTP request.
     * @param int     $id      Identifier
     *
     * @return array|Response
     */
    public function saveOnBcAction(Request $request, $id)
    {
        $repository = $this->getDoctrine()->getRepository(File::class);
        $file = $repository->find($id);

        if (!$file->getBcHash()) {
            //TODO dispatch event to save on BlockChain.
            $url = self::EXAMPLE_URL;
            $url = sprintf(
                $url,
                $file->getFileHash()
            );

            $resp = Unirest\Request::get($url);
            $info = json_decode(json_encode($resp->body), true);
            $code = (int) $resp->code;

            if ($code == 200) {
                $txid = $info['txid'];
                $file->setBcHash($txid);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($file);
            $entityManager->flush();
        }

        $url = $this->generateUrl('app_file_manager_detail', [ 'id' => $id ]);

        return $this->redirect($url);
    }
}
