import {Modal} from 'antd'
import moment from 'moment/moment'
import {LessonData, LocationData} from '../../shared/types'

type LessonModalProps = {
	selectedLesson?: LessonData,
	unselectLesson: () => void,
	locations: LocationData[],

}

const LessonModal = ({selectedLesson, unselectLesson, locations}: LessonModalProps) => (
	<Modal
		title="Детали пары"
		open={!!selectedLesson}
		onCancel={unselectLesson}
		footer={null}
	>
		{selectedLesson && (
			<div>
				<p>Дата: {moment(selectedLesson.date).format('DD.MM.YYYY')}</p>
				<p>Время начала: {selectedLesson.startTime}</p>
				<p>Продолжительность: {selectedLesson.duration} минут</p>
				<p>
					Аудитория: {
						locations.find(loc => loc.locationId === selectedLesson.locationId)?.name
					|| 'Не указано'
					}
				</p>
				<p>Описание: {selectedLesson.description || 'Нет описания'}</p>
			</div>
		)}
	</Modal>
)

export {
	LessonModal,
}