import {Card} from 'antd'
import moment from 'moment'
import {LessonData, LocationData, CourseData, TeacherSubjectData, SubjectData, GroupData, UserData} from '../../shared/types'
import {LessonBlock} from './LessonBlock'

type DayColumnProps = {
	date: moment.Moment,
	lessons: LessonData[],
	locations: LocationData[],
	courses: CourseData[],
	teacherSubjects: TeacherSubjectData[],
	subjects: SubjectData[],
	groups: GroupData[],
	users: UserData[],
	onCardClick: (lesson: LessonData) => void,
}

const DayColumn = ({date, lessons, ...props}: DayColumnProps) => (
	<div>
		<h3>{date.format('dddd, DD MMM')}</h3>
		{lessons.length ? (
			lessons.map(lesson => <LessonBlock key={lesson.lessonId} lesson={lesson} {...props} />)
		) : (
			<Card>Нет занятий</Card>
		)}
	</div>
)

export {DayColumn}
