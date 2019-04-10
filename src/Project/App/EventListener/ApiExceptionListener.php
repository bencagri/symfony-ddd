<?php

namespace App\Project\App\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\Validator\Exception\ValidatorException;

class ApiExceptionListener
{
    /**
     * @var bool
     */
    public $isKernelDebug;

    public function __construct(bool $isKernelDebug)
    {
        $this->isKernelDebug = $isKernelDebug;
    }

    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $throwedException = $event->getException();

        $errorBody = [
            'code'    => $throwedException->getCode(),
            'message' => $throwedException->getMessage(),
        ];

        if ($throwedException instanceof ValidatorException) {
            $errorBody['message'] = 'Invalid data has been sent';
        }

        if ($this->isKernelDebug) {
            $errorBody['exception'] = [
                'class'   => get_class($throwedException)
            ];
            $errorBody['trace'] = $throwedException->getTrace();
        }

        $event->setResponse(new JsonResponse(['success' => false, 'error' => $errorBody]));
    }
}
