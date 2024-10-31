<?php

declare(strict_types=1);

namespace App\Helpers\Request;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\HttpException;

class BaseRequest
{
    public function __construct(
        private readonly RequestStack $requestStack,
    ) {
    }

    public function getRequest(): Request
    {
        /** @var Request */
        $request = $this->requestStack->getCurrentRequest();

        return $request;
    }

    /** @return mixed[] */
    public function getJsonData(): array
    {
        $content = $this->getRequest()->getContent();

        if (empty($content)) {
            return [];
        }

        $input = json_decode($content, true);
        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new HttpException(statusCode: 400, message: 'Malformed JSON input');
        }

        /* @var mixed[] $input */
        return $input;
    }
}
