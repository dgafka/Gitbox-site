<?php

namespace Gitbox\Bundle\CoreBundle\Helper;

use Symfony\Component\Config\Definition\Exception\Exception;
use Doctrine\ORM\Query\Expr\Join;


/**
 * Klasa wspomagająca obsługę systemu głosowania
 *
 * Class RatingHelper
 * @package Gitbox\Bundle\CoreBundle\Helper
 */
class RatingHelper extends EntityHelper {

    public function __construct($entityManager, $cacheHelper) {
        parent::__construct($entityManager, $cacheHelper);
    }

    /**
     * Aktualizacja liczby głosow dla rekordu w tabeli Content oraz sumy, i ilości głosów w formie statystyk użytkownika
     *
     * @param $contentId
     * @param $type
     * @param $userId
     * @return array
     */
    public function doVote($contentId, $type, $userId) {
        if ($type == 'up' || $type == 'down') {
            $voteType = ucfirst($type);

            $queryBuilder = $this->instance()->createQueryBuilder();

            // pobranie potrzebnych danych
            $data = $this->getInitialData($contentId);

            if (!isset($data)) {
                return array('success' => false, 'msg' => 'Nie możesz głosować na nieistniejący atrykuł!');
            } else if ($data['user_id'] == $userId) {
                return array('success' => false, 'msg' => 'Nie możesz głosować na swoje dzieło!');
            }

            // zaktualizowanie liczby głosów dla rekordu w tabeli Content
            $queryBuilder
                ->update('GitboxCoreBundle:Content', 'c')
                ->set('c.vote' . $voteType , 'c.vote' . $voteType . ' + 1')
                ->where('c.id = :content_id')
                ->setParameters(array(
                    'content_id' => $contentId
                ))
                ->getQuery()->execute();

            // zaktualizuj pobrane wcześniej dane
            $data['vote' . $voteType]++;

            // zapobiegnięcie akumulowaniu się pól do zaktualizowania
            $queryBuilder = $this->instance()->createQueryBuilder();

            $scoreModifier = $type == 'up' ? ' + 1' : ' - 1';

            // zaktualizowanie sumy głosów dla użytkownika (DQL nie obsługuje JOIN-ów)
            $queryBuilder
                ->update('GitboxCoreBundle:UserDescription', 'ud')
                ->set('ud.ratingScore', 'ud.ratingScore' . $scoreModifier)
                ->set('ud.ratingQuantity', 'ud.ratingQuantity + 1')
                ->where('ud.id = :user_description_id')
                ->setParameters(array(
                    'user_description_id' => $data['user_description_id']
                ))
                ->getQuery()->execute();

            return array(
                'success' => true,
                'msg' => 'Zagłosowałeś pomyślnie.',
                'votesUp' => $data['voteUp'],
                'votesDown' => $data['voteDown']
            );
        }

        return array('success' => false, 'msg' => 'Wystąpił błąd podczas głosowania! Odśwież stronę i spróbuj ponownie.');
    }

    /**
     * @param $contentId
     * @return mixed
     */
    private function getInitialData($contentId) {
        $queryBuilder = $this->instance()->createQueryBuilder();

        // pobranie potrzebnych danych
        $data = $queryBuilder
            ->select('c.voteUp, c.voteDown, u.id AS user_id, ud.id AS user_description_id')
            ->from('GitboxCoreBundle:Content', 'c')
            ->innerJoin('GitboxCoreBundle:UserAccount', 'u', JOIN::WITH, 'c.idUser = u.id')
            ->innerJoin('GitboxCoreBundle:UserDescription', 'ud', JOIN::WITH, 'u.idDescription = ud.id')
            ->where('c.id = :content_id')
            ->setParameters(array(
                'content_id' => $contentId
            ))
            ->getQuery()->getOneOrNullResult();

        return $data;
    }
}