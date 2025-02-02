<?php
declare(strict_types=1);

namespace App\Lesson\Infrastructure\Query;

use App\Lesson\App\Query\Data\LessonData;
use App\Lesson\App\Query\LessonQueryServiceInterface;
use App\Lesson\Domain\Model\Lesson;
use App\Lesson\Domain\Model\LessonAttachment;
use Doctrine\ORM\EntityManagerInterface;

readonly class LessonQueryService implements LessonQueryServiceInterface
{
	public function __construct(
		private EntityManagerInterface $entityManager,
	)
	{
	}

	/**
	 * @inheritDoc
	 */
	public function listByTimeInterval(\DateTimeInterface $startTime, \DateTimeInterface $endTime): array
	{
		$qb = $this->entityManager->createQueryBuilder();

		$qb->select('l', 'la')
			->from(Lesson::class, 'l')
			->leftJoin(LessonAttachment::class, 'la', 'WITH', 'la.lessonId = l.lessonId')
			->where('l.date >= :startDate')
			->andWhere('l.date <= :endDate')
			->andWhere('l.startTime + l.duration <= :endTime')
			->setParameter('startDate', $startTime)
			->setParameter('endDate', $endTime)
			->setParameter('endTime', $endTime->getTimestamp());

		$result = $qb->getQuery()->getResult();

		$lessonsGroupedById = [];

		foreach ($result as $item)
		{
			if ($item instanceof Lesson)
			{
				$lessonId = $item->getLessonId()->toString();

				if (!isset($lessonsGroupedById[$lessonId]))
				{
					$lessonsGroupedById[$lessonId] = [
						'lesson' => $item,
						'attachmentIds' => [],
					];
				}
			}
			else if ($item instanceof LessonAttachment)
			{
				$lessonId = $item->getLessonId()->toString();

				if (isset($lessonsGroupedById[$lessonId]))
				{
					$lessonsGroupedById[$lessonId]['attachmentIds'][] = $item->getAttachmentId();
				}
			}
		}

		$lessonDataList = [];
		foreach ($lessonsGroupedById as $group)
		{
			/** @var Lesson $lesson */
			$lesson = $group['lesson'];

			$lessonDataList[] = new LessonData(
				$lesson->getLessonId(),
				$lesson->getDate(),
				$lesson->getStartTime(),
				$lesson->getDuration(),
				$lesson->getCourseId(),
				$group['attachmentIds'],
				$lesson->getLocationId(),
				$lesson->getDescription(),
			);
		}

		return $lessonDataList;
	}
}