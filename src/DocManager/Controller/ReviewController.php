<?php

declare(strict_types=1);

namespace RigorTalks\DocManager\Controller;

use Exception;

/**
 * Class ReviewController
 *
 * Class used to practice refactoring.
 *
 * The Steps:
 *
 * 1. remove unused variables
 *
 * @package RigorTalks\DocManager\Controller
 */
final class ReviewController extends BaseController
{
    public function update(int $reviewId, array $data = [])
    {
        $review = $this->get($reviewId);
        $date = new \DateTime;
        $dateFormat = $date->format("Y-m-d H:i:s");

        if ($review->getState() === ReviewStates::IN_PROGRESS) {
            $data['extra'] = serialize(json_decode($data['extra']));

            $review->update($data);

            if (isset($data['score'])) {
                $review->setScore($data['score']);
                $this->flushManager();
            }

            if (isset($data['id_error'])) {
                $review->setIdError = $data['id_error'];
                $this->flushManager();
            }

            $this->triggerReviewEvent(ReviewEvents::UPDATED, $review);

            $this->getService('superlog_controller')
                ->create(new \DateTime, null, $review->getAuction()->getAssignee()->getUid(),
                    serialize(['Review' => SuperLogEvents::REVIEW_UPDATED]),
                    serialize([
                        'auction' => $review->getAuction()->toArray(),
                        'review' => $review->toArray()]));

            return $review;
        } else {
            throw new Exception('The review cannot be updated');
        }
    }

    public function get(int $id): Review
    {
        return $this->getManager()->getRepository('RigorTalks\DocManager\Entity\Review')->find($id);
    }
}
