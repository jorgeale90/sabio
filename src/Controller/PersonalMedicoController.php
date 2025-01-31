<?php

namespace App\Controller;

use App\Entity\PersonalMedico;
use App\Form\PersonalMedicoType;
use App\Repository\PersonalMedicoRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/personalmedico")
 */
class PersonalMedicoController extends AbstractController
{
    public $personalmRepository;

    public function __construct(PersonalMedicoRepository $personalmRepository)
    {
        $this->personalmRepository = $personalmRepository;
    }

    /**
     * @Route("/", name="personalmedico_index", methods={"GET"})
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     */
    public function index(PersonalMedicoRepository $personalMedicoRepository): Response
    {
        return $this->render('personalmedico/index.html.twig', [
            'personalmedico' => $personalMedicoRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="personalmedico_new", methods={"GET","POST"})
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     */
    public function new(Request $request): Response
    {
        $entity = new PersonalMedico();
        $form = $this->createForm(PersonalMedicoType::class, $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $user = $this->getUser();
            $entity->setUser($user);
            $entityManager->persist($entity);
            $entityManager->flush();

            $flashBag = $this->get('session')->getFlashBag();
            $flashBag->add('app_success','Se ha creado un Personal Médico satisfactoriamente!!!');
            $flashBag->add('app_success', sprintf('Personal Médico: %s', $entity->getNombreCompleto()));

            return $this->redirectToRoute('personalmedico_index');
        }

        return $this->render('personalmedico/new.html.twig', [
            'personalmed' => $entity,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="personalmedico_edit", methods={"GET","POST"})
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     */
    public function edit(Request $request, PersonalMedico $entity): Response
    {
        $form = $this->createForm(PersonalMedicoType::class, $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $entity->setUser($user);
            $this->getDoctrine()->getManager()->flush();

            $flashBag = $this->get('session')->getFlashBag();
            $flashBag->add('app_warning','Se ha actualizado un Personal Médico satisfactoriamente!!!');
            $flashBag->add('app_warning', sprintf('Personal Médico: %s', $entity->getNombreCompleto()));

            return $this->redirectToRoute('personalmedico_index');
        }

        return $this->render('personalmedico/edit.html.twig', [
            'personalmed' => $entity,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/show", name="personalmedico_show", methods={"GET"})
     */
    public function show($id): Response
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('App:PersonalMedico')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Incapaz de encontrar el Personal deseado.');
        }

        $form = $this->createForm(PersonalMedicoType::class, $entity);

        return $this->render('personalmedico/show.html.twig', array(
            'entity'      => $entity,
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/delete/{id}", name="personalmedico_remove")
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     */
    public function remove(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository(PersonalMedico::class)->find($id);

        if (!$entity) {
            $flashBag = $this->get('session')->getFlashBag();
            $flashBag->add('app_warning','No se encuentra este Personal Médico!!!');
        } else {
            $em->remove($entity);
            $em->flush();

            $flashBag = $this->get('session')->getFlashBag();
            $flashBag->add('app_error','Se ha eliminado un Personal Médico satisfactoriamente!!!');
        }

        return $this->redirectToRoute('personalmedico_index');
    }

    /**
     * @Route("/change/personalmdenegar", name="change_personal_denegar", methods={"GET","POST"})
     */
    public function changeEnDenegarPersonal(Request $request): JsonResponse
    {
        $value = $request->get('value') == 'false' ? false : true;
        $id = $request->get('id');
        $entity = $this->personalmRepository->find($id);
        $entityManager = $this->getDoctrine()->getManager();
        $action = 'esDenegar';
        $entity->setEsDenegar($value);
        $entityManager->persist($entity);
        $entityManager->flush();

        return new JsonResponse(array('response' => $action));
    }

    /**
     * @Route("/getmunicipiopmxprovinciapm", name="municipiopm_x_provinciapm", methods={"GET","POST"})
     */
    public function getMunicipiopmxProvinciapm(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $provincia_id = $request->get('provincia_id');
        $municipio = $em->getRepository('App:Municipio')->findByProvinciapm($provincia_id);
        return new JsonResponse($municipio);
    }

    /**
     * @Route("/getinstitucionpmxmunicipiopm", name="institucionpm_x_municipiopm", methods={"GET","POST"})
     */
    public function getInstitucionpmxMunicipiopm(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $municipio_id = $request->get('municipio_id');
        $institucion = $em->getRepository('App:Institucion')->findByMunicipiopm($municipio_id);
        return new JsonResponse($institucion);
    }

    /**
     * @Route("/getespecialidadxcargo", name="especialidad_x_cargo", methods={"GET","POST"})
     */
    public function getEspecialidadxCargo(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $cargo_id = $request->get('cargo_id');
        $especialidad = $em->getRepository('App:Especialidad')->findByCargo($cargo_id);
        return new JsonResponse($especialidad);
    }
}
