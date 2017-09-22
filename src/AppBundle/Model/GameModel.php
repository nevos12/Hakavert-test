<?php
/**
 * Created by PhpStorm.
 * User: ami
 * Date: 10/29/15
 * Time: 12:30 PM
 */

namespace AppBundle\Model;


use AppBundle\Tic\Game;
use Symfony\Component\HttpFoundation\Session\Session;

class GameModel
{
    /** @var  Session */
    private $session;

    /** @var  Game */
    private $game;

    /**
     * @var boolean
     */
    private $vsComputer;

    /**
     * GameModel constructor.
     * @param Session $session
     */
    public function __construct(Session $session)
    {
        $this->session = $session;

        $this->vsComputer = $this->session->get('vsComputer', false);

        $this->loadGame();
        $this->storeGame();
    }

    /**
     * @return Game
     */
    public function getGame()
    {
        return $this->game;
    }

    /**
     * @param Game $game
     */
    public function setGame($game)
    {
        $this->game = $game;
        $this->storeGame();
    }

    private function loadGame()
    {
        $json = $this->session->get('game', $this->emptyGameJson());
        $game = new Game($this->vsComputer);
        $game->unserialize($json);
        return $this->game = $game;
    }

    private function storeGame()
    {
        $this->session->set('game', $this->game->serialize());
    }

    private function storeVsComputer($vsComputer)
    {
        $this->vsComputer = $vsComputer;
        $this->session->set('vsComputer', $vsComputer);
    }

    private function emptyGameJson()
    {
        $game = new Game($this->vsComputer);
        $game->start();
        return $game->serialize();
    }

    /**
     * @param  boolean $vsComputer
     * @return void
     */
    public function startGame($vsComputer)
    {
        $this->storeVsComputer($vsComputer);

        $this->game->start();
        $this->storeGame();
    }
}
