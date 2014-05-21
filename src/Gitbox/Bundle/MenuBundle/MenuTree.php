<?php
/**
 * Created by PhpStorm.
 * User: gitbox
 * Date: 14.05.14
 * Time: 22:51
 */

namespace Gitbox\Bundle\MenuBundle;


class MenuTree {

    /**
     * @var integer
     */
    private $id;

    /**
     * @var array | null
     *
     */
    private $menus;

    /**
     * @var array | null
     *
     */
    private $contents;


    /**
     * @var string
     *
     */
    private $title;


    /**
     * @var string
     *
     */
    private $user;



    /**
     * Set title
     *
     * @param string $title
     * @return MenuTree
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }


    /**
     * Set id
     *
     * @param integer $id
     * @return MenuTree
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set user
     *
     * @param string $user
     * @return MenuTree
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return string
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set menus
     *
     * @param array | null $menus
     * @return MenuTree
     */
    public function setMenus($menus)
    {
        $this->menus = $menus;

        return $this;
    }

    /**
     * Get menus
     *
     * @return array | null
     */
    public function getMenus()
    {
        return $this->menus;
    }

    /**
     * Set contents
     *
     * @param array | null $contents
     * @return MenuTree
     */
    public function setContents($contents)
    {
        $this->contents = $contents;

        return $this;
    }

    /**
     * Get contents
     *
     * @return array | null
     */
    public function getContents()
    {
        return $this->contents;
    }



} 