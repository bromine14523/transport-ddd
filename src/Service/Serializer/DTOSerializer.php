<?php

namespace App\Service\Serializer;

//use App\Event\AfterDtoCreatedEvent;
use Doctrine\Common\Annotations\Annotation;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class DTOSerializer implements SerializerInterface
{
    private SerializerInterface $serializer;

    public function __construct()
    {
        $this->serializer = new Serializer(
            [
                new ObjectNormalizer(
                    classMetadataFactory: new ClassMetadataFactory(
                        new AnnotationLoader(
                            new AnnotationReader()
                        )
                    ),
                    nameConverter: new CamelCaseToSnakeCaseNameConverter()
                )
            ],
            [new JsonEncoder()]
        );
    }

    public function serialize($data, string $format, array $context = [])
    {
        return $this->serializer->serialize($data, $format, $context);
    }

    public function deserialize($data, string $type, string $format, array $context = [])
    {
        $dto = $this->serializer->deserialize($data, $type, $format, $context);

//        $event = new AfterDtoCreatedEvent($dto);
//
//        // dispatch an after dto created event
//        $this->eventDispatcher->dispatch($event, $event::NAME);

        return $dto;
    }
}
