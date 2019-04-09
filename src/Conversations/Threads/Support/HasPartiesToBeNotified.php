<?php

declare(strict_types=1);

namespace HelpScout\Api\Conversations\Threads\Support;

trait HasPartiesToBeNotified
{
    /**
     * @var array
     */
    private $cc = [];

    /**
     * @var array
     */
    private $bcc = [];

    public function getCC(): ?array
    {
        return $this->cc;
    }

    public function setCC(array $cc)
    {
        $this->cc = $cc;
    }

    public function getBCC(): ?array
    {
        return $this->bcc;
    }

    public function setBCC(array $bcc)
    {
        $this->bcc = $bcc;
    }

    protected function hydrateCC($input)
    {
        $input = is_array($input) ? $input : [$input];
        $this->setCC($input);
    }

    protected function hydrateBCC($input)
    {
        $input = is_array($input) ? $input : [$input];
        $this->setBCC($input);
    }
}
