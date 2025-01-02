import {Modal, Button} from 'antd'
import {useEffect, useMemo} from 'react'
import {
	LessonData,
	LocationData,
	GroupData,
	SubjectData,
	UserData,
} from 'shared/types'
import {getViewableUserName} from '../../shared/libs'
import {formatStartTime} from './libs/formatStartTime'

type LessonReadModalProps = {
	open: boolean,
	setOpened: (opened: boolean) => void,
	selectedLesson?: LessonData,
	locations: LocationData[],
	subjects: SubjectData[],
	users: UserData[],
	groups: GroupData[],
}

const LessonReadModal = ({
	open,
	setOpened,
	selectedLesson,
	locations,
	subjects,
	users,
	groups,
}: LessonReadModalProps) => {

	useEffect(() => {
		if (!selectedLesson) {
			setOpened(false)
		}
	}, [selectedLesson, setOpened])

	const onCancel = () => {
		setOpened(false)
	}

	const teacher = useMemo(() => users.find(u => u.userId === selectedLesson?.teacherId), [selectedLesson?.teacherId, users])

	return (
		<Modal
			title={subjects.find(s => s.subjectId === selectedLesson?.subjectId)?.name ?? 'Данные пары'}
			open={open}
			onCancel={onCancel}
			footer={[
				<Button key="cancel" onClick={onCancel}>Закрыть</Button>,
			]}
			width={600}
		>
			{selectedLesson ? (
				<div>
					<div style={{marginBottom: '8px'}}>
						<strong>Группа:</strong> {groups.find(g => g.groupId === selectedLesson.groupId)?.name ?? '-'}
					</div>
					<div style={{marginBottom: '8px'}}>
						<strong>Учитель:</strong> {teacher ? getViewableUserName(teacher) : '-'}
					</div>
					<div style={{marginBottom: '8px'}}>
						<strong>Дата и
							время:</strong> {selectedLesson.date ? `${new Date(selectedLesson.date).toLocaleDateString()} ${formatStartTime(selectedLesson.startTime)}` : '-'}
					</div>
					<div style={{marginBottom: '8px'}}>
						<strong>Продолжительность:</strong> {selectedLesson.duration} минут
					</div>
					<div style={{marginBottom: '8px'}}>
						<strong>Место
							проведения:</strong> {locations.find(l => l.locationId === selectedLesson.lessonId)?.name ?? '-'}
					</div>
					<div style={{marginBottom: '8px'}}>
						<strong>Описание:</strong> {selectedLesson.description || '-'}
					</div>
				</div>
			) : (
				<div>Нет данных о выбранной паре.</div>
			)}
		</Modal>
	)
}

export {LessonReadModal}
