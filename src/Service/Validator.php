<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class Validator
{
    private ValidatorInterface $validator;
    private RequestEncoder $requestEncoder;

    public function __construct(ValidatorInterface $validator, RequestEncoder $requestEncoder)
    {
        $this->validator = $validator;
        $this->requestEncoder = $requestEncoder;
    }

    /**
     * @param $obj
     * @return JsonResponse|void
     */
    public function validate($obj)
    {
        $errors = $this->validator->validate($obj);

        if (count($errors) > 0) {
            $jsonData = $this->requestEncoder->encodeEntity([], $errors);
            return new JsonResponse(
                $jsonData, 400, [], true
            );
        }
    }
}