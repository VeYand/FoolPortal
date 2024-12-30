import moment from 'moment'
import {useState} from 'react'
import {LessonData} from '../../shared/types'
import {Preloader} from '../preloader/Preloader'
import {DatePicker} from './DatePicker'
import {LessonModal} from './LessonModal'
import {useInitialize} from './libs/useInitialize'
import {TimeGrid} from './TimeGrid'

const Schedule = () => {
	const [selectedLessonId, setSelectedLessonId] = useState<string | undefined>()
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
	} = useInitialize(startDate, endDate)

	const handleDateChange = (start: Date, end: Date) => {
		setStartDate(start)
		setEndDate(end)
	}

	const handleCardClick = (lesson: LessonData) => {
		setSelectedLessonId(lesson.lessonId)
	}

	if (loading) {
		return <Preloader />
	}

	return (
		<div>
			<h1>Расписание</h1>
			<DatePicker onSelectedDateChange={handleDateChange} />
			<TimeGrid
				lessons={lessons}
				locations={locations}
				courses={courses}
				teacherSubjects={teacherSubjects}
				subjects={subjects}
				groups={groups}
				users={users}
				onCardClick={handleCardClick}
			/>
			<LessonModal
				unselectLesson={() => setSelectedLessonId(undefined)}
				selectedLesson={lessons.find(l => l.lessonId === selectedLessonId)}
				locations={locations}
			/>
		</div>
	)
}

export {Schedule}
