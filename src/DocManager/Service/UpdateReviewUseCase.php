<?php

declare(strict_types=1);

namespace RigorTalks\DocManager\Service;

use Exception;

final class UpdateReviewUseCase
{
    private $entityManager;
    private $eventDispatcher;
    private $logger;
    private $reviewRepository;

    public function __construct(
        $entityManager,
        $eventDispatcher,
        $logger,
        $reviewRepository
    )
    {
        $this->entityManager = $entityManager;
        $this->eventDispatcher = $eventDispatcher;
        $this->logger = $logger;
        $this->reviewRepository = $reviewRepository;
    }

    /**
     * @param int $reviewId
     * @param array $data
     * @return mixed
     * @throws Exception
     */
    public function execute(int $reviewId, array $data = [])
    {
        $review = $this->reviewRepository->find($reviewId);

        if ($review->getState() == ReviewStates::IN_PROGRESS) {
            $data['extra'] = serialize(json_decode($data['extra']));

            $review->update($data);

            if (isset($data['score'])) {
                $review->setScore($data['score']);
                $this->entityManager->flush();
            }

            if (isset($data['id_error'])) {
                $review->setIdError = $data['id_error'];
                $this->entityManager->flush();
            }

            $this->eventDispatcher->dispatch(ReviewEvents::UPDATED, new ReviewEvent($review));

            $this->logger->create(new \DateTime, null, $review->getAuction()->getAssignee()->getUid(),
                serialize(['Review' => SuperLogEvents::REVIEW_UPDATED]),
                serialize([
                    'auction' => $review->getAuction()->toArray(),
                    'review' => $review->toArray()
                ]));

            return $review;
        } else {
            throw new Exception('The review cannot be updated');
        }
    }
}
