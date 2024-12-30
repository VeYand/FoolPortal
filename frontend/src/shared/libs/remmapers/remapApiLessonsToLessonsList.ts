import {LessonData as ApiLessonData} from 'shared/api'
import {LessonData} from '../../types/types'

const remapApiLessonToLessonData = (lesson: ApiLessonData): LessonData => ({
	lessonId: lesson.lessonId,
	date: new Date(lesson.date),
	startTime: lesson.startTime,
	duration: lesson.duration,
	courseId: lesson.courseId,
	locationId: lesson.locationId,
	description: lesson.description,
})

const remapApiLessonsToLessonsList = (lessons: ApiLessonData[]): LessonData[] => lessons.map(
	location => remapApiLessonToLessonData(location),
)

export {
	remapApiLessonsToLessonsList,
}