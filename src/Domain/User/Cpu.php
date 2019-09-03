<?php

namespace Deck\Domain\User;

class Cpu extends Player
{
    public function username(): string
    {
        return 'CPU';
    }
}
