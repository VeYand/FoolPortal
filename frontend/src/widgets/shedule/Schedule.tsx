import {Col, Row} from 'antd'
import moment from 'moment'
import {useState} from 'react'
import {LessonData} from '../../shared/types'
import {Preloader} from '../preloader/Preloader'
import {DatePicker, formatDateToISO} from './DatePicker'
import {LessonCard} from './LessonCard'
import {LessonModal} from './LessonModal'
import {useInitialize} from './libs/useInitialize'

const Schedule = () => {
	const [selectedLessonId, setSelectedLessonId] = useState<string | undefined>()
	const [startDate, setStartDate] = useState(formatDateToISO(moment().startOf('week').toDate()))
	const [endDate, setEndDate] = useState(formatDateToISO(moment().endOf('week').toDate()))

	const onSelectedDateChange = (start: string, end: string) => {
		setStartDate(start)
		setEndDate(end)
	}

	const {
		loading,
		lessons,
		locations,
		courses,
		teacherSubjects,
		subjects,
		groups,
		users,
	} = useInitialize(
		startDate,
		endDate,
	)

	const handleCardClick = (lesson: LessonData) => {
		setSelectedLessonId(lesson.lessonId)
	}


	if (loading) {
		return <Preloader/>
	}

	return (
		<div>
			<h1>{'Расписание'}</h1>
			<DatePicker onSelectedDateChange={onSelectedDateChange}/>
			<Row gutter={[16, 16]}>
				{lessons.map(lesson => (
					<Col key={lesson.lessonId} span={8}>
						<LessonCard
							lesson={lesson}
							locations={locations}
							courses={courses}
							teacherSubjects={teacherSubjects}
							subjects={subjects}
							groups={groups}
							users={users}
							onCardClick={handleCardClick}
						/>
					</Col>
				))}
			</Row>
			<LessonModal
				unselectLesson={() => setSelectedLessonId(undefined)}
				selectedLesson={lessons.find(l => l.lessonId === selectedLessonId)}
				locations={locations}
			/>
		</div>
	)
}

export {
	Schedule,
}
