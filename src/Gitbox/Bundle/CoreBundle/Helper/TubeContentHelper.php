<?php

namespace Gitbox\Bundle\CoreBundle\Helper;

use Gitbox\Bundle\CoreBundle\Entity\Content;
use Gitbox\Bundle\CoreBundle\Entity\Attachment;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use Gitbox\Bundle\CoreBundle\Controller\TubeController;
use Symfony\Component\Config\Definition\Exception\Exception;
use Doctrine\ORM\Query\Expr\Join;

/**
 * Class TubeContentHelper
 * @package Gitbox\Bundle\CoreBundle\Helper
 */
class TubeContentHelper extends ContentHelper {

    protected $module = 'GitTube';

    public function __construct($entityManager, $cacheHelper) {
        parent::__construct($entityManager, $cacheHelper);

        $this->module = 'GitTube';
    }

    /**
     * Pobranie contentow uzytkownika dla modułu gitTube
     *
     * @param $userLogin
     *
     * @return Content | null
     *
     * @throws Exception
     */
    public function getContents($userLogin) {
        if (!isset($this->module)) {
            throw new Exception("Nie zainicjalizowano instancji.");
        }
        $userId    = $this->instanceCache()->getUserIdByLogin($userLogin);
        $gitTubeId = $this->instanceCache()->getModuleIdByName('GitTube');

        $queryBuilder = $this->instance()->createQueryBuilder();

        $queryBuilder
            ->select('c')
            ->from('GitboxCoreBundle:Content', 'c')
            ->innerJoin('GitboxCoreBundle:Attachment', 'a', 'WITH', 'a.idContent = c.id')
            ->innerJoin('GitboxCoreBundle:Menu', 'm', 'WITH','m.id = c.idMenu')
            ->where('m.idUser = :userId')
            ->andWhere('m.idModule = :moduleId' )->orderby('c.createDate', 'DESC')

            ->setParameters(array(
                'userId'     => $userId,
                'moduleId'   => $gitTubeId
            ));

        $query = $queryBuilder->getQuery();
        $results = $query->getResult();

        return $results;
    }
    /**
     * Pobranie attachmentów uzytkownika dla modułu gitTube
     *
     * @param $userLogin
     *
     * @return Attachment | null
     *
     * @throws Exception
     */
    public function getAttachments($userLogin) {
        if (!isset($this->module)) {
            throw new Exception("Nie zainicjalizowano instancji.");
        }
        $userId    = $this->instanceCache()->getUserIdByLogin($userLogin);
        $gitTubeId = $this->instanceCache()->getModuleIdByName('GitTube');

        $queryBuilder = $this->instance()->createQueryBuilder();

        $queryBuilder
            ->select('a')
            ->from('GitboxCoreBundle:Content', 'c')
            ->innerJoin('GitboxCoreBundle:Attachment', 'a', 'WITH', 'a.idContent = c.id')
            ->innerJoin('GitboxCoreBundle:Menu', 'm', 'WITH','m.id = c.idMenu')
            ->where('m.idUser = :userId')
            ->andWhere('m.idModule = :moduleId' )

            ->setParameters(array(
                'userId'     => $userId,
                'moduleId'   => $gitTubeId
            ));

        $query = $queryBuilder->getQuery();
        $results = $query->getResult();

        return $results;
    }
    /**
     * Pobranie jendgo attachmentu z bazy
     *
     * @param $userLogin
     *
     * @return Attachment | null
     *
     * @throws Exception
     */
    public function getOneAttachmentById($userLogin, $idAttachment) {

        $queryBuilder = $this->instance()->createQueryBuilder();

        $queryBuilder
            ->select('a')
            ->from('GitboxCoreBundle:Attachment', 'a')
            ->where('a.id = :idAttachment')
            ->setParameters(array(
                'idAttachment' => $idAttachment
            ));

        $query = $queryBuilder->getQuery();
        $results = $query->getResult();

        return $results;
    }
    /**
     * Pobranie jednego filmu z bazy - tabela attachment
     *
     * @param int $id
     * @param $userLogin
     *
     * @return Content | null
     *
     * @throws Exception
     */
    public function getOneAttachment($idContent, $userLogin) {
        if (!isset($this->module)) {
            throw new Exception("Nie zainicjalizowano instancji.");
        }

        $userId    = $this->instanceCache()->getUserIdByLogin($userLogin);
        $gitTubeId = $this->instanceCache()->getModuleIdByName('GitTube');
        $queryBuilder = $this->instance()->createQueryBuilder();

        $queryBuilder
            ->select('a')
            ->from('GitboxCoreBundle:Content', 'c')
            ->innerJoin('GitboxCoreBundle:Attachment', 'a', 'WITH', 'a.idContent = c.id')
            ->innerJoin('GitboxCoreBundle:Menu', 'm', 'WITH','m.id = c.idMenu')
            ->where('m.idUser = :userId')
            ->andWhere('m.idModule = :moduleId')
            ->andWhere('c.id = :contentId')
            ->setParameters(array(
                'userId' => $userId,
                'moduleId' => $gitTubeId,
                'contentId' => $idContent
            ));

        $results = $queryBuilder->getQuery()->getResult();

        return $results;
    }

    /**
     * Pobranie contentu z bazy
     *
     * @param int $id
     * @param $userLogin
     *
     * @return Content | null
     *
     * @throws Exception
     */
    public function getOneContent($id, $userLogin) {
        if (!isset($this->module)) {
            throw new Exception("Nie zainicjalizowano instancji.");
        }

        $userId    = $this->instanceCache()->getUserIdByLogin($userLogin);
        $gitTubeId = $this->instanceCache()->getModuleIdByName('GitTube');
        $queryBuilder = $this->instance()->createQueryBuilder();

        $queryBuilder
            ->select('c')
            ->from('GitboxCoreBundle:Content', 'c')
            ->innerJoin('GitboxCoreBundle:Attachment', 'a', 'WITH', 'a.idContent = c.id')
            ->innerJoin('GitboxCoreBundle:Menu', 'm', 'WITH','m.id = c.idMenu')
            ->where('m.idUser = :userId')
            ->andWhere('m.idModule = :moduleId')
            ->andWhere('c.id = :contentId')
            ->setParameters(array(
                'userId' => $userId,
                'moduleId' => $gitTubeId,
                'contentId' => $id
            ));

        try {
            return $queryBuilder->getQuery()->getSingleResult();
        } catch (NoResultException $e) {
            return null;
        }
    }

    /**
     * @param $newContent \Gitbox\Bundle\CoreBundle\Entity\Content
     * @return null|\Gitbox\Bundle\CoreBundle\Entity\Content
     * @throws \Exception
     */
    public function insertIntoContent($newContent) {
        //$em = $this->getDoctrine()->getManager();

        $newContent->setCreateDate(new \DateTime('now'));
        $newContent->setLastModificationDate(new \DateTime('now'));
        $newContent->setStatus('A');
        $newContent->setHit(0);
        $newContent->setType('1');


        if($newContent instanceof \Gitbox\Bundle\CoreBundle\Entity\Content) {
            /*$em->persist($newContent);
            $em->flush();*/
            $newContent = $this->instance()->persist($newContent);
            $this->instance()->flush();
            }else {
                throw new Exception("Błąd podczas dodawania obiektu.");
            }
            return $newContent;
    }

    /**
     * @param $newAttachment \Gitbox\Bundle\CoreBundle\Entity\Attachment
     * @param $content \Gitbox\Bundle\CoreBundle\Entity\Content
     * @return null|\Gitbox\Bundle\CoreBundle\Entity\Attachment
     * @throws \Exception
     */
    public function insertIntoAttachment($newAttachment, $content) {
        //$em = $this->getDoctrine()->getManager();

        $newAttachment->setCreateDate($content->getCreateDate());
        $newAttachment->setStatus($content->getStatus());
        $newAttachment->setIdContent($content);

        if($newAttachment instanceof \Gitbox\Bundle\CoreBundle\Entity\Attachment) {
           /* $em->persist($newAttachment);
            $em->flush();*/
            $newAttachment = $this->instance()->persist($newAttachment);
            $this->instance()->flush();
        }else {
            throw new Exception("Błąd podczas dodawania obiektu.");
        }


        return $newAttachment;
    }

    public function findIdMenuByName($name) {
        if (!isset($this->module)) {
            throw new Exception("Nie zainicjalizowano instancji.");
        }
        $gitTubeId = $this->instanceCache()->getModuleIdByName($name);
        return $gitTubeId;
    }

    /**
     * @param mixed $content
     * @throws Exception
     */
    public function removeAttachment($content) {
        if ($content instanceof Attachment) {
            $this->instance()->remove($content);
            $this->instance()->flush();
        } else if (is_int($content)) {
            $queryBuilder = $this->instance()->createQueryBuilder();
            $queryBuilder
                ->delete('GitboxCoreBundle:Attachment', 'c')
                ->where('c.id = :id')
                ->setParameter('id', $content)
                ->getQuery()
                ->execute();
        } else {
            throw new Exception('Niepoprawny typ parametru.');
        }
    }
}