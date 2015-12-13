<?php

use Helmich\Psr7Assert\Constraint\HasHeaderConstraint;
use Helmich\Psr7Assert\Constraint\HasUriConstraint;

function hasUri($uri)
{
    return new HasUriConstraint($uri);
}

function hasHeader($name, $constraint = NULL)
{
    return new HasHeaderConstraint($name, $constraint);
}

function hasContentType($contentType)
{
    return new HasHeaderConstraint('Content-Type', $contentType);
}