<?php

use Helmich\Psr7Assert\Constraint\HasUriConstraint;

function hasUri($uri)
{
    return new HasUriConstraint($uri);
}