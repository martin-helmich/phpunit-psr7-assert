<?php
declare(strict_types=1);
namespace Helmich\Psr7Assert\Constraint;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Constraint\Constraint;
use Psr\Http\Message\ResponseInterface;

class HasStatusConstraint extends Constraint
{

    private Constraint $status;

    public function __construct(mixed $status)
    {
        if (!$status instanceof Constraint) {
            $status = Assert::equalTo($status);
        }

        $this->status = $status;
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString(): string
    {
        return "response status {$this->status->toString()}";
    }

    protected function matches($other): bool
    {
        if (!$other instanceof ResponseInterface) {
            return false;
        }

        return $this->status->evaluate($other->getStatusCode(), '', true);
    }

    protected function additionalFailureDescription($other): string
    {
        if ($other instanceof ResponseInterface) {
            return 'Actual status is ' . $other->getStatusCode() . ' and the body contains: ' . $other->getBody();
        }
        return '';
    }
}
