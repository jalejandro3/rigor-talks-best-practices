<?php

declare(strict_types=1);

namespace RigorTalks\DocManager\Controller;

use RigorTalks\DocManager\Service\UpdateReviewUseCase;

/**
 * Class ReviewController
 *
 * Class used to practice refactoring.
 *
 * First Class: https://www.youtube.com/watch?v=-RwBRikBXYc
 *
 * The Steps:
 *
 * 1. remove unused variables: we need remove unused variables from our code, in this case, $date and $dateFormat.
 *  - Clean code policies.
 * 2. generate an application service to point an app functionality.
 *  - Creating Service folder, then, UpdateReviewUseCase (using UseCase is more evident for users and programmers).
 *  - Identify Business logic and Infrastructure logic:
 *      * get(id)
 *      * flushManager()
 *      * getService()
 *  - Refactoring get(id) using inline code logic
 *      * Remove get() method, move it to line 35.
 *      * Send getManager()->getRepository to a variable (Refactor\Introduce Variable, line 36).
 *  - Refactoring flushManager using inline code
 *      * Send getManager()->flush() to a variable
 *  - Refactoring triggerReviewEvent
 *      * Inline refactoring getService()->dispatch()
 *  - Refactoring getService
 *      * Send getService() to a variable
 * 3. Move if/else to UpdateReviewUseCase
 * 4. Move ReviewController::update parameters to UpdateReviewCase::execute
 *
 * @package RigorTalks\DocManager\Controller
 */
final class ReviewController extends BaseController
{
    public function update(int $reviewId, array $data = [])
    {
        $entityManager = $this->getManager();
        $eventDispatcher = $this->getService('event_dispatcher');
        $logger = $this->getService('superlog_controller');
        $reviewRepository = $entityManager->getRepository('RigorTalks\DocManager\Entity\Review');

        return (new UpdateReviewUseCase(
            $entityManager,
            $eventDispatcher,
            $logger,
            $reviewRepository
        ))->execute($reviewId, $data);
    }
}
