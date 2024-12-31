import {Card, Row} from 'antd'
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

const DayColumn = ({date, lessons, groups, ...props}: DayColumnProps) => {
	const groupedLessons = groups.map(group => ({
		group,
		lessons: lessons.filter(lesson => lesson.courseId === group.groupId),
	}))

	return (
		<div>
			<h3>{date.format('dddd, DD MMM')}</h3>
			{groupedLessons.length ? (
				groupedLessons.map(({group, lessons: lessonsList}) => (
					<div key={group.groupId}>
						<h4>{group.name}</h4>
						<Row>
							{lessonsList.length ? (
								lessonsList.map(lesson => <LessonBlock key={lesson.lessonId} lesson={lesson} groups={groups} {...props} />)
							) : (
								<Card>Нет занятий</Card>
							)}
						</Row>
					</div>
				))
			) : (
				<Card>Нет занятий</Card>
			)}
		</div>
	)
}

export {DayColumn}
