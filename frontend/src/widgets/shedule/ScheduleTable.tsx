import {Table, Tag} from 'antd'
import {getViewableUserName} from 'shared/libs'
import {GroupData, LessonData, LocationData, SubjectData, UserData} from 'shared/types'
import {formatStartTime} from './libs/formatStartTime'

type ScheduleProps = {
	weekStartDate: Date,
	lessons: LessonData[],
	groups: GroupData[],
	subjects: SubjectData[],
	users: UserData[],
	locations: LocationData[],
}

const ScheduleTable = ({weekStartDate, lessons, groups, subjects, users, locations}: ScheduleProps) => {
	const weekDays = Array.from({length: 7}, (_, i) => {
		const day = new Date(weekStartDate)
		day.setDate(day.getDate() + i)
		return day
	})

	const getGroupLessons = (groupId: string, day: Date) => {
		const dayLessons = lessons.filter(
			lesson =>
				lesson.groupId === groupId
				&& new Date(lesson.date).toDateString() === day.toDateString(),
		)
		return dayLessons.sort((a, b) => a.startTime - b.startTime).map(lesson => {
			const teacher = users.find(u => u.userId === lesson.teacherId)
			const location = locations.find(l => l.locationId === lesson.locationId)
			return (
				<Tag
					key={lesson.lessonId}
					style={{
						margin: '5px 0',
						display: 'block',
						textAlign: 'center',
						background: '#bae7ff',
						userSelect: 'none',
					}}
				>
					<div>{`${formatStartTime(lesson.startTime)} - ${formatStartTime(lesson.startTime + lesson.duration)}`}</div>
					<div>{subjects.find(s => s.subjectId === lesson.subjectId)?.name ?? ' Урок'}</div>
					{location && <div>{location.name}</div>}
					{teacher && <div>{getViewableUserName(teacher, false)}</div>}
				</Tag>
			)
		})
	}

	const columns = [
		{
			title: 'Группа',
			dataIndex: 'group',
			key: 'group',
			fixed: 'left' as const,
		},
		...weekDays.map(day => ({
			title: `${day.toLocaleDateString('ru-RU', {weekday: 'long', day: 'numeric', month: 'long'})}`,
			dataIndex: day.toISOString(),
			key: day.toISOString(),
			render: (_: any, record: any) => (
				<div style={{position: 'relative'}}>
					{getGroupLessons(record.groupId, day)}
				</div>
			),
		})),
	]

	const dataSource = groups.map(group => ({
		key: group.groupId,
		group: group.name,
		groupId: group.groupId,
	}))

	return (
		<Table
			columns={columns}
			dataSource={dataSource}
			scroll={{x: 1200}}
			bordered
			pagination={false}
		/>
	)
}

export {
	ScheduleTable,
}
