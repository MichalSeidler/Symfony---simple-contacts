<?php
namespace App\EventListener;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class SlugGeneratorSubscriber implements EventSubscriberInterface
{
    public function getSubscribedEvents(): array
    {
        return [
            Events::prePersist,
            Events::postPersist,
            Events::postUpdate
        ];
    }

    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();
        
        if (is_callable([$entity, 'setSlug'])){
            $entity->setSlug();
        }
    }

    public function postPersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();
        
        if (is_callable([$entity, 'setSlug'])){
            $entity->setSlug();

            $entityManager = $args->getObjectManager();
            $entityManager->flush($entity);
        }
    }

    public function postUpdate(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();
        
        if (is_callable([$entity, 'setSlug'])){
            $entity->setSlug();
            
            $entityManager = $args->getObjectManager();
            $entityManager->flush($entity);
        }
    }
}