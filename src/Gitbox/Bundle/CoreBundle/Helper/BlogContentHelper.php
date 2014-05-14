<?php

namespace Gitbox\Bundle\CoreBundle\Helper;

use Gitbox\Bundle\CoreBundle\Entity\Content;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\Query;
use Gitbox\Bundle\CoreBundle\Entity\UserFavContent;
use Knp\Component\Pager\Paginator;
use Symfony\Component\HttpFoundation\Request;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;


/**
 * Class BlogContentHelper
 * @package Gitbox\Bundle\CoreBundle\Helper
 */
class BlogContentHelper extends ContentHelper implements PaginatorAwareInterface {

    /**
     * @var Paginator
     */
    protected $paginator;

    protected $module = 'GitBlog';


    public function __construct($entityManager, $cacheHelper) {
        parent::__construct($entityManager, $cacheHelper);
    }

    /**
     * Sets the KnpPaginator instance.
     *
     * @param Paginator $paginator
     *
     * @return PaginatorAware
     */
    public function setPaginator(Paginator $paginator)
    {
        $this->paginator = $paginator;

        return $this;
    }

    /**
     * Returns the KnpPaginator instance.
     *
     * @return Paginator
     */
    public function getPaginator()
    {
        return $this->paginator;
    }

    /**
     * Pobranie wpisów z bloga użytkownika
     *
     * @param $userLogin
     * @param null|string $title
     * @param int $perPage
     * @param Request $request
     *
     * @return array | null
     *
     * @throws Exception
     */
    public function getContents($userLogin, $title = null, $perPage = 0, Request & $request = null) {
        if (!isset($this->module)) {
            throw new Exception("Nie zainicjalizowano instancji.");
        }

        $userId = $this->instanceCache()->getUserIdByLogin($userLogin);
        $moduleId = $this->instanceCache()->getModuleIdByName($this->module);

        $queryBuilder = $this->instance()->createQueryBuilder();

        // może się kiedyś przyda... :)
//        /* COUNT */
//        $queryBuilder
//            ->select('COUNT(c.id)')
//            ->from('GitboxCoreBundle:Content', 'c')
//            ->innerJoin('c.idMenu', 'm') // automaticaly join keys, upon relation
//            ->where('c.idUser = :user_id AND m.idModule = :module_id');
//
//        if (isset($title)) {
//            $queryBuilder
//                ->andWhere($queryBuilder->expr()->like('c.title', $queryBuilder->expr()->literal('%' . $title . '%')));
//        }
//
//        $queryBuilder
//            ->setParameters(array(
//                'user_id' => $userId,
//                'module_id' => $moduleId
//            ));
//
//        $count = $queryBuilder->getQuery()->getSingleScalarResult();
//        /* END OF COUNT */
//
//        $queryBuilder->resetDQLParts();

        $queryBuilder
            ->select('c')
            ->from('GitboxCoreBundle:Content', 'c')
            ->leftJoin('GitboxCoreBundle:UserFavContent', 'fav', JOIN::WITH, 'fav.idContent = c.id')
            ->innerJoin('c.idMenu', 'm') // automaticaly join keys, upon relation
            ->where('c.idUser = :user_id AND m.idModule = :module_id');

        if (isset($title)) {
            $queryBuilder
                ->andWhere($queryBuilder->expr()->like('c.title', $queryBuilder->expr()->literal('%' . $title . '%')));
        }

        $queryBuilder
            ->orderBy('c.createDate', 'DESC')
            ->setParameters(array(
                'user_id' => $userId,
                'module_id' => $moduleId
            ));

        if ($perPage > 0 && $request instanceof Request) {
            $query = $queryBuilder->getQuery();

            $posts = $this->paginator->paginate($query, $request->query->get('page', 1), $perPage, array('distinct' => false));

            $favPosts = $this->getFavContents($posts->getItems());

            return array(
                'posts' => $posts,
                'favPosts' => $favPosts
            );
        } else {
            try {
                return $queryBuilder->getQuery()->getResult();
            } catch (NoResultException $e) {
                return null;
            }
        }
    }

    /**
     * Pobranie wpisów z bloga użytkownika o podanej kategorii
     *
     * @param $userLogin
     * @param array $categories
     * @param int $perPage
     * @param Request $request
     *
     * @return array | null
     *
     * @throws Exception
     */
    public function getContentsByCategories($userLogin, $categories, $perPage = 0, Request & $request = null) {
        if (!isset($this->module)) {
            throw new Exception("Nie zainicjalizowano instancji.");
        }

        $userId = $this->instanceCache()->getUserIdByLogin($userLogin);
        $moduleId = $this->instanceCache()->getModuleIdByName($this->module);

        $queryBuilder = $this->instance()->createQueryBuilder();

        $queryBuilder
            ->select('c')
            ->from('GitboxCoreBundle:Content', 'c')
            ->innerJoin('c.idMenu', 'm') // automaticaly join keys, upon relation
            ->innerJoin('c.idCategory', 'cc') // same here
            ->leftJoin('GitboxCoreBundle:UserFavContent', 'fav', JOIN::WITH, 'fav.idContent = c.id')
            ->where('c.idUser = :user_id AND m.idModule = :module_id AND cc.id IN(:categories)')
            ->groupBy('c.id')
            ->orderBy('c.createDate', 'DESC')
            ->having('COUNT(cc.id) = :categories_count')
            ->setParameters(array(
                'user_id' => $userId,
                'module_id' => $moduleId,
                'categories' => array_values($categories),
                'categories_count' => count($categories)
            ));

        if ($perPage > 0 && $request instanceof Request) {
            $query = $queryBuilder->getQuery();

            $posts = $this->paginator->paginate($query, $request->query->get('page', 1), $perPage, array('distinct' => false));

            $favPosts = $this->getFavContents($posts->getItems());

            return array(
                'posts' => $posts,
                'favPosts' => $favPosts
            );
        } else {
            try {
                return $queryBuilder->getQuery()->getResult();
            } catch (NoResultException $e) {
                return null;
            }
        }
    }

    /**
     * Pobranie wpisu z bloga użytkownika
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

        if (!is_int($id)) {
            return false;
        }

        $userId = $this->instanceCache()->getUserIdByLogin($userLogin);
        $moduleId = $this->instanceCache()->getModuleIdByName($this->module);

        $queryBuilder = $this->instance()->createQueryBuilder();

        $queryBuilder
            ->select('c')
            ->from('GitboxCoreBundle:Content', 'c')
            ->innerJoin('c.idMenu', 'm') // automaticaly join keys, upon relation
            ->where('c.id = :id AND c.idUser = :user_id AND m.idModule = :module_id')
            ->setParameters(array(
                'id' => $id,
                'user_id' => $userId,
                'module_id' => $moduleId
            ));

        $post = $queryBuilder->getQuery()->getOneOrNullResult();

        if (isset($post)) {
            $favPost = $this->getFavContent($post);

            return array(
                'post' => $post,
                'favPost' => $favPost
            );
        }

        return null;
    }

    /**
     * @param array $posts
     * @return UserFavContent | null
     */
    private function getFavContents($posts) {
        foreach ($posts as $post) {
            $postIds[] = $post->getId();
        }

        if (!empty($postIds)) {
            $queryBuilder = $this->instance()->createQueryBuilder();

            $queryBuilder
                ->select('fav')
                ->from('GitboxCoreBundle:UserFavContent', 'fav')
                ->where('fav.idContent IN(:content_ids)')
                ->setParameters(array(
                    'content_ids' => $postIds
                ));

            $favPosts = $queryBuilder->getQuery()->getResult();

            return $favPosts;
        }

        return null;
    }

    /**
     * @param Content $post
     * @return UserFavContent | null
     */
    private function getFavContent($post) {
        $postId = $post->getId();

        $queryBuilder = $this->instance()->createQueryBuilder();

        $queryBuilder
            ->select('fav')
            ->from('GitboxCoreBundle:UserFavContent', 'fav')
            ->where('fav.idContent = :content_id')
            ->setParameters(array(
                'content_id' => $postId
            ));

        $favPost = $queryBuilder->getQuery()->getOneOrNullResult();

        return $favPost;
    }
}