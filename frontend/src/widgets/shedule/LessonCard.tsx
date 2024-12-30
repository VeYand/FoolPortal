import {Card} from 'antd'
import {
	CourseData,
	GroupData,
	LessonData,
	LocationData,
	SubjectData,
	TeacherSubjectData,
	UserData,
} from '../../shared/types'
import {getColorByLessonType} from './libs/getColorByLessonType'

type LessonCardProps = {
	lesson: LessonData,
	locations: LocationData[],
	courses: CourseData[],
	teacherSubjects: TeacherSubjectData[],
	subjects: SubjectData[],
	groups: GroupData[],
	users: UserData[],
	onCardClick: (lesson: LessonData) => void,
}


const LessonCard = ({
	lesson,
	// locations,
	courses,
	teacherSubjects,
	subjects,
	// groups,
	users,
	onCardClick,
}: LessonCardProps) => {
	const course = courses.find(c => c.courseId === lesson.courseId)
	const teacherSubject = teacherSubjects.find(
		ts => ts.teacherSubjectId === course?.teacherSubjectId,
	)
	const subject = subjects.find(s => s.subjectId === teacherSubject?.subjectId)
	const teacher = users.find(u => u.userId === teacherSubject?.teacherId)

	return (
		<Card
			style={{backgroundColor: getColorByLessonType(subject?.name || '')}}
			onClick={() => onCardClick(lesson)}
		>
			<h3>{subject?.name}</h3>
			<p>{teacher ? `${teacher.firstName} ${teacher.lastName}` : ''}</p>
		</Card>
	)
}

export {
	LessonCard,
}