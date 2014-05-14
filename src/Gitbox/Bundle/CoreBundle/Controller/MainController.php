<?php

namespace Gitbox\Bundle\CoreBundle\Controller;

use Doctrine\ORM\QueryBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class MainController extends Controller
{
    /**
     * @Route("/", name="home_url")
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }

	/**
	 * Zwraca 6 ostatnich wpisów dodanych przez administratora w panelu administratora
	 * @Template()
	 */
	public function adminAnnouncementsAction() {
		return array();
	}

	/**
	 * Zwraca 3 ostatnich wpisów z GitBloga
	 * @Template()
	 */
	public function lastCreatedGitBlogAction() {
		$helper = $this->getRatingHelper();

		$results = $helper->getLastCreatedContents('GitBlog', 3);
		$this->cutContent($results);

		return array('results' => $results);

	}

	/**
	 * Zwraca najlepiej oceniane wpisy z GitBloga
	 * @Template()
	 */
	public function topGitBlogAction() {
		return array();
	}

	/** Zwraca 4 ostatnie dodane filmy do GitTube
	 * @Template()
	 */
	public function lastCreatedGitTubeAction() {
		$helper = $this->getRatingHelper();

		$results = $helper->getLastCreatedContents('GitTube', 4);
		$this->cutContent($results);

		return array('results' => $results);
	}

	/** Zwraca najlepiej ocenianie wpisy z GitTube-a
	 * @Template()
	 */
	public function topGitTubeAction() {
		return array();
	}

	/**
	 * Zwraca 3 ostatnie pliki dodane do GitDrive
	 * @Template()
	 */
	public function lastCreatedGitDriveAction() {
		return array();
	}

	/**
	 * Zwraca najlepiej ocenianie pliki z GitDrive
	 * @Template()
	 */
	public function topGitDriveAction() {
		return array();
	}

	/** Zwraca instancje rating helpera
	 * @return \Gitbox\Bundle\CoreBundle\Helper\RatingHelper
	 */
	private function getRatingHelper() {
		$helper = $this->container->get('rating_helper');
		return $helper;
	}

	/** Przycina tekst, dla lepszego wyświetlania na froncie
	 * @param $results
	 */
	private function cutContent(&$results) {

		foreach($results as &$result) {
			$result['description'] = substr(strip_tags($result['description']), 0, 50);
			$result['title']       = substr(strip_tags($result['title']), 0, 25);
		}

	}
}
