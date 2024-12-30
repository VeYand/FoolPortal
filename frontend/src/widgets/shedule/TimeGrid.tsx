import {Row, Col} from 'antd'
import moment from 'moment'
import {LessonData, LocationData, CourseData, TeacherSubjectData, SubjectData, GroupData, UserData} from '../../shared/types'
import {DayColumn} from './DayColumn'

type TimeGridProps = {
	lessons: LessonData[],
	locations: LocationData[],
	courses: CourseData[],
	teacherSubjects: TeacherSubjectData[],
	subjects: SubjectData[],
	groups: GroupData[],
	users: UserData[],
	onCardClick: (lesson: LessonData) => void,
}

const TimeGrid = ({
	lessons,
	locations,
	courses,
	teacherSubjects,
	subjects,
	groups,
	users,
	onCardClick,
}: TimeGridProps) => {
	const daysOfWeek = Array.from({length: 7}, (_, i) => moment().startOf('week').add(i, 'days'))

	return (
		<Row gutter={[16, 16]}>
			{daysOfWeek.map(day => (
				<Col key={day.format('YYYY-MM-DD')} span={24 / 7}>
					<DayColumn
						date={day}
						lessons={lessons.filter(lesson => moment(lesson.date).isSame(day, 'day'))}
						locations={locations}
						courses={courses}
						teacherSubjects={teacherSubjects}
						subjects={subjects}
						groups={groups}
						users={users}
						onCardClick={onCardClick}
					/>
				</Col>
			))}
		</Row>
	)
}

export {TimeGrid}
