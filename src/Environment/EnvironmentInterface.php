<?php

namespace DH\GUS\Environment;

interface EnvironmentInterface
{
    public function getLoginKey(): ?string;
    public function getEndpointUri(): ?string;
    public function getWsdl(): ?string;
    public function getIgnoreSsl(): ?bool;
}
