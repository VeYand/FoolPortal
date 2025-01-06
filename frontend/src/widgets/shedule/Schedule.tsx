import {Button} from 'antd'
import moment from 'moment'
import {useState} from 'react'
import {useAppSelector} from 'shared/redux'
import {USER_ROLE} from 'shared/types'
import {Preloader} from '../preloader/Preloader'
import {DatePicker} from './DatePicker'
import {LessonModalForAdministration} from './LessonModalForAdministration'
import {LessonModalForTeacherAndUser} from './LessonModalForTeacherAndUser'
import {useInitialize} from './libs/useInitialize'
import {ScheduleTable} from './ScheduleTable'

type ScheduleHeaderProps = {
	onSelectedDateChange: (startDate: Date, endDate: Date) => void,
	onCreateButtonClick: () => void,
}

const ScheduleHeader = ({onCreateButtonClick, onSelectedDateChange}: ScheduleHeaderProps) => {
	const currentUser = useAppSelector(state => state.userEntity.user)

	return (
		<div style={{display: 'flex', gap: 40}}>
			<DatePicker onSelectedDateChange={onSelectedDateChange}/>
			{(currentUser.role === USER_ROLE.ADMIN || currentUser.role === USER_ROLE.OWNER)
				&& <Button onClick={onCreateButtonClick}>{'Создать пару'}</Button>}
		</div>
	)
}

const Schedule = () => {
	const currentUser = useAppSelector(state => state.userEntity.user)

	const [selectedLessonId, setSelectedLessonId] = useState<string | undefined>()
	const [lessonModalOpened, setLessonModalOpened] = useState(false)
	const [startDate, setStartDate] = useState(moment().startOf('week').toDate())
	const [endDate, setEndDate] = useState(moment().endOf('week').toDate())

	const {
		loading,
		lessons,
		locations,
		courses,
		teacherSubjects,
		subjects,
		groups,
		users,
		refetch,
	} = useInitialize(startDate, endDate)


	const onCreateButtonClick = () => {
		setLessonModalOpened(true)
		setSelectedLessonId(undefined)
	}


	return (
		<div>
			<ScheduleHeader
				onSelectedDateChange={(start: Date, end: Date) => {
					setStartDate(start)
					setEndDate(end)
				}}
				onCreateButtonClick={onCreateButtonClick}
			/>
			{loading && <Preloader />}
			{!loading && (
				<div style={{marginTop: 20}}>
					<ScheduleTable
						weekStartDate={startDate}
						lessons={lessons}
						groups={groups}
						subjects={subjects}
						users={users}
						locations={locations}
						selectLesson={lessonId => {
							setSelectedLessonId(lessonId)
							setLessonModalOpened(true)
						}}
					/>
				</div>
			)}
			{(currentUser.role === USER_ROLE.OWNER || currentUser.role === USER_ROLE.ADMIN)
				&& <LessonModalForAdministration
					open={lessonModalOpened}
					setOpened={opened => {
						setLessonModalOpened(opened)
						setSelectedLessonId(undefined)
					}}
					refetch={refetch}
					selectedLesson={lessons.find(l => l.lessonId === selectedLessonId)}
					locations={locations}
					groups={groups}
					courses={courses}
					teacherSubjects={teacherSubjects}
					subjects={subjects}
					teachers={users.filter(u => u.role === USER_ROLE.TEACHER)}
				/>
			}
			{(currentUser.role === USER_ROLE.TEACHER || currentUser.role === USER_ROLE.STUDENT)
				&& <LessonModalForTeacherAndUser
					open={lessonModalOpened}
					setOpened={opened => {
						setLessonModalOpened(opened)
						setSelectedLessonId(undefined)
					}}
					selectedLesson={lessons.find(l => l.lessonId === selectedLessonId)}
					locations={locations}
					groups={groups}
					subjects={subjects}
					users={users}
				/>
			}
		</div>
	)
}

export {
	Schedule,
}
