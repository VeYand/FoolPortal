import {Card} from 'antd'
import {LessonData, LocationData, CourseData, TeacherSubjectData, SubjectData, GroupData, UserData} from '../../shared/types'
import {getColorByLessonType} from './libs/getColorByLessonType'

type LessonBlockProps = {
	lesson: LessonData,
	locations: LocationData[],
	courses: CourseData[],
	teacherSubjects: TeacherSubjectData[],
	subjects: SubjectData[],
	groups: GroupData[],
	users: UserData[],
	onCardClick: (lesson: LessonData) => void,
}

const LessonBlock = ({lesson, courses, teacherSubjects, subjects, users, onCardClick}: LessonBlockProps) => {
	const course = courses.find(c => c.courseId === lesson.courseId)
	const teacherSubject = teacherSubjects.find(ts => ts.teacherSubjectId === course?.teacherSubjectId)
	const subject = subjects.find(s => s.subjectId === teacherSubject?.subjectId)
	const teacher = users.find(u => u.userId === teacherSubject?.teacherId)

	return (
		<Card
			style={{backgroundColor: getColorByLessonType(subject?.name || '')}}
			onClick={() => onCardClick(lesson)}
		>
			<h3>{subject?.name}</h3>
			<p>{teacher ? `${teacher.firstName} ${teacher.lastName}` : 'Преподаватель не указан'}</p>
			<p>Время: {lesson.startTime}</p>
		</Card>
	)
}

export {LessonBlock}
