import moment from 'moment'
import {useState} from 'react'
import {useLazyCreateLesson, useLazyUpdateLesson} from 'shared/libs/query'
import {formatDateToISO} from '../../shared/libs'
import {LessonData} from '../../shared/types'
import {Preloader} from '../preloader/Preloader'
import {DatePicker} from './DatePicker'
import {LessonModal} from './LessonModal'
import {useInitialize} from './libs/useInitialize'
import {TimeGrid} from './TimeGrid'

const Schedule = () => {
	const [selectedLessonId, setSelectedLessonId] = useState<string | undefined>('019418cb-e2a8-7bab-9197-b08747672046 0')
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

	const [createLesson] = useLazyCreateLesson()
	const [updateLesson] = useLazyUpdateLesson()

	const handleDateChange = (start: Date, end: Date) => {
		setStartDate(start)
		setEndDate(end)
	}

	const handleCardClick = (lesson: LessonData) => {
		setSelectedLessonId(lesson.lessonId)
	}

	const handleSaveLesson = async (lesson: Partial<LessonData>) => {
		if (lesson.lessonId) {
			await updateLesson({
				...lesson,
				date: lesson.date ? formatDateToISO(lesson.date) : undefined,
				lessonId: lesson.lessonId,
			})
		}
		else {
			await createLesson({
				...lesson,
				date: formatDateToISO(lesson.date as Date),
				startTime: lesson.startTime as number,
				duration: lesson.duration as number,
				courseId: lesson.courseId as string,
				locationId: lesson.locationId as string,
				description: lesson.description,
			})
		}
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
				open={true}
				selectedLesson={lessons.find(l => l.lessonId === selectedLessonId)}
				locations={locations}
				onSave={handleSaveLesson}
				onCancel={() => setSelectedLessonId(undefined)}
				groups={groups}
				courses={courses}
				teacherSubjects={teacherSubjects}
				subjects={subjects}
				teachers={users}
			/>
		</div>
	)
}

export {Schedule}
