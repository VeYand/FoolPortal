import {LessonData as ApiLessonData} from 'shared/api'
import {CourseData, GroupData, LessonData, SubjectData, TeacherSubjectData, UserData} from '../../types'

type RemapApiLessonToLessonDataParams = {
	lesson: ApiLessonData,
	coursesList: CourseData[],
	teacherSubjectsList: TeacherSubjectData[],
	subjectsList: SubjectData[],
	groupsList: GroupData[],
	usersList: UserData[],
}

const remapApiLessonToLessonData = (params: RemapApiLessonToLessonDataParams): LessonData => {
	const course = params.coursesList.find(c => c.courseId === params.lesson.courseId)
	const teacherSubject = params.teacherSubjectsList.find(ts => ts.teacherSubjectId === course?.teacherSubjectId)
	const subject = params.subjectsList.find(s => s.subjectId === teacherSubject?.subjectId)
	const group = params.groupsList.find(g => g.groupId === course?.groupId)
	const teacher = params.usersList.find(u => u.userId === teacherSubject?.teacherId)

	return ({
		lessonId: params.lesson.lessonId,
		date: new Date(params.lesson.date),
		startTime: params.lesson.startTime,
		duration: params.lesson.duration,
		teacherId: teacher?.userId ?? '',
		subjectId: subject?.subjectId ?? '',
		groupId: group?.groupId ?? '',
		courseId: params.lesson.courseId,
		locationId: params.lesson.locationId,
		description: params.lesson.description,
	})
}

type RemapApiLessonsToLessonsListParams = {
	lessons: ApiLessonData[],
	coursesList: CourseData[],
	teacherSubjectsList: TeacherSubjectData[],
	subjectsList: SubjectData[],
	groupsList: GroupData[],
	usersList: UserData[],
}

const remapApiLessonsToLessonsList = (params: RemapApiLessonsToLessonsListParams): LessonData[] => (
	params.lessons.map(lesson => remapApiLessonToLessonData({
		lesson,
		...params,
	}))
)

export {
	remapApiLessonsToLessonsList,
}