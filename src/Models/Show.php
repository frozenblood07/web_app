<?php
/**
 * File: Show.php
 * User: karan.tuteja26@gmail.com
 * Description:
 */

namespace Ticket\Models;


class Show
{
    private $showID;
    private $name;
    private $date;
    private $genre;

    public function __construct($showID,$name,$date,$genre) {
        $this->showID = $showID;
        $this->name = $name;
        $this->date = $date;
        $this->genre = $genre;
    }

    /**
     * @return mixed
     */
    public function getShowID()
    {
        return $this->showID;
    }

    /**
     * @param mixed $showID
     */
    public function setShowID($showID): void
    {
        $this->showID = $showID;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date): void
    {
        $this->date = $date;
    }

    /**
     * @return mixed
     */
    public function getGenre()
    {
        return $this->genre;
    }

    /**
     * @param mixed $genre
     */
    public function setGenre($genre): void
    {
        $this->genre = $genre;
    }






}